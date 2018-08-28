<script src="js/modBillet.js"></script>
<?php
	if(!empty($list))
	{
		?>
			<h3> Liste de vos Books </h3>
			<input type="button" value="Créer un Book" id="bCreate" />
			<span class="error"></span>
		<?php
		foreach($list as $book)
		{
			?>
				<article class="bookPreview col-lg-3 col-md-4 col-sm-6 col-8">
					<div class="row col-lg-12 col-12">
					<i class="far fa-trash-alt delBook" title="Supprimer ?" id="b_<?php echo $book->getId(); ?>"></i>
					<a href="./modBook-<?php echo $book->getId(); ?>"><i class="far fa-edit" title="Modifier ?"></i></a>
					</div>
					<h4 class="col-12"> <a href="./<?php echo 'book-'.$book->getId(); ?>"> <?php echo $book->getName(); ?> </a> </h4>
					<ul class="col-12">
						<li> Publié le: <?php echo date('d-m-Y à H:i:s', $book->getDatePub()); ?> </li>
						<?php
							if(!empty($book->getDateMod()))
							{
								?>
									<li> Modifié il y a : <?php echo $style->styleDate($book->getDateMod()); ?> </li>
								<?php
							}
						?>
						<li> <?php echo $book->getNbVue(true); ?> </li>
					</ul>
				</article>
			<?php
		}
	}
	else
	{
		?>
			<article>
				<h4> Vous n'avez aucun Book. </h4>
				<input type="button" value="Créer un Book" id="bCreate" />
			</article>
		<?php
	}