<?php

	namespace Model;
	
	use \Core\Manager;
	use \Entity\Book;
	
	abstract class BookManager extends Manager
	{
		abstract public function getName($id);
		
		abstract public function addView($id, $nb);
		
		abstract public function addBook(Book $book);
		
		abstract public function getBook($cat, $data);
		
		abstract public function searchBook($name);
		
		abstract public function existBook($id);
		
		abstract public function modBook(Book $book);
		
		abstract public function delBook($id);
	}