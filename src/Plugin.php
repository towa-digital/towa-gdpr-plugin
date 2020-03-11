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
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Towa\GdprPlugin\Acf\AcfCookies;
use Towa\GdprPlugin\Acf\AcfSettings;
use Towa\GdprPlugin\Rest\Rest;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Main plugin class.
 *
 * @author  Martin Welte <martin.welte@towa.at>
 */
class Plugin
{
    use ConfigTrait;

    const TOWA_GDPR_AJAX_URL = 'towa/gdpr/checkip';
    const TOWA_IP_DIR_UPLOADPERMISSIONS = 0750;

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
        add_action('plugins_loaded', [$this, 'handleCheckIpRequest'], 0);
        add_action('activate_towa-gdpr-plugin.php', [$this, 'activatePlugin']);
        add_action('acf/save_post', [$this, 'saveOptionsHook'], 20);
        add_action('acf/init', [$this, 'init']);
        add_action('acf/input/admin_head', [$this, 'registerCustomMetaBox'], 10);
        add_action('rest_api_init', [$this, 'initRest']);
        add_action('wp_head', [$this, 'addMetaTagNoCookieSite']);
        add_action('acf/validate_value/key=towa_gdpr_settings_towa_gdpr_ip', [$this, 'validateIp'], 10, 2);
        add_action('admin_init', [$this, 'syncJsonFile']);
        if (!function_exists('acf_add_options_page')) {
            add_action('admin_notices', [$this, 'myAcfNotice']);
        }
        if (!\is_admin() && function_exists('get_fields')) {
            add_action('wp_footer', [$this, 'loadFooter']);
        }
    }

    /**
     * Initial load of the plugin.
     */
    public function init(): void
    {
        $this->loadTextdomain();
        $this->registerMenupages();
        $this->loadDependencies();
        $this->activatePlugin();
    }

    /**
     * Initialize Rest
     */
    public function initRest(): void
    {
        (new Rest())->registerRestEndpoints();
    }

    /**
     * Activate Plugin Hook:
     * - Updates Table Structures
     */
    public function activatePlugin(): void
    {
        SettingsTableAdapter::updateTableStructure();
    }

    /**
     * Uninstall Plugin Hook
     * - drops Table created by Settings Table Adapter
     */
    public static function uninstallPlugin(): void
    {
        \delete_transient(self::TRANSIENT_KEY . get_locale());
        AcfSettings::deleteFields();
        AcfCookies::deleteFields();
        SettingsTableAdapter::destroyTable();
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
        if (function_exists('acf_add_options_page')) {
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
        if (!\is_admin()) {
            $dependencies = new DependencyManager($this->config->getSubConfig('Settings.frontend.dependencies'));
            add_action('init', [$dependencies, 'register']);
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

            $ips = [];
            if (isset($_POST['acf']['towa_gdpr_settings_towa_gdpr_internal']) && $internalIps = $_POST['acf']['towa_gdpr_settings_towa_gdpr_internal']) {
                foreach ($internalIps as $ip) {
                    $ips[] = trim($ip['towa_gdpr_settings_towa_gdpr_ip']);
                }
            }

            if ($ips) {
                $this->writeJsonFile($ips);
            }
        }
        (new SettingsTableAdapter())->save();
    }

    /**
     * returns path for json file
     *
     * @return string
     */
    public static function getJsonFileName(): string
    {
       return implode('/', [TOWA_GDPR_DATA, 'ip', 'ip.json']);
    }

    /**
     * writes IPs to JsonFile for better performance
     *
     * @param array $ips
     */
    private function writeJsonFile(array $ips)
    {
        $fileName = self::getJsonFileName();

        if (file_exists($fileName) && !$ips) {
            @unlink($fileName);
        } elseif ($ips) {
            $pathParts = pathinfo($fileName);

            if (!@mkdir($concurrentDirectory = $pathParts['dirname'], self::TOWA_IP_DIR_UPLOADPERMISSIONS, true) && !is_dir($concurrentDirectory)) {
                global $errors;
                if (!$errors) {
                    $errors = new \WP_Error();
                }
                $errors->add(500, sprintf('Directory "%s" was not created', $concurrentDirectory));
            }

            @file_put_contents($fileName, json_encode($ips));
        }
    }

    /**
     * checks if jsonFile is missing or should be deleted on admin_init
     */
    public function syncJsonFile(): void
    {
        if (defined('DOING_AJAX') && !DOING_AJAX) {
            $fields = false;

            if (!file_exists(self::getJsonFileName()) && $fields = get_field('towa_gdpr_internal', 'option')) {
                $ips = [];
                foreach ($fields as $field) {
                    $ips[] = $field['towa_gdpr_ip'];
                }

                if ($ips) {
                    $this->writeJsonFile($ips);
                }
            } elseif (!$fields && file_exists(self::getJsonFileName())) {
                @unlink(self::getJsonFileName());
            }
        }
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
     * Add Meta Tag To No Cookie Site
     */
    public function addMetaTagNoCookieSite(): void
    {
        global $post;
        $cookie_pages = get_field('towa_gdpr_settings_no_cookie_pages', 'options', false);
        if (is_array($cookie_pages) && in_array($post->ID, $cookie_pages)) {
            echo '<meta name="towa-gdpr-no-cookies" content="true"/>';
        }
    }

    /**
     * performs validation for towa_gdpr_ip
     *
     * @param mixed $valid
     * @param mixed $value
     * @return mixed
     */
    public function validateIp($valid, $value)
    {
        if ($valid) {
            $address = $value;
            $netmask = null;

            if (false !== strpos($address, '/')) {
                list($address, $netmask) = explode('/', $address, 2);
            }

            if (false !== strpos($address, ':')) {
                if ($netmask && ($netmask < 1 || $netmask > 128)) {
                    return sprintf('%s %s', $netmask, __('is not a valid netmask', 'towa-gdpr-plugin'));
                }
            } elseif ($netmask && ($netmask < 0 || $netmask > 32)) {
                return sprintf('%s %s', $netmask, __('is not a valid netmask', 'towa-gdpr-plugin'));
            }

            if (!filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return sprintf('%s %s', $value, __('is not a valid IP', 'towa-gdpr-plugin'));
            }
        }

        return $valid;
    }

    /**
     * handles Requests for TOWA_GDPR_AJAX_URL and checks if request is internal
     */
    public function handleCheckIpRequest(): void
    {
        $request = Request::createFromGlobals();
        $request::setTrustedProxies(['127.0.0.1', 'REMOTE_ADDR'], Request::HEADER_X_FORWARDED_ALL);
        $uri = trim($request->getRequestUri(), '/');
        $clientIp = $request->getClientIp();
        $internal = false;

        if ($uri === self::TOWA_GDPR_AJAX_URL) {
            if (!filter_var($clientIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $internal = true;
            }

            if (!$internal && file_exists(self::getJsonFileName())) {
                $ips = file_get_contents(self::getJsonFileName());
                $internal = IpUtils::checkIp($clientIp, array_values(json_decode($ips, true)));
            }

            $response = (new JsonResponse(['internal' => $internal]))->setPrivate()->setMaxAge(0)->setSharedMaxAge(0);
            $response->send();

            exit();
        }
    }
}
