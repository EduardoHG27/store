<?php include 'includes/session.php'; ?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="includes/css/font/css/font-awesome.min.css">

<div class="card">
	<div class="row">
		<div class="col-md-8 cart">
			<div class="title">
				<div class="row">
					<div class="col">
						<h4><b>Carrito de Compra</b></h4>
					</div>
					<div class="col align-self-center text-right text-muted">Articulo(s) <span class="cart_count"></span></div>
					
				</div>
			</div>


			<div id="tbody">


			</div>



			
			<div class="back-to-shop"><a href="index.php">&leftarrow;</a><span class="text-muted">Back to shop</span></div>
		</div>
		<div class="col-md-4 summary">
			<div>
				<h5><b>Summary</b></h5>
			</div>
			<hr>
			<div class="row">
				<div class="col" style="padding-left:0;">Articulo(s) <span class="cart_count"></div>
		
			</div>
			<form>
				<p>SHIPPING</p> <select>
					<option class="text-muted">Standard-Delivery- &dollar;5.00</option>
				</select>
				<p>GIVE CODE</p> <input id="code" placeholder="Enter your code">
			</form>
			<div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
				<div class="col">TOTAL PRICE</div>
				<div class="col text-right">&dollar; 137.00</div>
			</div> 
			<form action="zpagar.php" method="POST">
					<input type="input" class="form-control" name="txt_vencido" id="txt_vencido" value="" style="display: none">
					<input type="input" class="form-control" name="txt_id" id="txt_id" value="<?php if(isset($_SESSION['user'])){ echo $_SESSION['user'] ;} ?>" style="display: none">
					

			<?php
						//<div id='paypal-button'></div>
								
	        			if(isset($_SESSION['user'])){
						
							$stmt = $conn->prepare("SELECT zipcode,address FROM users WHERE id=:user_id");
							$stmt->execute(['user_id'=>$_SESSION['user']]);
							$row = $stmt->fetch();

							if($row['zipcode']=="")
							{
								echo "
								<h5>Necesitas asignar un codigo postal en tu  <a class='btn btn-link' href='profile.php'>Perfil</a> para hacer el pedido.</h5>
								";
							}
							else if($row['address']=="")
							{
								echo "
								<h5>Necesitas asignar un domicilio en tu  <a class='btn btn-link' href='profile.php'>Perfil</a> para hacer el pedido.</h5>
								";
							}
							else
							{
								$cart = $conn->prepare("SELECT product_id FROM cart WHERE user_id=:user_id");
								$cart->execute(['user_id'=>$_SESSION['user']]);
								$row_cart = $cart->fetch();

							
								if(!$row_cart)
								{
									echo "
									<h5>Necesitas asignar un domicilio en tu  <a class='btn btn-link' href='index.php'>Home</a> para hacer el pedido.</h5>
									";
								}
								else
								{
									echo "
								<button type='submit' class='btn' name='signup'><i class='fa fa-pencil'></i>Pagar</button>
								";
								}
								
							}
							
	        			}
	        			else{
	        				echo "
									<label for='exampleInputPassword1'>Necesitas acceder a tu <a class='btn btn-link' href='signup.php'>Cuenta</a> para hacer el pedido.</label>
	        					
	        				";
	        			}
	        		?>
				</form>
		</div>
	</div>
</div>
<!-- 
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">


	 
	  <div class="content-wrapper">
	    <div class="container">

	  
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
	        		<h1 class="page-header">YOUR CART</h1>
					<script src="https://js.stripe.com/v3/"></script>
	        		<div class="box box-solid">
	        			<div class="box-body">
		        		<table class="table table-bordered">
						
		        			<thead>
		        			
		        				<th>Imagen</th>
		        				<th>Articulo</th>
		        				<th>Precio</th>
		        				<th width="20%">Cantidad</th>
		        				<th>Subtotal</th>
								<th>Eliminar</th>
		        			</thead>
		        			<tbody id="tbody">
		        			</tbody>
		        		</table>
	        			</div>
	        		</div>
					<form action="zpagar.php" method="POST">
					<input type="input" class="form-control" name="txt_vencido" id="txt_vencido" value="" style="display: none">
					<input type="input" class="form-control" name="txt_id" id="txt_id" value="<?php if (isset($_SESSION['user'])) {
																									echo $_SESSION['user'];
																								} ?>" style="display: none">
					

					
	
	        		<?php
					//<div id='paypal-button'></div>

					if (isset($_SESSION['user'])) {

						echo "
							<button type='submit' class='btn btn-primary mr-sm-2' name='signup'><i class='fa fa-pencil'></i>Pagar</button>
				
	        				
								
	        				";
					} else {
						echo "
	        					<h4>You need to <a href='signup.php'>Login</a> to checkout.</h4>
	        				";
					}
					?>
					</form>
	        	</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  	<?php $pdo->close();

		?>
  	<?php include 'includes/footer.php'; ?>
</div>
	-->
	
<?php include 'includes/scripts.php'; ?>

				
<script>
	var total = 0;
	$(function() {
		$(document).on('click', '.cart_delete', function(e) {
			e.preventDefault();
			var id = $(this).data('id');

			$.ajax({
				type: 'POST',
				url: 'cart_delete.php',
				data: {
					id: id
				},
				dataType: 'json',
				success: function(response) {
					if (!response.error) {
						getDetails();
						getCart();
						getTotal();
						getButtons();

					}
				}
			});
		});




		$(document).on('click', '.minus', function(e) {
			e.preventDefault();
			var id = $(this).data('id');
			var qty = $('#qty_' + id).val();
			console.log(id);
			if (qty > 1) {
				qty--;
			}
			$('#qty_' + id).val(qty);
			$.ajax({
				type: 'POST',
				url: 'cart_update.php',
				data: {
					id: id,
					qty: qty,
				},
				dataType: 'json',
				success: function(response) {
					if (!response.error) {
						getDetails();
						getCart();
						getTotal();
						getButtons();

					}

				}
			});
		});

		$(document).on('click', '.add', function(e) {
			e.preventDefault();
			var id = $(this).data('id');
			var qty = $('#qty_' + id).val();

			qty++;
			$('#qty_' + id).val(qty);
			$.ajax({
				type: 'POST',
				url: 'cart_update.php',
				data: {
					id: id,
					qty: qty,
				},
				dataType: 'json',
				success: function(response) {
					if (!response.error) {
						getDetails();
						getCart();
						getTotal();
						getButtons();

					}
				}
			});
		});

		getDetails();
		getTotal();


	});

	function getDetails() {
		$.ajax({
			type: 'POST',
			url: 'cart_details.php',
			dataType: 'json',
			success: function(response) {
				$('#tbody').html(response['list']);
				$('.total').html(response['total']);
				getCart();
				getButtons();
			}
		});
	}

	function getTotal() {
		$.ajax({
			type: 'POST',
			url: 'cart_total.php',
			dataType: 'json',
			success: function(response) {
				total = response;

				$('#txt_vencido').val(total);
				$('.totallabel').html(total);
			}
		});
	}
</script>
<!-- Paypal Express -->
<script>
	
</script>
</body>

<style type="text/css">
	body {
		background: #ddd;
		min-height: 100vh;
		vertical-align: middle;
		display: flex;
		font-family: sans-serif;
		font-size: 0.8rem;
		font-weight: bold
	}

	

.badge-sm {
    min-width: 1.8em;
    padding: .25em !important;
    margin-left: .1em;
    margin-right: .1em;
    color: white !important;
    cursor: pointer;
}

	.title {
		margin-bottom: 5vh
	}

	.card {
		margin: auto;
		max-width: 950px;
		width: 90%;
		box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		border-radius: 1rem;
		border: transparent
	}

	@media(max-width:767px) {
		.card {
			margin: 3vh auto
		}
	}

	.cart {
		background-color:rgb(247, 247, 247);
		padding: 4vh 5vh;
		border-bottom-left-radius: 1rem;
		border-top-left-radius: 1rem
	}

	@media(max-width:767px) {
		.cart {
			padding: 4vh;
			border-bottom-left-radius: unset;
			border-top-right-radius: 1rem
		}
	}

	.summary {
		background-color: rgb(122, 237, 181 );
		border-top-right-radius: 1rem;
		border-bottom-right-radius: 1rem;
		padding: 4vh;
		color: rgb(65, 65, 65)
	}

	@media(max-width:767px) {
		.summary {
			border-top-right-radius: unset;
			border-bottom-left-radius: 1rem
		}
	}

	.summary .col-2 {
		padding: 0
	}

	.summary .col-10 {
		padding: 0
	}

	.row {
		margin: 0
	}

	.title b {
		font-size: 1.5rem
	}

	.main {
		margin: 0;
		padding: 2vh 0;
		width: 100%
	}

	.col-2,
	.col {
		padding: 0 1vh
	}

	a {
		padding: 0 1vh
	}

	.close {
		margin-left: auto;
		font-size: 0.7rem
	}

	img {
		width: 3.5rem
	}

	.back-to-shop {
		margin-top: 4.5rem
	}

	h5 {
		margin-top: 4vh
	}

	hr {
		margin-top: 1.25rem
	}

	form {
		padding: 2vh 0
	}

	select {
		border: 1px solid rgba(0, 0, 0, 0.137);
		padding: 1.5vh 1vh;
		margin-bottom: 4vh;
		outline: none;
		width: 100%;
		background-color: rgb(247, 247, 247)
	}

	



	input:focus::-webkit-input-placeholder {
		color: transparent
	}

	

	.btn:focus {
		box-shadow: none;
		outline: none;
		box-shadow: none;
		color: white;
		-webkit-box-shadow: none;
		-webkit-user-select: none;
		transition: none
	}

	.btn:hover {
		color: white
	}

	a {
		color: black
	}

	a:hover {
		color: black;
		text-decoration: none
	}

	#code {
		background-image: linear-gradient(to left, rgba(255, 255, 255, 0.253), rgba(255, 255, 255, 0.185)), url("https://img.icons8.com/small/16/000000/long-arrow-right.png");
		background-repeat: no-repeat;
		background-position-x: 95%;
		background-position-y: center
	}
</style>





</html>