<?php
	if(!empty($book))
	{
		?>
			<header>
				<h1 class="col-12"><i class="fas fa-book" title="Book <?php echo $book->getName(); ?>"></i> <?php echo $book->getName(); ?> </h1>
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
				<blockquote class="blockquote"><i class="far fa-user" title="Auteur"></i> <a href="profil-<?php echo $book->getIdUtilisateur(); ?>"> <?php echo $book->getPseudo(); ?> </a> </blockquote>
				
				<p class="col-12 blockquote">
					<?php echo nl2br($book->getContent()); ?> 
				</p>
				
				<?php
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
								<?php
								foreach($listBillet as $billet)
								{
									?>
										<a href="billet-<?php echo $billet->getId(); ?>" class="col-12" title="Consulter le chapitre ?"> <button class="btn btn-info my-2">  <i class="far fa-file-alt"></i> <?php echo $billet->getTitre(); ?> </button></a>
									<?php
								}
								?>
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
					
					<blockquote class="affCat blockquote"> 
						<i class="fas fa-ellipsis-h"></i><br />
						<?php
							if(!empty($bookCategory) AND $bookCategory != null)
							{
								foreach($bookCategory as $category)
								{
									?>
										<button class="btn btn-info" title="<?php echo $category->getComment(); ?>"> <?php echo $category->getName(); ?> </button>
									<?php
								}
							}
							else
							{
								?>
									<h5> Ce book n'a pas de catégorie. </h5>
								<?php
							}
						?>
						<br />
						<i class="far fa-eye mt-5"></i> <?php echo $book->getNbVue(true); ?>
					</blockquote>
					
					<?php
					
					if($user->isAuthentificated())
					{
						if($_SESSION['membre']->getId() == $book->getIdUtilisateur())
						{
							?>
								<a href="addBillet-<?php echo $book->getId(); ?>" class="adm"> <button class="btn btn-primary mr-auto ml-auto"> Ajouter un Billet </button> </a>
							<?php
						}
					}
				?>
			</article>
		<?php
	}