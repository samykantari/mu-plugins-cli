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
if ( ! defined( 'WP_CLI' ) ) {
	return;
}


class Mu_plugins_CLI {

	public function path( $args ) {

		if ( ! defined( 'WPMU_PLUGIN_DIR' ) ) {
			WP_CLI::error( 'The constant "WPMU_PLUGIN_DIR" does not exist' );
		}


		WP_CLI::line( WPMU_PLUGIN_DIR );
	}

	// create a new command list mu-plugins
	public function list( $args ) {
		$mu_plugins = get_mu_plugins(); // details get_mu_plugins() https://developer.wordpress.org/reference/functions/get_mu_plugins/

		if ( empty( $mu_plugins ) ) {
			WP_CLI::error( 'No mu-plugins found' );
		}

		$mu_plugins = array_map( function ( $mu_plugin, $key ) {
			return [
				'name'        => $mu_plugin['Name'],
				'name_delete' => $key,
				'version'     => $mu_plugin['Version'],
			];
		}, $mu_plugins );

		WP_CLI\Utils\format_items( 'table', $mu_plugins, [ 'name', 'version' ] );
	}

	// delete file with $wp_filesystem
	public function delete( $args ) {
		global $wp_filesystem;

		// check if the file exists
		if ( ! file_exists( WPMU_PLUGIN_DIR . '/' . $args[0] ) ) {
			WP_CLI::error( 'The file does not exist' );
		}

		// check if the file is writable
		if ( ! is_writable( WPMU_PLUGIN_DIR . '/' . $args[0] ) ) {
			WP_CLI::error( 'The file is not writable' );
		}

		// delete the file
		if ( ! $wp_filesystem->delete( WPMU_PLUGIN_DIR . '/' . $args[0] ) ) {
			WP_CLI::error( 'The file could not be deleted' );
		}

		WP_CLI::success( 'The file has been deleted' );
	}


}


WP_CLI::add_command( 'mu-plugin', 'Mu_plugins_CLI' );