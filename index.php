<?php
include 'db_connect.php';
include 'retrieve_record.php';
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/modalStyles.css">
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="css/styles.css">
        <title>Document</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        </style>
    </head>
    <body>
        <div id="login">
            <div class="login-form-container">
                <form id="LoginForm" method="post" action="">
                    <h1>Login<h1>
                    <div class="block-label-input mg-bottom-60 text-left">
                        <label for="email-login">Email</label> 
                        <input type="text" id="email-login" class="underline-input" required>        
                    </div>  
                    <div class="block-label-input mg-bottom-60 text-left">
                        <label for="password-login">Password</label> 
                        <input type="password" id="password-login" class="underline-input" required>        
                    </div>  
                    <button id="signin-button" type="submit" class="semi-bold border-rad-filter" data-action="sign-in">SIGN IN</button>
                </form>
                <p style="margin-bottom: 5px;">Or Sign Up Using<p>
                <a id="goto-signup">SIGN UP</a>
            </div>
        </div>

        <div id="signup">
            <div class="signup-form-container">
                <form id="SignupForm" method="post" action="">
                    <h1>Sign up<h1>
                    <div class="block-label-input mg-bottom-60 text-left">
                        <label for="first-name-signup">First Name</label> 
                        <input type="text" id="first-name-signup" class="underline-input" required>        
                    </div>  
                    <div class="block-label-input mg-bottom-60 text-left">
                        <label for="last-name-signup">Last Name</label> 
                        <input type="text" id="last-name-signup" class="underline-input" required>        
                    </div>  
                    <div class="block-label-input mg-bottom-60 text-left">
                        <label for="email-signup">Email</label> 
                        <input type="text" id="email-signup" class="underline-input" required>        
                    </div>  
                    <div class="block-label-input mg-bottom-60 text-left">
                        <label for="password-signup">Password</label> 
                        <input type="password" id="password-signup" class="underline-input" minlength="8" required>        
                    </div>  
                    <button id="signup-button" type="submit" class="semi-bold border-rad-filter" data-action="sign-in">SIGN UP</button>
                </form>
                <p style="margin-bottom: 5px;">Or Sign In Using<p>
                <a id="goto-signin">SIGN IN</a>
            </div>
        </div>

        <!-- SUCCESS MODAL -->
        <div id="successModal" class="modal">
            <div class="confirm-modal-container">
                <img src="./assets/check-icon.svg" alt="alert icon" class="alert-icon">                  
                <h4>STATUS</h4>
                <p id="success-text"></p>
                <button id='success-cancel-button' class="cancel-delete-button modal-button semi-bold corner-smooth border-rad-filter" style="width: 100px;">Ok</button>
            </div>
        </div>

        <!-- ERROR MODAL -->
        <div id="errorModal" class="modal">
            <div class="confirm-modal-container">
                <img src="./assets/error-icon.svg" alt="alert icon" class="alert-icon">                  
                <h4>STATUS</h4>
                <p id="error-text"></p>
                <button id='error-cancel-button' class="cancel-delete-button modal-button semi-bold corner-smooth border-rad-filter" style="width: 100px;">Ok</button>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="./js/ajaxRequests.js"></script>
        <script src="./js/authenticationScripts.js"></script>
    </body>
</html>