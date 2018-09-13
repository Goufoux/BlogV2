<?php
	if(!empty($report))
	{
		$nb = count($report);
		?>
			<h4> <?php echo $nb . ' commentaire(s) signalé(s)'; ?> </h4>
				<section class="boxComment">
					<?php
						foreach($report as $comment)
						{
							?>
								<div class="card bg-info text-white col-lg-4 col-md-6 col-sm-8 col-8 py-3 my-2 mx-5">
									<div class="card-header">
										<?php echo nl2br($comment->getContenu()); ?>
									</div>
									<div class="card-body">
										<h5 class="card-title"> <?php echo $comment->getReport(); ?> signalement(s) </h5>
										<p class="card-text"> <i class="fas fa-edit"></i> <?php echo $style->styleDate($comment->getDatePub()); ?></p>
									</div> 
									<button class="delComment btn btn-danger col-6 mr-auto ml-auto" id="comment_<?php echo $comment->getId(); ?>"> Supprimer </button> </a>
								</div>
							<?php
						}
					?>
				</section>
		<?php
	}
	else
	{
		?>
			<h4> Aucun commentaire signalé. </h4>
		<?php
	}
if(!empty($error))
{
	?>
		<article class="error">
				<img src="img/croix.png" class="croix" alt="Fermer ?" title="Fermer ?" />
				<h4> <?php echo $error; ?> </h4>
			</article>
	<?php
}