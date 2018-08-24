<?php
	if(!empty($listUser))
	{
		$nb = count($listUser) - 1;
		if($nb > 1)
			$nb = $nb . ' Utilisateurs';
		else
			$nb = $nb . ' Utilisateur';
		?>
			<h3> <?php echo $nb; ?> </h3>
			<h5 class="formError"> </h5>
		<?php
			foreach($listUser as $user)
			{
				if($user->getId() != $_SESSION['membre']->getId())
				{
					?>
						<article class="user col-lg-3">
							<h4> Pseudo: <?php echo $user->getPseudo(); ?> </h4>
								<ul id="dtlU">
									<li> Inscris le : <?php echo date('d-m-Y à H:i:s', $user->getDti()); ?> </li>
									<li> E-Mail : <?php echo $user->getEmail(); ?> </li>
									<li> E-Mail vérifié: <?php echo $user->getKeyEmail(); ?> </li>
									<li> Catégorie : <?php echo $style->styleCatUser($user->getCategoryUser()); ?> </li>
									<li> Niveau d'accès : <?php echo $user->getAccessLevel(); ?> </li>
									<?php
										if($_SESSION['membre']->getAccessLevel() >= 5)
										{
											?>
												<button class="delUser" id="user_<?php echo $user->getId(); ?>"> Supprimer l'utilisateur </button>
												<button class="modLevelUser" id="user_<?php echo $user->getId(); ?>"> Modifier niveau d'accès </button>
											<?php
										}
									?>
								</ul>
						</article>
					<?php
				}
			}
	}