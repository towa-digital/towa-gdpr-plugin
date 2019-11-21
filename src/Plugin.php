<?php
/**
 * Main plugin file
 *
 * @package      Towa\DsgvoPlugin
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare(strict_types=1);

namespace Towa\DsgvoPlugin;

use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use BrightNucleus\Dependency\DependencyManager;

/**
 * Main plugin class.
 *
 * @package Towa\DsgvoPlugin
 * @author  Martin Welte
 */
class Plugin {


	use ConfigTrait;

	/**
	 * Static instance of the plugin.
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Transient used for settings key
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
	 * @throws FailedToProcessConfigException If the Config could not be parsed correctly.
	 *
	 * @param ConfigInterface $config Config to parametrize the object.
	 */
	public function __construct( ConfigInterface $config ) {
		$this->processConfig( $config );
	}

	/**
	 * Launch the initialization process.
	 */
	public function run(): void {
		add_action( 'acf/save_post', array( $this, 'save_options_hook' ), 20 );
		\add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initial load of the plugin
	 */
	public function init(): void {
		$this->load_textdomain();
		$this->register_menupages();
		$this->load_dependencies();
		if ( ! is_admin() && function_exists( 'get_fields' ) ) {
			\add_action( 'wp_footer', array( $this, 'render_footer' ) );
		}
	}

	/**
	 * Add Plugin to the Footer of Frontend
	 *
	 * @throws \Twig\Error\LoaderError LoaderError.
	 * @throws \Twig\Error\RuntimeError RuntimeError.
	 * @throws \Twig\Error\SyntaxError Syntaxerror.
	 */
	public function render_footer(): void {
		$loader = new \Twig\Loader\FilesystemLoader( TOWA_DSGVO_PLUGIN_DIR . '/views/' );
		$twig   = new \Twig\Environment( $loader );

		$transient = get_transient( self::TRANSIENT_KEY );

		if ( ! empty( $transient ) ) {
			$data = $transient;
		} else {
			$data = get_fields( 'options' );
			set_transient( self::TRANSIENT_KEY, $data, MONTH_IN_SECONDS );
		}

		$function = new \Twig\TwigFunction(
			'__',
			function ( string $string, string $textdomain = 'towa-dsgvo-plugin' ) {
				return __( $string, $textdomain ); //phpcs:ignore
			}
		);

		$twig->addFunction( $function );
		$template = $twig->load( 'cookie-notice.twig' );
		echo $template->render( $data ); // phpcs:ignore
	}

	/**
	 * Register all menu pages from Configuration file & register ACF Fields
	 */
	private function register_menupages():void {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			\add_action( 'admin_notices', array( $this, 'my_acf_notice' ) );
		} else {
			collect( $this->config->getSubConfig( 'Settings.submenu_pages' )->getAll() )->map(
				function ( $menupage ) {
					[   //phpcs:ignore
						'page_title' => $page_title,
						'menu_title' => $menu_title,
						'menu_slug'  => $menu_slug,
						'capability' => $capability,
						'redirect'   => $redirect,
					] = $menupage; //phpcs:ignore

					\acf_add_options_page(
						array(
							'page_title' => $page_title,
							'menu_title' => $menu_title,
							'menu_slug'  => $menu_slug,
							'capability' => $capability,
							'redirect'   => $redirect,
						)
					);

					( new AcfSettings() )->register( $menu_slug );
					( new AcfCookies() )->register( $menu_slug );
				}
			);
		}
	}

	/**
	 * Load dependencies automatically from config file
	 */
	private function load_dependencies(): void {
		$dependencies = new DependencyManager( $this->config->getSubConfig( 'Settings.submenu_pages.0.dependencies' ) );
		add_action( 'init', array( $dependencies, 'register' ) );
	}

	/**
	 * Adds notice to WordPress Backend if Acf is not active
	 */
	public function my_acf_notice(): void {
		?>
			<div class="error">
					<p><?php _e( '<b>Towa DSGVO Plugin:</b> Please install and activate ACF Pro', $this->config->getKey( 'Plugin.textdomain' ) ); // phpcs:ignore ?>
			</div>
		<?php
	}

	/**
	 * Load the plugin text domain.
	 */
	private function load_textdomain(): void {
		$text_domain   = $this->config->getKey( 'Plugin.textdomain' );
		$languages_dir = 'languages';
		if ( $this->config->hasKey( 'Plugin/languages_dir' ) ) {
			$languages_dir = $this->config->getKey( 'Plugin.languages_dir' );
		}

		\load_plugin_textdomain( $text_domain, false, $text_domain . '/' . $languages_dir );
	}

	/**
	 * Hook to be run on save
	 */
	public function save_options_hook(): void {
			$screen = get_current_screen();
		if ( strpos( $screen->id, 'towa-dsgvo-plugin' ) !== false ) {
			delete_transient( self::TRANSIENT_KEY );
		}
	}
}
