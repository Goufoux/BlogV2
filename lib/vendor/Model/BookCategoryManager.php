<?php

	namespace Model;
	
	use Core\Manager;
	use Entity\CategoryBook;
	
	abstract class BookCategoryManager extends Manager
	{
		abstract public function getBookCategory($idBook);
		
		abstract public function addBookCategory(CategoryBook $categoryBook);
	}