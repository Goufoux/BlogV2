<script src="js/profil.js"></script>
<?php
	if(!empty($userView))
	{
		?>
			<article class="user">
				<h3> Profil de <?php echo $userView->getPseudo(); ?> (<?php echo $style->styleCatUser($userView->getCategoryUser()); ?>)</h3>
				<span> Inscrit le <?php echo date('d-m-Y', $userView->getDti()); ?> </span>
			</article>
		<?php
	}
	if($user->isAuthentificated() AND !empty($userMod))
	{
		?>
		<article class="user col-lg-6 col-md-6 col-12">
			<h3> Votre Profil: </h3>
				<form id="formInfo">
					<label for="mPseudo"> Pseudo </label>
						<input type="text" class="col-lg-4" name="mPseudo" id="mPseudo" value="<?php echo $_SESSION['membre']->getPseudo(); ?>" placeholder="Pseudo" required />
					<label for="mEmail"> E-Mail </label>
						<input type="email" class="col-lg-4" name="mEmail" id="mEmail" value="<?php echo $_SESSION['membre']->getEmail(); ?>" placeholder="E-Mail" required />
				<span class="error"> </span>
				</form>
		</article>
		<article>
			<h4> <?php echo $style->styleCatUser($user->getCategoryUser()); ?> <a href='?modifiedCategory'> Modifier ? </a> </h4>
		</article>
		<?php
	}
?>