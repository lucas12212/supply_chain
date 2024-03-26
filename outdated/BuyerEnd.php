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

                    <form class="d-flex w-100 me-5 ms-5" action="" method="get">
                        <input class="form-control me-2 search-input" type="search" placeholder="Search for everything and anything" aria-label="Search" name="search">
                        <button class="btn btn-outline-primary" style="margin-right: 7px;" type="submit">Search</button>
                        <!-- Filter Button -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                            Filter
                        </button>
                    </form>

                    <div class="d-flex">
                        <div class="cart-icon">
                            <a href="cart.php">
                                <img class="cart-image" src="Images/cart.png" alt="Shopping Cart">
                                <span id="cart-count"></span>
                            </a>
                        </div>
                        <a class="nav-link btn btn-outline-purple mx-2" href="cart.php">Cart</a>
                        <a class="nav-link btn btn-outline-purple mx-2" href="#">Account</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="get" id="filter-form">
                        <!-- Brand Filter -->
                        <div class="form-group">
                            <label for="brand">Brand:</label>
                            <select name="brand" id="brand" class="form-control" style="margin-bottom: 20px">
                                <option value="">- Please choose an option -</option>
                            </select>
                        </div>


                        <!-- Price Filter -->
                        <div class="form-group">
                            <label for="min_price">Min Price:</label>
                            <input type="number" id="min_price" name="min_price" class="form-control" style="margin-bottom: 20px">
                        </div>
                        <div class="form-group">
                            <label for="max_price">Max Price:</label>
                            <input type="number" id="max_price" name="max_price" class="form-control" style="margin-bottom: 20px">
                        </div>

                        <!-- Average Ratings Filter -->
                        <div class="form-group">
                            <label for="rating">Average Rating:</label>
                            <select name="rating" id="rating" class="form-control" style="margin-bottom: 20px">
                                <option value="" style="color: grey">- Please choose an option -</option>
                                <!-- You can populate this with options manually -->
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                        <button type="button" id="applyFiltersBtn" class="btn btn-primary" style="margin-bottom: 20px" onClick="filterProduct()">Apply Filters</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="addToCartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <small>Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Item has been added to cart!
            </div>
        </div>
    </div>

    <section class="banner">
        <!-- Add your banner photo here -->
        <img src="Images/banner.png" alt="Banner Photo">
    </section>

    <section class="container" style="margin-top: 50px;">
        <h2>Featured Products</h2>
        <!-- Add your featured products here -->
        <div id="productContainer" class="row justify-content-between">
            <!-- Fetch Products -->

        </div>
        <!-- Product Modal Start -->
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Price: $<span id="productPrice">x</span></p>
                        <p>Stock: <span id="productStock"></span></p>
                        <p>Reviews: <span id="productReview"></span></p>
                        <p>Average Rating: <span id="productRating"></span>/5</p>
                    </div>

                    <div class="modal-footer no-flex">
                        <form class="add-to-cart-form" action="" method="post">
                            <div class="input-group">
                                <input type="hidden" name="product_id" id="productIDInput" class="form-control" value="0">
                                <button class="btn btn-outline-primary" type="button" onclick="decrementQuantity()">-</button>
                                <input type="number" name="quantity" class="form-control" id="quantityInput" value="1" min="1">
                                <button class="btn btn-outline-primary" type="button" onclick="incrementQuantity()">+</button>
                                <button type="submit" class="btn btn-primary add-to-cart-button">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                    </divo </div>
                </div>
                <!-- Product Modal End -->

            </div>
    </section>

    <footer>
        <p>&copy; 2023 My E-commerce Store. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script>
        // Initialize cart count at the top of the script
        let cartCount = 0;

        window.onload = function() {
            const myModal = document.getElementById('myModal')
            const myInput = document.getElementById('myInput')

            // myModal.addEventListener('shown.bs.modal', () => {
            //     myInput.focus()
            // })
        }

        function incrementQuantity() {
            let quantityInput = document.getElementById("quantityInput");
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }

        function decrementQuantity() {
            var value = parseInt(document.getElementById('quantityInput').value, 10);
            value = isNaN(value) ? 0 : value;
            value < 1 ? value = 1 : '';
            value--;
            document.getElementById('quantityInput').value = value;
        }

        function openProductModal(productId) {
            const product = productArr.find(p => {
                return p.product_id == productId
            })

            // Update the hidden input field with the product_id
            $('#productIDInput').val(productId);

            $("#productModal #exampleModalLabel").text(product.product_name)
            $("#productModal #productPrice").text(product.product_cost)
            $("#productModal #productStock").text(product.stocks_left)
            $("#productModal #productReview").text(product.no_reviews)
            $("#productModal #productRating").text(product.avg_rating)
            jQuery('#productModal').modal("show");

            // Open the modal
            jQuery('#productModal').modal("show");
        }
    </script>

    <div class="position-fixed top-0 start-50 translate-middle-x p-3 toast-padding" style="z-index: 11; top: 10px;">
        <div id="cartToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <h6>Product Added to Cart!<h6>
            </div>
        </div>
    </div>

    <script>
        var productArr = [];

        $(document).ready(function() {

            // Add this line to reset the quantity input of the add-to-cart modal to 1 when the modal is closed
            $('#productModal').on('hidden.bs.modal', function(e) {
                $('#quantityInput').val(1);
            });

            // Fetch the current cart count when the page loads
            $.ajax({
                url: 'api/cart2.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Assuming your GET API returns a field 'totalCartCount'
                    if (response.totalCartCount !== undefined) {
                        $("#cart-count").text(response.totalCartCount);
                    }
                },
                error: function() {
                    console.error('Could not retrieve cart count.');
                }
            });

            // Fetch unique brands when the page is loaded
            $.ajax({
                url: 'api/product.php', // Use the existing URL to your PHP script for products
                type: 'PUT',
                dataType: 'json',
                success: function(response) {
                    if (Array.isArray(response.brands) && response.brands.length) {
                        const brandDropdown = $("#brand");
                        response.brands.forEach(function(brand) {
                            brandDropdown.append(`<option value="${brand}">${brand}</option>`);
                        });
                    }
                },
                error: function() {
                    console.error('Could not retrieve brands.');
                }
            });

            // Get all product items and display them (no filters or search           )
            $.ajax({
                url: 'api/product.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $("#productContainer").empty();

                    if (Array.isArray(response.products) && response.products.length) {
                        response.products.forEach(function(item) {
                            $("#productContainer").append(`
                                <div class="col-md-4 product1">
                                    <div class="product bg_${item.product_id}_Img">
                                        <button type="button" class="btn btn-primary" onClick=openProductModal(${item.product_id})>
                                            ${item.product_name}
                                        </button>
                                    </div>
                                </div>

                            `);
                        });
                        productArr = response.products;
                    } else {
                        $("#productContainer").append(`
                            <div class="no-products">
                                <h3>No Products Available</h3>
                            </div>
                        `)
                    }
                },
                error: function() {
                    console.error('Could not retrieve product.');
                }
            });


            // Server Side Filter
            function filterProduct() {
                console.log("filterProduct function called"); // Add this line
                // Collect filter data from the form
                const brand = $("#brand").val();
                const min_price = $("#min_price").val();
                const max_price = $("#max_price").val();
                const rating = $("#rating").val();

                // Create an object to hold the filter criteria
                const filterCriteria = {
                    brand: brand,
                    min_price: min_price,
                    max_price: max_price,
                    rating: rating
                };
                 
                // Perform AJAX request to filter products based on criteria
                $.ajax({
                    url: 'api/product.php',
                    type: 'POST',
                    dataType: 'json',
                    data: filterCriteria,
                    success: function(response) {
                        $("#productContainer").empty();

                        if (Array.isArray(response.products) && response.products.length) {
                            response.products.forEach(function(item) {
                                $("#productContainer").append(`
                            <div class="col-md-4 product1">
                                <div class="product bg_${item.product_id}_Img">
                                    <button type="button" class="btn btn-primary" onClick=openProductModal(${item.product_id})>
                                        ${item.product_name}
                                    </button>
                                </div>
                            </div>
                        `);
                            });
                        } else {
                            $("#productContainer").append(`
                        <div class="no-products">
                            <h3>No Products Available</h3>
                        </div>
                        `);
                        }
                        // Close the filter modal
                        $('#filterModal').modal('hide');
                    },
                    error: function() {
                        console.error('Could not retrieve filtered products.');
                    }
                });
            }

            // Add the event listener for the button click
            $("#applyFiltersBtn").click(function() {
                filterProduct(); // Call the function here
            });




            // Existing code for form submission
            $(document).on('submit', '.add-to-cart-form', function(e) {

                e.preventDefault(); // Prevent the default behavior (form submission)

                // Fetch the product_id and quantity from the form
                let productId = $(this).find("input[name='product_id']").val();
                let quantity = $(this).find("input[name='quantity']").val();

                console.log(productId)

                $.ajax({
                    url: 'api/cart2.php',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity,
                        user_id: 81
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Success response:", response);
                        if (response.success) {
                            $("#cart-count").text(response.totalCartCount);
                            // Reset the quantity input for the specific product
                            $("#quantityInput" + productId).val(1);
                            // Close the modal
                            $("#productModal").modal('hide');

                            // Show the toast after a short delay to ensure the modal has closed
                            setTimeout(function() {
                                $('#addToCartToast').toast('show');
                            }, 500);
                        } else {
                            alert('Failed to add to cart. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
