<?php include 'includes/session.php'; ?>
<?php
	$conn = $pdo->open();

	$slug = $_GET['product'];

	try{
		 		
	    $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname, products.id AS prodid FROM products LEFT JOIN category ON category.id=products.category_id WHERE slug = :slug");
	    $stmt->execute(['slug' => $slug]);
	    $product = $stmt->fetch();
		
	}
	catch(PDOException $e){
		echo "There is some problem in connection: " . $e->getMessage();
	}

	//page view
	$now = date('Y-m-d');
	if($product['date_view'] == $now){
		$stmt = $conn->prepare("UPDATE products SET counter=counter+1 WHERE id=:id");
		$stmt->execute(['id'=>$product['prodid']]);
	}
	else{
		$stmt = $conn->prepare("UPDATE products SET counter=1, date_view=:now WHERE id=:id");
		$stmt->execute(['id'=>$product['prodid'], 'now'=>$now]);
	}

?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<script>
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12';
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<div class="wrapper">

	 
	  <div class="box">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="columns">
	        	<div class="column is-four-fifths">
	        		<div class="notification is-primary callout" id="callout" style="display:none">
	        			<button type="button" class="button is-primary close"><span aria-hidden="true">&times;</span>
						<span class="tag is-white message"></span>
						</button>
	        		</div>
		            <div class="columns">
		            	<div class="column is-half">
		            		<img src="<?php echo (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg'; ?>" width="100%" class="zoom" data-magnify-src="images/large-<?php echo $product['photo']; ?>">
		            		<br><br>
		            		<form class="box" id="productForm">
		            			<div class="field">
			            			<div class="columns">
			            				
			            				<span class="input-group-btn">
			            					<button type="button" id="minus" class="button is-info is-small"><i class="fa fa-minus"></i></button>
			            				</span>
							          	<input type="text" name="quantity" id="quantity" class="input is-primary is-small" value="1" readonly>
							            <span class="input-group-btn">
							                <button type="button" id="add" class="button is-success is-small"><i class="fa fa-plus"></i></button>
							            </span>
							            <input type="hidden" value="<?php echo $product['prodid']; ?>" name="id">
							        </div>
			            			<button type="submit" class="button is-link"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
			            		</div>
		            		</form>
		            	</div>
		            	<div class="column is-half">
							<div class="column is-half">

		            		<h1 class="page-header"><?php echo $product['prodname']; ?></h1>
		            		<h3><b>&#36; <?php echo number_format($product['price'], 2); ?></b></h3>
		            		<p><b>Category:</b> <a href="category.php?category=<?php echo $product['cat_slug']; ?>"><?php echo $product['catname']; ?></a></p>
		            		<p><b>Description:</b></p>
							</div>
							<div class="column is-half">
		            		<p><?php echo $product['description']; ?></p>
							</div>
		            	</div>
		            </div>
		            <br>
					
					
				
				   </div>
	        	<div class="column is-one-quarter">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
		  <div class="fb-comments" data-href="http://localhost/ecommerce/product.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div> 
	        	
	    </div>
	  </div>
  	<?php $pdo->close(); ?>
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$('#add').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		quantity++;
		$('#quantity').val(quantity);
	});
	$('#minus').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		if(quantity > 1){
			quantity--;
		}
		$('#quantity').val(quantity);
	});

});
</script>
</body>
</html>