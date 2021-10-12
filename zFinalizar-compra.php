<?php include 'includes/session.php'; ?>

<?php include 'includes/header.php'; ?>


<body class="hold-transition skin-blue layout-top-nav">
  <?php
  if (isset($_SESSION['user'])) {

    echo "sip";
  } else {
    if (isset($_SESSION['cart'])) {

  ?>

      <div class="box">
      <form action="zpagar1.php" method="POST">
        <div>

          <div class="columns">

            <div class="column is-two-fifths">

              <article class="panel is-info">

                <p class="panel-heading ">
                  DETALLES DE FACTURACIÓN
                </p>
                <div class="box">
                
                  <div class="field">
                    <div class="columns">
                      <div class="column is-half">
                        <label class="label">Nombre*</label>
                        <div class="control">
                          <input class="input is-small" type="text" name="txt_nombre" id="txt_nombre" placeholder="Text input" required>
                        </div>
                      </div>
                      <div class="column is-half">
                        <label class="label">Apellidos*</label>
                        <div class="control">
                          <input class="input is-small" type="text" name="txt_apellidos" id="txt_apellidos" placeholder="Text input" required>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="field">
                    <label class="label">Nombre Empresa (Opcional)</label>
                    <div class="control has-icons-left has-icons-right">
                      <input class="input is-success is-small" type="text" placeholder="Text input" value="">
                      <span class="icon is-small is-left">
                        <i class="fa fa-user"></i>
                      </span>
                      <span class="icon is-small is-right">
                        <i class="fa fa-check"></i>
                      </span>
                    </div>
                    <p class="help is-success">This username is available</p>
                  </div>

                  <div class="field">
                    <label class="label">Pais</label>
                    <div class="control has-icons-left has-icons-right">
                      <input class="input is-success is-small" type="text" placeholder="Text input" value="Mexico" readonly>
                    </div>
                  </div>
                  <div class="field">
                  <label class="label">Direccion de la calle*</label>
                  <div class="control has-icons-left has-icons-right">
                    <input class="input is-success is-small" type="text" name="txt_dir1" id="txt_dir1" placeholder="Numero de la casa y nombre de la calle" value="" required>
                    <span class="icon is-small is-left">
                      <i class="fa fa-book"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i class="fa fa-check"></i>
                    </span>
                  </div>
                  <div class="control has-icons-left has-icons-right">
                    <input class="input is-success is-small" type="text"name="txt_dir2" id="txt_dir2" placeholder="Colonia, apartamento,habitacion,etc(opcional)" value="" required>
                    <span class="icon is-small is-left">
                      <i class="fa fa-book"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i class="fa fa-check"></i>
                    </span>
                  </div>
                  <p class="help is-success">This username is available</p>
                </div>


                <div class="field">
                  <label class="label">Localidad*</label>
                  <div class="control has-icons-left has-icons-right">
                    <input class="input is-success is-small" type="text" name="txt_loc" id="txt_loc" placeholder="" value="" required>
                    <span class="icon is-small is-left">
                      <i class="fa fa-envelope"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i class="fa fa-exclamation-triangle"></i>
                    </span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Region/Estado</label>
                  <div class="control">
                    <div class="select">
                      <select>
                        <option>Select dropdown</option>
                        <option>With options</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Código Postal</label>
                  <div class="control has-icons-left has-icons-right">
                    <input class="input is-success is-small" type="text" name="txt_cp" id="txt_cp" placeholder="" value="" required>
                    <span class="icon is-small is-left">
                      <i class="fa fa-paper-plane-o"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i class="fa fa-exclamation-triangle"></i>
                    </span>
                  </div>
                </div>

                <div class="field">
                  <label class="label">Teléfono</label>
                  <div class="control has-icons-left has-icons-right">
                    <input class="input is-success" type="text" placeholder="" name="txt_tel" id="txt_tel" value="" required>
                    <span class="icon is-small is-left">
                      <i class="fa fa-phone"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i class="fa fa-exclamation-triangle"></i>
                    </span>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Correo Electronico</label>
                  <div class="control has-icons-left has-icons-right">
                    <input class="input is-success is-small" type="text" name="txt_mail" id="txt_mail" placeholder="" value="" required>
                    <span class="icon is-small is-left">
                      <i class="fa fa-envelope"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i class="fa fa-exclamation-triangle"></i>
                    </span>
                  </div>
                </div>

                <div class="field">
                  <label class="label">Subject</label>
                </div>

                <div class="field">
                  <label class="label">Detalles</label>
                  <div class="control">
                    <textarea class="textarea" placeholder="Textarea"></textarea>
                  </div>
                </div>

                <div class="field">
                  <div class="control">
                    <label class="checkbox">
                      <input type="checkbox">
                      I agree to the <a href="#">terms and conditions</a>
                    </label>
                  </div>
                </div>
                <div class="field is-grouped">
                  <div class="control">
                    <button type="submit" class="button is-link">Submit</button>

                  </div>
                  <div class="control">
                    <button class="button is-link is-light">Cancel</button>
                  </div>
                </div>

    
                </div>

              </article>

            </div>

            <div class="column is-three-fifths">
              <article class="panel is-info">
                <p class="panel-heading ">
                  Carrito
                </p>

                <div class="box">
                  <div id="tbody">


                  </div>
                  <div class="col text-right">&dollar; <span class="total"></span>
                
                </div>
                </div>
              </article>
            </div>

          </div>

          <div class="column is-two-fifths"></div>
        </div>
        </form>
      </div>


  <?php
    } else if (empty($_SESSION['cart'])) {

      $output['count'] = 0;

      echo "adios";
    } else {
      echo "hostras";
    }
  }

  ?>
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/scripts.php'; ?>
</body>

</html>

<style type="text/css">
  .image_full {
    display: block;
  }

  .image_mobile {
    display: none;
  }

  @media (max-width: 740px) and (min-width: 320px) {
    .image_full {
      display: none;
    }

    .image_mobile {
      display: block;
    }
  }
</style>
<script>
  var total = 0;
  $(function() {
    $(document).on('click', '.cart_delete', function(e) {
      e.preventDefault();
      var id = $(this).data('id');

      $.ajax({
        type: 'POST',
        url: 'cart_delete.php',
        data: {
          id: id
        },
        dataType: 'json',
        success: function(response) {
          if (!response.error) {
            getDetails();
            getCart();
            getTotal();
            getButtons();


          }
        }
      });
    });




    $(document).on('click', '.minus', function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      var qty = $('#qty_' + id).val();
      console.log(id);
      if (qty > 1) {
        qty--;
      }
      $('#qty_' + id).val(qty);
      $.ajax({
        type: 'POST',
        url: 'cart_update.php',
        data: {
          id: id,
          qty: qty,
        },
        dataType: 'json',
        success: function(response) {
          if (!response.error) {
            getDetails();
            getCart();
            getTotal();
            getButtons();
          }

        }
      });
    });

    $(document).on('click', '.add', function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      var qty = $('#qty_' + id).val();

      qty++;
      $('#qty_' + id).val(qty);
      $.ajax({
        type: 'POST',
        url: 'cart_update.php',
        data: {
          id: id,
          qty: qty,
        },
        dataType: 'json',
        success: function(response) {
          if (!response.error) {
            getDetails();
            getCart();
            getTotal();
            getButtons();
          }
        }
      });
    });

    getDetails();
    getTotal();


  });

  function getDetails() {
    $.ajax({
      type: 'POST',
      url: 'cart_details1.php',
      dataType: 'json',
      success: function(response) {
        $('#tbody').html(response['list']);
        $('.total').html(response['total']);
        getCart();
        getButtons();
      }
    });
  }

  function getTotal() {
    $.ajax({
      type: 'POST',
      url: 'cart_total.php',
      dataType: 'json',
      success: function(response) {
        total = response;

        $('#txt_vencido').val(total);
        $('.totallabel').html(total);
      }
    });
  }
</script>

</script>