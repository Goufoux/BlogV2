<?php
	/*
		Genarkys
		ver 1.0
		Pattern Factory
	*/
	namespace Core;
	
	class PDOFactory
	{
		public static function getMysqlConnexion()
		{
			$user = 'root';
			$pass = '';
			$host = 'mysql:host=127.0.0.1;dbname=BlogV2';
			// On tente la connexion //
			try
			{
				$bdd = new \PDO($host, $user, $pass, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
				return $bdd;
			}
			catch(\PDOException $e)
			{
				echo 'Le serveur est indisponible. Vérifier votre connexion ou rééssayer plus tard.';
				exit;
			}
		}
	}
