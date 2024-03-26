
<!DOCTYPE html>
<html>

<head>
    <title>Checkout Cart - My E-commerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
<header>
        <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
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

                    <form class="d-flex w-100 me-5 ms-5" action="" method="post">
                        <input class="form-control me-2 search-input" type="search" placeholder="Search for everything and anything" aria-label="Search" name="search">
                        <button class="btn btn-outline-primary" style="margin-right: 7px;" type="submit">Search</button>
                        <!-- Filter Button -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                            Filter
                        </button>
                    </form>

                    <div class="d-flex">
                        <div class="cart-icon">
                            <img class="cart-image" src="Images/cart.png" alt="Shopping Cart">
                            <span id="cart-count">0</span>
                        </div>
                        <a class="nav-link btn btn-outline-purple mx-2" href="#">Cart</a>
                        <a class="nav-link btn btn-outline-purple mx-2" href="#">Account</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
<div class="container mt-5">
    <h2>Shopping Cart</h2>
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
            <!-- This is where the list of products in the cart will be populated -->
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6">
            <!-- Action Buttons -->
            <button class="btn btn-outline-primary">Continue Shopping</button>
        </div>
        <div class="col-md-6 text-end">
            <h4>Cart Summary</h4>
            <p>Subtotal: $<!-- Subtotal Amount --></p>
            <p>Tax: $<!-- Tax Amount --></p>
            <p>Shipping: $<!-- Shipping Amount --></p>
            <h5>Total: $<!-- Total Amount --></h5>
            <button class="btn btn-primary">Proceed to Checkout</button>
        </div>
    </div>
</div>

</body>
</html>
