<header>
	<h1 class="col-12"> Liste des Books </h1>
</header>
<section class="row col-12">
<?php
	if(!empty($listBook))
	{
		foreach($listBook as $book)
		{
			?>
				<article class="col-11 col-lg-3 col-md-5 col-sm-11 prevBook">
					<h3> <a href="book-<?php echo $book->getId(); ?>"> <?php echo $book->getName(); ?> </a> <a href="profil-<?php echo $book->getIdUtilisateur(); ?>" class="hInfo"> Par <?php echo $book->getPseudo(); ?> </a></h3>
					<blockquote class="col-12">
						<?php echo nl2br($book->getContent()); ?>
					</blockquote>
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
?>
</section>