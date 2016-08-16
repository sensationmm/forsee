<?php

class ITSEC_Recaptcha_Module_Init extends ITSEC_Module_Init {
	protected $_id   = 'recaptcha';
	protected $_name = 'Recaptcha';
	protected $_desc = 'Recaptcha escalation.';
}
new ITSEC_Recaptcha_Module_Init();
