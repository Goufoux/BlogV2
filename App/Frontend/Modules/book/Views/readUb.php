<script src="js/book.js"></script>
<script src="js/admin/ajaxGet.js"></script>
<header>
	<h1 class="col-12"> Liste de vos Books </h1>
</header>
<?php
	if(!empty($list))
	{
		?>
		<blockquote class="col-12" style="text-align: center;">
			<input type="button" value="Créer un Book" id="bCreate" class="btn btn-primary" title="Créer un nouveau Book ?"  />
			<span class="error"></span>
		</blockquote>
		<section class="row col-12 col-sm-12">
		<?php
		foreach($list as $book)
		{
			?>
				<article class="col-12 col-lg-5 col-md-5 col-sm-12 my-lg-5 my-5 my-sm-5 ml-auto mr-auto prevBook">
						<i class="far fa-trash-alt delBook" title="Supprimer ?" id="b_<?php echo $book->getId(); ?>"></i>
						<a href="./modBook-<?php echo $book->getId(); ?>"><i class="far fa-edit" title="Modifier ?"></i></a>
					<h4 class="col-12"> <a href="./<?php echo 'book-'.$book->getId(); ?>"> <i class="fas fa-book"></i> <?php echo $book->getName(); ?> </a> </h4>
					<blockquote class="blockquote">
						<p> <?php echo nl2br($book->getContent()); ?> </p>
					</blockquote>
					<ul class="list-group col-12">
						<li class="list-group-item"> <i class="far fa-calendar-alt" title="Date de publication"></i> <?php echo date('d-m-Y à H:i:s', $book->getDatePub()); ?> </li>
						<?php
							if(!empty($book->getDateMod()))
							{
								?>
									<li class="list-group-item"> <i class="fas fa-user-edit" title="Dernière modification"></i> <?php echo $style->styleDate($book->getDateMod()); ?> </li>
								<?php
							}
						?>
						<li class="list-group-item"> <i class="far fa-eye" title="Nombre de vue"></i> <?php echo $book->getNbVue(true); ?> </li>
					</ul>
				</article>
			<?php
		}
	}
	else
	{
		?>
			<blockquote class="col-12">
				<h4> Vous n'avez aucun Book. </h4>
				<input type="button" value="Créer un Book" id="bCreate" title="Créer un nouveau Book ?" />
			</blockquote>
		<?php
	}
?>
</section>