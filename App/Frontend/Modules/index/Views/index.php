<header>
	<h1 class="col-12"> Liste des Books </h1>
</header>
<?php
	if(!empty($listBook))
	{
		foreach($listBook as $book)
		{
			?>
				<article class="col-12 col-md-8 col-sm-12 col-lg-4 bookPreview">
					<h3> <a href="book-<?php echo $book->getId(); ?>"> <?php echo $book->getName(); ?> </a> <a href="profil-<?php echo $book->getIdUtilisateur(); ?>" class="hInfo"> Par <?php echo $book->getPseudo(); ?> </a></h3>
					<p class="content">
						<?php echo nl2br($book->getContent()); ?>
					</p>
					<ul>
						<li> Publié le: <?php echo date('d-m-Y à H:i:s', $book->getDatePub()); ?> </li>
						<?php
							if($book->getDateMod())
							{
								?>
									<li> Modifié il y a <?php echo $style->styleDate($book->getDateMod()); ?> </li>
								<?php
							}
						?>
					</ul>
				</article>
			<?php
		}
	}
	else
	{
		?>
			<h3> Aucune Publication ! </h3>
		<?php
	}