<?php

class ITSEC_Two_Factor_Module_Init extends ITSEC_Module_Init {
	protected $_id   = 'two-factor';
	protected $_name = 'Two Factor Auth';
	protected $_desc = 'Two Factor Authentication system.';
}
new ITSEC_Two_Factor_Module_Init();
