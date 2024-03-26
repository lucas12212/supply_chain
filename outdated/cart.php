<!DOCTYPE html>
<html>

<head>
  <title>My E-commerce Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar" style="height: 40px;">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="Images/logoo.png" alt="Logo" class="logo me-3">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Product</a>
            </li>
          </ul>
          <div class="d-flex">
            <a class="nav-link btn btn-outline-purple mx-2" href="#">Account</a>
          </div>
        </div>
      </div>
    </nav>
  </header>


  <section class="banner">
    <!-- Add your banner photo here -->
    <img src="Images/banner.png" alt="Banner Photo">
  </section>

  <section class="container" style="margin-top: 30px;">
    <div class="d-flex align-items-baseline">
      <h2>Shopping Cart</h2>
      <div class="d-inline-block">
        <a href="BuyerEnd.php" class="btn btn-outline-primary ms-3" style="margin-top: -10px;">Continue Shopping</a>
      </div>
      <form action="" method="POST" class="d-inline ms-2">
        <div class="d-inline-block">
          <button id="clearCartBtn" type="submit" name="clear_cart" class="btn btn-outline-danger" style="margin-top: -10px;">Clear Cart</button>
        </div>
      </form>
    </div>




    <div class="row justify-content-between">
      <!-- Fetch Products -->
      <div class="container mt-2 mr-5">
        <table class="table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>

        <div class="row">
          <div class="col-md-6">
          </div>
          <div class="col-md-6 text-end">
            <h4>Cart Summary</h4>


            <p>Subtotal: $<span id="cartSubtotal"><!-- Will be filled by JS --></span></p>
            <p>Tax: $<span id="cartTax"><!-- Tax Amount --></span></p>
            <p>Shipping: $<span id="cartShipping"><!-- Shipping Amount --></span></p>
            <h5>Total: $<span id="cartTotal"><!-- Will be filled by JS --></span></h5>
            <button class="btn btn-primary">Proceed to Checkout</button>
          </div>
        </div>
      </div>
      <div id="emptyCartMessage" class="no-products" style="display: none;">
        <h3>No Products Available</h3>
      </div>
    </div>
  </section>
  <script>
    function clearCartTable() {
      // Handle empty cart here
      $("tbody").empty();
      $("tbody").append("<tr><td colspan='5'>Your cart is empty.</td></tr>");
      $("#cartSubtotal, #cartTotal").text("0.00");
      // Show the empty cart message
      $("#emptyCartMessage").show();
      //console.log($("#emptyCartMessage"));
    }

    $(document).ready(function() {
      $.ajax({
        url: 'api/cart2.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          console.log("Server Response:", response); // <-- This line logs the server response to the console.
          // Initialize subtotal to zero
          let subtotal = 0;

          // Update the cart count
          $("#cart-count").text(response.totalCartCount);

          // Clear the existing cart items
          $("tbody").empty();

          // Loop through the cart items in the response and populate the cart items
          if (Array.isArray(response.cart_items) && response.cart_items.length) {
            response.cart_items.forEach(function(item) {
              // Calculate the total cost for this item
              let total = item.product_cost * item.quantity;

              // Add to the subtotal
              subtotal += total;

              // Append this item to the cart table
              $("tbody").append(`
          <tr>
              <td>${item.product_name}</td>
              <td>${item.quantity}</td>
              <td>${item.product_cost}</td>
              <td>${total}</td>
              <td>Remove</td>
          </tr>
        `);
            });

            // Update the cart subtotal and total
            $("#cartSubtotal").text(subtotal.toFixed(2));
            $("#cartTotal").text(subtotal.toFixed(2));
            $("#emptyCartMessage").hide();
          } else {
            clearCartTable()
          }

          // You can also calculate tax, shipping etc. here and update
        },
        error: function() {
          alert('An error occurred. Please try again.');
        }
      });

      $("#clearCartBtn").click(function() {
        event.preventDefault(); // prevent form submission
        $.ajax({
          url: 'api/cart2.php',
          type: 'DELETE',
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              // Update cart count on the navbar
              // $("#cart-count").text(0);
              // Clear cart items displayed on the page
              clearCartTable()
              $("#cartTax, #cartShipping").text("0.00");
            } else {
              alert('Failed to clear the cart. Please try again.');
            }
          },
          error: function() {
            alert('An error occurred. Please try again.');
          }
        });
      });
    });
  </script>


</body>

</html>