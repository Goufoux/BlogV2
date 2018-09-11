<script src="../js/admin/gestionCategory.js"></script>
<script src="../js/form.js"></script>
<script src="../js/admin/ajaxGet.js"></script>
<nav class="col-4 col-lg-2 panel">
	<button class="ajouterCategory col-12 col-lg-12"> Nouvelle Catégorie </button>
	<button class="createJSONCat col-12 col-lg-12"> Mettre à jour fichier JSON </button>
</nav>
<header>
	<h2> Gestion des Catégories </h2>
</header>
<section class="col-12 col-lg-12 gestCategory">
	<h4 class="col-12"> Liste des catégories </h4>
	<?php
		if(!empty($bookCategoryList) AND !is_string($bookCategoryList))
		{
			foreach($bookCategoryList as $cat)
			{
				?>
					<article class="col-12 col-lg-3">
						<h5> Nom: <?php echo $cat->getName(); ?> </h5>
						<p> <?php echo nl2br($cat->getComment()); ?> </p>
					</article>
				<?php
			}
		}
		elseif(is_string($bookCategoryList))
		{
			?>
				<h5> <?php echo $bookCategoryList; ?> </h5>
			<?php
		}
	?>
</section>