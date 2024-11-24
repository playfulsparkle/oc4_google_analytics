<?php
namespace Opencart\Catalog\Controller\Extension\PsGoogleAnalytics\Analytics;
/**
 * Playful Sparkle - Google Analytics (GA4)
 *
 * This extension allows users to integrate Google Analytics (GA4) tracking into their OpenCart store
 * without any coding skills. The extension provides a user-friendly interface for setting up and managing
 * Google Analytics tracking.
 *
 * @package Opencart\Catalog\Controller\Extension\PsGoogleAnalytics\Analytics
 * @subpackage  Google Analytics (GA4)
 * @version     1.0.0
 * @link        https://github.com/playfulsparkle/oc4_google_analytics.git
 * @email       support@playfulsparkle.com
 */
class PsGoogleAnalytics extends \Opencart\System\Engine\Controller
{
    /**
     * Generates the Google Analytics tracking code if enabled.
     *
     * This method checks whether the Google Analytics tracking is enabled in the settings.
     * If enabled, it retrieves the Google Tag ID from the configuration and returns the HTML
     * necessary to integrate Google Analytics tracking into the frontend of the store.
     *
     * @return string Returns the Google Analytics tracking code as a string.
     *               Returns an empty string if Google Analytics is not enabled.
     */
    public function index(): string
    {
        if (!$this->config->get('analytics_ps_google_analytics_status')) {
            return '';
        }

        $google_tag_id = $this->config->get('analytics_ps_google_analytics_google_tag_id');

        $gtag_config = [];

        if ($this->config->get('analytics_ps_google_analytics_debug_mode')) {
            $gtag_config['debug_mode'] = true;
        }

        if ($this->request->server['HTTPS']) {
            $gtag_config['cookie_flags'] = 'SameSite=None;Secure';
        }

        $gtag_config_json = $gtag_config ? json_encode($gtag_config) : null;

        $html = '<!-- Google tag (gtag.js) -->' . PHP_EOL;
        $html .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $google_tag_id . '"></script>' . PHP_EOL;
        $html .= "<script>" . PHP_EOL;
        $html .= "window.dataLayer = window.dataLayer || [];" . PHP_EOL;
        $html .= "function gtag() { dataLayer.push(arguments); }" . PHP_EOL . PHP_EOL;
        $html .= "gtag('js', new Date());" . PHP_EOL;

        if ($gtag_config_json) {
            $html .= "gtag('config', '" . $google_tag_id . "', " . $gtag_config_json . ");" . PHP_EOL;
        } else {
            $html .= "gtag('config', '" . $google_tag_id . "');" . PHP_EOL;
        }

        $html .= "</script>" . PHP_EOL;

        return $html;
    }
}
