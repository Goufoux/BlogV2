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
					<article class="col-lg-2 col-12">
						<p>
							<?php echo nl2br($comment->getContenu()); ?>
						</p>
						<ul>
							<li> Par <?php echo $comment->getPseudo(); ?> </li>
							<li> Le <?php echo date('d-m-Y à H:i:s', $comment->getDatePub()); ?> </li>
						</li>
							<button class="btSignaler" value="comment_<?php echo $comment->getId(); ?>" name="btSignaler"> Signaler </button>
					</article>
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