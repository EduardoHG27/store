<?php
	include 'includes/session.php';

	$conn = $pdo->open();

	$output = array('error'=>false);
	$id = $_POST['id'];

	

	if(isset($_SESSION['user'])){
		try{
			$pieces = explode("-", $id);
			$stmt = $conn->prepare("DELETE FROM cart WHERE product_id=:product_id and user_id=:user_id");
			$stmt->execute(['product_id'=>$pieces[0],'user_id'=>$pieces[1]]);
			$output['message'] = 'Deleted';
			
		}
		catch(PDOException $e){
			$output['message'] = $e->getMessage();
		}
		

	}
	else{
	
		foreach($_SESSION['cart'] as $key => $row){
		
			if($row['productid'] == $id){
				unset($_SESSION['cart'][$key]);
				$output['message'] = 'Deleted';
				
			}
		}
	}

	$pdo->close();
	echo json_encode($output);

?>