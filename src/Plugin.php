<?php
/**
 * Main plugin file
 *
 * @package      Towa\DsgvoPlugin
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Towa\DsgvoPlugin;

use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use BrightNucleus\Settings\Settings;
use BrightNucleus\Dependency\DependencyManager;

/**
 * Main plugin class.
 *
 * @since   0.1.0
 *
 * @package Towa\DsgvoPlugin
 * @author  Martin Welte
 */
class Plugin {

	use ConfigTrait;

	/**
	 * Static instance of the plugin.
	 *
	 * @since 0.1.0
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Instantiate a Plugin object.
	 *
	 * Don't call the constructor directly, use the `Plugin::get_instance()`
	 * static method instead.
	 *
	 * @since 0.1.0
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
	 *
	 * @since 0.1.0
	 */
	public function run(): void {
		\add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init(): void {
		$this->load_textdomain();
		$this->register_menupages();
		$this->load_dependencies();
		if(!is_admin()){
			\add_action('wp_footer', [$this,'render_footer']);
		}
	}

public function  render_footer():void {
		$loader = new \Twig\Loader\FilesystemLoader(TOWA_DSGVO_PLUGIN_DIR.'/views/');
		$twig = new \Twig\Environment($loader);
		$cookies = Cookies::get_fields();

		$function = new \Twig\TwigFunction('__', function (string $string, string $textdomain) {
				return __($string,$textdomain);
		});

		$twig->addFunction($function);
		$template = $twig->load('cookie-notice.twig');

		echo $template->render($cookies);
	}

	private function register_menupages(){
		if (!function_exists('acf_add_options_page')) {
			\add_action( 'admin_notices', [$this,'my_acf_notice' ]);
		}
		else{
			collect($this->config->getSubConfig('Settings.submenu_pages')->getAll())->map(function($menupage){
					[
								'page_title' => $page_title,
								'menu_title' => $menu_title,
								'menu_slug' => $menu_slug,
								'capability' => $capability,
								'redirect' => $redirect,
						] = $menupage;

						\acf_add_options_page([
								'page_title' => $page_title,
								'menu_title' => $menu_title,
								'menu_slug' => $menu_slug,
								'capability' => $capability,
								'redirect' => $redirect,
						]);

						(new Cookies())->register($menu_slug);
				});
			}
	}

	private function load_dependencies(): void {
			$dependencies = new DependencyManager($this->config->getSubConfig('Settings.submenu_pages.0.dependencies'));
			add_action( 'init', [ $dependencies, 'register' ] );
	}

	public function my_acf_notice(){
		?>
			<div class="error">
					<p><?php _e('<b>Towa DSGVO Plugin:</b> Please install and activate ACF Pro',$this->config->getKey('Plugin.textdomain'));?>
			</div>
			<?php
	}

	/**
	 * Load the plugin text domain.
	 *
	 * @since 0.1.0
	 */
	private function load_textdomain(): void {
		$text_domain   = $this->config->getKey( 'Plugin.textdomain' );
		$languages_dir = 'languages';
		if ( $this->config->hasKey( 'Plugin/languages_dir' ) ) {
			$languages_dir = $this->config->getKey( 'Plugin.languages_dir' );
		}

		\load_plugin_textdomain( $text_domain, false, $text_domain . '/' . $languages_dir );
	}
}
