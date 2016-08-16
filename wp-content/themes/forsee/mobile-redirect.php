<?php

	if(isset($_POST["action"])) {
		if($_POST["action"] == 'redirect') {
			$destination = $_POST["destination"];

			header('Location: /'.$destination.'/');
		} else {
			header('Location: /');
		}
	} else {
		header('Location: /');
	}

?>