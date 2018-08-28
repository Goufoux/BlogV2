<?php
/* Chargement de composer */
	require(__DIR__.'/../../vendor/autoload.php');
	
	use \Core\UserFunction;
	
	session_start();
	
	if(!empty($_SESSION))
	{
		if(!empty($_GET['role']))
		{
			if(!empty($_GET['fData']))
			{
				$role = htmlspecialchars($_GET['role']);
				$data = htmlspecialchars($_GET['fData']);
				$userFunction = new UserFunction($role, $data);
				if($userFunction->launcher() == true)
				{
					echo $userFunction->getHtml();
				}
				else
				{
					echo $userFunction->getError();
				}
			}
		}
	}