<?php
include 'includes/session.php';
$conn = $pdo->open();

$output = array('list' => '', 'total' => 0);
$ruta = $_POST['ruta'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
$stmt->execute(['email' => $ruta]);
$crow = $stmt->fetch();



$pdo->close();

if ($crow) {

	echo json_encode(array('estado' => 'success','datos'=>$crow));
} else {
	echo json_encode(array('estado' => 'error','datos'=>$crow));
}



	/* 
	
	
	<tr>
						<td><button type='button' data-id='".$row['productid']."' class='btn btn-danger btn-flat cart_delete'><i class='fa fa-remove'></i></button></td>
						<td><img src='".$image."' width='30px' height='30px'></td>
						<td>".$product['name']."</td>
						<td>&#36; ".number_format($product['price'], 2)."</td>
						<td class='input-group'>
							<span class='input-group-btn'>
            					<button type='button' id='minus' class='btn btn-default btn-flat minus' data-id='".$row['productid']."'><i class='fa fa-minus'></i></button>
            				</span>
            				<input type='text' class='form-control' value='".$row['quantity']."' id='qty_".$row['productid']."'>
				            <span class='input-group-btn'>
				                <button type='button' id='add' class='btn btn-default btn-flat add' data-id='".$row['productid']."'><i class='fa fa-plus'></i>
				                </button>
				            </span>
						</td>
						<td>&#36; ".number_format($subtotal, 2)."</td>
					</tr>
					
					*/
