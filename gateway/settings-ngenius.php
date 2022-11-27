<?php

/**
 * Settings for N-Genius Gateway.
 */
defined('ABSPATH') || exit;

return array(
    'enabled'        => array(
        'title'   => __('Enable/Disable', 'woocommerce'),
        'label'   => __('Enable N-Genius Payment Gateway', 'woocommerce'),
        'type'    => 'checkbox',
        'default' => 'no',
    ),
    'title'          => array(
        'title'       => __('Title', 'woocommerce'),
        'type'        => 'text',
        'description' => __('The title which the user sees during checkout.', 'woocommerce'),
        'default'     => __('N-Genius Payment Gateway', 'woocommerce'),
    ),
    'description'    => array(
        'title'       => __('Description', 'woocommerce'),
        'type'        => 'textarea',
        'css'         => 'width: 400px;height:60px;',
        'description' => __('The description which the user sees during checkout.', 'woocommerce'),
        'default'     => __('You will be redirected to payment gateway.', 'woocommerce'),
    ),
    'environment'    => array(
        'title'   => __('Environment', 'woocommerce'),
        'type'    => 'select',
        'class'   => 'wc-enhanced-select',
        'options' => array(
            'uat'  => __('UAT', 'woocommerce'),
            'live' => __('Live', 'woocommerce'),
        ),
        'default' => 'uat',
    ),
    'uat_api_url'    => array(
        'title'   => __('UAT API URL', 'woocommerce'),
        'type'    => 'text',
        'default' => 'https://api-gateway-uat.acme.ngenius-payments.com',
    ),
    'live_api_url'    => array(
        'title'   => __('LIVE API URL', 'woocommerce'),
        'type'    => 'text',
        'default' => 'https://api-gateway.ngenius-payments.com',
    ),
    'payment_action' => array(
        'title'   => __('Payment Action', 'woocommerce'),
        'type'    => 'select',
        'class'   => 'wc-enhanced-select',
        'options' => array(
            'sale'      => __('Sale', 'woocommerce'),
            'authorize' => __('Authorize', 'woocommerce'),
            'purchase' => __('Purchase', 'woocommerce'),
        ),
        'default' => 'sale',
    ),
    'order_status'   => array(
        'title'   => __('Status of new order', 'woocommerce'),
        'type'    => 'select',
        'class'   => 'wc-enhanced-select',
        'options' => array(
            'ngenius_pending' => __('N-Genius Pending', 'woocommerce'),
        ),
        'default' => 'ngenius_pending',
    ),
    'outlet_ref'     => array(
        'title' => __('Outlet Reference ID', 'woocommerce'),
        'type'  => 'text',
    ),
    'api_key'        => array(
        'title' => __('API Key', 'woocommerce'),
        'type'  => 'textarea',
        'css'   => 'width: 400px;height:50px;',
    ),
    'debug'          => array(
        'title'       => __('Debug Log', 'woocommerce'),
        'type'        => 'checkbox',
        'label'       => __('Enable logging', 'woocommerce'),
        'description' => sprintf(
            __('Log file will be %s', 'woocommerce'),
            '<code>' . WC_Log_Handler_File::get_log_file_path('ngenius') . '</code>'
        ),
        'default'     => 'no',
    ),
);
