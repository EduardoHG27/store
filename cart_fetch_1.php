<?php
include 'includes/session.php';
$conn = $pdo->open();

$output = array('list' => '', 'count' => 0);

if (isset($_SESSION['user'])) {
	
	try {
		$stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM cart LEFT JOIN products ON products.id=cart.product_id LEFT JOIN category ON category.id=products.category_id WHERE user_id=:user_id");
		$stmt->execute(['user_id' => $user['id']]);
		//errror <img src='" . $image . "' class='img-circle' alt='User Image'>  <img src="images/large-apple-10-5-ipad-pro-64-gb-space-grey-2017.jpg" class="img-fluid" alt="">
		
		foreach ($stmt as $row) {

			$stmt1 = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM cart LEFT JOIN products ON products.id=cart.product_id LEFT JOIN category ON category.id=products.category_id WHERE user_id=:user_id");
			$stmt1->execute(['user_id' => $user['id']]);
			$product = $stmt1->fetch();

			$output['count']++;
			$image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
			$productname = (strlen($row['prodname']) > 30) ? substr_replace($row['prodname'], '...', 27) : $row['prodname'];
			$output['list'] .= "
			<a class='navbar-item'>
			<a type='button' id='id'  data-id=". $product['product_id']. "-" .$product['user_id']." class='btn-get-started cart_delete1'><i class='fa fa-times'></i> </a>
			<div class='col-2'>
			</div>
			<div class='col-10'>
			<img src='" . $image . "' class='img-thumbnail' alt=''> 
					<b>" . $row['catname'] . "</b>
					<a href='product.php?product=" . $row['slug'] . "'><p>" . $productname . "</p>
					<small>&times; " . $row['quantity'] . "</small>
					</a>	
			</div>
			</a>";
		}
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
		$output['list'] .= "	<div class='columns'>";
		foreach ($_SESSION['cart'] as $row) {
			$num = count($_SESSION['cart']);
			$output['count']++;
			$stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM products LEFT JOIN category ON category.id=products.category_id WHERE products.id=:id");
			$stmt->execute(['id' => $row['productid']]);
			$product = $stmt->fetch();

			$image = (!empty($product['photo'])) ? 'images/' . $product['photo'] : 'images/noimage.jpg';
			$output['list'] .= "
				<div class='column'>
					<div class='columns'>
							<div class='column'>
								<a href='product.php?product=" . $product['slug'] . "'>
									<button type='button' class='button is-ghost is-small cart_delete1' id='id'  data-id=" . $row['productid'] . " ><i class='fa fa-times' aria-hidden='true'></i></button>
									<img src='" . $image . "' class='img-circle' alt='User Image'>
		                        	<b>" . $product['catname'] . "</b>
		                        	<small>&times; " . $row['quantity'] . "</small>
								</a>
								<p>" . $product['prodname'] . "</p>
							</div>
					</div>	
				</div>
				";

			$output['list'] .= "</div>";
		}
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
