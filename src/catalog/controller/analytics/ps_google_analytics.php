<?php
namespace Opencart\Catalog\Controller\Extension\PsGoogleAnalytics\Analytics;
/**
 * Class PsGoogleAnalytics
 *
 * @package Opencart\Catalog\Controller\Extension\PsGoogleAnalytics\Analytics
 */
class PsGoogleAnalytics extends \Opencart\System\Engine\Controller
{
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
