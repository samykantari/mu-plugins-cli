<?php

/**
 * Plugin Name: MU plugins cli
 * Plugin URI: https://github.com/samykantari/mu-plugins-cli
 * Description:
 * Version: 0.0.1
 * Author: Kantari Samy
 * Author URI: https://github.com/samykantari
 *
 */

// Only load this plugin once and bail if WP CLI is not present
if (!defined('WP_CLI')) {
    return;
}


class Mu_plugins_CLI
{

    public function path($args)
    {

        if (!defined('WPMU_PLUGIN_DIR')) {
            WP_CLI::error('The constant "WPMU_PLUGIN_DIR" does not exist');
        }


        WP_CLI::line(WPMU_PLUGIN_DIR);
    }

    // create a new command list mu-plugins
    public function list($args)
    {
        $mu_plugins = get_mu_plugins(); // details get_mu_plugins() https://developer.wordpress.org/reference/functions/get_mu_plugins/

        if (empty($mu_plugins)) {
            WP_CLI::error('No mu-plugins found');
        }

        $mu_plugins = array_map(function ($mu_plugin) {
            return [
                'name' => $mu_plugin['Name'],
                'path' => $mu_plugin['PluginURI'],
            ];
        }, $mu_plugins);

        WP_CLI\Utils\format_items('table', $mu_plugins, ['name', 'path']);
    }



}


WP_CLI::add_command('mu-plugin', 'Mu_plugins_CLI');