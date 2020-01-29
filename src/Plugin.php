<?php

/**
 * Main plugin file.
 *
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare(strict_types=1);

namespace Towa\GdprPlugin;

use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use BrightNucleus\Dependency\DependencyManager;

/**
 * Main plugin class.
 *
 * @author  Martin Welte <martin.welte@towa.at>
 */
class Plugin
{
	use ConfigTrait;

	/**
	 * Static instance of the plugin.
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Transient used for settings key.
	 *
	 * @var string
	 */
	private const TRANSIENT_KEY = __CLASS__ . '_settings';

	/**
	 * Instantiate a Plugin object.
	 *
	 * Don't call the constructor directly, use the `Plugin::get_instance()`
	 * static method instead.
	 *
	 * @throws FailedToProcessConfigException if the Config could not be parsed correctly
	 *
	 * @param ConfigInterface $config config to parametrize the object
	 */
	public function __construct(ConfigInterface $config)
	{
		$this->processConfig($config);
	}

	/**
	 * Launch the initialization process.
	 */
	public function run(): void
	{
		add_action('acf/save_post', array($this, 'save_options_hook'), 20);
		add_action('acf/init', array($this, 'init'));
		add_action('acf/input/admin_head', array($this, 'register_custom_meta_box'), 10);
	}

	/**
	 * Initial load of the plugin.
	 */
	public function init(): void
	{
		$this->load_textdomain();
		$this->register_menupages();
		$this->load_dependencies();
		if (!\is_admin() && function_exists('get_fields')) {
			\add_action('wp_footer', array($this, 'render_footer'));
		}
	}

	/**
	 * Add Plugin to the Footer of Frontend.
	 *
	 * @throws \Twig\Error\LoaderError  loaderError
	 * @throws \Twig\Error\RuntimeError runtimeError
	 * @throws \Twig\Error\SyntaxError  syntaxerror
	 */
	public function render_footer(): void
	{
		$loader = new \Twig\Loader\FilesystemLoader(TOWA_GDPR_PLUGIN_DIR . '/views/');
		$twig = new \Twig\Environment($loader);
		$function = new \Twig\TwigFunction(
			'__',
			function (string $string, string $textdomain = 'towa-gdpr-plugin') {
				return __($string, $textdomain); //phpcs:ignore
			}
		);

		$twig->addFunction($function);

		$data = self::get_data();

		$template = $twig->load('cookie-notice.twig');
		echo $template->render($data); // phpcs:ignore
	}

	/**
	 * Register all menu pages from Configuration file & register ACF Fields.
	 */
	private function register_menupages(): void
	{
		if (!function_exists('acf_add_options_page')) {
			\add_action('admin_notices', array($this, 'my_acf_notice'));
		} else {
			collect($this->config->getSubConfig('Settings.submenu_pages')->getAll())->map(
				function ($menupage) {
					[   //phpcs:ignore
						'page_title' => $page_title,
						'menu_title' => $menu_title,
						'menu_slug' => $menu_slug,
						'capability' => $capability,
						'redirect' => $redirect,
					] = $menupage; //phpcs:ignore

					\acf_add_options_page(
						array(
							'page_title' => $page_title,
							'menu_title' => $menu_title,
							'menu_slug' => $menu_slug,
							'capability' => $capability,
							'redirect' => $redirect,
						)
					);

					(new AcfSettings())->register($menu_slug);
					(new AcfCookies())->register($menu_slug);
				}
			);
		}
	}

	/**
	 * Load dependencies automatically from config file.
	 */
	private function load_dependencies(): void
	{
		$dependencies = new DependencyManager($this->config->getSubConfig('Settings.submenu_pages.0.dependencies'));
		add_action('init', array($dependencies, 'register'));
		if (\get_field('tagmanager', 'option')) {
			$tagmanagerDependencies = new DependencyManager($this->config->getSubConfig('Settings.tagmanager.dependencies'));
			add_action('init', array($tagmanagerDependencies, 'register'));
		}
	}

	/**
	 * Adds notice to WordPress Backend if Acf is not active.
	 */
	public function my_acf_notice(): void
	{
?>
		<div class="error">
			<p><?php \_e('<b>Towa GDPR Plugin:</b> Please install and activate ACF Pro', $this->config->getKey('Plugin.textdomain')); // phpcs:ignore
					?>
		</div>
<?php
	}

	/**
	 * Load the plugin text domain.
	 */
	private function load_textdomain(): void
	{
		$text_domain = $this->config->getKey('Plugin.textdomain');
		$languages_dir = 'languages';
		if ($this->config->hasKey('Plugin/languages_dir')) {
			$languages_dir = $this->config->getKey('Plugin.languages_dir');
		}

		\load_plugin_textdomain($text_domain, false, $text_domain . '/' . $languages_dir);
	}

	/**
	 * Hook to be run on save.
	 */
	public function save_options_hook(): void
	{
		$screen = \get_current_screen();
		if (strpos($screen->id, 'towa-gdpr-plugin') !== false) {
			if (!isset($_POST['acf']['towa_gdpr_settings_hash']) || $_POST['acf']['towa_gdpr_settings_hash'] === '' || sanitize_text_field($_POST['save_and_hash'])) {
				\update_field('towa_gdpr_settings_hash', (new Hash())->get_hash(), 'option');
			}
			\delete_transient(self::TRANSIENT_KEY);
		}
	}

	/**
	 * register custom meta box for hash regeneration.
	 */
	public function register_custom_meta_box(): void
	{
		$screen = \get_current_screen();

		if (strpos($screen->id, 'towa-gdpr-plugin') !== false) {
			\add_meta_box(
				'towa-gdpr-plugin-meta',
				__(
					'publish & force new consent',
					'towa-gdpr-plugin'
				),
				array($this, 'display_acf_metabox'),
				'acf_options_page',
				'side'
			);
		}
	}

	/**
	 * display additional meta box for hash regeneration.
	 */
	public function display_acf_metabox(): void
	{
		$loader = new \Twig\Loader\FilesystemLoader(TOWA_GDPR_PLUGIN_DIR . '/views/');
		$twig = new \Twig\Environment($loader);
		$function = new \Twig\TwigFunction(
			'__',
			function (string $string, string $textdomain = 'towa-gdpr-plugin') {
				return __($string, $textdomain); //phpcs:ignore
			}
		);

		$twig->addFunction($function);

		$template = $twig->load('meta-box.twig');
		echo $template->render(); // phpcs:ignore
	}

	/**
	 * Return Settings.
	 */
	public static function get_data(): array
	{
		$data = [];
		$transient = \get_transient(self::TRANSIENT_KEY);

		if (!empty($transient)) {
			$data = $transient;
		} else {
			$data = \get_fields('options');
			// transient valid for one month
			\set_transient(self::TRANSIENT_KEY, $data, 60 * 60 * 24 * 30);
		}
		// modify data to have uniform groups reason: acf doesn't work if they are named the same way
		if (isset($data['essential_group'])) {
			$data['essential_group'] = array(
				'title' => $data['essential_group']['essential_title'],
				'group_description' => $data['essential_group']['essential_group_description'],
				'cookies' => $data['essential_group']['essential_cookies'],
			);
		}
		if(isset($data['no_cookie_pages'])){
			$data['no_cookie_pages'] = collect($data['no_cookie_pages'])->map(function($page){
				return \get_permalink($page);
			})->all();
		}

		return is_array($data) ? $data : [];;
	}
}
