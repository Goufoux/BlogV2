<?php
	if(!empty($book))
	{
		?>
			<header>
				<h1 class="col-12"> <?php echo $book->getName(); ?> </h1>
			</header>
			<article class="book col-lg-10 col-md-10 col-11">
				<span class="error"> </span>
				<?php
					if(!empty($_SESSION['auth']))
					{
						?>
							<script>
								setInterval(function() 
								{
									$('.possAct').load('ajax/funcUser?role=book&fData='+<?php echo $book->getId(); ?>+'');
								}, 500);
							</script>
							<div class="possAct">
								<button> Initialisation </button>
							</div>
						<?php
					}
				?>
				<blockquote> De <a href="profil-<?php echo $book->getIdUtilisateur(); ?>"> <?php echo $book->getPseudo(); ?> </a> </blockquote>
				<blockquote> Catégorie: <?php echo $book->getListCat(); ?> </blockquote>
				<blockquote> 
					<?php echo $book->getNbVue(true); ?>
				</blockquote>
				<p class="col-12">
					<?php echo nl2br($book->getContent()); ?> 
				</p>
				
				<?php
					if($user->isAuthentificated())
					{
						if($_SESSION['membre']->getId() == $book->getIdUtilisateur())
						{
							?>
								<a href="addBillet-<?php echo $book->getId(); ?>" class="adm"> <button> Ajouter un Billet </button> </a>
							<?php
						}
					}
					if(!empty($listBillet))
					{
						$nb = count($listBillet);
						if($nb > 1)
							$nb = $nb. ' Billets';
						else
							$nb = $nb. ' Billet';
						?>
							<h4> <?php echo $nb; ?> </h4>
							<nav>
								<ul>
						<?php
						foreach($listBillet as $billet)
						{
							?>
								<li class="col-lg-2 col-md-4 col-sm-6 col-10"> <button class="col-12"> <a href="billet-<?php echo $billet->getId(); ?>" class="col-12"> <?php echo $billet->getTitre(); ?></a>  </button> </li>
							<?php
						}
						?>
							</ul>
						</nav>
						<?php
					}
					else
					{
						?>
							<h4> Aucun billet dans ce book... </h4>
						<?php
					}
				?>
			</article>
		<?php
	}