<?php
namespace Opencart\Catalog\Controller\Extension\PsGoogleAnalytics\Analytics;
/**
 * Playful Sparkle - Google Analytics (GA4) Extension for OpenCart 4
 *
 * This extension allows users to integrate Google Analytics 4 (GA4) tracking into their OpenCart store
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

        return <<<HTML
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={$google_tag_id}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag(){ dataLayer.push(arguments); }

            gtag('js', new Date());
            gtag('config', '{$google_tag_id}');
        </script>
        HTML;
    }
}
