<section class="row col-12">
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
						<div class="card bg-secondary text-white col-lg-5 col-md-10 col-sm-8 col-12 py-3 my-1 mx-auto user">
							<div class="card-header">
								<h5 class="card-title"> Pseudo: <?php echo $user->getPseudo(); ?> </h5>
							</div>
							<div class="card-body col-12" id="dtlU_<?php echo $user->getId(); ?>">
								<p class="card-text"> Inscris le : <?php echo date('d-m-Y à H:i:s', $user->getDti()); ?> </p>
								<p class="card-text"> E-Mail : <?php echo $user->getEmail(); ?> </p>
								<p class="card-text"> E-Mail vérifié: <?php echo $user->getKeyEmail(); ?> </p>
								<p class="card-text"> Catégorie : <?php echo $style->styleCatUser($user->getCategoryUser()); ?> </p>
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