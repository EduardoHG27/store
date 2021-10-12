<?php include 'includes/session.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
?>

<?php
require_once 'keys.php';

$payment_id = $statusMsg = '';
$ordStatus = 'error';

var_dump($_POST['stripeToken']);
// Check whether stripe token is not empty 
if (!empty($_POST['stripeToken'])) {

    // Retrieve stripe token, card and user info from the submitted form data 
    $token  = $_POST['stripeToken'];
    $name = $_POST['txt_nombre'];
    $second = $_POST['txt_apellidos'];
    $email = $_POST['txt_cor'];
    $itemPrice = $_POST['txt_pag'];
    $direccion=$_POST['txt_dir'];
    $cp=$_POST['txt_cp'];


  
 
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
                $stripeiID = $chargeJson["id"];
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


                    if (isset($_SESSION['user'])) {
                        $id = $_POST['id'];
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
                                    $stmt = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
                                    $stmt->execute(['sales_id' => $salesid, 'product_id' => $row['product_id'], 'quantity' => $row['quantity']]);
                                }

                                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
                                $stmt->execute(['user_id' => $id]);

                                $_SESSION['success'] = 'Transaction successful. Thank you.';
                                $pdo->close();
                            } catch (PDOException $e) {
                                $_SESSION['error'] = $e->getMessage();
                            }
                        } catch (PDOException $e) {
                            $_SESSION['error'] = $e->getMessage();
                        }
                    }
                    else
                    {
                        $conn = $pdo->open();

                     

                        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
                        $password = "";
                        //Reconstruimos la contraseña segun la longitud que se quiera
                        for($i=0;$i<10;$i++) {
                           //obtenemos un caracter aleatorio escogido de la cadena de caracteres
                           $password .= substr($str,rand(0,62),1);
                        }

                        $password_code = password_hash($password, PASSWORD_DEFAULT);

                        $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $code = substr(str_shuffle($set), 0, 12);
                        
                        try {
                            $stmt = $conn->prepare("INSERT INTO users (email, address ,zipcode, password, firstname, lastname, activate_code, created_on) VALUES (:email,:address,:zipcode, :password, :firstname, :lastname, :code, :now)");
                            $stmt->execute(['email' => $email,'address' => $direccion,'zipcode' => $cp, 'password' => $password_code, 'firstname' => $name, 'lastname' => $second, 'code' => $code, 'now' => date("Y-m-d")]);
                            $userid = $conn->lastInsertId();



                            $message = "
                                    <h2>Gracias Por tu Compra.</h2>
                                    <p>Accede a tu cuenta con tu usuario y contraseña:</p>
                                    <p>Usuario: " . $email . "</p>
                                    <p>Password: " . $password. "</p>
                                    <p>Porfavor da click en el enlace para activar tu cuenta</p>
                                    <a href='http://localhost:8080/ecommerce_original_1/activate1.php?code=" . $code . "&user=" . $userid . "'>Activar Cuenta</a>
                                ";
            
                            //Load phpmailer
                            require 'vendor/autoload.php';
            
                            $Correo = new PHPMailer(true);
                            try {
            
                                $Correo->IsSMTP();
                                $Correo->SMTPAuth = true;
                                $Correo->SMTPSecure = "tls";
                                $Correo->Host = "smtp.gmail.com";
                                $Correo->Port = 587;
                                $Correo->Username = "eduardoenrique.hernandez@gmail.com";
                                $Correo->Password = "Eduardo_27";
                                $Correo->SetFrom('eduardoenrique.hernandez@gmail.com', 'De Mi');
                                $Correo->FromName = "From";
                                $Correo->AddAddress($email);
                                $Correo->Subject = "ECommerce Site Sign Up";
                                $Correo->Body  = $message;
                                $Correo->IsHTML(true);
            
            
                                $Correo->send();
            
                                unset($_SESSION['firstname']);
                                unset($_SESSION['lastname']);
                                unset($_SESSION['email']);
            
                                $_SESSION['success'] = 'Account created. Check your email to activate.';
                              //  header('location: signup.php');
                            } catch (Exception $e) {
                                $_SESSION['error'] = 'Message could not be sent. Mailer Error: ' . $Correo->ErrorInfo;
                               // header('location: signup.php');
                            }

                            $stmt = $conn->prepare("INSERT INTO sales (user_id,pay_id,stripe_id,sales_date) VALUES (:user_id, :pay_id, :stripe_id, :sales_date)");
                            $stmt->execute(['user_id' => $userid, 'pay_id' => $transactionID, 'stripe_id' => $stripeiID, 'sales_date' => $today]);
                            $salesid = $conn->lastInsertId();
                           
                            foreach ($_SESSION['cart'] as $row) {
                                $stmt = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
                                $stmt->execute(['sales_id' => $salesid, 'product_id' => $row['productid'], 'quantity' => $row['quantity']]);
                            }

                            unset($_SESSION['cart']);

                            var_dump($_SESSION['cart']);

                        } catch (Exception $e) {
                            $_SESSION['error'] = $e->getMessage();
                           // header('location: register.php');
                        }
                        $pdo->close();
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
<?php include 'includes/header1.php'; ?>
<div class="alert alert-success">
			                <h4><i class="icon fa fa-check"></i> Cobro Realizado!</h4>
			                Se te ha enviado un correo para activar tu cuenta!! <b></b>.
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
    <a href="index1.php" class="btn-link">Back to Payment Page</a>
			            </div>
			            <h4>You may <a href="signup.php">Login</a> or back to <a href="index.php">Homepage</a>.</h4>
				
<div class="container">
    <div class="status">
        
</div>