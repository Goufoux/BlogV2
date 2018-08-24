<?php
	if(!empty($billet))
	{
		?>
			<h4> ReadBillet.php </h4>
			<article class="prevBillet col-lg-3 col-md-4 col-sm-6 col-8">
				
				<h4 class="col-12"> <a href="./<?php echo 'billet-'.$billet->getId(); ?>"> <?php echo $billet->getTitre(); ?> </a> </h4>
				<p>	
					<?php echo nl2br($billet->getContenu()); ?> 
				</p>
				<ul class="col-12">
					<li> Publié le: <?php echo date('d-m-Y à H:i:s', $billet->getDatePub()); ?> </li>
					<?php
						if(!empty($billet->getDateMod()))
						{
							?>
								<li> Modifié il y a : <?php echo $style->styleDate($billet->getDateMod()); ?> </li>
							<?php
						}
					?>
				</ul>
			</article>
		<?php
	}