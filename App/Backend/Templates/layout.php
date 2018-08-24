<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="../css/bootstrap.min.css" />
		<link rel="stylesheet" href="../css/backend/index.css" />
		<link rel="stylesheet" media="screen and (min-width: 768px)" href="../css/backend/md.css" />
		<script src="../js/jquery-3.3.1.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/admin/index.js"></script>
		<link rel="icon" type="image/x-icon" href="../img/logo.png" />
		<title><?= isset($title) ? $title : 'Genarkys' ?></title>
	</head>
  
	<body>
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
					<article class="alert alert-danger fade show col-lg-5 dol-md-6 col-sm-10 col-12" role="alert">
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
					<article class="alert alert-success fade show col-lg-5 dol-md-6 col-sm-10 col-12" role="alert">
						<button type="button" class="close col-1" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="col-11"> <?php echo $success; ?> </h4>
					</article>
				<?php
			}
		?>
		<div class="container-fluid">
			<nav class="col-12 col-lg-12 col-sm-4">
				<h3 class="col-12"> Administration </h3>
					<ul>
						<li> <a href="commentReport"> <button> Commentaire signalé </button> </a> </li>
						<li> <a href="listUser"> <button> Liste des utilisateurs </button> </a> </li>
					</ul>
			</nav>
			<div class="contentAdmin col-12 col-sm-12 col-md-12 col-lg-12">
				<h5 class="errorForm"> </h5>
				<p>
					<?= $content; ?>
				</p>
			</div>
		</div>
	</body>
</html>