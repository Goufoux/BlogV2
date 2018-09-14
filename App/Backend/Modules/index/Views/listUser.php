<section class="row justify-content-center col-12">
<?php
	if(!empty($listUser))
	{
		$nb = count($listUser) - 1;
		if($nb > 1)
			$nb = $nb . ' Utilisateurs';
		else
			$nb = $nb . ' Utilisateur';
		?>
			<h3 class="col-12"> <?php echo $nb; ?> </h3>
		<?php
			foreach($listUser as $user)
			{
				if($user->getId() != $_SESSION['membre']->getId())
				{
					?>
						<div class="card bg-secondary text-white col-lg-3 col-md-10 col-sm-8 col-12 py-3 my-3 mx-5 user">
							<div class="card-header">
								<h5 class="card-title"> Pseudo: <?php echo $user->getPseudo(); ?> </h5>
							</div>
							<div class="card-body col-12" id="dtlU_<?php echo $user->getId(); ?>">
								<p class="card-text"> <i class="far fa-calendar-alt" title="Date d'inscription"></i> <?php echo date('d-m-Y à H:i:s', $user->getDti()); ?> </p>
								<p class="card-text"> <i class="fas fa-at" class="Adresse E-Mail"></i> <?php echo $user->getEmail(); ?> </p>
								<p class="card-text"> <i class="fas fa-user-check" title="Email vérifiée ?"></i> <?php echo $user->getKeyEmail(); ?> </p>
								<p class="card-text"> <i class="fas fa-user-tag" title="Catégorie de l'utilisateur"></i> <?php echo $style->styleCatUser($user->getCategoryUser()); ?> </p>
								<p class="card-text"> Niveau d'accès : <?php echo $user->getAccessLevel(); ?> </p>
								<button class="delUser btn btn-danger col-lg-8 col-12 mx-auto my-1" id="user_<?php echo $user->getId(); ?>"> Supprimer l'utilisateur </button>
								<button class="modLevelUser btn btn-secondary col-lg-8 col-12 mx-auto my-1" id="user_<?php echo $user->getId(); ?>"> Modifier niveau d'accès </button>
							</div>
						</div>
					<?php
				}
			}
	}
	?>
</section>