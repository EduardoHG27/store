<?php
	include 'includes/session.php';
	$conn = $pdo->open();

	$output = array('list'=>'');

	if(isset($_SESSION['user'])){
		
		$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id=:user");
		$stmt->execute(['user'=>$_SESSION['user']]);
		$crow = $stmt->fetch();
		
		
		

	
		if(empty($crow))
			{
				
			}
			else
			{			
					$output['list'] .= '
						<div class="col-6">
						<a type="submit" href="zFinalizar-compra_usuario.php" class="">Ir al Carrito</a>
						</div>
						
					';
		}
		
	}
	else{
			if(!$_SESSION['cart'])
			{
				$output['list'] .= 
					'';
				}
			else{
				$output['list'] .= 
					'<div class="col-6">
					<a type="submit" href="zFinalizar-compra1.php" class="button is-primary is-outlined is-small"><i class="fa fa-shopping-cart"></i>Carrito</a>
					</div>
					<div class="col-6">
						</div>';
			}

				

	}

	$pdo->close();
	echo json_encode($output);

	/*
<a href='product.php?product=".$row['slug']."'>
							<div class='pull-left'>
								<img src='".$image."' class='thumbnail' alt='User Image'>
							</div>
							<h4>
		                        <b>".$row['catname']."</b>
		                        <small>&times; ".$row['quantity']."</small>
		                    </h4>
		                    <p>".$productname."</p>
						</a>
	*/
