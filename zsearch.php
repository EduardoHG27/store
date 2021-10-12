<?php include 'includes/session.php'; ?>
<?php include 'includes/header1.php'; 

$slug = $_GET['category'];
$_SESSION['slug'] = $slug;

?>
<section id="breadcrumbs" class="breadcrumbs">
		<div class="container">
			<div class="d-flex justify-content-between align-items-center">
				<h2>Busqueda</h2>
				<ol>
					<li><a href="index1.php">Home</a></li>
					<li>Busqueda</li>
				</ol>
			</div>

		</div>
	</section>
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-md-9">
	            	<?php
	       			$output = array('list'=>'');
					 $results_per_page = 6;
	       			$conn = $pdo->open();

	       			$stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM products WHERE name LIKE :keyword ");
	       			$stmt->execute(['keyword' => '%'.$_POST['keyword'].'%']);
				    $row = $stmt->fetch();
					$number_of_results = $stmt->rowCount();
					$number_of_pages = ceil($number_of_results / $results_per_page);
					if (!isset($_GET['page'])) {
						$page = 1;
					} else {
						$page = $_GET['page'];
					}
					$this_page_first_result = ($page - 1) * $results_per_page;

	       			if($row['numrows'] < 1){
	       				echo '<h1 class="page-header">No results found for <i>'.$_POST['keyword'].'</i></h1>';
	       			}
	       			else{
	       				echo '<h1 class="page-header">Search results for <i>'.$_POST['keyword'].'</i></h1>';
		       			try{
		       			 	$inc = 3;	
						    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE :keyword  limit " . $this_page_first_result . "," . $results_per_page);
						    $stmt->execute(['keyword' => '%'.$_POST['keyword'].'%']);
					 
						    foreach ($stmt as $row) {
						    	$highlighted = preg_filter('/' . preg_quote($_POST['keyword'], '/') . '/i', '<b>$0</b>', $row['name']);
						    	$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
						    	$inc = ($inc == 3) ? 1 : $inc + 1;
	       						if($inc == 1) echo "<hr width=100% align='center'>
								   <div class='row'>";
	       						echo "<div class='col-lg-4 col-md-6'>
	       							
		       								<div class='container'>
		       									<img src='".$image."' width='100%' height='100%' class='thumbnail'>
		       									<h5><a href='product.php?product=".$row['slug']."'>".$highlighted."</a></h5>
		       								</div>
		       								<div class='container'>
		       									<b>&#36; ".number_format($row['price'], 2)."</b>
		       								</div>
	       							</div>
	       						";
	       						if($inc == 3) echo "</div>";
						    }
							if ($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
							if ($inc == 2) echo "<div class='col-sm-4'></div></div>";
							echo'
								<div class="container">
								<br>							
								<ul class="pagination">';
								for ($page = 1; $page <= $number_of_pages; $page++) {

									if(isset($_GET['page']))
									{
									if($page == $_GET['page'])
									{
										echo '
										<li class="page-item active">
										<a class="page-link" href="zsearch.php?category=' . $slug . '&page=' . $page . '">' . $page . '</a> 
										</li>
											';
									}
									else
									{
										echo'
										<li class="page-item">
										<a class="page-link" href="zsearch.php?category=' . $slug . '&page=' . $page . '">' . $page . '</a> 
										</li>
								';
									}
								}
								else
								{
									echo '
									<li>
									<a class="page-item" href="zsearch.php?category=' . $slug . '&page=' . $page . '">' . $page . '</a> 
									</li>
							';
								}
								
								}
								echo '</ul>
								</div>
								</nav>';
							
						}
						catch(PDOException $e){
							echo "There is some problem in connection: " . $e->getMessage();
						}
					}

					$pdo->close();

	       		?> 
	        	</div>
	        	<div class="col-md-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>  
  	<?php include 'includes/footer.php'; ?>
	<?php include 'includes/scripts.php'; ?>

</html>