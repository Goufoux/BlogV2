<?php

	namespace Core;
	
	abstract class Manager
	{
		protected $dao;
		protected $parameter;
		
		public function __construct($dao, $parameter)
		{
			$this->dao = $dao;
			$this->parameter = $parameter;
		}
	}