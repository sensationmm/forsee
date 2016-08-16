<?php

if ( ! class_exists( 'ITSEC_Recaptcha_Setup' ) ) {

	class ITSEC_Recaptcha_Setup {

		private
			$defaults;

		public function __construct() {

			add_action( 'itsec_modules_do_plugin_activation',   array( $this, 'execute_activate'   )          );
			add_action( 'itsec_modules_do_plugin_deactivation', array( $this, 'execute_deactivate' )          );
			add_action( 'itsec_modules_do_plugin_uninstall',    array( $this, 'execute_uninstall'  )          );
			add_action( 'itsec_modules_do_plugin_upgrade',      array( $this, 'execute_upgrade'    ), null, 2 );

			$this->defaults = array(
				'enabled'         => false,
				'login'           => false,
				'comments'        => false,
				'register'        => false,
				'theme'           => false,
				'language'        => '',
				'error_threshold' => 7,
				'check_period'    => 5,
				'site_key'        => '',
				'secret_key'      => '',
			);

		}

		/**
		 * Execute module activation.
		 *
		 * @since 1.13
		 *
		 * @return void
		 */
		public function execute_activate() {

			$options = get_site_option( 'itsec_recaptcha' );

			if ( $options === false ) {

				add_site_option( 'itsec_recaptcha', $this->defaults );

			}

		}

		/**
		 * Execute module deactivation
		 *
		 * @since 1.13
		 *
		 * @return void
		 */
		public function execute_deactivate() {
		}

		/**
		 * Execute module uninstall
		 *
		 * @since 1.13
		 *
		 * @return void
		 */
		public function execute_uninstall() {

			$this->execute_deactivate();

			delete_site_option( 'itsec_recaptcha' );

		}

		/**
		 * Execute module upgrade
		 *
		 * @since 1.13
		 *
		 * @return void
		 */
		public function execute_upgrade() {

		}

	}

}

new ITSEC_Recaptcha_Setup();