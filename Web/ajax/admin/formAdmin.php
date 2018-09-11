<?php
	require(__DIR__.'/../../../vendor/autoload.php');
	
	use \Core\AjaxAdminRequest;
	
	session_start();
	if(!empty($_GET['role']))
	{
		$role = htmlspecialchars($_GET['role']);
		if(!empty($_GET['fData']))
			$data = htmlspecialchars($_GET['fData']);
		else
			$data = false;
		if(!empty($_GET['sData']))
			$sData = $_GET['sData'];
		else
			$sData = false;
		$AjaxRequest = new AjaxAdminRequest($role, $data, $sData);
		if($AjaxRequest->run() == true)
			// var_dump($AjaxRequest->run());
			echo true;
		else
			echo $AjaxRequest->getAjaxError();
			// var_dump($AjaxRequest->getAjaxError());
	}