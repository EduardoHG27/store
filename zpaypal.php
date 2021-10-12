<?php include 'includes/session.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$status = $_POST['status'];
$nombre = $_POST['nom'];
$id = $_POST['id'];

$ap = $_POST['apellido'];
$dir = $_POST['dir'];
$cp = $_POST['cp'];
$email = $_POST['email'];
$pago = $_POST['pago'];

$today = date("Y-m-d");

var_dump($status,$nombre,$id);


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
                            $stmt->execute(['email' => $email,'address' => $dir,'zipcode' => $cp, 'password' => $password_code, 'firstname' => $nombre, 'lastname' => $ap, 'code' => $code, 'now' => date("Y-m-d")]);
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

                            $stmt = $conn->prepare("INSERT INTO sales (user_id,pay_id,sales_date) VALUES (:user_id, :pay_id, :sales_date)");
                            $stmt->execute(['user_id' => $userid,'pay_id' => $id, 'sales_date' => $today]);
                            $salesid = $conn->lastInsertId();
                           
                            foreach ($_SESSION['cart'] as $row) {
                                $stmt = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
                                $stmt->execute(['sales_id' => $salesid, 'product_id' => $row['productid'], 'quantity' => $row['quantity']]);
                               
                                $stmt = $conn->prepare("SELECT existences FROM products WHERE id=:id");
                                $stmt->execute(['id' => $row['productid']]);
                                $dato = $stmt->fetch();

                                $total=$dato['existences']-$row['quantity'];

                                $stmt = $conn->prepare("UPDATE products SET existences= ".$total." WHERE id=:id");
                                $stmt->execute(['id'=>$row['productid']]);

                            }

                            unset($_SESSION['cart']);

                            var_dump($_SESSION['cart']);

                        } catch (Exception $e) {
                            $_SESSION['error'] = $e->getMessage();
                           // header('location: register.php');
                        }
                        $pdo->close();


?>

