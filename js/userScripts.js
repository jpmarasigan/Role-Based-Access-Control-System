var userFirstName; 
var userLastName;
var userEmail;

document.addEventListener('DOMContentLoaded', function () {
    highlightUserNavTab();
    highlightPersonalDetailsLink();
    getEmailUrlParams();
    submitNewOrder();
    submitUpdatePersonalDetails();
    showUserOrderHistory();
    checkPasswordInput();
    setDateConstraint();
    downloadReceipt();
    exitReceiptModal();
    filterUserOrderDetails();
    viewReceipt();
});

// Highlight the selected navigation tab
function highlightUserNavTab() {
    const navItems = document.querySelectorAll('.user-nav-item');
    let lastActiveTab = 'user-nav-item-2';

    // Function to show the tab based on its ID
    function showTab(tabId) {
        const userProfileContainer = document.getElementById('userProfileContainer');
        const userPersonalDetailsContainer = document.getElementById('userPersonalDetailsContainer');
        if (tabId === 'user-nav-item-1') {
            if (userProfileContainer.classList.contains('visible')) {
                document.getElementById('profileDisplayBackDrop').style.display = 'none';
                userPersonalDetailsContainer.classList.remove('visible');
                userProfileContainer.classList.remove('visible');
                userProfileContainer.classList.remove('active');
            }
            else {
                var profileLink = document.querySelectorAll('.details-link');
                profileLink.forEach(link => {
                    if (link.classList.contains('active')) {
                        if (link.id === 'user-details-link-1') {
                            userPersonalDetailsContainer.classList.add('visible');
                        }
                        else {
                            // Notification tab is active
                        }
                    }
                });
                userProfileContainer.classList.add('visible');
                userProfileContainer.classList.add('active');
                document.getElementById('profileDisplayBackDrop').style.display = 'flex';
            }
        } else {
            document.getElementById('profileDisplayBackDrop').style.display = 'none';
            document.getElementById('userProfileContainer').classList.remove('visible');
            document.getElementById('userAddOrderContainer').style.display = 'none';
            document.getElementById('userViewEditOrderContainer').style.display = 'none';
            document.getElementById('userDashboardContainer').style.display = 'none';
            
            if (tabId === 'user-nav-item-3') {
                document.getElementById('userAddOrderContainer').style.display = 'block';
                userPersonalDetailsContainer.classList.remove('visible');
                userProfileContainer.classList.remove('visible');
            } else if (tabId === 'user-nav-item-4') {
                document.getElementById('userViewEditOrderContainer').style.display = 'block';
                userPersonalDetailsContainer.classList.remove('visible');
                userProfileContainer.classList.remove('visible');
            } else if (tabId === 'user-nav-item-2') {
                document.getElementById('userDashboardContainer').style.display = 'block';
                userPersonalDetailsContainer.classList.remove('visible');
                userProfileContainer.classList.remove('visible');
            }
        }
    }

    // Event listeners for tab navigation
    navItems.forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();    // Prevent the default submit action
            navItems.forEach(navItem => navItem.classList.remove('active'));
    
            const userProfileContainer = document.getElementById('userProfileContainer');
            if (this.id === 'user-nav-item-1' && userProfileContainer.classList.contains('visible')) {
                document.getElementById(lastActiveTab).classList.add('active');
            }
            else {
                this.classList.add('active');
                if (this.id !== 'user-nav-item-1') {
                    lastActiveTab = this.id;
                }
            }
            showTab(this.id);

            // Save the active tab to localStorage
            localStorage.setItem('lastActiveTab', this.id);
        });
    });

    // Retrieve the active tab from localStorage and display it
    const activeTab = localStorage.getItem('lastActiveTab') || 'user-nav-item-2';  
    showTab(activeTab);

    // Add 'active' class to the active nav item
    document.getElementById(activeTab).classList.add('active');
};


// Highlight the selected personal details link 
function highlightPersonalDetailsLink() {
    var profileLink = document.querySelectorAll('.details-link');
    var logoutButton = document.querySelector('#user-logout-button');

    function showUserPersonalDetails(tab) {
        if (tab.id === 'user-details-link-1') {
            const userPersonalDetailsContainer = document.getElementById('userPersonalDetailsContainer');
            if (userPersonalDetailsContainer.classList.contains('visible')) {
                userPersonalDetailsContainer.classList.remove('visible');
                tab.classList.remove('active');
            }
            else {
                if (userFirstName && userLastName && userEmail) {
                    document.getElementById('user-update-email').value = userEmail;
                    document.getElementById('user-update-first-name').value = userFirstName;
                    document.getElementById('user-update-last-name').value = userLastName;
                }
                userPersonalDetailsContainer.classList.add('visible');
                tab.classList.add('active');
            }
        }
    }

    profileLink.forEach(link => {
        link.addEventListener('click', function() {
            // Remove all active link
            profileLink.forEach(link => link.classList.remove('active'));
            // Add active class to the clicked link
            this.classList.add('active');
            showUserPersonalDetails(this);
        });
    });

    logoutButton.addEventListener('click', function() {
        localStorage.clear();
        window.location.href = 'index.php';
    })
};


// Get Email in URLParams 
function getEmailUrlParams() {
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get('email');;

    $.ajax({
        type: 'POST',
        url: 'fetchRecord.php',
        data: {email: email, typeOfFetch: 'personalDetails'},
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success && !(data.admin)) {
                document.getElementById('user-profile-full-name').innerText = data.firstName + " " + data.lastName;
                document.getElementById('user-profile-type').innerText = 'CUSTOMER';

                document.getElementById('user-update-email').value = data.email;
                document.getElementById('user-update-first-name').value = data.firstName;
                document.getElementById('user-update-last-name').value = data.lastName;

                // for order's user details
                document.getElementById('order-owner').innerText = data.lastName + '\'s Orders';
                userFirstName = data.firstName; 
                userLastName = data.lastName;
                userEmail = data.email;
            }
            else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = "Error Displaying Personal Info";
            }
        }
    })
}


// Check if password has value and set required attribute
function checkPasswordInput() {
    var oldPassword = document.getElementById('user-update-old-password');
    var newPassword = document.getElementById('user-update-new-password');

    function updatePasswordRequiredStatus() {
        var oldPassword = document.getElementById('user-update-old-password');
        var newPassword = document.getElementById('user-update-new-password');
    
        if (oldPassword.value != '' || newPassword.value != '') {
            oldPassword.required = true;
            newPassword.required = true;
        } else {
            oldPassword.required = false;
            newPassword.required = false; 
        }
    }

    // Input event listener
    oldPassword.addEventListener('input', updatePasswordRequiredStatus);
    newPassword.addEventListener('input', updatePasswordRequiredStatus);
}


// Submit Update Personal Details
function submitUpdatePersonalDetails() {
    document.getElementById('userPersonalDetailsForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var firstName = document.getElementById('user-update-first-name').value;
        var lastName = document.getElementById('user-update-last-name').value;
        var oldEmail = new URLSearchParams(window.location.search).get('email');
        var newEmail = document.getElementById('user-update-email').value;
        var oldPassword = document.getElementById('user-update-old-password').value;
        var newPassword = document.getElementById('user-update-new-password').value;

        updateUserPersonalDetails(firstName, lastName, oldEmail, newEmail, oldPassword, newPassword);
    });
}


// Submit New Order 
function submitNewOrder() {
    document.getElementById('addCustomerOrderForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var today = new Date();
        var receiptDate = today.toISOString().slice(0, 10);
        var orderDate = document.getElementById('user-add-order-date').value;
        var amount = document.getElementById('user-add-order-amount').value;
        var email = new URLSearchParams(window.location.search).get('email');

        userAddOrder(email, receiptDate, orderDate, amount);
    })
}


// Cancel Success Button in Modal
var receiptCancelSuccessButton = document.getElementById('receipt-success-cancel-button');
receiptCancelSuccessButton.addEventListener('click', function() {
    document.getElementById('receiptSuccessModal').style.display = 'none';
    document.getElementById('receiptModal').style.display = 'flex';
})

var cancelSuccessButton = document.getElementById('success-cancel-button');
cancelSuccessButton.addEventListener('click', function() {
    document.getElementById('successModal').style.display = 'none';
    location.reload();
})

// Cancel Error Button in Modal
var cancelErrorButton = document.getElementById('error-cancel-button');
cancelErrorButton.addEventListener('click', function() {
    document.getElementById('errorModal').style.display = 'none';
    document.getElementById('error-text').innerText = '';
})


// Date Format and its Minimum Date
function setDateConstraint() {
    function formatDate(date) {
    var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) {
            month = '0' + month;
        }
        if (day.length < 2) {
            day = '0' + day;
        }

        return [year, month, day].join('-');
    }

    // Date minimum available
    var today = new Date()
    var formattedToday = formatDate(today);
    document.getElementById('user-add-order-date').setAttribute('min', formattedToday);
    // document.getElementById('edit-order-date').setAttribute('min', formattedToday);
}


// SHOW USER ORDER HISTORY 
function showUserOrderHistory() {
    function isViewOrderTabActive() {
        var viewOrderHistoryTab = $('#user-nav-item-3');
        if(viewOrderHistoryTab.hasClass('active')) {
            var email = new URLSearchParams(window.location.search).get('email');  
            userDisplayOrder(email);
        }
    }
    isViewOrderTabActive();     // Initial call
    $('#user-nav-item-3').on('click', isViewOrderTabActive);
}


// SEARCH FILTER 
function filterUserOrderDetails() {
    function fetchUserOrderDetails() {
        var sortBy = $('#sortByUserOrder').val();
        var searchFilter = $('#searchFilterUserOrder').val();
        var email = new URLSearchParams(window.location.search).get('email');

        $.ajax({
            type: 'POST',
            url: 'fetchRecord.php',
            data: {email: email, sortBy: sortBy, searchFilter: searchFilter, typeOfFetch: 'userOrderDetails'},
            success: function(response) {
                $('#userOrderHistoryTableBody').html(response);
            }
        });
    }
    fetchUserOrderDetails();
    $('#searchFilterUserOrder').on('input', fetchUserOrderDetails);
    $('#sortByUserOrder').on('change', fetchUserOrderDetails);
}


// VIEW RECEIPT 
function viewReceipt() {
    document.body.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('receipt-button')) {
            var button = event.target;
            var orderId = button.getAttribute('data-user-order-orderId');
            var name = button.getAttribute('data-user-order-name');
            var email = button.getAttribute('data-user-order-email');
            var orderAmount = button.getAttribute('data-user-order-totalAmount');
            var receiptDate = button.getAttribute('data-user-order-receiptDate');
            var shippingFee = 0.00; 
            
            // Apply user details and order details to receipt
            document.getElementById('user-receipt-date').innerText = receiptDate;
            document.getElementById('user-receipt-order-id').innerText = orderId;
            document.getElementById('user-receipt-name').innerText = name;
            document.getElementById('user-receipt-email').innerText = email;
            document.getElementById('user-receipt-amount').innerText = Number(orderAmount).toFixed(2);
            document.getElementById('user-receipt-total').innerText = (Number(orderAmount) + Number(shippingFee)).toFixed(2);

            // Display the receipt
            document.getElementById('receiptModal').style.display = 'flex';
        }
    });
}


// RECEIPT DOWNLOAD 
function downloadReceipt() {
    document.getElementById('downloadReceipt').addEventListener('click', function(event) {
        event.preventDefault();

        const receiptModal = document.getElementById('receiptModal');
        const downloadLink = document.getElementById('downloadReceipt');

        // Hide to not include in ss
        downloadLink.style.display = 'none';

        html2canvas(receiptModal).then(function(canvas) {
            let link = document.createElement('a');
            link.href = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
            link.download = 'receipt_screenshot.png';
            link.click();

            // Show the download link again
            downloadLink.style.display = '';
        })
    });
}


// EXIT RECEIPT MODAL
function exitReceiptModal() {
    var receiptExitButtons = document.querySelectorAll('.receipt-exit-button');
    receiptExitButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            document.getElementById('receiptModal').style.display = 'none';
        })
    })
}
