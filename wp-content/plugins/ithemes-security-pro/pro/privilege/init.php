<?php

class ITSEC_Privilege_Module_Init extends ITSEC_Module_Init {
	protected $_id   = 'privilege';
	protected $_name = 'Privilege';
	protected $_desc = 'Privilege escalation.';
}
new ITSEC_Privilege_Module_Init();
