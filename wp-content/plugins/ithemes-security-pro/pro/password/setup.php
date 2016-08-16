<?php

if ( ! class_exists( 'ITSEC_Password_Setup' ) ) {

	class ITSEC_Password_Setup {

		private
			$defaults;

		public function __construct() {

			add_action( 'itsec_modules_do_plugin_activation',   array( $this, 'execute_activate'   )          );
			add_action( 'itsec_modules_do_plugin_deactivation', array( $this, 'execute_deactivate' )          );
			add_action( 'itsec_modules_do_plugin_uninstall',    array( $this, 'execute_uninstall'  )          );
			add_action( 'itsec_modules_do_plugin_upgrade',      array( $this, 'execute_upgrade'    ), null, 2 );

			$this->defaults = array(
				'enabled'         => false,
				'generate'        => true,
				'generate_role'   => 'administrator',
				'generate_length' => 50,
				'expire'          => false,
				'expire_force'    => false,
				'expire_max'      => 120,
				'expire_role'     => 'administrator',
			);

		}

		/**
		 * Execute module activation.
		 *
		 * @since 4.0
		 *
		 * @return void
		 */
		public function execute_activate() {

			$options = get_site_option( 'itsec_password' );

			if ( $options === false ) {

				add_site_option( 'itsec_password', $this->defaults );

			}

		}

		/**
		 * Execute module deactivation
		 *
		 * @return void
		 */
		public function execute_deactivate() {

			delete_metadata( 'user', null, 'itsec_password_change_required', null, true );

		}

		/**
		 * Execute module uninstall
		 *
		 * @return void
		 */
		public function execute_uninstall() {

			$this->execute_deactivate();

			delete_site_option( 'itsec_password' );
			delete_metadata( 'user', null, 'itsec_last_password_change', null, true );

		}

		/**
		 * Execute module upgrade
		 *
		 * @return void
		 */
		public function execute_upgrade() {

		}

	}

}

new ITSEC_Password_Setup();