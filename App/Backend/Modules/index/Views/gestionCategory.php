<script src="../js/admin/gestionCategory.js"></script>
<script src="../js/admin/ajaxGet.js"></script>
<nav class="col-4 col-lg-2 panel">
	<button class="btn ajouterCategory col-12 my-1 btn-primary"> <i class="fas fa-plus"></i> Nouvelle Catégorie </button>
	<button class="btn createJSONCat col-12 my-1 btn-primary"> <i class="fas fa-external-link-alt"></i> Mettre à jour fichier JSON </button>
</nav>
<header>
	<h2> Gestion des Catégories </h2>
</header>
<section class="row col-12">
	<h4 class="col-12"> Liste des catégories </h4>
	<?php
		if(!empty($bookCategoryList) AND !is_string($bookCategoryList))
		{
			foreach($bookCategoryList as $cat)
			{
				?>
					<div class="card bg-dark text-white col-3 mx-5 my-2">
						<div class="card-header">
							<h5 class="card-title"> Nom: <?php echo $cat->getName(); ?> </h5>
						</div>
						<div class="card-body">
							<p class="card-text"> <?php echo nl2br($cat->getComment()); ?> </p>
						</div>
					</div>
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