document.addEventListener('DOMContentLoaded', function() {
    loginSignupLink();
    submitLoginSignUpForm();
    cancelSuccessButton();
    cancelErrorButton();
});



function loginSignupLink() {
    var signupLink = document.getElementById('goto-signup');
    var signinLink = document.getElementById('goto-signin');

    signupLink.addEventListener('click', function() {
        document.getElementById('first-name-signup').value = '';
        document.getElementById('last-name-signup').value = '';
        document.getElementById('email-signup').value = '';
        document.getElementById('password-signup').value = '';
        document.getElementById('login').style.display = 'none';
        document.getElementById('signup').style.display = 'flex';
    });
    signinLink.addEventListener('click', function() {
        document.getElementById('email-login').value = '';
        document.getElementById('password-login').value = '';
        document.getElementById('signup').style.display = 'none';
        document.getElementById('login').style.display = 'flex';
    });
}


function submitLoginSignUpForm() {
    var loginForm = document.getElementById('LoginForm');
    var signupForm = document.getElementById('SignupForm');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();

        var email = document.getElementById('email-login').value;
        var password = document.getElementById('password-login').value;

        signInAccount(email, password);
    });

    signupForm.addEventListener('submit', function(event) {
        event.preventDefault();

        var firstName = document.getElementById('first-name-signup').value;
        var lastName = document.getElementById('last-name-signup').value;
        var email = document.getElementById('email-signup').value;
        var password = document.getElementById('password-signup').value;

        signUpAccount(firstName, lastName, email, password);
    });
}


// Cancel Delete Button in Modal
function cancelSuccessButton() {
    var cancelSuccessButton = document.getElementById('success-cancel-button');
    cancelSuccessButton.addEventListener('click', function() {
        document.getElementById('successModal').style.display = 'none';
        location.reload();
        // Show sign in form
        document.getElementById('email-login').value = '';
        document.getElementById('password-login').value = '';
        document.getElementById('signup').style.display = 'none';
        document.getElementById('login').style.display = 'flex';
    });
}

// Cancel Delete Button in Modal
function cancelErrorButton() {
    var cancelErrorButton = document.getElementById('error-cancel-button');
    cancelErrorButton.addEventListener('click', function() {
        document.getElementById('errorModal').style.display = 'none';
        document.getElementById('error-text').innerText = '';
    });
}