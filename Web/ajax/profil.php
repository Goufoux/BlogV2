<?php
	/* Chargement de composer */
	require(__DIR__.'/../../vendor/autoload.php');
	
	use \Core\Form;
	use \Core\AjaxRequest;
	
	session_start();
	$form = new Form();
	/* PSEUDO */
	
	if(!empty($_GET['pseudo']))
	{	
		if($form->verifPseudo(htmlspecialchars($_GET['pseudo'])))
		{
			$pseudo = htmlspecialchars($_GET['pseudo']);
		}
		else
		{
			echo $form->getFormError();
		}
	}
	else
		echo 'Veuillez indiquer un pseudo !';
	
	/* EMAIL */
	
	if(!empty($_GET['email']))
	{
		if($form->verifEmail(htmlspecialchars($_GET['email'])))
		{
			$email = htmlspecialchars($_GET['email']);
		}
		else
		{
			echo $form->getFormError();
		}
	}
	else
		echo 'Veuillez indiquer votre adresse E-Mail.';
	
	/* Final */
	if(!empty($_GET['role']))
	{
		if(is_string(htmlspecialchars($_GET['role'])))
		{
			$role = htmlspecialchars($_GET['role']);
			$nData = ['pseudo', 'email'];
			if(!empty($pseudo) AND !empty($email))
			{
				$vData = [$pseudo, $email];
				$ajax = new AjaxRequest($role, [$nData, $vData]);
				if($ajax->run())
				{
					echo true;
				}
				else
				{
					echo $ajax->getAjaxError();
				}
			}
		}
		else
		{
			echo "Une erreur est survenue";
		}
	}
	