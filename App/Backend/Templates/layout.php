<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="../css/bootstrap.min.css" />
		<link rel="stylesheet" href="../css/backend/index.css" />
		<link rel="stylesheet" media="screen and (min-width: 768px)" href="../css/backend/md.css" />
		<link rel="stylesheet" media="screen and (min-width: 1px)" href="../css/backend/sm.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
		<script src="../js/jquery-3.3.1.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/admin/index.js"></script>
		<script src="../js/form.js"></script>
		<link rel="icon" type="image/x-icon" href="../img/logo.png" />
		<title><?= isset($title) ? $title : 'Genarkys' ?></title>
	</head>
  
	<body id="bd">
		<!-- NAVBAR -->
		<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="../"> Blog </a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
				<ul class="navbar-nav">
					<?php
						if(!empty($_SESSION['auth']) AND $_SESSION['auth'])
						{
							if($_SESSION['membre']->getAccessLevel() >= 3)
							{
								?>
									<li class="nav-item">
										<a href="./" class="nav-link" title="Admin"> Admin </a>
									</li>
								<?php
							}
							?>
								<li class="nav-item">
									<a href="../deconnect" class="nav-link" title="deco"> Déco </a>
								</li>
							<?php
						}
					?>
				</ul>
			</div>
		</nav>
		<div class="bloc"></div>
		<?php
			if(!empty($error))
			{
				?>
					<article class="alert alert-danger fade show col-lg-5 dol-md-6 col-sm-10 col-12 alertP" role="alert">
						<button type="button" class="close col-1" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="col-11"> <?php var_dump($error); ?> </h4>
					</article>
				<?php
			}
			if(!empty($success))
			{
				?>
					<article class="alert alert-success fade show col-lg-5 dol-md-6 col-sm-10 col-12 alertP" role="alert">
						<button type="button" class="close col-1" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="col-11"> <?php echo $success; ?> </h4>
					</article>
				<?php
			}
		?>
		<nav class="col-12 col-lg-12 panelAdmin">
			<h3 class="col-12"> Administration </h3>
					<a href="commentReport"> <button class="btn btn-primary col-md-4 col-lg-2 col-10">  Commentaire signalé  </button></a>
					<a href="listUser"> <button class="btn btn-primary col-md-4 col-lg-2 col-10"> Liste des utilisateurs </button></a>
					<a href="gestionCategory"> <button class="btn btn-primary col-md-4 col-lg-2 col-10"> Gérer les catégories </button></a>
		</nav>
		<section class="container-fluid col-12">
			<h5 class="error"> </h5>
				<?= $content; ?>
		</section>
	</body>
</html>