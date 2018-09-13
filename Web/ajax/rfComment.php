<?php

	/* Chargement de composer */
	require(__DIR__.'/../../vendor/autoload.php');
	
	use \Core\AjaxRequest;
	
	$urlBillet = $_SERVER['REQUEST_URI'];
	$billet = explode('=', $urlBillet);
	$billet = explode('.', $billet[1]);
	if((int)htmlspecialchars($billet[0]))
	{
		session_start();
		$ajaxRequest = new AjaxRequest('refreshComment', $billet[0]);
		$listComment = $ajaxRequest->run();
		if($listComment)
		{
			/* Affichage des commentaires */
			foreach($listComment as $comment)
			{
				?>
					<div class="card text-white bg-dark col-lg-3 col-md-8 col-12 mr-auto ml-auto mt-2 mb-2 pb-1">
						<div class="card-header">
							<?php echo nl2br($comment->getContenu()); ?>
						</div>
						<div class="card-body">
							<h5 class="card-title"><i class="fas fa-user"></i> <?php echo $comment->getPseudo(); ?> </h5>
							<p class="card-text"><small><i class="far fa-calendar-alt"></i> <?php echo date('d-m-Y à H:i:s', $comment->getDatePub()); ?></small></p>
						</div>
							<button class="btSignaler btn btn-danger col-6 mr-auto ml-auto" value="comment_<?php echo $comment->getId(); ?>" name="btSignaler"> Signaler </button>
					</div>
				<?php
			}
		}
		else
		{
			echo "Aucun commentaire.";
		}
	}
	else
	{
		echo "Une erreur est survenue.";
	}