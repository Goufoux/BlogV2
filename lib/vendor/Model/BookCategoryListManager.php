<?php

	namespace Model;
	
	use Core\Manager;
	// use Entity\Category;
	
	abstract class BookCategoryListManager extends Manager
	{
		// abstract public function addCategory(Category $category);
		
		abstract public function getNameCategory($name);
		
		abstract public function getBookCategoryList();
	}