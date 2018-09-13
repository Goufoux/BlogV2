<link rel="stylesheet" href="css/frontend/showBillet.css" />
<script src="js/billet.js"></script>
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
			<header>
				<h1 class="col-12 h1"> <i class="fas fa-file"></i> <?php echo $billet->getTitre(); ?> </h1>
			</header>
			<section class="row col-12">
			<article class="col-11 col-md-10 col-lg-10 billet">
				<?php
					if($user->isAuthentificated())
					{
						if($_SESSION['membre']->getId() == $billet->getIdUtilisateur())
						{
							?>
									<i class="far fa-trash-alt delBillet" title="Supprimer ?" id="b_<?php echo $billet->getId(); ?>"></i>
									<a href="./modUb-<?php echo $billet->getId(); ?>"><i class="far fa-edit" title="Modifier ?"></i></a>
							<?php
						}
					}
				?>
				<p class="col-12">
					<?php echo nl2br($billet->getContenu()); ?>
				</p>
				<ul class="list-group col-lg-4 col-10 col-md-8 mr-auto ml-auto">
					<li class="list-group-item list-group-item-info"> <i class="fas fa-user-edit" title="Auteur"></i> <?php echo $billet->getPseudo(); ?> </li>
					<li class="list-group-item list-group-item-info"> <i class="far fa-calendar-alt" title="Date de publication"></i> <?php echo date('d-m-Y à H:i:s', $billet->getDatePub()); ?> </li>
						<?php
							if($billet->getDateMod())
							{
								?>
									<li class="list-group-item list-group-item-info"> <i class="far fa-edit" title="Dernière modification"></i> <?php echo $style->styleDate($billet->getDateMod()); ?> </li>
								<?php
							}
						?>
					<li class="nbLike list-group-item list-group-item-info"> <i class="far fa-thumbs-up" title="Nombre de J'aime"></i> <?php echo $billet->getNbLike(); ?> j'aime </li>
					<li class="nbVue list-group-item list-group-item-info"> <i class="far fa-eye" title="Nombre de Vue"></i> <?php echo $billet->getNbVue(true); ?> </li>
			</article>
			<?php
			if(!empty($_SESSION['auth']))
			{
				?>
					<article class="col-12" style="text-align: center;">
						<input type="button" value="J'aime" class="addLike btn btn-secondary" id="addLike_<?php echo $billet->getId(); ?>" />
						<input type="button" value="Laisser un commentaire" id="cComment" class="btn btn-secondary"/>
					</article>
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
			<span class="error"> </span>
			<section class="boxComment col-12 mr-auto ml-auto mt-4">
				<h4> Chargement des commentaires... </h4>
			</section>
		<?php
	}
?>
</section>