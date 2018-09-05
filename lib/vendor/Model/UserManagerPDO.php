<?php

	/*
		Genarkys
	
		UserManagerPDO()
		
		Gestion de toutes les interactions qui nécessitent un appel à la BDD pour l'utilisateur
		
		Ver 1.0
		
		@ Mise à jour 17/08/18
		
		Réécriture des méthodes d'inscription et de connexion
		
		@ Mise à jour 21/08/2018
		
		Ajout de existId(), printUser()
		
		@ Mise à jour 04/03/2018
		
		- Ajout de updateHistory()
		- 
		
		
	*/

	namespace Model;
	
	use \Entity\Membre;
	use \Core\Form;
	use \Core\Email;
	use \Core\Generator;
	use \Core\MyError;
	
	class UserManagerPDO extends UserManager
	{
		protected $error = '';
		
		/*
			A l'affichage de la liste des abonnements pour les books, si lors de la vérification si un id est mort, cette méthode est appelée et supprime l'id contenu dans la liste 
			$idUser = id utilisateur
			$idBook = id Book mort
		*/
		
		public function cleanFollowBook($idUser, $idBook)
		{
			if(!empty($idUser) AND (int) $idUser)
			{
				if(!empty($idBook) AND (int) $idBook)
				{
					$actAbonnement = unserialize($this->getData('followBook', 'id', $idUser));
					$key = array_search($idBook, $actAbonnement);
					unset($actAbonnement[$key]);
					sort($actAbonnement);
					try
					{
						$req = $this->dao->prepare('UPDATE utilisateur SET followBook = :followBook WHERE id = :id');
						$req->bindValue(':followBook', serialize($actAbonnement), \PDO::PARAM_STR);
						$req->bindValue(':id', $idUser, \PDO::PARAM_INT);
						if(!$req->execute())
							throw new MyError('Une erreur est survenue');
						return true;
					}
					catch(MyError $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
				}
				else
				{
					$this->setError('Une erreur est survenue');
					return false;
				}
			}
			else
			{
				$this->setError('Une erreur est survenue');
				return false;
			}
		}
		
		/*
			A l'affichage d'un historique, si lors de la vérification si un id est mort, cette méthode est appelée et supprime l'id contenu dans l'historique
			$idUser = id utilisateur
			$idDead = id Book mort
		*/
		
		public function cleanHistory($idUser, $idDead)
		{
			if(!empty($idUser) AND (int) $idUser)
			{
				if(!empty($idDead) AND (int) $idDead)
				{
					$actHistory = unserialize($this->getHistory($idUser)['history']);
					$key = array_search($idDead, $actHistory);
					unset($actHistory[$key]);
					sort($actHistory);
					try
					{
						$req = $this->dao->prepare('UPDATE userHistory SET history = :history WHERE idUtilisateur = :idUtilisateur');
						$req->bindValue(':history', serialize($actHistory), \PDO::PARAM_STR);
						$req->bindValue(':idUtilisateur', $idUser, \PDO::PARAM_INT);
						if(!$req->execute())
							throw new MyError('Une erreur est survenue');
						return true;
					}
					catch(MyError $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
				}
				else
				{
					$this->setError('Une erreur est survenue');
					return false;
				}
			}
			else
			{
				$this->setError('Une erreur est survenue');
				return false;
			}
		}
		
		/*
			Récupère l'historique d'un utilisateur 
			idUser -> id de l'utilisateur
		*/
		
		public function getHistory($idUser)
		{
			if(!empty($idUser) AND (int) $idUser)
			{
				try
				{
					$req = $this->dao->prepare('SELECT * FROM userHistory WHERE idUtilisateur = :idUtilisateur');
					$req->bindValue(':idUtilisateur', $idUser, \PDO::PARAM_INT);
					if(!$req->execute())
						throw new MyError('Une erreur est survenue');
					if(!$rs = $req->fetch())
						throw new MyError('Une erreur est survenue');
					return $rs;
				}
				catch(MyError $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
			else
			{
				$this->setError('Une erreur est survenue.');
				return false;
			}
		}
		
		public function updateHistory($idUser, $idBook)
		{
			if(!empty($idUser))
			{
				if(!empty($idBook))
				{
					try
					{
						$req = $this->dao->prepare('SELECT * FROM userHistory WHERE idUtilisateur = :idUtilisateur');
						$req->bindValue(':idUtilisateur', $idUser, \PDO::PARAM_INT);
						$req->execute();
						if(!$rs = $req->fetch()) // l'utilisateur n'existe pas car aucun historique, donc création d'une ligne dans la table pour cet utilisateur //
						{
							try
							{
								$req = $this->dao->prepare('INSERT INTO userHistory(idUtilisateur, history) VALUES(:idUtilisateur, :history)');
								$req->bindValue(':idUtilisateur', $idUser, \PDO::PARAM_INT);
								$req->bindValue(':history', serialize(array($idBook)), \PDO::PARAM_STR);
								if(!$req->execute())
									throw new MyError('Une erreur est survenue. {Code -> 56}');
								else
									return true;
							}
							catch(MyError $e)
							{
								$this->setError($e->getMessage());
								return false;
							}
						}
						else // L'utilisateur a un historique, on le met à jour //
						{
							$actHistory = unserialize($rs['history']);
							if(in_array($idBook, $actHistory)) /* On regarde si le book est déjà présent dans l'historique */
							{
								$key = array_search($idBook, $actHistory);
								unset($actHistory[$key]);
								sort($actHistory);
								array_unshift($actHistory, $idBook);
								try
								{
									$req = $this->dao->prepare('UPDATE userHistory SET history = :history WHERE idUtilisateur = :idUtilisateur');
									$req->bindValue(':history', serialize($actHistory), \PDO::PARAM_STR);
									$req->bindValue(':idUtilisateur', $idUser, \PDO::PARAM_INT);
									if(!$req->execute())
										throw new MyError('Une erreur est survenue. {Code -> 83}');
									else
										return true;
								}
								catch(MyError $e)
								{
									$this->setError($e->getMessage());
									return false;
								}
							}
							else
							{
								$actHistory[] = $idBook;
								try
								{
									$req = $this->dao->prepare('UPDATE userHistory SET history = :history WHERE idUtilisateur = :idUtilisateur');
									$req->bindValue(':history', serialize($actHistory), \PDO::PARAM_STR);
									$req->bindValue(':idUtilisateur', $idUser, \PDO::PARAM_INT);
									if(!$req->execute())
										throw new MyError('Une erreur est survenue. {Code -> 83}');
									else
										return true;
								}
								catch(MyError $e)
								{
									$this->setError($e->getMessage());
									return false;
								}
							}
						}
					}
					catch(MyError $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
				}
				else
				{
					$this->setError('Une erreur est survenue.');
					return false;
				}
			}
			else
			{
				$this->setError('Une erreur est survenue.');
				return false;
			}
		}
		
		/*
			Récupère une donnée spécifique
			$dataName = Donnée à récupérer
			$dataGiven = Donnée de sélection
			$valueGiven = Valeur donnée de sélection
		*/
		
		public function getData($dataName, $dataGiven, $valueGiven)
		{
			$sql = 'SELECT ' . $dataName . ' FROM utilisateur WHERE ' . $dataGiven . ' = :' . $dataGiven;
			$req = $this->dao->prepare($sql);
			$req->bindValue(':'.$dataGiven, $valueGiven);
			$req->execute();
			if($rs = $req->fetch())
			{
				return $rs[$dataName];
			}
			else
			{
				return false;
			}
		}
		
		/*
			Appelé lorsqu'un utilisateur a oublié son Pass
		*/
		
		public function setNewPass($login)
		{
			try
			{
				if(!$this->existLogin($login))
				{
					throw new MyError("Le login n'existe pas.");
				}
				$form = new Form();
				if(!$form->isEmail($login))
				{
					$login = $this->getData('email', 'pseudo', $login);
				}
				$Generator = new Generator();
				$pass = $Generator->generatePass();
				try
				{
					$req = $this->dao->prepare('UPDATE utilisateur SET pass = :pass WHERE email = :email');
					$req->bindValue(':pass', password_hash($pass, PASSWORD_BCRYPT), \PDO::PARAM_STR);
					$req->bindValue(':email', $login, \PDO::PARAM_STR);
					if(!$rs = $req->execute())
					{
						$rs = $req->errorInfo();
						throw new MyError($rs[2]);
					}
					$dataEmail = ['pass' => $pass, 'time' => time()];
					$email = new Email($login, 'pass', $dataEmail);
					try
					{
						$emailSend = $email->launch();
						// $emailSend = true;
						if(!$emailSend)
							throw new MyError($email->getError());
						else
						{
							$_SESSION['success'] = 'Votre pass a été réinitialisé.<br />Consulter vos emails';
							return true;
						}
					}
					catch(MyError $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
				}
				catch(MyError $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
			catch(MyError $e)
			{
				$this->setError($e->getMessage());
				return false;
			}
		}
		
		/* 
			Retourne la liste des utilisateurs 
		*/
		
		public function getUser($module, $id = false)
		{
			if($module == 'list')
			{
				try
				{
					$req = $this->dao->query('SELECT * FROM utilisateur');
					$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Membre');
					$req->execute();
					$rs = $req->fetchAll();
					return $rs;
				}
				catch(MyError $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
		}
		
		/*
			Vérifie l'existence d'un pseudo ou d'un email 
			$login = pseudo ou email
		*/
		
		public function existLogin($login)
		{
			try
			{
				$req = $this->dao->prepare('SELECT pseudo FROM utilisateur WHERE pseudo = :pseudo OR email = :pseudo');
				$req->bindValue(':pseudo', $login, \PDO::PARAM_STR);
				$req->execute();
				if($rs = $req->fetch())
					return true;
				else
					return false;
			}
			catch(\PDOException $e)
			{
				return false;
				exit;
			}
		}
		
		/*
			Vérifie l'existence d'un utilisateur 
			$id = id de l'utilisateur
		*/
		
		public function existId($id)
		{
			try
			{
				$req = $this->dao->prepare('SELECT id FROM utilisateur WHERE id = :id');
				$req->bindValue(':id', htmlspecialchars($id), \PDO::PARAM_STR);
				$req->execute();
				if($rs = $req->fetch())
					return true;
				else
					return false;
			}
			catch(\PDOException $e)
			{
				echo "Erreur";
			}
		}
		
		/*
			récupère les données pour l'affichage sur un profil 
		*/
		
		public function printUser($id)
		{
			$req = $this->dao->prepare('SELECT id, pseudo, dti, categoryUser FROM utilisateur WHERE id = :id');
			$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Membre');
			$req->bindValue(':id', $id, \PDO::PARAM_INT);
			$req->execute();
			if($rs = $req->fetch())
			{
				return $rs;
			}
			else
			{
				$this->setError('Cet utilisateur n\'existe pas ou plus.');
				return false;
			}
		}
		
		/*
			Vérifie l'existence d'une email
		*/
		
		public function existEmail($email)
		{
			$req = $this->dao->prepare('SELECT email FROM utilisateur WHERE email = :email');
			$req->bindValue(':email', htmlspecialchars($email), \PDO::PARAM_STR);
			$req->execute();
			if($rs = $req->fetch())
				return true;
			else
				return false;
		}
		
		/*
			Compte le nombre d'utilisateur 
		*/
		
		public function countUser()
		{
			$req = $this->dao->query('SELECT COUNT(id) as id FROM utilisateur');
			$req->execute();
			$rs = $req->fetch();
			$nb = $rs['id'] + 1;
			return $nb;
		}
		
		/*
			Vérifie l'email de l'utilisateur
			$email = email de l'utilisateur
			$key = clé fourni lors de l'inscription
		*/
		
		public function userVerifiedEmail($email, $key)
		{
			$form = new Form();
			if($form->verifEmail($email))
			{
				$req = $this->dao->prepare('SELECT keyEmail FROM utilisateur WHERE email = :email');
				$req->bindValue(':email', $email, \PDO::PARAM_STR);
				if($req->execute())
				{
					if($rs = $req->fetch())
					{
						if(password_verify($rs['keyEmail'], $key))
						{
							/* Mise à jour de la clé pour indiquer qu'elle a été vérifiée */
							$req->closeCursor();
							$maj = $this->dao->prepare('UPDATE utilisateur SET keyEmail = :keyEmail WHERE email= :email');
							$maj->bindValue(':keyEmail', 'verified', \PDO::PARAM_STR);
							$maj->bindValue(':email', $email, \PDO::PARAM_STR);
							if($maj->execute())
							{
								$_SESSION['success'] = 'Votre inscription a été confirmée !<br />Vous pouvez désormais vous connectez.';
								return true;
							}
							else
							{
								$this->setError('Une erreur est survenue. 206');
								return false;
							}
						}
						else
						{
							$this->setError('Il semblerait que le lien soit cassé.<br />Contactez nous à : admin@genarkys.fr');
							return false;
						}
					}
					else
					{
						$this->setError('Aucun inscription effectuée avec cette adresse E-Mail');
						return false;
					}
				}
				else
				{
					$this->setError('Une erreur est survenue.');
					return false;
				}
			}
			else
			{
				$this->setError('Il semblerait que le lien soit cassé.<br />Contactez nous à : admin@genarkys.fr');
				return false;
			}
		}
		
		/*
			Retourne le pseudo et l'id en fonction de l'id donnée
			id = id de l'utilisateur
		*/
		
		public function getPseudo($id)
		{
			if((int) $id)
			{
				try
				{
					if(!$this->existId($id))
						throw new MyError('Utilisateur introuvable');
					
					$req = $this->dao->prepare('SELECT id, pseudo FROM utilisateur WHERE id = :id');
					$req->bindValue(':id', $id, \PDO::PARAM_INT);
					$req->execute();
					$rs = $req->fetch();
					return $rs;
				}
				catch(MyError $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
			else
			{
				$this->setError('Une erreur est survenue.');
				return false;
			}
		}
		
		/*
			majUser()
			
			Mise à jour des données de l'utilisateur
		*/
		
		public function majUser($nData, $vData)
		{
			if(!empty($nData))
			{
				if(!empty($vData))
				{
					if(count($nData) == count($vData))
					{
						try
						{
							$setSQL = '';
							for($i = 0; $i < count($nData); $i++)
							{
								if(($i+1) != count($nData))
									$setSQL .= ' ' . $nData[$i] . ' = :' . $nData[$i] . ',';
								else
									$setSQL .= ' ' . $nData[$i] . ' = :' . $nData[$i];
							}
							$req = $this->dao->prepare('UPDATE utilisateur SET' . $setSQL . ' WHERE id = :id');
							for($j = 0; $j < count($vData); $j++)
							{
								$req->bindValue(':'.$nData[$j], $vData[$j]);
							}
							$req->bindValue(':id', $_SESSION['membre']->getId());
							if($req->execute())
							{
								for($k = 0; $k < count($nData); $k++)
								{
									$_SESSION['membre']->hydrate([$nData[$k] => $vData[$k]]);
								}
								$_SESSION['success'] = 'Données mise à jour !';
								return true;
							}
							else
							{
								$this->setError('Une erreur est survenue.');
								return false;
							}
						}
						catch(MyError $e)
						{
							$this->setError($e->getMessage());
							return false;
						}
					}
					else
					{
						$this->setError('Une erreur est survenue.');
						return false;
					}
				}
				else
				{
					$this->setError('Une erreur est survenue.');
					return false;
				}
			}
			else
			{
				$this->setError('Une erreur est survenue.');
				return false;
			}
		}
		
		/*
			userConnect()
			
			Établi la connexion avec l'utilisateur
			
		*/
		
		public function userConnect($login, $pass)
		{
			if(is_string($login))
			{
				if(is_string($pass))
				{
					$req = $this->dao->prepare('SELECT * FROM utilisateur WHERE pseudo = :login OR email = :login');
					$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Membre');
					$req->bindValue(':login', $login, \PDO::PARAM_STR);
					try
					{
						$req->execute();
						if($rs = $req->fetch())
						{
							if($rs->getKeyEmail() == 'verified')
							{
								if(password_verify($pass, $rs->getPass()))
								{
									if(empty($_COOKIE['alreadySuscribe']))
										setcookie('alreadySuscribe', true, strtotime('+30 days'), '/', null, false, false);
									$_SESSION['auth'] = true;
									$_SESSION['membre'] = $rs;
									$_SESSION['success'] = 'Bonjour ' . $rs->getPseudo();
									return true;
								}
								else
								{
									$this->setError('Pass incorrect');
									return false;
								}
							}
							else
							{
								$this->setError('Adresse Email non vérifié.');
								return false;
							}
						}
						else
						{
							$this->setError('Le login n\'existe pas.');
							return false;
						}
					}
					catch(MyError $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
					
				}
				else
				{
					$this->setError('Le pass est invalide.');
				}
			}
			else
			{
				$this->setError('Le login est invalide');
				return false;
			}
		}
		
		/*
			addUser()
			
			Ajoute un utilisateur dans la base de donnée 
		*/
		
		public function addUser($login, $pass)
		{
			$form = new Form();
			if($form->verifEmail($login))
			{
				if($form->verifPass($pass))
				{
					if(!$this->existEmail($login))
					{
						$dti = getDate();
						$keyEmail = mt_rand($dti[0], strtotime('+30 days'));
						$pseudo = 'Utilisateur_'.$this->countUser();
						$req = $this->dao->prepare('INSERT INTO utilisateur(pseudo, pass, email, dti, accessLevel, keyEmail, categoryUser, followBook, followUser) VALUES(:pseudo, :pass, :email, :dti, :accessLevel, :keyEmail, :categoryUser, :followBook, :followUser)');
						$req->bindValue(':pseudo', $pseudo, \PDO::PARAM_STR);
						$req->bindValue(':pass', password_hash($pass, PASSWORD_BCRYPT), \PDO::PARAM_STR);
						$req->bindValue(':email', $login, \PDO::PARAM_STR);
						$req->bindValue(':dti', $dti[0], \PDO::PARAM_INT);
						$req->bindValue(':accessLevel', 0, \PDO::PARAM_INT);
						$req->bindValue(':keyEmail', $keyEmail, \PDO::PARAM_INT);
						$req->bindValue(':categoryUser', 0, \PDO::PARAM_INT);
						$req->bindValue(':followBook', serialize(array()), \PDO::PARAM_STR);
						$req->bindValue(':followUser', serialize(array()), \PDO::PARAM_STR);
						$tryAdd = $req->execute();
						if($tryAdd)
						{
							$dataEmail = ['pseudo' => $pseudo, 'dti' => $dti[0], 'keyEmail' => $keyEmail];
							$email = new Email($login, 'inscription', $dataEmail);
							if($email->launch())
							{
								$_SESSION['success'] = 'Inscription effectuée !<br />Vous allez reçevoir un E-Mail de notre part pour confirmer votre inscription';
								setcookie('alreadySuscribe', true, strtotime('+30 days'), '/', null, false, false);
								return true;
							}
							else
							{
								$this->setError($email);
								return false;
							}
						}
						else
						{
							$this->setError('Une erreur est survenue.');
							return false;
						}
					}
					else
					{
						$this->setError('L\'adresse E-Mail est déjà utilisée.');
						return false;
					}
				}
				else
				{
					$this->setError($form->getFormError());
					return false;
				}
			}
			else
			{
				$this->setError($form->getFormError());
				return false;
			}
		}
		
		/* 
			DelUser() - ADMIN 
			Supprime un utilisateur
			$id = id de l'utilisateur à supprimer
		*/
		
		public function delUser($id)
		{
			if((int) $id)
			{
				if($this->existId($id))
				{
					$req = $this->dao->prepare('DELETE FROM utilisateur WHERE id = :id');
					$req->bindValue(':id', $id, \PDO::PARAM_INT);
					$req->execute();
					
					$req = $this->dao->prepare('DELETE FROM book WHERE idUtilisateur = :idUtilisateur');
					$req->bindValue(':idUtilisateur', $id, \PDO::PARAM_INT);
					$req->execute();
					
					$req = $this->dao->prepare('DELETE FROM billet WHERE idUtilisateur = :idUtilisateur');
					$req->bindValue(':idUtilisateur', $id, \PDO::PARAM_INT);
					$req->execute();
					return true;
				}
				else
				{
					$this->setError('L\'id transmis n\'existe pas.');
					return false;
				}
			}
			else
			{
				$this->setError('Une erreur est survenue.');
				return false;
			}
		}
		
		/*
			updAccessLevel() - ADMIN
			Mise à jour du niveau d'accès 
		*/
		
		public function updAccessLevel($id, $newAccess)
		{
			if($newAccess == 'aucun')
				$newAccess = '0';
			if($this->existId($id))
			{
				$req = $this->dao->prepare('UPDATE utilisateur SET accessLevel = :accessLevel WHERE id = :id');
				$req->bindValue(':accessLevel', $newAccess, \PDO::PARAM_INT);
				$req->bindValue(':id', $id, \PDO::PARAM_INT);
				$req->execute();
				return true;
			}
			else
			{
				$this->setError('L\'id transmis n\'existe pas');
				return false;
			}
		}
		
		public function getManagerError()
		{
			return $this->error;
		}
		
		public function setError($error)
		{
			$this->error = $error;
		}
	}