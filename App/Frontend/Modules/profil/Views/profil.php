<script src="js/profil.js"></script>
<?php
	if(!empty($userView))
	{
		?>
			<article class="user">
			<span class="error"></span>
			<?php
				if(!empty($_SESSION['auth']))
				{
					if($userView->getId() != $_SESSION['membre']->getId())
					{
						?>
						<script>
							setInterval(function() 
							{
								$('.possAct').load('ajax/funcUser?role=user&fData='+<?php echo $userView->getId(); ?>+'');
							}, 1000);
						</script>
						<div class="possAct">
							<button> Initialisation </button>
						</div>
						<?php
					}
				}
			?>
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
			<section class="row col-lg-12 col-12">
				<article class="user col-lg-6 col-md-6 col-12">
					<h3> Votre Profil: <a href="profil-<?php echo $_SESSION['membre']->getId(); ?>"> <?php echo $_SESSION['membre']->getPseudo(); ?> </a></h3>
					<h3> <?php echo $style->styleCatUser($user->getCategoryUser()); ?> <a href='?modifiedCategory'> Modifier ? </a> </h3>
						<div id="formInfo" class="row">
								<input type="text" class="col-lg-4 col-md-4 col-sm-6 col-8" name="mPseudo" id="mPseudo" value="<?php echo $_SESSION['membre']->getPseudo(); ?>" placeholder="Pseudo" required />
								<input type="email" class="col-lg-6 col-md-7 col-sm-6 col-8" name="mEmail" id="mEmail" value="<?php echo $_SESSION['membre']->getEmail(); ?>" placeholder="E-Mail" required />
						</div>
						<hr>
						<div id="formPass" class="row">
							<h4 class="col-10"> Votre Pass </h4>
							<input type="password" class="col-lg-4 col-md-6 col-8" name="mOldPass" id="mOldPass" placeholder="Pass actuel" required />
							<input type="password" class="col-lg-4 col-md-6 col-8" name="mNewPass" id="mNewPass" placeholder="Nouveau Pass" required />
							<input type="password" class="col-lg-4 col-md-6 col-8" name="mConfirmPass" id="mConfirmPass" placeholder="Confirmation" required />
							<button class="uModPass col-lg-4 col-md-6 col-8"> Changer le Pass </button>
						</div>
						<span class="error"> </span>
						<?php
							if($user->getCategoryUser() >= 2)
							{
								?>
									<h5> <a href="readUb" title="Consultez la liste de vos Books"> Voir vos Books ? </a> </h5>
								<?php
							}
						?>
				</article>
				<article class="userFollow col-lg-6 col-md-6 col-12">
					<h3> Vos abonnements </h3>
						<div>
							<h4> Utilisateurs </h4>
							<?php 
								if(!empty($followUser))
								{
									foreach($followUser as $userData)
									{
										?>
											<a href="profil-<?php echo $userData['id']; ?>"> <button> <?php echo $userData['pseudo']; ?> </button> </a>	
										<?php
									}
								}
								else
								{
									?>
										<h5> Rien </h5>
									<?php
								}
								?>
						</div>
						<div>
							<h4> Books </h4>
								<?php
									if(!empty($followBook))
									{
										foreach($followBook as $bookName)
										{
											?>
												<a href="book-<?php echo $bookName['id']; ?>"> <button> <?php echo $bookName['name']; ?> </button> </a>
											<?php
										}
									}
									else
									{
										?>
											<h5> Rien </h5>
										<?php
									}
								?>
						</div>
				</article>
			</section>
		<?php
	}
?>