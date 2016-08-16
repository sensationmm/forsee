<?php

class ITSEC_WP_CLI_Module_Init extends ITSEC_Module_Init {
	protected $_id   = 'wp-cli';
	protected $_name = 'WP CLI';
	protected $_desc = 'Provides command line access via WP-CLI';
}
new ITSEC_WP_CLI_Module_Init();
