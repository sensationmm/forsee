<?php

class ITSEC_Password_Module_Init extends ITSEC_Module_Init {
	protected $_id   = 'password';
	protected $_name = 'Password';
	protected $_desc = 'Set expiration rules for WordPress passwords or force all users to change their passwords.';
}
new ITSEC_Password_Module_Init();
