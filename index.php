<?php include 'includes/session.php'; ?>

<?php include 'includes/header.php'; ?>


<body class="hold-transition skin-blue layout-top-nav">

	<div class="box">
		<div id="mydiv">
			<img src="includes/img/MASVENDIDOS.jpg" class="image_full">
			<img src="includes/img/click.jpg" class="image_mobile">
		</div>
	</div>

	<div class="wrapper">
		<div class="content-wrapper">
			<div class="container">

				<!-- Main content -->
				<section class="content">
					<div class="columns">
						
						<div class="column is-four-fifths">
							<h1 class="page-header"><a href="category.php?category=laptops">Laptops</a></h1>
							<?php


							$conn = $pdo->open();

							try {



								$inc = 3;
								$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid ORDER BY existences DESC limit 3");
								$stmt->execute(['catid' => '1']);

								foreach ($stmt as $row) {
									$image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
									$inc = ($inc == 3) ? 1 : $inc + 1;
									if ($inc == 1) echo "<div class='columns'>";
									echo "
		 								  <div class='column'>
										   	<div class='box box-solid'>
											   <div class='box-body prod-body'>
												   <img src='" . $image . "' width='100%' height='230px' class='thumbnail'>
												   <h5><a href='product.php?product=" . $row['slug'] . "'>" . $row['name'] . "</a></h5>
				   								</div>
				  							 <div class='box-footer'>
											   <b>&#36; <del>" . number_format($row['price']+500, 2) . "<del></b><br>
					  						 <b>&#36; " . number_format($row['price'], 2) . "</b>
				   							</div>
			  							 </div>
		  							 </div>
	 									  ";
									if ($inc == 3) echo "</div>";
								}
								if ($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
								if ($inc == 2) echo "<div class='col-sm-4'></div></div>";
							} catch (PDOException $e) {
								echo "There is some problem in connection: " . $e->getMessage();
							}

							$pdo->close();

							?>
							<h1 class="page-header"><a href="category.php?category=tablets">Tablets</a></h1>
							<?php


							$conn = $pdo->open();

							try {



								$inc = 3;
								$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid ORDER BY existences DESC limit 3");
								$stmt->execute(['catid' => '3']);

								foreach ($stmt as $row) {
									$image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
									$inc = ($inc == 3) ? 1 : $inc + 1;
									if ($inc == 1) echo "<div class='columns'>";
									echo "
		 								  <div class='column'>
										   	<div class='box box-solid'>
											   <div class='box-body prod-body'>
												   <img src='" . $image . "' width='100%' height='230px' class='thumbnail'>
												   <h5><a href='product.php?product=" . $row['slug'] . "'>" . $row['name'] . "</a></h5>
				   								</div>
				  							 <div class='box-footer'>
					  						 <b>&#36; " . number_format($row['price'], 2) . "</b>
				   							</div>
			  							 </div>
		  							 </div>
	 									  ";
									if ($inc == 3) echo "</div>";
								}
								if ($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
								if ($inc == 2) echo "<div class='col-sm-4'></div></div>";
							} catch (PDOException $e) {
								echo "There is some problem in connection: " . $e->getMessage();
							}

							$pdo->close();

							?>

							<h1 class="page-header"><a href="category.php?category=desktop-pc">Pc Escritorio</a></h1>
							<?php


							$conn = $pdo->open();

							try {



								$inc = 3;
								$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid ORDER BY existences DESC limit 3");
								$stmt->execute(['catid' => '2']);

								foreach ($stmt as $row) {
									$image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
									$inc = ($inc == 3) ? 1 : $inc + 1;
									if ($inc == 1) echo "<div class='columns'>";
									echo "
		 								  <div class='column'>
										   	<div class='box box-solid'>
											   <div class='box-body prod-body'>
												   <img src='" . $image . "' width='100%' height='230px' class='thumbnail'>
												   <h5><a href='product.php?product=" . $row['slug'] . "'>" . $row['name'] . "</a></h5>
				   								</div>
				  							 <div class='box-footer'>
											   <b>&#36; <del>" . number_format($row['price']+500, 2) . "<del></b><br>
					  						 <b>&#36; " . number_format($row['price'], 2) . "</b>
				   							</div>
			  							 </div>
		  							 </div>
	 									  ";
									if ($inc == 3) echo "</div>";
								}
								if ($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
								if ($inc == 2) echo "<div class='col-sm-4'></div></div>";
							} catch (PDOException $e) {
								echo "There is some problem in connection: " . $e->getMessage();
							}

							$pdo->close();

							?>
						</div>
						


						<div class="col-sm-3">
							<?php include 'includes/sidebar.php'; ?>
						</div>
					</div>
				</section>

			</div>
		</div>

		<?php include 'includes/footer.php'; ?>
	</div>

	<?php include 'includes/scripts.php'; ?>
</body>

</html>

<style type="text/css">
	.image_full {
		display: block;
	}

	.image_mobile {
		display: none;
	}

	@media (max-width: 740px) and (min-width: 320px) {
		.image_full {
			display: none;
		}

		.image_mobile {
			display: block;
		}
	}
</style>