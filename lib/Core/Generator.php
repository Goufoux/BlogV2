<?php

	/*
		Genarkys
		
		ver 1.0
		
		Generator Object
	*/
	
	namespace Core;
	
	class Generator
	{
		private $alp = ['a', 'z', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'm', 'l', 'k', 'j', 'h', 'g', 'f', 'd', 's', 'q', 'n', 'b', 'v', 'c', 'x', 'w'];
		private $string;
		
		public function generatePass($charNumber = 8)
		{
			for($i = 0; $i < $charNumber; $i++)
			{
				shuffle($this->alp);
				$this->string .= $this->alp[$i];
			}
			$this->string .= mt_rand(100, 999);
			return ucfirst($this->string);
		}
	}