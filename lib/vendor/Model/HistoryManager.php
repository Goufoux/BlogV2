<?php

	/*
		Genarkys
		
		06/09/2018
		
		HistoryManager()
		
		Classe abstraite de HistoryManagerPDO()
		
		Liste des méthodes abstraites:
			- run()
			- executePrint()
			- executeUnfollow()
			- executeSearch()
			- executeFollow()
			- executeAddHistory()
			
	*/
	
	namespace Model;
	
	use \Core\Manager;
	
	abstract class HistoryManager extends Manager
	{
		abstract public function run();
		
		abstract public function executePrint();
		
		abstract public function executeUnfollow();
		
		abstract public function executeSearch();
		
		abstract public function executeFollow();
		
		abstract public function  executeAddHistory();
	}