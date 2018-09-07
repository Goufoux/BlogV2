<?php

	namespace Model;
	
	use \Core\Manager;
	
	abstract class UserManager extends Manager
	{
		abstract public function getData($dataName, $dataGiven, $valueGiven);
		
		abstract public function setNewPass($login);
		
		abstract public function getUser($module, $id = false);
		
		abstract public function existLogin($login);
		
		abstract public function existId($id);
		
		abstract public function printUser($id);
		
		abstract public function existEmail($email);
		
		abstract public function countUser();
		
		abstract public function userVerifiedEmail($email, $key);
		
		abstract public function majUser($nData, $vData);
		
		abstract public function userConnect($login, $pass);
		
		abstract public function addUser($login, $pass);
		
		abstract public function delUser($id);
		
		abstract public function updAccessLevel($id, $newAccess);
	}