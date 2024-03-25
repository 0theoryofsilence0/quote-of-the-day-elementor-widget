<?php
/*
Plugin Name: Quote of the Day
Description: An Elementor add-on widget that lets users add a "Quote of the Day" to any page using the Elementor builder.
Version: 1.0
Author: Julius Enriquez
Author URI: https://0theoryofsilence0.github.io/jenriquez/
Text Domain: qotd_elementor
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include the Elementor widget class
add_action('elementor/widgets/widgets_registered', function () {
    require_once dirname(__FILE__) . '/qotd-widget.php';
});

function qotd_check_elementor()
{
    // Check if Elementor is installed but not active
    if (check_plugin_installed('elementor')) {
        if (!is_plugin_active('elementor/elementor.php')) {
            if (current_user_can('activate_plugins')) {
                // Elementor is installed but not active, show "Activate" button
                $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . 'elementor/elementor.php' . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_elementor/elementor.php');
                $message = '<p>' . __('Quote of the Day plugin is not working because you need to activate Elementor plugin.', 'qotd_elementor') . '</p>';
                $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, __('Activate Now', 'qotd_elementor')) . '</p>';
                add_action('admin_notices', function () use ($message) {
                    echo '<div class="notice notice-error is-dismissible">' . $message . '</div>';
                });
                // Deactivate the plugin
                deactivate_plugins(plugin_basename(__FILE__));
                return;
            }
        }
    } else {
        // Elementor is not installed, show "Install" button
        if (current_user_can('install_plugins')) {
            $install_url = wp_nonce_url(self_admin_url(sprintf('update.php?action=install-plugin&plugin=%s', 'elementor')), 'install-plugin_elementor');
            $message = sprintf('<p>%s</p>', __('Quote of the Day plugin is not working because you need to Install Elementor plugin.', 'qotd_elementor'));
            $message .= sprintf('<p><a href="%s" class="button-primary">%s</a></p>', $install_url, __('Install Now', 'qotd_elementor'));
            add_action('admin_notices', function () use ($message) {
                echo '<div class="notice notice-error is-dismissible">' . $message . '</div>';
            });
            // Deactivate the plugin
            deactivate_plugins(plugin_basename(__FILE__));
            return;
        }
    }
}

// Function to check if a plugin is installed
function check_plugin_installed($plugin_slug) {
    $installed_plugins = get_plugins();
    return isset($installed_plugins[$plugin_slug . '/' . $plugin_slug . '.php']);
}

add_action('admin_init', 'qotd_check_elementor');


// Deactivate the QOTD plugin if Elementor is deactivated
function qotd_deactivate_on_elementor_deactivation($plugin, $network_deactivating)
{
    if ($plugin === 'elementor/elementor.php') {
        deactivate_plugins(plugin_basename(__FILE__));
    }
}

add_action('deactivated_plugin', 'qotd_deactivate_on_elementor_deactivation', 10, 2);

// Enqueue CSS
function qotd_widget_enqueue_styles()
{
    wp_enqueue_style('qotd-widget-styles', plugins_url('assets/widget-style.css', __FILE__));
}

add_action('elementor/frontend/after_enqueue_styles', 'qotd_widget_enqueue_styles');
