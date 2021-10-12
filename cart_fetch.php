<?php
include 'includes/session.php';
$conn = $pdo->open();

$output = array('list' => '', 'count' => 0);

if (isset($_SESSION['user'])) {

	try {
		$stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM cart LEFT JOIN products ON products.id=cart.product_id LEFT JOIN category ON category.id=products.category_id WHERE user_id=:user_id");
		$stmt->execute(['user_id' => $user['id']]);
		//errror
		
		foreach ($stmt as $row) {
			//	<img src='" . $image . "' class='img-circle' alt='User Image'>
			$output['count']++;
			$image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
			$productname = (strlen($row['prodname']) > 30) ? substr_replace($row['prodname'], '...', 27) : $row['prodname'];
			$output['list'] .= "
				<div class='col-2'>
				<a type='button' id='id'  data-id=" . $row['product_id'] . "-" . $row['user_id'] . " class='cart_delete1'><i class='fa fa-times'></i> </a>
				</div>
				<div class='col-10'>
					<b>" . $row['catname'] . "</b>
					<a href='product.php?product=" . $row['slug'] . "'>	
					<p>" . $productname . " &times; " . $row['quantity'] . "</p></a>
					<img class='imagen1' src='" . $image . "' class='img-fluid' alt=''>
				</div>
				
				";
		}
		$_SESSION['cart_count']=$output['count'];
	} catch (PDOException $e) {
		$output['message'] = $e->getMessage();
	}
} else {
	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}

	if (empty($_SESSION['cart'])) {
		$output['count'] = 0;
	} else {
	
		foreach ($_SESSION['cart'] as $row) {
			$num = count($_SESSION['cart']);
			$output['count']++;
			$stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM products LEFT JOIN category ON category.id=products.category_id WHERE products.id=:id");
			$stmt->execute(['id' => $row['productid']]);
			$product = $stmt->fetch();

			$image = (!empty($product['photo'])) ? 'images/' . $product['photo'] : 'images/noimage.jpg';
			$output['list'] .= "
				<div class='col-2'>
					<button type='button' class='btn btn-link cart_delete1' id='id'  data-id=" . $row['productid'] . " ><i class='fa fa-times' aria-hidden='true'></i></button>
				</div>
				<div class='col-10'>
					<b>" . $product['catname'] . "</b>
					<a href='product.php?product=" . $product['slug'] . "'>	
					<p>" . $product['prodname'] . " &times; " . $row['quantity'] . "</p></a>
					<img class='imagen1' src='" . $image . "' class='img-fluid' alt=''>
				</div>
				";

	
		}
		$_SESSION['cart_count']=$output['count'];
	
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
