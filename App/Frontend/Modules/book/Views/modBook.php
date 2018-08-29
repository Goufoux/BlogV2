<?php
	if(!empty($book))
	{
		?>
			<h3> Modifier Book </h3>
			<form action="#" method="post" class="formModBook form">
				<input type="text" name="bTitle" placeholder="Titre" id="bTitle" class="col-12" value="<?php echo $book->getName(); ?>"/>
				<textarea name="bDesc" placeholder="Contenu" id="bDesc" class="col-12"><?php echo $book->getContent(); ?></textarea>
				<input type="submit" value="Modifier" />
			</form>
		<?php
	}