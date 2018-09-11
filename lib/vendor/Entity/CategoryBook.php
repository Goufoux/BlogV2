<?php

	namespace Entity;
	
	use Core\Entity;
	
	class CategoryBook extends Entity
	{
		protected $id;
		protected $idBook;
		protected $idCategory;
		protected $name;
		protected $comment;
		
		/* Getters */
		
		public function getId()
		{
			return $this->id;
		}
		
		public function getIdCategory()
		{
			return $this->idCategory;
		}
		
		public function getIdBook()
		{
			return $this->idBook;
		}
		
		public function getName()
		{
			return $this->name;
		}
		
		public function getComment()
		{
			return $this->comment;
		}
		
		/* Setters */
		
		public function setId($id)
		{
			$this->id = $id;
		}
		
		public function setIdCategory($idCategory)
		{
			$this->idCategory = $idCategory;
		}
		
		public function setIdBook($idBook)
		{
			$this->idBook = $idBook;
		}
		
		public function setName($name)
		{
			$this->name = $name;
		}
		
		public function setComment($comment)
		{
			$this->comment = $comment;
		}
	}