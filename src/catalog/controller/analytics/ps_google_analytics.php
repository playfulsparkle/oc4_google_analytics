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
        /**
         * Checks if the Google Analytics tracking is enabled.
         *
         * This setting, 'analytics_ps_google_analytics_status', is retrieved from the configuration.
         * If disabled, the function will return an empty string, meaning no tracking code is added to the page.
         */
        if (!$this->config->get('analytics_ps_google_analytics_status')) {
            return '';
        }

        /**
         * Retrieves the Google Tag ID for Google Analytics from the configuration.
         *
         * This ID (e.g., 'G-XXXXXXXXXX') uniquely identifies the Google Analytics property and
         * is required to initialize tracking. If this ID is missing or incorrect, tracking will not work.
         */
        $google_tag_id = $this->config->get('analytics_ps_google_analytics_google_tag_id');

        /**
         * Outputs the HTML and JavaScript code required for Google Analytics tracking.
         *
         * The output includes:
         * - Loading the Google Analytics script asynchronously.
         * - Initializing the `dataLayer` object, which serves as a container for analytics data.
         * - Defining the `gtag` function to add commands to `dataLayer` for processing by Google Analytics.
         * - Setting the current timestamp to mark when the script was loaded.
         * - Configuring Google Analytics using the retrieved Google Tag ID and adding `cookie_flags` for
         *   compatibility with cross-site requests, marking the cookie as `SameSite=None` and `Secure`.
         *
         * @return string Google Analytics tracking code with specified configurations.
         */
        return <<<HTML
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={$google_tag_id}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }

            gtag('js', new Date());
            gtag('config', '{$google_tag_id}', {'cookie_flags': 'SameSite=None;Secure'});
        </script>
        HTML;
    }
}
