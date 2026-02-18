<?php
namespace Opencart\Admin\Controller\Extension\PsGoogleAnalytics\Analytics;
/**
 * Playful Sparkle - Google Analytics (GA4)
 *
 * This extension allows users to integrate Google Analytics (GA4) tracking into their OpenCart store
 * without any coding skills. The extension provides a user-friendly interface for setting up and managing
 * Google Analytics tracking.
 *
 * @package Opencart\Admin\Controller\Extension\PsGoogleAnalytics\Analytics
 * @subpackage  Google Analytics (GA4)
 * @version     1.0.0
 * @link        https://github.com/playfulsparkle/oc4_google_analytics.git
 * @email       support@playfulsparkle.com
 */
class PsGoogleAnalytics extends \Opencart\System\Engine\Controller
{
    /**
     * @var string The support email address.
     */
    const EXTENSION_EMAIL = 'support@playfulsparkle.com';

    /**
     * @var string The documentation URL for the extension.
     */
    const EXTENSION_DOC = 'https://github.com/playfulsparkle/oc4_google_analytics.git';

    /**
     * Displays the main interface for the Google Analytics settings.
     *
     * This method loads the necessary language files, sets the page title,
     * prepares the breadcrumb navigation, and gathers data for the view.
     *
     * @return void
     */
    public function index(): void
    {
        $this->load->language('extension/ps_google_analytics/analytics/ps_google_analytics');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics', true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/analytics/ps_google_analytics', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true),
        ];


        $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        $data['action'] = $this->url->link('extension/ps_google_analytics/analytics/ps_google_analytics' . $separator . 'save', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics');

        $data['analytics_ps_google_analytics_status'] = (bool) $this->config->get('analytics_ps_google_analytics_status');
        $data['analytics_ps_google_analytics_debug_mode'] = (bool) $this->config->get('analytics_ps_google_analytics_debug_mode');
        $data['analytics_ps_google_analytics_google_tag_id'] = $this->config->get('analytics_ps_google_analytics_google_tag_id');

        $data['text_contact'] = sprintf($this->language->get('text_contact'), self::EXTENSION_EMAIL, self::EXTENSION_EMAIL, self::EXTENSION_DOC);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/ps_google_analytics/analytics/ps_google_analytics', $data));
    }

    /**
     * Saves the Google Analytics settings.
     *
     * This method processes the submitted data, checks for permissions, validates the Google Tag ID,
     * and saves the settings if everything is correct.
     *
     * @return void
     */
    public function save(): void
    {
        $this->load->language('extension/ps_google_analytics/analytics/ps_google_analytics');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/ps_google_analytics/analytics/ps_google_analytics')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            if (empty($this->request->post['analytics_ps_google_analytics_google_tag_id'])) {
                $json['error']['input-google-tag-id'] = $this->language->get('error_google_tag_id');
            } elseif (preg_match('/^G-[A-Z0-9]{10}$/', strtoupper($this->request->post['analytics_ps_google_analytics_google_tag_id'])) !== 1) {
                $json['error']['input-google-tag-id'] = $this->language->get('error_google_tag_id_invalid');
            }
        }

        if (!$json) {
            $this->load->model('setting/setting');

            $this->request->post['analytics_ps_google_analytics_google_tag_id'] = strtoupper($this->request->post['analytics_ps_google_analytics_google_tag_id']);

            $this->model_setting_setting->editSetting('analytics_ps_google_analytics', $this->request->post);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function install(): void
    {
        $this->load->model('setting/setting');

        $data = [
            'analytics_ps_google_analytics_status' => 0,
            'analytics_ps_google_analytics_debug_mode' => 0,
            'analytics_ps_google_analytics_google_tag_id' => '',
        ];

        $this->model_setting_setting->editSetting('analytics_ps_google_analytics', $data);
    }

    public function uninstall(): void
    {

    }
}
