<?php include 'includes/session.php'; ?>
<?php
$slug = $_GET['category'];

$conn = $pdo->open();

try {
	$stmt = $conn->prepare("SELECT * FROM category WHERE cat_slug = :slug");
	$stmt->execute(['slug' => $slug]);

	$cat = $stmt->fetch();

	$catid = $cat['id'];
} catch (PDOException $e) {
	echo "There is some problem in connection: " . $e->getMessage();
}

$pdo->close();

?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">

	<div class="wrapper">
		<div class="content-wrapper">
			<div class="container">
				<!-- Main content -->
				<section class="content">
					<h1 class="page-header"><?php echo $cat['name']; ?></h1>
			
					<div class="columns">
						<div class="column is-four-fifths">

							<?php

							$results_per_page = 3;
							$conn = $pdo->open();

							try {

								$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid");
								$stmt->execute(['catid' => $catid]);
								$number_of_results = $stmt->rowCount();
								$number_of_pages = ceil($number_of_results / $results_per_page);

								if (!isset($_GET['page'])) {
									$page = 1;
								} else {
									$page = $_GET['page'];
								}
								$this_page_first_result = ($page - 1) * $results_per_page;


								$inc = 3;
								$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid ORDER BY existences DESC limit " . $this_page_first_result . "," . $results_per_page);
								$stmt->execute(['catid' => $catid]);

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
											   <b><del>&#36; " . number_format($row['price']+500, 2) . "<del></b><br>
		       									<b>&#36; " . number_format($row['price'], 2) . "</b>
		       								</div>
	       								</div>
	       							</div>
	       						";
									if ($inc == 3) echo "</div>";
								}
								if ($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
								if ($inc == 2) echo "<div class='col-sm-4'></div></div>";

								echo'
								<nav class="pagination is-small" role="navigation" aria-label="pagination">
								<ul class="pagination-list">';
								for ($page = 1; $page <= $number_of_pages; $page++) {

									if(isset($_GET['page']))
									{

								
									if($page == $_GET['page'])
									{
										echo '
										<li>
										<a class="pagination-link is-current" href="category.php?category=' . $slug . '&page=' . $page . '">' . $page . '</a> 
										</li>
											';
									}
									else
									{
										echo '
										<li>
										<a class="pagination-link" href="category.php?category=' . $slug . '&page=' . $page . '">' . $page . '</a> 
										</li>
								';
									}
								}
								else
								{
									echo '
									<li>
									<a class="pagination-link is-current" href="category.php?category=' . $slug . '&page=' . $page . '">' . $page . '</a> 
									</li>
							';
								}
								
								}
								echo '		</ul>
								</nav>';
							} catch (PDOException $e) {
								echo "There is some problem in connection: " . $e->getMessage();
							}

							$pdo->close();

							?>
						</div>
						<div class="column is-one-quarter">
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