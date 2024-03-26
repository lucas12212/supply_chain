<!DOCTYPE html>
<html>

<head>
    <title>My E-commerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>


<?php
session_start();
$cart_id = null;

// Assuming you start a session at the beginning of your script
if (!session_id()) {
    session_start();
}

$conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e-commerce_websitev2', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$minPrice = !empty($_GET['min_price']) ? (float)$_GET['min_price'] : NULL;
$maxPrice = !empty($_GET['max_price']) ? (float)$_GET['max_price'] : NULL;
$selectedBrand = isset($_GET['brand']) ? $_GET['brand'] : NULL;
$selectedRating = isset($_GET['rating']) ? (int)$_GET['rating'] : NULL;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $queryStr = "SELECT * FROM product_table WHERE 1";

    if (isset($_POST['search']) && !empty($_POST['search'])) {
        $searchKeyword = "%" . $_POST['search'] . "%";
        $queryStr .= " AND product_name LIKE :searchKeyword";
    }

    if ($minPrice !== NULL) {
        $queryStr .= " AND product_cost >= :minPrice";
    }
    if ($maxPrice !== NULL) {
        $queryStr .= " AND product_cost <= :maxPrice";
    }
    if ($selectedBrand !== NULL && $selectedBrand != "") {
        $queryStr .= " AND product_category = :selectedBrand";
    }
    if ($selectedRating !== NULL) {
        $queryStr .= " AND avg_rating >= :selectedRating";
    }

    $query = $conn->prepare($queryStr);

    if (isset($_POST['search']) && !empty($_POST['search'])) {
        $query->bindParam(':searchKeyword', $searchKeyword, PDO::PARAM_STR);
    }
    if ($minPrice !== NULL) {
        $query->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
    }
    if ($maxPrice !== NULL) {
        $query->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
    }
    if ($selectedBrand !== NULL && $selectedBrand != "") {
        $query->bindParam(':selectedBrand', $selectedBrand, PDO::PARAM_STR);
    }
    if ($selectedRating !== NULL) {
        $query->bindParam(':selectedRating', $selectedRating, PDO::PARAM_INT);
    }

    $query->execute();

    if ($query->rowCount() === 0) {
        // If no results found, retrieve all products from the database
        $queryStr = "SELECT * FROM product_table";
        $conditions = [];

        if ($minPrice !== NULL) {
            $conditions[] = "product_cost >= :minPrice";
        }
        if ($maxPrice !== NULL) {
            $conditions[] = "product_cost <= :maxPrice";
        }
        if ($selectedBrand !== NULL && $selectedBrand != "") {
            $conditions[] = "product_category = :selectedBrand";
        }

        if (!empty($conditions)) {
            $queryStr .= ' WHERE ' . implode(' AND ', $conditions);
            $query = $conn->prepare($queryStr);
            if ($minPrice !== NULL) {
                $query->bindValue('minPrice', $minPrice);
            }
            if ($maxPrice !== NULL) {
                $query->bindValue('maxPrice', $maxPrice);
            }
            if ($selectedBrand !== NULL && $selectedBrand != "") {
                $query->bindValue('selectedBrand', $selectedBrand);
            }
            $query->execute();
        } else {
            $query = $conn->prepare("SELECT * FROM product_table");
            $query->execute();
        }
    }
} else {
    // $queryStr = "SELECT * FROM product_table";
    // $query = $conn->prepare($queryStr);

    // If no results found, retrieve all products from the database
    $queryStr = "SELECT * FROM product_table";
    $conditions = [];

    if ($minPrice !== NULL) {
        $conditions[] = "product_cost >= :minPrice";
    }
    if ($maxPrice !== NULL) {
        $conditions[] = "product_cost <= :maxPrice";
    }
    if ($selectedBrand !== NULL && $selectedBrand != "") {
        $conditions[] = "product_category = :selectedBrand";
    }

    if (!empty($conditions)) {
        $queryStr .= ' WHERE ' . implode(' AND ', $conditions);
        $query = $conn->prepare($queryStr);
        if ($minPrice !== NULL) {
            $query->bindValue('minPrice', $minPrice);
        }
        if ($maxPrice !== NULL) {
            $query->bindValue('maxPrice', $maxPrice);
        }
        if ($selectedBrand !== NULL && $selectedBrand != "") {
            $query->bindValue('selectedBrand', $selectedBrand);
        }
        $query->execute();
    } else {
        $query = $conn->prepare("SELECT * FROM product_table");
        $query->execute();
    }
}

$query->execute();

// $stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart_items WHERE cart_id = ?");
// $stmt->execute([$cart_id]);
// $result = $stmt->fetch();
$totalCartCount = $result['total_items'];
if ($totalCartCount == null) {
    $totalCartCount = 0;
}


?>

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
                            <a href="cart.php">
                                <img class="cart-image" src="Images/cart.png" alt="Shopping Cart">
                                <span id="cart-count"><?php echo $totalCartCount; ?></span>
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
                                <?php

                                try {
                                    //Fetch unique brands from the database
                                    $stmt = $conn->query("SELECT DISTINCT product_category FROM product_table");
                                    if (!$stmt) {
                                        echo 'Error in query execution: ' . $conn->errorInfo()[2];
                                    } else {
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row['product_category'] . '">' . $row['product_category'] . '</option>';
                                        }
                                    }
                                } catch (PDOException $e) {
                                    echo 'Connection failed: ' . $e->getMessage();
                                }

                                ?>
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

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 20px">Apply Filters</button>
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

    <?php
    if (isset($errorMessage)) {
        echo "<div class='alert alert-danger'>$errorMessage</div>";
    }
    ?>




    <section class="banner">
        <!-- Add your banner photo here -->
        <img src="Images/banner.png" alt="Banner Photo">
    </section>

    <section class="container" style="margin-top: 50px;">
        <h2>Featured Products</h2>
        <!-- Add your featured products here -->
        <div class="row justify-content-between">
            <!-- Fetch Products -->
            <?php
            if ($query->rowCount() > 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    // Access row data using $row['column_name']
                    $productId = $row['product_id'];
                    $product_name = $row['product_name'];
                    $price = $row['product_cost'];
                    $stock = $row['stocks_left'];
                    $reviews = $row['no_reviews'];
                    $average_rating = $row['avg_rating'];
                    $product_cat = $row['product_category'];

                    // Your code to display a product goes here.
            ?>


                    <div class="col-md-4 product1">
                        <div class="product bg_<?php echo $productId; ?>_Img">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $productId; ?>">
                                <?php echo $product_name; ?>
                            </button>

                            <!-- Modal -->
                            <!-- Inside the while loop, for each product -->
                            <!-- Inside the while loop, for each product -->
                            <div class="modal fade" id="exampleModal<?php echo $productId; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $product_name; ?></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Price: $<?php echo $price; ?></p>
                                            <p>Stock: <?php echo $stock; ?></p>
                                            <p>Reviews: <?php echo $reviews; ?></p>
                                            <p>Average Rating: <?php echo $average_rating; ?>/5</p>
                                        </div>

                                        <div class="modal-footer no-flex">
                                            <form action="" method="post">
                                                <div class="input-group">
                                                    <input type="hidden" name="product_id" class="form-control" value="<?php echo $productId; ?>">
                                                    <button class="btn btn-outline-primary" type="button" onclick="decrementQuantity(<?php echo $productId; ?>)">-</button>
                                                    <input type="number" name="quantity" class="form-control" id="quantityInput<?php echo $productId; ?>" value="1" min="1">
                                                    <button class="btn btn-outline-primary" type="button" onclick="incrementQuantity(<?php echo $productId; ?>)">+</button>
                                                    <button type="submit" class="btn btn-primary add-to-cart-button">Add to Cart</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                <?php

                }
            } else {
                ?>

                <div class="no-products">
                    <h3>No Products Available</h3>
                </div>

            <?php
            }
            ?>
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

        function incrementQuantity(productId) {
            let quantityInput = document.getElementById("quantityInput" + productId);
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }

        window.decrementQuantity = function(productId) {
            var value = parseInt(document.getElementById('quantityInput' + productId).value, 10);
            value = isNaN(value) ? 0 : value;
            value < 1 ? value = 1 : '';
            value--;
            document.getElementById('quantityInput' + productId).value = value;
        }

        // New function: handle add to cart button click
        window.addToCart = function(productId) {
            var quantityInput = document.getElementById('quantityInput' + productId);
            cartCount += parseInt(quantityInput.value);
            document.getElementById('cart-count').innerText = cartCount;

            // Close the modal
            jQuery('#exampleModal' + productId).modal('hide');

            // Show the toast after a short delay to ensure modal has closed
            setTimeout(function() {
                var toastEl = document.getElementById('cartToast');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            }, 500);
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
        $(document).ready(function() {
            // Fetch the current cart count when the page loads
            $.ajax({
                url: 'REST/cart2.php',
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

            // Existing code for form submission
            $("form").submit(function(e) {
                e.preventDefault(); // Prevent the default behavior (form submission)

                // Fetch the product_id and quantity from the form
                let productId = $(this).find("input[name='product_id']").val();
                let quantity = $(this).find("input[name='quantity']").val();

                $.ajax({
                    url: 'REST/cart2.php',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Success response:", response);
                        if (response.success) {
                            $("#cart-count").text(response.totalCartCount);
                            // Reset the quantity input for the specific product
                            $("#quantityInput" + productId).val(1);
                            // Close the modal
                            $("#exampleModal" + productId).modal('hide');

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

<?php
$conn = null; // Close the database connection
?>