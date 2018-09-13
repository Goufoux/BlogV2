<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/frontend/index.css" />
		<link rel="stylesheet" media="screen and (min-width: 768px)" href="css/frontend/md.css" />
		<link rel="stylesheet" media="screen and (min-width: 1px)" href="css/frontend/sm.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/index.js"></script>
		<script src="js/form.js"></script>
		<link rel="icon" type="image/x-icon" href="img/logo.png" />
		<title><?= isset($title) ? $title : 'Genarkys' ?></title>
	</head>
  
	<body id="bd">
		<!-- NAVBAR -->
		<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="."> <i class="fas fa-home"></i> Blog </a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
				<ul class="navbar-nav">
					<?php
					if(!empty($_SESSION['auth']) AND $_SESSION['auth'])
					{
						?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-user"></i>
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="profil"> <i class="far fa-user-circle"></i> Profil </a>
									<?php
										switch($_SESSION['membre']->getCategoryUser())
									{
										case '0':
											break;
										case '1':
											break;
										case '2':
											?>
												<a href="readUb" class="dropdown-item" title="Consultez vos Books"> <i class="fas fa-book"></i> Book </a>
											<?php
											break;
										case '3':
											?>
												<a href="readUb" class="dropdown-item" title="Consultez vos Book"> <i class="fas fa-book"></i> Book </a>
											<?php
											break;
										default:
											break;
									}
									?>
								</div>
							</li>
							<li class="nav-item">
								<a href="deconnect" class="nav-link" title="Se déconnecter ?"> <i class="fas fa-sign-out-alt"></i> </a>
							</li>
						<?php
							if($_SESSION['membre']->getAccessLevel() >= 3)
							{
								?>
									<li class="nav-item">
										<a href="admin/" class="nav-link" title="Accès Administration"> <i class="fas fa-unlock-alt"></i> Admin </a>
									</li>
								<?php
							}
							switch($_SESSION['membre']->getCategoryUser())
							{
								case '0':
									break;
								case '1':
									break;
								case '2':
									?>
										<li class="nav-item">
											<a href="readUb" class="nav-link" title="Écrire ?"> <i class="fas fa-pencil-alt"></i> Publier </a>
										</li>
									<?php
									break;
								case '3':
									?>
										<li class="nav-item">
											<a href="readUb" class="nav-link" title="Écrire ?"> <i class="fas fa-pencil-alt"></i> Publier </a>
										</li>
									<?php
									break;
								default:
									break;
							}
					}
					else
					{
						if(!empty($_COOKIE['alreadySuscribe']))
						{
							?>
								<li class="nav-item"><button id="clConnect" class="col-lg-12 col-md-4 col-sm-6 col-6 btn btn-secondary"> Connexion </button></li>
							<?php
						}
						else
						{
							?>
								<li class="nav-item"><button id="clSuscribe" class="col-lg-12 col-md-4 col-sm-6 col-6 btn btn-secondary"> Inscription </button></li>
								<li class="nav-item"><button id="clConnect" class="col-lg-12 col-md-4 col-sm-6 col-6 btn btn-secondary"> Connexion </button></li>
							<?php
						}
					}
				?>
				</ul>
			</div>
			<form class="form-inline my-2 my-lg-0" method="get" action="./">
				<label style="display: none;" for="bSearch">Chercher</label>
				<input class="form-control mr-sm-0 col-sm-8 col-8 col-md-8" id="bSearch" type="search" name="bSearch" placeholder="Rechercher un Book" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0">Search</button>
			</form>
		</nav>
		<div class="bloc"></div>
		<?php
			if(!empty($error))
			{
				?>
					<article class="alert alert-danger fade show col-lg-5 col-md-6 col-sm-10 col-12 alertP" role="alert">
						<button type="button" class="close col-1" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="col-11"> <?php echo $error; ?> </h4>
					</article>
				<?php
			}
			if(!empty($success))
			{
				?>
					<article class="alert alert-success fade show col-lg-5 col-md-6 col-sm-10 col-12 alertP" role="alert">
						<button type="button" class="close col-1" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="col-11"> <?php echo $success; ?> </h4>
					</article>
				<?php
			}
			/* Si emptyCategory = true; alors l'utilisateur n'a pas encore renseigné ce qu'il faisait sur le Blog, on affiche donc un formulaire */
			if(!empty($emptyCategory))
			{
				?>
					<article class="alert alert-info fade show col-lg-5 col-md-6 col-sm-10 col-12 alertP" role="alert">
						<button type="button" class="close col-1" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="col-11"> Et sinon, que faites vous ici ? </h4>
							<form action="#" method="get" id="ucForm" class="col-12">
								<input type="checkbox" name="ucRead" class="ucRead col-1" id="ucRead" value="1" /><label for="ucRead" class="col-11"> Lire du contenu </label> 
								<input type="checkbox" name="ucWrite" class="ucWrite col-1" id="ucWrite" value="2" /><label for="ucWrite" class="col-11"> Publier du contenu </label>
								<input type="button" value="Valider" id="ucVd" /> 
								<span class="ucError col-12"> </span>
							</form>
					</article>
				<?php
			}
		?>
		<section class="container-fluid col-12 col-sm-12">
				<?= $content; ?>
		</section>
		<?php
			if(empty($_COOKIE['cookieAccept']))
			{
				?>
					<section class="cookieInfo">
						<h4> A propos de vos données. </h4>
						<p>
							En naviguant sur notre site vous acceptez l'utilisation de cookie qui améliorent votre expérience. <button class="cookie btn btn-success" id="clAcceptCookie"> Ok </button> <button class="cookie btn btn-warning" id="refuseCookie"> Refuser </button>
						</p>
					</section>
				<?php
			}
		?>
	</body>
</html>