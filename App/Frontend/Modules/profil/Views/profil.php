<script src="js/profil.js"></script>
<?php
	if(!empty($userView))
	{
		?>
			<header>
				<h1 class="col-12"> <i class="fas fa-user-alt"></i> <?php echo $userView->getPseudo(); ?> (<?php echo $style->styleCatUser($userView->getCategoryUser()); ?>) </h1>
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
				<blockquote class="col-12 my-3"> <i class="fas fa-calendar-alt" title="Inscrit le"></i> Inscrit le <?php echo date('d-m-Y', $userView->getDti()); ?> </blockquote>
				<?php
					if(!empty($listBook))
					{
						?>
							<h4 class="col-12"> Liste des Books </h4>
						<?php
						foreach($listBook as $book)
						{
							?>
								<div class="col-lg-3 col-11 col-md-5 card bg-secondary text-white mx-lg-5 my-lg-5 my-2 mx-3 mx-md-4" style="display: inline-block;">
									<div class="card-header">
										<h5 class="card-title"> <a href="book-<?php echo $book->getId(); ?>"> <i class="fas fa-book"></i> <?php echo $book->getName(); ?> </a> </h5>
									</div>
									<div class="card-body">
										<p class="card-text"> <i class="fas fa-calendar-alt" title="Publié le"></i> <?php echo date('d-m-Y H:i:s', $book->getDatePub()); ?> </p>
										<p class="card-text"> <i class="far fa-eye"></i> <?php echo $book->getNbVue(true); ?> </p>
									</div>
								</div>
							<?php
						}
					}
					if(!empty($pagUserBook))
					{
						if((int) $pagUserBook)
						{
							if(!empty($_GET['pageUserBook']))
								$pageUserBook = $_GET['pageUserBook'];
							else
								$pageUserBook = 1;
							
							?>
								<nav aria-label="Page Navigation" class="paginationNav col-12 col-lg-12">
									<ul class="pagination justify-content-center flex-wrap col-12">
									<?php
										if($pageUserBook != 1)
										{
											?>
												<li class="page-item">
													<a class="page-link" href="?pageUserBook=<?php echo ($pageUserBook-1); ?>" aria-label="Previous">
														<span aria-hidden="true">&laquo;</span>
														<span class="sr-only">Previous</span>
													</a>
												</li>
											<?php
										}
										for($i = 1; $i <= $pagUserBook; $i++)
										{
											if($i == $pageUserBook)
											{
												?>
													<li class="page-item active"><span class="page-link"> <?php echo $i; ?> <span class="sr-only">(current)</span></span></li>
												<?php
											}
											else
											{
												?>
													<li class="page-item"><a class="page-link" href="?pageUserBook=<?php echo $i; ?>"> <?php echo $i; ?> </a></li>
												<?php
											}
										}
										if($pageUserBook < $pagUserBook)
										{
											?>
												<li class="page-item">
													<a class="page-link" href="?pageUserBook=<?php echo ($pageUserBook+1); ?>" aria-label="Next">
														<span aria-hidden="true">&raquo;</span>
														<span class="sr-only">Next</span>
													</a>
												</li>
											<?php
										}
									?>
									</ul>
								</nav>
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
				<h1 class="col-12" style="font-size: 25px;"><i class="fas fa-users-cog" title="Modifier vos infos, consulter vos abonnements et historique"></i> <a href="profil-<?php echo $_SESSION['membre']->getId(); ?>" style="font-size: 25px;"> <?php echo $_SESSION['membre']->getPseudo(); ?> </a> </h1>
			</header>
			<section class="col-12 user">
				<span class="error"> </span>
				<article class="col-lg-12 col-12 row rgp">
					<div class="col-12 col-lg-6">
						<h3 class="col-12"> <?php echo $style->styleCatUser($user->getCategoryUser()); ?> <a href='?modifiedCategory'> Modifier ? </a> </h3>
						<input type="text" class="form-control col-lg-5 col-md-4 col-sm-6 col-10" name="mPseudo" id="mPseudo" value="<?php echo $_SESSION['membre']->getPseudo(); ?>" placeholder="Pseudo" required />
						<input type="email" class="form-control col-lg-5 col-md-4 col-sm-6 col-10" name="mEmail" id="mEmail" value="<?php echo $_SESSION['membre']->getEmail(); ?>" placeholder="E-Mail" required />
						<button class="uModInfo col-lg-5 col-md-4 col-sm-6 col-10 btn"> Modifier </button>
					</div>
					<div class="col-12 col-lg-6">
						<h3 class="col-12"> <i class="fas fa-key"></i> Votre Pass </h3>
						<input type="password" class="col-lg-5 col-md-4 col-10 form-control" name="mOldPass" id="mOldPass" placeholder="Pass actuel" required />
						<input type="password" class="col-lg-5 col-md-4 col-10 form-control" name="mNewPass" id="mNewPass" placeholder="Nouveau Pass" required />
						<input type="password" class="col-lg-5 col-md-4 col-10 form-control" name="mConfirmPass" id="mConfirmPass" placeholder="Confirmation" required />
						<button class="uModPass col-lg-5 col-md-4 col-10 btn"> Changer le Pass </button>
						<?php
							if($user->getCategoryUser() >= 2)
							{
								?>
									<h5> <a href="readUb" title="Consultez la liste de vos Books"> Voir vos Books ? </a> </h5>
								<?php
							}
						?>
					</div>
				</article>
				<hr>
				<section class="col-lg-12 col-12 row">
					<h4 class="col-12 my-3"> <i class="fab fa-hubspot"></i> Abonnement </h4>
						<div class="col-12 row">
								<div class="col-lg-6 col-12">
									<h4> <i class="fas fa-users"></i> Utilisateurs </h4>
										<div class="list-group col-10 col-lg-5 ml-auto mr-auto">
											<?php
												if(!empty($userFollow))
												{
													foreach($userFollow as $user)
													{
														?>
															<a class="list-group-item list-group-item-action" href="profil-<?php echo $user->getIdHistory(); ?>"> <?php echo $user->getName(); ?> </a>
														<?php
													}
												}
												else
												{
													?>
														<h5> Aucun abonnement </h5>
													<?php
												}
											?>
										</div>
										<?php
											if(!empty($pagFolUser))
											{
												if((int) $pagFolUser)
												{
													if(!empty($_GET['pageFolUser']))
														$pageFolUser = $_GET['pageFolUser'];
													else
														$pageFolUser = 1;
													
													?>
														<nav aria-label="Page Navigation" class="paginationNav col-12 col-lg-12">
															<ul class="pagination justify-content-center flex-wrap col-12">
															<?php
																if($pageFolUser != 1)
																{
																	?>
																		<li class="page-item">
																			<a class="page-link" href="?pageFolUser=<?php echo ($pageFolUser-1); ?>" aria-label="Previous">
																				<span aria-hidden="true">&laquo;</span>
																				<span class="sr-only">Previous</span>
																			</a>
																		</li>
																	<?php
																}
																for($i = 1; $i <= $pagFolUser; $i++)
																{
																	if($i == $pageFolUser)
																	{
																		?>
																			<li class="page-item active"><span class="page-link"> <?php echo $i; ?> <span class="sr-only">(current)</span></span></li>
																		<?php
																	}
																	else
																	{
																		?>
																			<li class="page-item"><a class="page-link" href="?pageFolUser=<?php echo $i; ?>"> <?php echo $i; ?> </a></li>
																		<?php
																	}
																}
																if($pageFolUser < $pagFolUser)
																{
																	?>
																		<li class="page-item">
																			<a class="page-link" href="?pageFolUser=<?php echo ($pageFolUser+1); ?>" aria-label="Next">
																				<span aria-hidden="true">&raquo;</span>
																				<span class="sr-only">Next</span>
																			</a>
																		</li>
																	<?php
																}
															?>
															</ul>
														</nav>
													<?php
												}
											}
										?>
								</div>
								<div class="col-lg-6 col-12">
									<h4> <i class="fas fa-book"></i> Books </h4>
										<div class="list-group col-10  col-lg-5 ml-auto mr-auto">
										<?php
											if(!empty($bookFollow))
											{
												foreach($bookFollow as $book)
												{
													?>
														<a class="list-group-item list-group-item-action" href="book-<?php echo $book->getIdHistory(); ?>"> <?php echo $book->getName(); ?></a>
													<?php
												}
											}
											else
											{
												?>
													<h5> Aucun abonnement </h5>
												<?php
											}
										?>
										</div>
										<?php
											if(!empty($pagFolBook))
											{
												if((int) $pagFolBook)
												{
													if(!empty($_GET['pageFolBook']))
														$pageFolBook = $_GET['pageFolBook'];
													else
														$pageFolBook = 1;
													
													?>
														<nav aria-label="Page Navigation" class="paginationNav col-12 col-lg-12">
															<ul class="pagination justify-content-center flex-wrap col-12">
															<?php
																if($pageFolBook != 1)
																{
																	?>
																		<li class="page-item">
																			<a class="page-link" href="?pageFolBook=<?php echo ($pageFolBook-1); ?>" aria-label="Previous">
																				<span aria-hidden="true">&laquo;</span>
																				<span class="sr-only">Previous</span>
																			</a>
																		</li>
																	<?php
																}
																for($i = 1; $i <= $pagFolBook; $i++)
																{
																	if($i == $pageFolBook)
																	{
																		?>
																			<li class="page-item active"><span class="page-link"> <?php echo $i; ?> <span class="sr-only">(current)</span></span></li>
																		<?php
																	}
																	else
																	{
																		?>
																			<li class="page-item"><a class="page-link" href="?pageFolBook=<?php echo $i; ?>"> <?php echo $i; ?> </a></li>
																		<?php
																	}
																}
																if($pageFolBook < $pagFolBook)
																{
																	?>
																		<li class="page-item">
																			<a class="page-link" href="?pageFolBook=<?php echo ($pageFolBook+1); ?>" aria-label="Next">
																				<span aria-hidden="true">&raquo;</span>
																				<span class="sr-only">Next</span>
																			</a>
																		</li>
																	<?php
																}
															?>
															</ul>
														</nav>
													<?php
												}
											}
										?>
								</div>
						</div>
						<h4 class="col-12 my-3"> <i class="fas fa-history"></i> Historique </h4>
						<div class="col-12 row my-lg-5">
								<div class="col-lg-6 col-12">
									<h4> <i class="fas fa-book"></i> Books </h4>
										<div class="list-group col-10 col-lg-5 ml-auto mr-auto">
										<?php
											if(!empty($historyBook))
											{
												foreach($historyBook as $Hisbook)
												{
													?>
														<a class="list-group-item list-group-item-action" href="book-<?php echo $Hisbook->getIdHistory(); ?>"> <?php echo $Hisbook->getName(); ?> </a>
													<?php
												}
											}
											else
											{
												?>
													<h5> Pas d'historique. </h5>
												<?php
											}
										?>
										</div>
										<?php
											if(!empty($pagHisBook))
											{
												if((int) $pagHisBook)
												{
													if(!empty($_GET['pageHisBook']))
														$pageHisBook = $_GET['pageHisBook'];
													else
														$pageHisBook = 1;
													
													?>
														<nav aria-label="Page Navigation" class="paginationNav col-12 col-lg-12">
															<ul class="pagination justify-content-center flex-wrap col-12">
															<?php
																if($pageHisBook != 1)
																{
																	?>
																		<li class="page-item">
																			<a class="page-link" href="?pageHisBook=<?php echo ($pageHisBook-1); ?>" aria-label="Previous">
																				<span aria-hidden="true">&laquo;</span>
																				<span class="sr-only">Previous</span>
																			</a>
																		</li>
																	<?php
																}
																for($i = 1; $i <= $pagHisBook; $i++)
																{
																	if($i == $pageHisBook)
																	{
																		?>
																			<li class="page-item active"><span class="page-link"> <?php echo $i; ?> <span class="sr-only">(current)</span></span></li>
																		<?php
																	}
																	else
																	{
																		?>
																			<li class="page-item"><a class="page-link" href="?pageHisBook=<?php echo $i; ?>"> <?php echo $i; ?> </a></li>
																		<?php
																	}
																}
																if($pageHisBook < $pagHisBook)
																{
																	?>
																		<li class="page-item">
																			<a class="page-link" href="?pageHisBook=<?php echo ($pageHisBook+1); ?>" aria-label="Next">
																				<span aria-hidden="true">&raquo;</span>
																				<span class="sr-only">Next</span>
																			</a>
																		</li>
																	<?php
																}
															?>
															</ul>
														</nav>
													<?php
												}
											}
										?>
								</div>
								<div class="col-lg-6 col-12">
									<h4> <i class="fas fa-book-open"></i> Billets </h4>
										<div class="list-group col-10 col-lg-5 ml-auto mr-auto">
										<?php
											if(!empty($historyBillet))
											{
												foreach($historyBillet as $HisBillet)
												{
													?>
														<a class="list-group-item list-group-item-action" href="billet-<?php echo $HisBillet->getIdHistory(); ?>"> <?php echo $HisBillet->getName(); ?> </a>
													<?php
												}
											}
											else
											{
												?>
													<h5> Pas d'historique. </h5>
												<?php
											}
										?>
										</div>
										<?php
											if(!empty($pagHisBillet))
											{
												if((int) $pagHisBillet)
												{
													if(!empty($_GET['pageHisBillet']))
														$pageHisBillet = $_GET['pageHisBillet'];
													else
														$pageHisBillet = 1;
													
													?>
														<nav aria-label="Page Navigation" class="paginationNav col-12 col-lg-12">
															<ul class="pagination justify-content-center flex-wrap col-12">
															<?php
																if($pageHisBillet != 1)
																{
																	?>
																		<li class="page-item">
																			<a class="page-link" href="?pageHisBillet=<?php echo ($pageHisBillet-1); ?>" aria-label="Previous">
																				<span aria-hidden="true">&laquo;</span>
																				<span class="sr-only">Previous</span>
																			</a>
																		</li>
																	<?php
																}
																for($i = 1; $i <= $pagHisBillet; $i++)
																{
																	if($i == $pageHisBillet)
																	{
																		?>
																			<li class="page-item active"><span class="page-link"> <?php echo $i; ?> <span class="sr-only">(current)</span></span></li>
																		<?php
																	}
																	else
																	{
																		?>
																			<li class="page-item"><a class="page-link" href="?pageHisBillet=<?php echo $i; ?>"> <?php echo $i; ?> </a></li>
																		<?php
																	}
																}
																if($pageHisBillet < $pagHisBillet)
																{
																	?>
																		<li class="page-item">
																			<a class="page-link" href="?pageHisBillet=<?php echo ($pageHisBillet+1); ?>" aria-label="Next">
																				<span aria-hidden="true">&raquo;</span>
																				<span class="sr-only">Next</span>
																			</a>
																		</li>
																	<?php
																}
															?>
															</ul>
														</nav>
													<?php
												}
											}
										?>
								</div>
						</div>
				</section>
			</section>
		<?php
	}
?>