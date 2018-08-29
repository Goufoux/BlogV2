<?php
	if(!empty($billet))
	{
		?>
			<link rel="stylesheet" href="css/frontend/write.css" />
			<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=f4dp9wawlw7lf2eguscb58wx6b7v18y76xypimmcsb77gewa"></script>
			<script src="js/write.js"></script>
			<header>
				<h1 class="col-12"> Modification du Billet : <?php echo $billet->getTitre(); ?> </h1>
			</header>
			<section class="col-12">
				<form action="#" method="post" class="form">
					<input type="text" name="bTitle" placeholder="Titre" id="bTitle" class="col-12" value="<?php echo $billet->getTitre(); ?>"/>
					<textarea name="bDesc" placeholder="Contenu" id="bDesc" class="col-12"><?php echo $billet->getContenu(); ?></textarea>
					<input type="submit" value="Modifier" />
				</form>
			</section>
		<?php
	}