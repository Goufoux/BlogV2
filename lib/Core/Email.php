<?php

	namespace Core;
	
	class Email
	{
		protected $destinataire;
		protected $module;
		protected $data = [];
		protected $body;
		
		public function __construct($destinataire, $module, $data)
		{
			$this->destinataire = $destinataire;
			$this->module = 'email'.ucfirst($module);
			$this->data = $data;
		}
		
		public function launch()
		{
			$module = $this->module;
			if(is_callable([$this, $module]))
			{
				return $this->$module($this->data);
			}
		}
		
		public function emailInscription($data)
		{
			$this->setBodyInscription($data);
			$subject = 'Inscription Blog';
			if(!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $this->destinataire)) // On filtre les serveurs qui rencontrent des bogues.
			{
				$passage_ligne = "\r\n";
			}
			else
			{
				$passage_ligne = "\n";
			}
			// Création de la boundary
			$boundary = "-----=".md5(rand());
			// Création du header de l'e-mail.
			$header = "From: \"Genarkys\"<genarkys@gmail.com>".$passage_ligne;
			$header.= "Reply-to: \"Genarkys\"<genarkys@gmail.com>".$passage_ligne;
			$header.= "MIME-Version: 1.0".$passage_ligne;
			$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			
			// Création du message.
			$message = $passage_ligne."--".$boundary.$passage_ligne;
			// Ajout du message au format texte.
			$message.= "Content-Type: text/plain; charset=\"utf-8\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$this->body.$passage_ligne;
			$message.= $passage_ligne."--".$boundary.$passage_ligne;
			// Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"utf-8\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$this->body.$passage_ligne;
			//
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			try
			{
				mail($this->destinataire, $subject, $message, $header);
				return true;
			}
			catch(\Exception $e)
			{
				throw new \Exception("Impossible d'envoyer l'email.");
			}
		}
		
		public function setBodyInscription($data)
		{
			$this->body = '<html>
							<head>
							</head>
							<body>
								<section style="background-color: rgb(33, 37, 41);">	
									<br />
									<h1 style="color: white; text-align: center;"> Inscription Blog </h1>
										<p style="color: white; text-align: center;">
											Bonjour,<br />
											Vous avez effectué votre inscription sur le Blog de Genarkys, et nous vous en remercions !
										</p>
										<br />
											<hr />
										<br />
										<p style="color: white; text-align: center;">
											Lors de votre inscription un pseudo a été généré automatiquement, vous pourrez le changer par la suite.<br />
											Votre pseudo: <em> ' . $data["pseudo"] . ' </em><br />
											Lien en ligne : Confirmer votre inscription en suivant ce <a href="https://genarkys.fr/openclassroom/BlogV2/Web/?email=' . $this->destinataire . '&confirmkey=' . password_hash($data["keyEmail"], PASSWORD_BCRYPT) . '" title="Lien d\'inscription" target="_blank" style="color: aqua; text-decoration: none;"> lien </a><br />
											Lien en local : Confirmer votre inscription en suivant ce <a href="genarkys/openclassroom/BlogV2/Web/?email=' . $this->destinataire . '&confirmkey=' . password_hash($data["keyEmail"], PASSWORD_BCRYPT) . '" title="Lien d\'inscription" target="_blank" style="color: aqua; text-decoration: none;"> lien </a><br />
										</p>
										<br />
								</section>
							</body>
						   </html>';
		}
		
	}