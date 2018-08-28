<?php
	if(!empty($book))
	{
		?>
			<article class="book">
				<span class="error"> </span>
				<?php
					if(!empty($_SESSION['auth']))
					{
						?>
							<script>
								setInterval(function() 
								{
									$('.possAct').load('ajax/funcUser?role=book&fData='+<?php echo $book->getId(); ?>+'');
								}, 1000);
							</script>
							<div class="possAct">
								<button> Initialisation </button>
							</div>
						<?php
					}
				?>
				<h3> <?php echo $book->getName(); ?> </h3>
				<blockquote> De <?php echo $book->getPseudo(); ?> </blockquote>
				<blockquote> Catégorie: <?php echo $book->getListCat(); ?> </blockquote>
				<blockquote> 
					<?php echo $book->getNbVue(true); ?>
				</blockquote>
				<p>
					<?php echo nl2br($book->getContent()); ?> 
				</p>
				
				<?php
					if($user->isAuthentificated())
					{
						if($_SESSION['membre']->getId() == $book->getIdUtilisateur())
						{
							?>
								<a href="writeBillet-<?php echo $book->getId(); ?>" class="adm"> <button> Ajouter un Billet </button> </a>
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
								<li> <a href="billet-<?php echo $billet->getId(); ?>" class="linkList"> <button> <?php echo $billet->getTitre(); ?> </button></a> </li>
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