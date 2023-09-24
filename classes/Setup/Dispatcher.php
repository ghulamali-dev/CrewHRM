<?php

namespace CrewHRM\Setup;

use CrewHRM\Controllers\ApplicationHandler;
use CrewHRM\Helpers\_String;
use CrewHRM\Main;
use CrewHRM\Models\User;

use CrewHRM\Controllers\CompanyProfile;
use CrewHRM\Controllers\JobManagement;
use CrewHRM\Controllers\PluginSettings;
use CrewHRM\Helpers\_Array;
use Error;

class Dispatcher {
	/**
	 * Controlles class array
	 *
	 * @var array
	 */
	private static $controllers = array(
		PluginSettings::class,
		CompanyProfile::class,
		JobManagement::class,
		ApplicationHandler::class,
	);

	/**
	 * Dispatcher registration in constructor
	 * 
	 * @return void
	 */
	public function __construct() {
		// Register ajax handlers only if it is ajax call
		if ( ! wp_doing_ajax() ) {
			return;
		}
		
		$registered_methods = array();

		// Loop through controllers classes
		foreach ( self::$controllers as $class ) {

			// Loop through controller methods in the class
			foreach ( $class::PREREQUISITES as $method => $prerequisites ) {
				if ( in_array( $method, $registered_methods ) ) {
					throw new Error( __( 'Duplicate endpoint not possible' ) );
				}

				$endpoint = _String::camelToSnakeCase( $method );

				// Determine ajax handler types
				$handlers    = array();
				$handlers [] = 'wp_ajax_' . Main::$configs->app_name . '_' . $endpoint;

				// Check if norpriv necessary
				if ( ( $prerequisites['nopriv'] ?? false ) === true ) {
					$handlers[] = 'wp_ajax_nopriv_' . Main::$configs->app_name . '_' . $endpoint;
				}

				// Loop through the handlers and register
				foreach ( $handlers as $handler ) {
					add_action(
						$handler,
						function() use ( $class, $method, $prerequisites ) {
							$this->dispatch( $class, $method, $prerequisites );
						} 
					);
				}

				$registered_methods[] = $method;
			}
		}
	}

	/**
	 * Dispatch request to target handler after doing verifications
	 *
	 * @param string $class
	 * @param string $method
	 * @param array $prerequisites Controller access prerequisites
	 * 
	 * @return void
	 */
	public function dispatch( $class, $method, $prerequisites ) {
		// Determine post/get data
		$is_post = isset( $_SERVER['REQUEST_METHOD'] ) ? strtolower( sanitize_text_field( $_SERVER['REQUEST_METHOD'] ) ) === 'post' : null;
		$data    = $is_post ? $_POST : $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$data    = _Array::stripslashesRecursive( _Array::getArray( $data ) );

		// Verify nonce first of all
		$matched = wp_verify_nonce( ( $data['nonce'] ?? '' ), get_home_url() );
		if ( ! $matched ) {
			wp_send_json_error( array( 'message' => __( 'Session Expired! Reloading the page might help resolve.', 'crewhrm' ) ) );
		}

		// Verify required user role
		if ( ! empty( $required_roles = $prerequisites['role'] ?? array() ) ) {
			if ( ! User::validateRole( get_current_user_id(), $required_roles ) ) {
				wp_send_json_error( array( 'message' => __( 'Access Denied!', 'crewhrm' ) ) );
			}
		}

		// Now pass to the action handler function
		if ( class_exists( $class ) && method_exists( $class, $method ) ) {
			$class::$method( $data );
		} else {
			wp_send_json_error( array( 'message' => __( 'Invalid Endpoint!', 'crewhrm' ) ) );
		}
	}
}
