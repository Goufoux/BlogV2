<header>
	<h1 class="col-12"> Liste des Books </h1>
</header>
<section class="row col-12 col-sm-12">
<?php
	if(!empty($listBook))
	{
		?>
		<h2 class="col-12 col-sm-12"> <small class="text-muted"> <i class="far fa-copy" title="Nombre de Book affiché sur la page"></i> <?php echo count($listBook); ?> Books </small></h2>
		<?php
		foreach($listBook as $book)
		{
			?>
				<article class="col-12 col-lg-5 col-md-5 col-sm-12 my-lg-5 my-5 my-sm-5 ml-auto mr-auto prevBook">
					<h2> <a href="book-<?php echo $book->getId(); ?>"> <i class="fas fa-book" style="font-size: 20px;"></i> <?php echo $book->getName(); ?> </a> </h2>
					<blockquote class="blockquote col-12">
						<?php echo nl2br($book->getContent()); ?>
					</blockquote>
					<ul class="list-group">
						<li class="list-group-item"> <i class="fas fa-calendar-alt" title="Date de Publication"></i> <?php echo date('d-m-Y à H:i:s', $book->getDatePub()); ?> </li>
						<li class="list-group-item"> <i class="fas fa-pen" title="Auteur"></i> <a href="profil-<?php echo $book->getIdUtilisateur(); ?>" class="hInfo"> <?php echo $book->getPseudo(); ?> </a> </li>
						<?php
							if($book->getDateMod())
							{
								?>
									<li class="list-group-item"> <i class="far fa-edit"></i> <?php echo $style->styleDate($book->getDateMod()); ?> </li>
								<?php
							}
						?>
					</ul>
				</article>
			<?php
		}
		?>
			<nav aria-label="Page Navigation" class="paginationNav col-12 col-lg-12">
				<ul class="pagination justify-content-center flex-wrap col-12">
				<?php
					if(!empty($pagination) AND (int) $pagination)
					{
						if(empty($_GET['page']))
						{
							$page = 1;
						}
						else
						{
							if($_GET['page'] > 0)
							{
								if((int) $_GET['page'])
									$page = $_GET['page'];
								else
									$page = 1;
							}
							else
								$page = 1;
						}
							
						if($page != 1)
						{
							?>
								<li class="page-item">
									<a class="page-link" href="?page=<?php echo ($page-1); ?>" aria-label="Previous">
										<span aria-hidden="true">&laquo;</span>
										<span class="sr-only">Previous</span>
									</a>
								</li>
							<?php
						}
						for($i = 1; $i <= $pagination; $i++)
						{
							if($i == $page)
							{
								?>
									<li class="page-item active"><span class="page-link"> <?php echo $i; ?> <span class="sr-only">(current)</span></span></li>
								<?php
							}
							else
							{
								?>
									<li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"> <?php echo $i; ?> </a></li>
								<?php
							}
						}
						if($page < $pagination)
						{
							?>
								<li class="page-item">
									<a class="page-link" href="?page=<?php echo ($page+1); ?>" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">Next</span>
									</a>
								</li>
							<?php
						}
					}
				?>
				</ul>
			</nav>
		<?php
	}
	else
	{
		?>
			<h3 class="col-12"> Aucune Publication ! </h3>
		<?php
	}
?>
</section>