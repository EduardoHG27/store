<?php
include 'includes/session.php';
require_once 'keys.php';

$payment_id = $statusMsg = '';
$ordStatus = 'error';

// Check whether stripe token is not empty 
if (!empty($_POST['stripeToken'])) {

    // Retrieve stripe token, card and user info from the submitted form data 
    $token  = $_POST['stripeToken'];
    $name = $_POST['txt_nombre'];
    $email = $_POST['txt_cor'];
    $itemPrice = $_POST['txt_pag'];

    var_dump($_SESSION['cart']);
    $id = 78;

    $int = round((float)$itemPrice, 2);

    // Include Stripe PHP library 
    require_once 'vendor\stripe\stripe-php\init.php';

    // Set API key 
    \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

    // Add customer to stripe 
    try {
        $customer = \Stripe\Customer::create(array(
            'email' => $email,
            'source'  => $token
        ));
    } catch (Exception $e) {
        $api_error = $e->getMessage();
        var_dump($api_error);
    }

    if (empty($api_error) && $customer) {

        // Convert price to cents 
        $itemPriceCents = ($int * 100);

        //date
        $today = date("Y-m-d");

        // Charge a credit or a debit card 
        try {
            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $itemPriceCents,
                'currency' => $currency,
                'description' => $itemName
            ));
        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }

        if (empty($api_error) && $charge) {

            // Retrieve charge details 
            $chargeJson = $charge->jsonSerialize();


            // Check whether the charge is successful 
            if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {
                // Transaction details  
                $stripeiID= $chargeJson["id"];
                $transactionID = $chargeJson['balance_transaction'];
                $paidAmount = $chargeJson['amount'];
                $paidAmount = ($paidAmount / 100);
                $paidCurrency = $chargeJson['currency'];
                $payment_status = $chargeJson['status'];



                // Include database connection file  
                include_once 'includes/dbConnect.php';

                $sql = "INSERT INTO orders(name,email,item_name,item_number,item_price,item_price_currency,paid_amount,paid_amount_currency,txn_id,payment_status,created,modified) VALUES('" . $name . "','" . $email . "','" . $itemName . "','5','" . $itemPrice . "','" . $currency . "','" . $paidAmount . "','" . $paidCurrency . "','" . $transactionID . "','" . $payment_status . "',NOW(),NOW())";
                $insert = $db->query($sql);

                $payment_id = $db->insert_id;

                // If the order is successful 
                if ($payment_status == 'succeeded') {
                    $ordStatus = 'success';
                    $statusMsg = 'Your Payment has been Successful!';

                    try {
                        include_once 'includes/conn.php';
                        $conn = $pdo->open();

                        $stmt = $conn->prepare("INSERT INTO sales (user_id,pay_id,stripe_id,sales_date) VALUES (:user_id, :pay_id, :stripe_id, :sales_date)");
                        $stmt->execute(['user_id' => $id, 'pay_id' => $transactionID, 'stripe_id' => $stripeiID, 'sales_date' => $today]);
                        $salesid = $conn->lastInsertId();

                        try {
                            $stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user_id");
                            $stmt->execute(['user_id' => $id]);

                            foreach ($stmt as $row) {
                                $row['product_id'];
                                $row['quantity'];

                                $exi = $conn->prepare("SELECT existences FROM products WHERE id = :catid");
								$exi->execute(['catid' =>$row['product_id']]);

                                $dato = $exi->fetch();

                                $existencia=$dato['existences']-$row['quantity'];

                                var_dump($existencia);
                                $exis = $conn->prepare("UPDATE products SET existences=:existences WHERE id=:id");
                                $exis->execute(['existences'=>$existencia, 'id'=>$row['product_id']]);


                                $stmt = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
                                $stmt->execute(['sales_id' => $salesid, 'product_id' => $row['product_id'], 'quantity' => $row['quantity']]);
                            }

                            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
                            $stmt->execute(['user_id' => $id]);

                            $_SESSION['success'] = 'Transaction successful. Thank you.';
                        } catch (PDOException $e) {
                            $_SESSION['error'] = $e->getMessage();
                        }
                    } catch (PDOException $e) {
                        $_SESSION['error'] = $e->getMessage();
                    }
                } else {
                    $statusMsg = "Your Payment has Failed!";
                }
            } else {
                $statusMsg = "Transaction has been failed!";
            }
        } else {
            $statusMsg = "Charge creation failed! $api_error";
        }
    } else {
        $statusMsg = "Invalid card details! $api_error";
    }
} else {
    $statusMsg = "Error on form submission.";
}
?>

<div class="container">
    <div class="status">
        <?php if (!empty($payment_id)) { ?>
            <h1 class="<?php echo $ordStatus; ?>"><?php echo $statusMsg; ?></h1>

            <h4>Payment Information</h4>
            <p><b>Reference Number:</b> <?php echo $payment_id; ?></p>
            <p><b>Transaction ID:</b> <?php echo $transactionID; ?></p>
            <p><b>Paid Amount:</b> <?php echo $paidAmount . ' ' . $paidCurrency; ?></p>
            <p><b>Payment Status:</b> <?php echo $payment_status; ?></p>

            <h4>Product Information</h4>
            <p><b>Name:</b> <?php echo $itemName; ?></p>
            <p><b>Price:</b> <?php echo $itemPrice . ' ' . $currency; ?></p>


        <?php
        } else { ?>
            <h1 class="error">Your Payment has Failed</h1>
        <?php } ?>
    </div>
    <a href="index.php" class="btn-link">Back to Payment Page</a>
</div>