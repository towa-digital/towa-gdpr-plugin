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
use Towa\GdprPlugin\Acf\AcfCookies;
use Towa\GdprPlugin\Acf\AcfSettings;
use Towa\GdprPlugin\Rest\Rest;

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
     * @param ConfigInterface $config config to parametrize the object
     *
     * @throws FailedToProcessConfigException if the Config could not be parsed correctly
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
        add_action('acf/save_post', [$this, 'saveOptionsHook'], 20);
        add_action('acf/init', [$this, 'init']);
        add_action('acf/input/admin_head', [$this, 'registerCustomMetaBox'], 10);
        add_action('init', [$this, 'initControllers']);
        add_action('wp_head', [$this, 'addMetaTagNoCookieSite']);
    }

    /**
     * Initial load of the plugin.
     */
    public function init(): void
    {
        $this->loadTextdomain();
        $this->registerMenupages();
        $this->loadDependencies();
        if (!\is_admin() && function_exists('get_fields')) {
            \add_action('wp_footer', [$this, 'loadFooter']);
        }
    }

    /**
     * Initialize Controllers
     */
    public function initControllers(): void
    {
        (new Rest())->registerRoutes();
    }

    /**
     * Add Plugin to the Footer of Frontend.
     *
     * @throws \Twig\Error\LoaderError  loaderError
     * @throws \Twig\Error\RuntimeError runtimeError
     * @throws \Twig\Error\SyntaxError  syntaxerror
     */
    public function loadFooter(): void
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

        $data = self::getData();

        $template = $twig->load('cookie-notice.twig');
        echo $template->render($data); // phpcs:ignore
    }

    /**
     * Register all menu pages from Configuration file & register ACF Fields.
     */
    private function registerMenupages(): void
    {
        if (!function_exists('acf_add_options_page')) {
            \add_action('admin_notices', [$this, 'myAcfNotice']);
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
                        [
                            'page_title' => $page_title,
                            'menu_title' => $menu_title,
                            'menu_slug' => $menu_slug,
                            'capability' => $capability,
                            'redirect' => $redirect,
                        ]
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
    private function loadDependencies(): void
    {
        $dependencies = new DependencyManager($this->config->getSubConfig('Settings.submenu_pages.0.dependencies'));
        add_action('init', [$dependencies, 'register']);
        if (\get_field('tagmanager', 'option')) {
            $tagmanagerDependencies = new DependencyManager($this->config->getSubConfig('Settings.tagmanager.dependencies'));
            add_action('init', [$tagmanagerDependencies, 'register']);
        }
    }

    /**
     * Adds notice to WordPress Backend if Acf is not active.
     */
    public function myAcfNotice(): void
    {
        ?>
        <div class="error">
            <p>
                <?php
                \_e('<b>Towa GDPR Plugin:</b> Please install and activate ACF Pro', $this->config->getKey('Plugin.textdomain')); // phpcs:ignores
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * Load the plugin text domain.
     */
    private function loadTextdomain(): void
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
    public function saveOptionsHook(): void
    {
        $screen = \get_current_screen();
        if (false !== strpos($screen->id, 'towa-gdpr-plugin')) {
            if (!isset($_POST['acf']['towa_gdpr_settings_hash']) || '' === $_POST['acf']['towa_gdpr_settings_hash'] || isset($_POST['save_and_hash'])) {
                \update_field('towa_gdpr_settings_hash', (new Hash())->getHash(), 'option');
            }
            \delete_transient(self::TRANSIENT_KEY . get_locale());
        }
        (new SettingsTableAdapter())->save();
    }

    /**
     * Register custom meta box for hash regeneration.
     */
    public function registerCustomMetaBox(): void
    {
        $screen = \get_current_screen();

        if (false !== strpos($screen->id, 'towa-gdpr-plugin')) {
            \add_meta_box(
                'towa-gdpr-plugin-meta',
                __(
                    'publish & force new consent',
                    'towa-gdpr-plugin'
                ),
                [$this, 'displayAcfMetabox'],
                'acf_options_page',
                'side'
            );
        }
    }

    /**
     * Display additional meta box for hash regeneration.
     */
    public function displayAcfMetabox(): void
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
    public static function getData(): array
    {
        $data = \get_transient(self::TRANSIENT_KEY . get_locale());
        if (!$data) {
            $data = \get_fields('options');
            // transient valid for one month
            \set_transient(self::TRANSIENT_KEY . get_locale(), $data, 60 * 60 * 24 * 30);
        }
        // modify data to have uniform groups reason: acf doesn't work if they are named the same way
        if (isset($data['essential_group'])) {
            $data['essential_group'] = [
                'title' => $data['essential_group']['essential_title'],
                'group_description' => $data['essential_group']['essential_group_description'],
                'cookies' => $data['essential_group']['essential_cookies'],
            ];
        }

        $data['consent_url'] = get_rest_url(null, Rest::TOWA_GDPR_REST_NAMESPACE . Rest::CONSENT_ENDPOINT);

        return $data;
    }

    /**
     * Add Meta Tag to Sites which are non-cookie Sites
     */
    public function addMetaTagNoCookieSite()
    {
        global $post;
        $data = self::getData();
        if (is_array($data['no_cookie_pages']) && in_array($post->ID, $data['no_cookie_pages'])) {
            ?>
                <meta name="towa-gdpr-no-cookies" content="true"/>
            <?php
        }
    }
}
