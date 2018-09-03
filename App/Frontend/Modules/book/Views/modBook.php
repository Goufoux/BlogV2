
<?php
	if(!empty($book))
	{
		?>
			<header>
				<h1 class="col-12"> Modification du Book : <?php echo $book->getName(); ?> </h1>
			</header>
			<section>
				<form action="#" method="post" class="form">
					<input type="text" name="bTitle" placeholder="Titre" id="bTitle" class="col-12 col-lg-6" value="<?php echo $book->getName(); ?>"/>
					<textarea name="bDesc" placeholder="Contenu" id="bDesc" class="col-12 col-lg-6"><?php echo $book->getContent(); ?></textarea>
					<input type="submit" value="Modifier" />
				</form>
			</section>
		<?php
	}