<script src="js/profil.js"></script>
<?php
	if(!empty($userView))
	{
		?>
			<article class="user">
				<h3> Profil de <?php echo $userView->getPseudo(); ?> (<?php echo $style->styleCatUser($userView->getCategoryUser()); ?>)</h3>
				<span> Inscrit le <?php echo date('d-m-Y', $userView->getDti()); ?> </span>
				<?php
					if(!empty($listBook))
					{
						?>
							<h4> Liste des Books </h4>
						<?php
						foreach($listBook as $book)
						{
							?>
								<article class="bookPreview">
									<h5> <a href="book-<?php echo $book->getId(); ?>"> <?php echo $book->getName(); ?> </a> </h5>
								</article>
							<?php
						}
					}
				?>
			</article>
		<?php
	}
	if($user->isAuthentificated() AND !empty($userMod))
	{
		?>
		<article class="user col-lg-6 col-md-6 col-12">
			<h3> Votre Profil: <a href="profil-<?php echo $_SESSION['membre']->getId(); ?>"> <?php echo $_SESSION['membre']->getPseudo(); ?> </a></h3>
				<form id="formInfo">
					<label for="mPseudo"> Pseudo </label>
						<input type="text" class="col-lg-4" name="mPseudo" id="mPseudo" value="<?php echo $_SESSION['membre']->getPseudo(); ?>" placeholder="Pseudo" required />
					<label for="mEmail"> E-Mail </label>
						<input type="email" class="col-lg-4" name="mEmail" id="mEmail" value="<?php echo $_SESSION['membre']->getEmail(); ?>" placeholder="E-Mail" required />
				</form>
				<hr>
				<article id="formPass">
					<h4> Votre Pass </h4>
					<input type="password" class="col-4" name="mOldPass" id="mOldPass" placeholder="Pass actuel" required />
					<input type="password" class="col-4" name="mNewPass" id="mNewPass" placeholder="Nouveau Pass" required />
					<input type="password" class="col-4" name="mConfirmPass" id="mConfirmPass" placeholder="Confirmation" required />
					<button class="uModPass"> Changer le Pass </button>
				</article>
				<span class="error"> </span>
		</article>
		<article>
			<h4> <?php echo $style->styleCatUser($user->getCategoryUser()); ?> <a href='?modifiedCategory'> Modifier ? </a> </h4>
				<?php
					if($user->getCategoryUser() >= 2)
					{
						?>
							<h5> <a href="readUb" title="Consultez la liste de vos Books"> Voir vos Books ? </a> </h5>
						<?php
					}
				?>
		</article>
		<?php
	}
?>