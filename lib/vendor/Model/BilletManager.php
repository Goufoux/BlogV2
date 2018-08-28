<?php

	namespace Model;
	
	use \Core\Manager;
	use \Entity\Billet;
	
	abstract class BilletManager extends Manager
	{
		abstract public function addLike($id);
		
		abstract public function getBillet($cat, $data = false);
		
		abstract public function existBillet($id);
		
		abstract public function delBillet($id);
		
		abstract public function updBillet(Billet $billet);
		
		abstract public function addBillet(Billet $billet);
		
		abstract public function existTitle($title);
		
		abstract public function getNbBilletOfBook($idBook);
	}