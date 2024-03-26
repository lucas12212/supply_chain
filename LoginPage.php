<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Banking</title>
  <link rel="stylesheet" href="newcss.css">
  <link rel="stylesheet" href="style2.css"> <!-- New Line -->
  <style>
    .srouce {
      text-align: center;
      color: #ffffff;
      padding: 10px;
    }
  </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>

<div class="main-container">
    <div class="form-container">
        <div class="srouce"><a title="Banking Login" href="#">Watch Store</a></div>
        <div class="form-body">
            <h2 class="title">Log in with</h2>
            <div class="social-login">
                <ul>
                    <li class="google"><a href="#">Google</a></li>
                    <li class="fb"><a href="#">Facebook</a></li>
                </ul>
            </div>
            <div class="_or">or</div>
            <form id="loginForm" class="the-form">
                <label for="FullName">Name</label>
                <input type="text" name="FullName" id="fullname" placeholder="Enter your name" class="custom-input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" class="custom-input">
                <input type="submit" value="Log In">
            </form>

        </div>
        <div class="form-footer">
            <div>
                <span>Don't have an account?</span> <a href="RegisterPage.php">Sign Up</a>
            </div>
        </div>
    </div>
</div>

<script>

$(document).on('submit','#loginForm',function(){
   // code
    event.preventDefault()
    $.ajax({
        url: 'api/login.php',
        type: 'POST',
        dataType: 'json',
        data: {
          "name" : $("#fullname").val(),
          "password" : $("#password").val()
        },
        success: function(response) {
          if(response.msg == "login successfully") {
            window.location.href = "BuyerEnd.html";
            localStorage.setItem("userId", response.user_id);
          } else {
            alert(response.msg);
          }
        },
        error: function() {
            console.error('Could not retrieve cart count.');
        }
    });

});


</script>

</body>