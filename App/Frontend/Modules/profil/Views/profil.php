<script src="js/profil.js"></script>
<?php
	if(!empty($userView))
	{
		?>
			<header>
				<h1 class="col-12"> Profil de :  <?php echo $userView->getPseudo(); ?> (<?php echo $style->styleCatUser($userView->getCategoryUser()); ?>) </h1>
			</header>
			<section class="col-12 user">
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
				<blockquote> Inscrit le <?php echo date('d-m-Y', $userView->getDti()); ?> </blockquote>
				<?php
					if(!empty($listBook))
					{
						?>
							<h4> Liste des Books </h4>
						<?php
						foreach($listBook as $book)
						{
							?>
								<article class="prevBook col-lg-2 col-11" style="display: inline-block; min-height: auto; max-height: auto;">
									<h5> <a href="book-<?php echo $book->getId(); ?>"> <?php echo $book->getName(); ?> </a> </h5>
								</article>
							<?php
						}
					}
				?>
			</section>
		<?php
	}
	if($user->isAuthentificated() AND !empty($userMod))
	{
		?>
			<header>
				<h1 class="col-12" style="font-size: 25px;"> Votre Profil: <a href="profil-<?php echo $_SESSION['membre']->getId(); ?>" style="font-size: 25px;"> <?php echo $_SESSION['membre']->getPseudo(); ?> </a> </h1>
			</header>
			<section class="row col-12 user">
				<article class="col-lg-6">
					<h3 class="col-12"> <?php echo $style->styleCatUser($user->getCategoryUser()); ?> <a href='?modifiedCategory'> Modifier ? </a> </h3>
						<input type="text" class="col-lg-6 col-md-7 col-sm-6 col-10" name="mPseudo" id="mPseudo" value="<?php echo $_SESSION['membre']->getPseudo(); ?>" placeholder="Pseudo" required />
						<input type="email" class="col-lg-6 col-md-7 col-sm-6 col-10" name="mEmail" id="mEmail" value="<?php echo $_SESSION['membre']->getEmail(); ?>" placeholder="E-Mail" required />
						<button class="uModInfo col-lg-4 col-md-8 col-10"> Modifier </button>
						<hr>
					<h4 class="col-12"> Votre Pass </h4>
						<input type="password" class="col-lg-4 col-md-6 col-10" name="mOldPass" id="mOldPass" placeholder="Pass actuel" required />
						<input type="password" class="col-lg-4 col-md-6 col-10" name="mNewPass" id="mNewPass" placeholder="Nouveau Pass" required />
						<input type="password" class="col-lg-4 col-md-6 col-10" name="mConfirmPass" id="mConfirmPass" placeholder="Confirmation" required />
						<button class="uModPass col-lg-4 col-md-6 col-10"> Changer le Pass </button>
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
				<article class="col-lg-6">
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
										<h5> Aucun Abonnement. </h5>
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
											<h5> Aucun Abonnement. </h5>
										<?php
									}
								?>
						</div>
				</article>
			</section>
		<?php
	}
?>