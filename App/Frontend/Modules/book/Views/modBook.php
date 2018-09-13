
<?php
	if(!empty($book))
	{
		?>
			<header>
				<h1 class="col-12 h1"> <i class="fas fa-user-edit"></i> Modification du Book <small class="text-muted"> <?php echo $book->getName(); ?> </small> </h1>
			</header>
			<section>
				<form action="#" method="post" class="form">
					<input type="text" name="bTitle" placeholder="Titre" id="bTitle" class="col-12 col-lg-6 form-control" value="<?php echo $book->getName(); ?>"/>
					<textarea name="bDesc" placeholder="Contenu" id="bDesc" class="col-12 col-lg-6 form-control"><?php echo $book->getContent(); ?></textarea>
					<input type="submit" class="btn btn-primary" value="Modifier" />
				</form>
			</section>
		<?php
	}