<?php
	if(!empty($billet))
	{
		?>
			<link rel="stylesheet" href="css/frontend/write.css" />
			<h3> Modifier Billet </h3>
			<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=f4dp9wawlw7lf2eguscb58wx6b7v18y76xypimmcsb77gewa"></script>
			<script src="js/write.js"></script>
			<form action="#" method="post" class="formMod">
				<input type="text" name="bTitle" placeholder="Titre" id="bTitle" class="col-12" value="<?php echo $billet->getTitre(); ?>"/>
				<textarea name="bDesc" placeholder="Contenu" id="bDesc" class="col-12"><?php echo $billet->getContenu(); ?></textarea>
				<input type="submit" value="Modifier" />
			</form>
		<?php
	}