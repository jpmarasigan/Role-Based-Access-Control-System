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
            document.getElementById('userAddOrderContainer').style.display = 'none';
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
    const email = urlParams.get('email');

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
    document.getElementById('userAddOrderForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var today = new Date();
        var orderDate = today.toLocaleDateString('en-CA');
        // Calculate the receipt date (7 days from the order date)
        var receiptDateObj = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000);  
        var receiptDate = receiptDateObj.toLocaleDateString('en-CA');      
        var totalAmount = getTotalOrderAmount();
        var orderList = userProductData;
        var email = new URLSearchParams(window.location.search).get('email');

        userAddOrder(email, orderList, orderDate, receiptDate, totalAmount);
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
            var orderDate = button.getAttribute('data-user-order-orderDate');
            var shippingFee = 0.00; 
            
            // Apply user details and order details to receipt
            document.getElementById('user-receipt-date').innerText = orderDate;
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


var addOrderProductContainer = document.querySelector('.user-flex-button');
addOrderProductContainer.addEventListener('click', function(event) {
    event.preventDefault();
    if (event.target.classList.contains('add-order-product')) {
        var parentElement = event.target.parentNode.parentNode.parentNode;
        
        // Add a new product div
        var newProductDiv = document.createElement('div');
        newProductDiv.setAttribute('class', 'user-inner-flex');

        // Add innerHTML 
        $.ajax({
            url: 'fetchRecord.php',
            data: {typeOfFetch: 'addProductDropdown'},
            type: 'POST',
            success: function(response) {
                if (response != '') {
                    var productDivInnerHTML = `
                    <div class="block-label-input">
                        <select class="select-filter ht-max pd-filter border-rad-filter corner-smooth mg-right" name="addOrderProductDropdown" style="width: 250px;" required>
                            <option value='' selected disabled>Order Product</option>
                            ${response}
                        </select>    
                    </div>
                    <div class="block-label-input">
                        <label for="add-quantity">Qty</label> 
                        <input type="number" name="add-quantity" class="add-order-quantity underline-input" min="1" value="1" required>        
                    </div>
                    `;

                    newProductDiv.innerHTML = productDivInnerHTML;
                    parentElement.appendChild(newProductDiv);
                }
            }
        });
    }
    else if (event.target.classList.contains('delete-order-product')) {
        var parentElement = event.target.parentNode.parentNode.parentNode;
        var lastChildElement = parentElement.children[parentElement.children.length - 1];

        // Remove the element
        if (parentElement.children.length > 1) {
            var index = Array.prototype.indexOf.call(parentElement.children, lastChildElement);
            lastChildElement.remove();
            if(userProductData[index]) {
                delete userProductData[index];
                getTotalAmountOrder(userProductData);
            }
        }
    }
});


// Reset Order Product Container 
function userResetOrderProductContainer() {
    productData = {};
    var parentElement = document.querySelector('.user-flex');

    while (parentElement && parentElement.children.length > 1) {
        var lastChildElement = parentElement.children[parentElement.children.length - 1];
        lastChildElement.remove();
    }

    // Reset the product dropdown and quantity
    var productDropdown = document.querySelector('[name="addOrderProductDropdown"]');
    productDropdown.selectedIndex = 0;
    var quantityInput = document.querySelector('[name="add-quantity"]');
    quantityInput.value = 1;
}


// Display the value of product dropdown and quantity
var getProductElement = document.querySelector('.user-flex');
let userProductData = {};

getProductElement.addEventListener('change', function(event) {
    if (event.target.name === 'addOrderProductDropdown') {
        var selectedProduct = event.target.value;    
        var parentDiv = event.target.closest('.user-inner-flex');
        var index = Array.prototype.indexOf.call(parentDiv.parentNode.children, parentDiv);

        // If product data for this index doesn't exist, create it
        if (!userProductData[index]) {
            userProductData[index] = {
                productId: selectedProduct,
                quantity: 1
            };
        } else {
            // If it does exist, just update the productId
            userProductData[index].productId = selectedProduct;
        }
        getTotalAmountOrder(userProductData);
    }
});

getProductElement.addEventListener('input', function(event) {
    if (event.target.name === 'add-quantity') {
        var qty = event.target.value;    
        var parentDiv = event.target.closest('.user-inner-flex');
        var index = Array.prototype.indexOf.call(parentDiv.parentNode.children, parentDiv);

        // If product data for this index doesn't exist, create it
        if (!userProductData[index]) {
            userProductData[index] = {
                productId: '', // We don't know the productId yet
                quantity: parseInt(qty)
            };
        } else {
            // If it does exist, just update the quantity
            userProductData[index].quantity = parseInt(qty);
        }
        getTotalAmountOrder(userProductData); 
    }
});
