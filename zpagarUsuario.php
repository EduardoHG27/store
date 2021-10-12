<?php include 'includes/session.php'; ?>
<script src="https://www.paypal.com/sdk/js?client-id=AWXnPhBGC8avGxHyhwuALzC6njuOKgb4xk7aveqvALMgAmRvPgLuEjlfOBEyQ-TpkLipFdHTLvnL7pWE&currency=MXN"></script>
<?php include 'includes/header.php';
include("keys.php");

if (isset($_SESSION['user'])) {

    $stmt = $conn->prepare("SELECT * FROM users WHERE id=:user");
    $stmt->execute(['user' => $_SESSION['user']]);
    $crow = $stmt->fetch();
    $tot = $_POST['txt_total'];
} else {

    $nombre = $_POST['txt_nombre'];
    $apellidos = $_POST['txt_apellidos'];
    $dir1 = $_POST['txt_dir1'];
    $tot = $_POST['txt_total'];
    $cor = $_POST['txt_mail'];
    $cart = $_SESSION['cart'];
    $cp = $_POST['txt_cp'];
}

?>



<?php
// Include configuration file  


?>

<div class="panel">
    <div class="box">
        <div class="columns">

            <div class="column is-one-quarter">

            </div>
            <div class="column auto">
                <article class="panel is-info">

                    <p class="panel-heading ">
                        Detalle del pago
                    </p>
                    <form action="payment1.php" method="POST" id="paymentFrm">
                        <div id="paymentResponse"></div>
                        <script src="https://js.stripe.com/v3/"></script>
                        <div class="box">
                            <div class="field">
                                <div class="columns">
                                    <div class="column is-half">
                                        <label class="label">Nombre*</label>
                                        <div class="control">
                                            <input class="input is-small" type="text" name="txt_nombre" id="txt_nombre" value="<?php
                                                                                                                                if (isset($_SESSION['user'])) {
                                                                                                                                    echo $crow['firstname'];
                                                                                                                                } else {
                                                                                                                                    echo $nombre;
                                                                                                                                }

                                                                                                                                ?>" placeholder="Text input" required readonly>
                                            <input type="input" class="form-control" name="id" id="id" value="<?php
                                                                                                                if (isset($_SESSION['user'])) {
                                                                                                                    echo $_SESSION['user'];
                                                                                                                } else {
                                                                                                                }
                                                                                                                ?>" style="display: none">

                                        </div>
                                    </div>
                                    <div class="column is-half">
                                        <label class="label">Apellidos*</label>
                                        <div class="control">
                                            <input class="input is-small" type="text" name="txt_apellidos" id="txt_apellidos" placeholder="Text input" value="<?php
                                                                                                                                                                if (isset($_SESSION['user'])) {
                                                                                                                                                                    echo $crow['lastname'];
                                                                                                                                                                } else {
                                                                                                                                                                    echo $apellidos;
                                                                                                                                                                } ?>" required readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Domicilio</label>
                                <div class="control has-icons-left has-icons-right">
                                    <input class="input is-success is-small" type="text" name="txt_dir" id="txt_dir" placeholder="" value="<?php if (isset($_SESSION['user'])) {
                                                                                                                                                echo $crow['address'];
                                                                                                                                            } else {
                                                                                                                                                echo $dir1;
                                                                                                                                            } ?>" required readonly>
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Codigo Postal</label>
                                <div class="control has-icons-left has-icons-right">
                                    <input class="input is-success is-small" type="text" name="txt_cp" id="txt_cp" placeholder="" value="<?php if (isset($_SESSION['user'])) {
                                                                                                                                                echo $crow['zipcode'];
                                                                                                                                            } else {
                                                                                                                                                echo $cp;
                                                                                                                                            } ?>" required readonly>
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Correo</label>
                                <div class="control has-icons-left has-icons-right">
                                    <input class="input is-success is-small" type="text" name="txt_cor" id="txt_cor" placeholder="" value="<?php if (isset($_SESSION['user'])) {
                                                                                                                                                echo $crow['email'];
                                                                                                                                            } else {
                                                                                                                                                echo $cor;
                                                                                                                                            } ?>" required readonly>
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Total de pago</label>
                                <div class="control has-icons-left has-icons-right">
                                    <input class="input is-success is-small" type="text" name="txt_pag" id="txt_pag" placeholder="" value="<?php echo $tot; ?>" required readonly>
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column is-half">
                                    <div class="form-group">
                                        <label>Número de tarjeta</label>
                                        <div id="card_number" class="field"></div>
                                    </div>
                                    <div class="row">
                                        <div class="left">
                                            <div class="form-group">
                                                <label>Fecha vencimiento</label>
                                                <div id="card_expiry" class="field"></div>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <div class="form-group">
                                                <label>CVC</label>
                                                <div id="card_cvc" class="field"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="button is-link" id="payBtn">Submit Payment</button>
                                </div>
                                <div class="column is-half">
                                    <div class="box">
                                    <div id="paypal-button-container"></div>  
                                    </div>
                                </div>
                            </div>


                    </form>

                </article>
            </div>
            <div class="column is-one-quarter">

            </div>
        </div>
        <!-- Display errors returned by createToken -->

        <!-- Payment form -->

    </div>
</div>






    <!-- Set up a container element for the button -->
   

    <!-- Include the PayPal JavaScript SDK -->
    


<script>
    var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

    // Create an instance of elements
    var elements = stripe.elements();

    var style = {
        base: {
            fontWeight: 400,
            fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
            fontSize: '16px',
            lineHeight: '1.4',
            color: '#555',
            backgroundColor: '#fff',
            '::placeholder': {
                color: '#888',
            },
        },
        invalid: {
            color: '#eb1c26',
        }
    };

    var cardElement = elements.create('cardNumber', {
        style: style
    });
    cardElement.mount('#card_number');

    var exp = elements.create('cardExpiry', {
        'style': style
    });
    exp.mount('#card_expiry');

    var cvc = elements.create('cardCvc', {
        'style': style
    });
    cvc.mount('#card_cvc');

    // Validate input of the card elements
    var resultContainer = document.getElementById('paymentResponse');
    cardElement.addEventListener('change', function(event) {
        if (event.error) {
            resultContainer.innerHTML = '<p>' + event.error.message + '</p>';
        } else {
            resultContainer.innerHTML = '';
        }
    });

    // Get payment form element
    var form = document.getElementById('paymentFrm');

    // Create a token when the form is submitted.
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        createToken();
    });

    // Create single-use token to charge the user
    function createToken() {
        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error
                resultContainer.innerHTML = '<p>' + result.error.message + '</p>';
            } else {
                // Send the token to your server
                stripeTokenHandler(result.token);
            }
        });
    }

    // Callback to handle the response from stripe
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }


    paypal.Buttons({

// Set up the transaction
createOrder: function(data, actions) {
    return actions.order.create({
        purchase_units: [{
            amount: {
                value: '<?php echo $tot; ?>'
            }
        }]
    });
},

// Finalize the transaction 
onApprove: function(data, actions) {
    nombre = $('#txt_nombre').val();
    apellido = $('#txt_apellidos').val();
    dir1 = $('#txt_dir').val();
    cp = $('#txt_cp').val();
    email = $('#txt_cor').val();
    pago = $('#txt_pag').val();
    

    return actions.order.capture().then(function(orderData) {
        // Successful capture! For demo purposes:
        console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
        var transaction = orderData.purchase_units[0].payments.captures[0];
           
        
           $.ajax({
            type: 'POST',
            url: 'zpaypal.php',
            dataType: 'json',
            data: {
                status:transaction.status,
                id:transaction.id,
                nom:nombre,
                apellido:apellido,
                dir:dir1,
                cp:cp,
                email:email,
                pago:pago

            },
            success: function(response) {
               
            }
        });
        actions.redirect('zsuccess.php');
        // Replace the above to show a success message within this page, e.g.
        // const element = document.getElementById('paypal-button-container');
        // element.innerHTML = '';
        // element.innerHTML = '<h3>Thank you for your payment!</h3>';
        // Or go to another URL:  actions.redirect('thank_you.html');
    });
}


}).render('#paypal-button-container');

</script>