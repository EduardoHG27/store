<?php include 'includes/session.php'; ?>
<?php
	if(!isset($_SESSION['user'])){
		header('location: index1.php');
	}
?>
<?php include 'includes/header1.php'; ?>
<br>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
	  <div class="box">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='callout callout-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}

	        			if(isset($_SESSION['success'])){
	        				echo "
	        					<div class='callout callout-success'>
	        						".$_SESSION['success']."
	        					</div>
	        				";
	        				unset($_SESSION['success']);
	        			}
	        		?>
	        		<div class="card">
						<br>
	        			<div class="container">
							<div class="row">
	        				<div class="col-sm-3">
	        					<img src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" width="100%">
	        				</div>
	        				<div class="col-sm-9">
	        					<div class="row">
	        						<div class="col-sm-6">
	        							<h4>Nombre:</h4><h4><?php echo $user['firstname'].' '.$user['lastname']; ?></h4>	

	        						</div>
									<div class="col-sm-6">
	        							<h4>Email:</h4>
										<h4><?php echo $user['email']; ?></h4>
	        						</div>
	        					</div>

								<div class="row">
	        						<div class="col-sm-6">
	        							<h4>Direccion:</h4>
										<h4><?php echo (!empty($user['address'])) ? $user['address'] : 'N/a'; ?></h4>

	        						</div>
									<div class="col-sm-6">
	        							<h4>Codigo Postal:</h4>
										<h4><?php echo (!empty($user['zipcode'])) ? $user['zipcode'] : 'N/a'; ?></h4>
	        						</div>
	        					</div>

	        				</div>
							</div>
							<span class="pull-right">
											<button class='btn btn-success btn-flat btn-sm'data-toggle="modal" id="btn_cerrar" data-id=''><i class='fa fa-edit'></i> Editar</button>
	        									
	        								</span>
	        			</div>
	        		</div>
					<br>
	        		<div class="card">
						<br>
						<div class="container">
	        			<div class="form-group">
	        				<h4 class="box-title"><i class="fa fa-calendar"></i> <b>Transaction History</b></h4>
	        			</div>
	        			<div class="card-body" style="overflow-y: scroll;">
	        				<table class="table table-striped" id="example1">
	        					<thead>
	        						<th class="hidden"></th>
	        						<th>Fecha</th>
	        						<th>Transaction#</th>
	        						<th>Amount</th>
	        						<th>Full Details</th>
	        					</thead>
	        					<tbody>
	        					<?php
	        						$conn = $pdo->open();

	        						try{
	        							$stmt = $conn->prepare("SELECT * FROM sales WHERE user_id=:user_id ORDER BY sales_date DESC");
	        							$stmt->execute(['user_id'=>$user['id']]);
	        							foreach($stmt as $row){
	        								$stmt2 = $conn->prepare("SELECT * FROM details LEFT JOIN products ON products.id=details.product_id WHERE sales_id=:id");
	        								$stmt2->execute(['id'=>$row['id']]);
	        								$total = 0;
	        								foreach($stmt2 as $row2){
	        									$subtotal = $row2['price']*$row2['quantity'];
	        									$total += $subtotal;
	        								}
	        								echo "
	        									<tr>
	        										<td class='hidden'></td>
	        										<td>".date('M d, Y', strtotime($row['sales_date']))."</td>
	        										<td>".$row['pay_id']."</td>
	        										<td>&#36; ".number_format($total, 2)."</td>
	        										<td><button class='btn btn-sm btn-flat btn-info transact' data-id='".$row['id']."'><i class='fa fa-search'></i> View</button></td>
	        									</tr>
	        								";
	        							}

	        						}
        							catch(PDOException $e){
										echo "There is some problem in connection: " . $e->getMessage();
									}

	        						$pdo->close();
	        					?>
	        					</tbody>
	        				</table>
	        			</div>
	        		</div>
					</div>
	        	</div>
	        	<div class="col-sm-3">
						<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
	  
  
  	<?php include 'includes/footer.php'; ?>
  	<?php include 'includes/profile_modal.php'; ?>


	  
</div>



      <!-- Modal -->
      
    </div>
  
<?php include 'includes/scripts.php'; ?>
<script src="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"></script>
 <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
$(function(){
	$(document).on('click', '.transact', function(e){
		e.preventDefault();
		$('#transaction').modal('show');
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: 'transaction.php',
			data: {id:id},
			dataType: 'json',
			success:function(response){
				$('#date').html(response.date);
				$('#transid').html(response.transaction);
				$('#detail').prepend(response.list);
				$('#total').html(response.total);
			}
		});
	});

	$("#transaction").on("hidden.bs.modal", function () {
	    $('.prepend_items').remove();
	});
});

$(document).ready(function() {
    $('#example1').DataTable();
} );






</script>
</body>
</html>