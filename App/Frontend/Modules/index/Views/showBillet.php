<link rel="stylesheet" href="css/frontend/showBillet.css" />
<?php
	if(!empty($billet))
	{
		?>
		<script>
		setInterval(function() 
		{
			$('.boxComment').load('ajax/rfComment?idBillet=<?php echo $billet->getId(); ?>.php');
		}, 1000);
		</script>
			<article class="col-12 col-md-10 col-lg-8 billet">
				<?php
					if($user->isAuthentificated())
					{
						if($_SESSION['membre']->getId() == $billet->getIdUtilisateur())
						{
							?>
								<div class="row col-lg-12 col-12">
									<i class="far fa-trash-alt delBillet" title="Supprimer ?" id="b_<?php echo $billet->getId(); ?>"></i>
									<a href="./modUb-<?php echo $billet->getId(); ?>"><i class="far fa-edit" title="Modifier ?"></i></a>
								</div>
							<?php
						}
					}
				?>
				<h3> <?php echo $billet->getTitre(); ?> </h3>
				<div class="content">
					<?php echo nl2br($billet->getContenu()); ?>
				</div>
				<ul>
					<li> Par: <?php echo $billet->getPseudo(); ?> </li>
					<li> Publié le: <?php echo date('d-m-Y à H:i:s', $billet->getDatePub()); ?> </li>
						<?php
							if($billet->getDateMod())
							{
								?>
									<li> Modifié il y a : <?php echo $style->styleDate($billet->getDateMod()); ?> </li>
								<?php
							}
						?>
					<li class="nbLike"> <?php echo $billet->getNbLike(); ?> j'aime </li>
					<li class="nbVue"> <?php echo $billet->getNbVue(true); ?> </li>
			</article>
			<span class="error"> </span>
			<?php
			if(!empty($_SESSION['auth']))
			{
				?>
					<input type="button" value="J'aime" class="addLike" id="addLike_<?php echo $billet->getId(); ?>" />
					<input type="button" value="Laisser un commentaire" id="cComment" />
				<?php
			}
			else
			{
				?>
				<article class="alert alert-info fade show col-lg-5 col-md-6 col-sm-10 col-12 alertP" role="alert">
					<button type="button" class="close col-1" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4> Connectez vous pour laisser un commentaire </h4>
				</article>
				<?php
			}
			?>
			<section class="boxComment col-12 col-lg-4 col-md-8 col-sm-10 col-12">
				<h4> Chargement des commentaires... </h4>
			</section>
		<?php
	}
?>