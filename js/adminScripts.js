// Highlight the selected tab
document.addEventListener('DOMContentLoaded', function () {
    const navItems = document.querySelectorAll('.nav-item');
    let lastActiveTab = 'admin-nav-item-2';

    // Function to show the tab based on its ID
    function showTab(tabId) {
        const adminProfileContainer = document.getElementById('adminProfileContainer');
        const adminPersonalDetailsContainer = document.getElementById('adminPersonalDetailsContainer');
        if (tabId === 'admin-nav-item-1') {
            if (lastActiveTab === 'admin-nav-item-2') {
                document.getElementById('inputForm').style.display = 'flex';
            }
            if (adminProfileContainer.classList.contains('visible')) {
                document.getElementById('profileDisplayBackDrop').style.display = 'none';
                adminPersonalDetailsContainer.classList.remove('visible');
                adminProfileContainer.classList.remove('visible');
                adminProfileContainer.classList.remove('active');
            }
            else {
                var profileLink = document.querySelectorAll('.details-link');
                profileLink.forEach(link => {
                    if (link.classList.contains('active')) {
                        if (link.id === 'admin-details-link-1') {
                            adminPersonalDetailsContainer.classList.add('visible');
                        }
                        else {
                            // Notification tab is active
                        }
                    }
                });
                adminProfileContainer.classList.add('visible');
                adminProfileContainer.classList.add('active');
                document.getElementById('profileDisplayBackDrop').style.display = 'flex';
            }
        } else {
            document.getElementById('adminProfileContainer').classList.remove('visible');
            document.getElementById('inputForm').style.display = 'none';
            document.getElementById('customerDetailReport').style.display = 'none';
            document.getElementById('customerOrderReport').style.display = 'none';
            document.getElementById('productCatalogReport').style.display = 'none';
            document.getElementById('profileDisplayBackDrop').style.display = 'none';

            if (tabId === 'admin-nav-item-2') {
                document.getElementById('inputForm').style.display = 'flex';
                adminProfileContainer.classList.remove('visible');
                adminPersonalDetailsContainer.classList.remove('visible');
            } else if (tabId === 'admin-nav-item-3') {
                document.getElementById('customerDetailReport').style.display = 'block';
                adminProfileContainer.classList.remove('visible');
                adminPersonalDetailsContainer.classList.remove('visible');
            } else if (tabId === 'admin-nav-item-4') {
                document.getElementById('customerOrderReport').style.display = 'block';
                adminProfileContainer.classList.remove('visible');
                adminPersonalDetailsContainer.classList.remove('visible');
            } else if (tabId === 'admin-nav-item-5') {
                document.getElementById('productCatalogReport').style.display = 'block';
                adminProfileContainer.classList.remove('visible');
                adminPersonalDetailsContainer.classList.remove('visible');
            }
        }
    }

    // Event listeners for tab navigation
    navItems.forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();    // Prevent the default submit action
            navItems.forEach(navItem => navItem.classList.remove('active'));

            const adminProfileContainer = document.getElementById('adminProfileContainer');
            if (this.id === 'admin-nav-item-1' && adminProfileContainer.classList.contains('visible')) {
                document.getElementById(lastActiveTab).classList.add('active');
            }
            else {
                this.classList.add('active');
                if (this.id !== 'admin-nav-item-1') {
                    lastActiveTab = this.id;
                }
            }
            showTab(this.id);

            // Store the ID of the last active tab
            localStorage.setItem('lastActiveTab', this.id);
        });
    });

    // Retrieve the active tab from localStorage and display it
    const activeTab = localStorage.getItem('lastActiveTab') || 'admin-nav-item-2';  
    showTab(activeTab);

    // Add 'active' class to the active nav item
    document.getElementById(activeTab).classList.add('active');
});


// Highlight the selected personal details link 
function highlightPersonalDetailsLink() {
    var profileLink = document.querySelectorAll('.details-link');
    var logoutButton = document.querySelector('#admin-logout-button');

    function showUserPersonalDetails(tab) {
        if (tab.id === 'admin-details-link-1') {
            const adminPersonalDetailsContainer = document.getElementById('adminPersonalDetailsContainer');
            if (adminPersonalDetailsContainer.classList.contains('visible')) {
                adminPersonalDetailsContainer.classList.remove('visible');
                tab.classList.remove('active');
            }
            else {
                adminPersonalDetailsContainer.classList.add('visible');
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


// Table Display Filter
$(document).ready(function() {
    function fetchCustomerDetails() {
        var sortBy = $('#sortByCustomerDetails').val();
        var searchFilter = $('#searchFilterCustomerDetails').val();
    
        $.ajax({
            type: 'POST',
            url: 'fetchRecord.php',
            data: { sortBy: sortBy, searchFilter: searchFilter, typeOfFetch: 'customerDetails'},
            success: function(response) {
                $('#customerDetailBody').html(response);
            }
        });
    }

    function fetchCustomerOrder() {
        var sortBy = $('#sortByCustomerOrder').val();
        var searchFilter = $('#searchFilterCustomerOrder').val();
        var dateFilter = $('#orderDateDropdown').val();
        var customerId = $('#dropdownCustomerId').val();

        $.ajax({
            type: 'POST',
            url: 'fetchRecord.php',
            data: { sortBy: sortBy, searchFilter: searchFilter, dateFilter: dateFilter, customerId: customerId, typeOfFetch: 'customerOrder'},
            success: function(response) {
                $('#customerOrderBody').html(response);
            }
        });
    }

    function fetchProductCatalog() {
        var sortBy = $('#sortByProductCatalog').val();
        var searchFilter = $('#searchFilterProductCatalog').val();
        var categoryName = $('#categoryNameDropdown').val();

        $.ajax({
            type: 'POST',
            url: 'fetchRecord.php',
            data: { sortBy: sortBy, searchFilter: searchFilter, categoryName: categoryName, typeOfFetch: 'productCatalog'},
            success: function(response) {
                $('#productCatalogBody').html(response);
            }
        });
    }

    // Customer Details
    $('#searchFilterCustomerDetails').on('input', fetchCustomerDetails);
    $('#sortByCustomerDetails').on('change', fetchCustomerDetails);
    // Customer Orders
    $('#searchFilterCustomerOrder').on('input', fetchCustomerOrder);
    $('#sortByCustomerOrder').on('change', fetchCustomerOrder);
    $('#orderDateDropdown').on('change', fetchCustomerOrder);
    $('#dropdownCustomerId').on('change', fetchCustomerOrder);
    // Product Catalog
    $('#searchFilterProductCatalog').on('input', fetchProductCatalog);
    $('#sortByProductCatalog').on('change', fetchProductCatalog);
    $('#categoryNameDropdown').on('change', fetchProductCatalog);

    // Initial fetch to load the table
    fetchCustomerDetails();
    fetchCustomerOrder();
    fetchProductCatalog();
});

// Delete Record Modal Display
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        // Modal Customer Detail Record Display 
        if (event.target && event.target.classList.contains('customer-detail-button')) {
            var button = event.target;
            var id = button.getAttribute('data-customer-customerId');
            var firstName = button.getAttribute('data-customer-firstName');
            var lastName = button.getAttribute('data-customer-lastName');
            var email = button.getAttribute('data-customer-email');

            // Logging purpose
            // console.log("Record to be deleted");
            // console.log("ID:", id, " | Firstname:", firstName, " | LastName:", lastName, " Email:", email);
        
            // Set content to modal display
            document.getElementById('record-data-modal').innerHTML = `
                <p><span class="semi-bold">Customer ID:</span> ${id}</p>
                <p><span class="semi-bold">First Name:</span> ${firstName}</p>
                <p><span class="semi-bold">Last Name:</span> ${lastName}</p>
                <p><span class="semi-bold">Email:</span> ${email}</p>
            `;

            // Display the modal
            document.getElementById('confirmModal').style.display = 'flex';

            // COnfirmed Delete Button in Modal
            var confirmedDeleteButtons = document.querySelectorAll('.confirmed-delete-button');
            confirmedDeleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('errorModal').style.display = 'none';
                    deleteCustomerDetailRecord(email);
                });
            });
        }
        // Modal Customer Order Record Display 
        else if (event.target && event.target.classList.contains('customer-order-button')) {
            var button = event.target;
            var orderId = button.getAttribute('data-order-orderId');
            var customerId = button.getAttribute('data-order-customerId');
            var firstName = button.getAttribute('data-order-firstName');
            var lastName = button.getAttribute('data-order-lastName');
            var orderDate = button.getAttribute('data-order-orderDate');
            var receiptDate = button.getAttribute('data-order-receiptDate');
            var totalAmount = button.getAttribute('data-order-totalAmount');

            // Logging purpose
            // console.log("Record to be deleted");
            // console.log("OrderID:", orderId, " | CustomerID:", customerId, " | FirstName:", firstName, " | LastName:", lastName, " | OrderDate:", orderDate, " | TotalAmount:", totalAmount);
            
            // Set content to modal display
            document.getElementById('record-data-modal').innerHTML = `
                <p><span class="semi-bold">Order ID:</span> ${orderId}</p>
                <p><span class="semi-bold">Customer ID:</span> ${customerId}</p>
                <p><span class="semi-bold">First Name:</span> ${firstName}</p>
                <p><span class="semi-bold">Last Name:</span> ${lastName}</p>
                <p><span class="semi-bold">Order Date:</span> ${orderDate}</p>
                <p><span class="semi-bold">Receipt Date:</span> ${receiptDate}</p>
                <p><span class="semi-bold">Total Amount:</span> ${totalAmount}</p>
            `;

            // Display the modal
            document.getElementById('confirmModal').style.display = 'flex';

            // COnfirmed Delete Button in Modal
            var confirmedDeleteButtons = document.querySelectorAll('.confirmed-delete-button');
            confirmedDeleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('errorModal').style.display = 'none';
                    deleteCustomerOrderRecord(orderId, customerId, orderDate, totalAmount);
                });
            });
        }
        // Modal Product Catalog Record Display 
        else if (event.target && event.target.classList.contains('product-catalog-button')) {
            var button = event.target;
            var productName = button.getAttribute('data-product-productName');
            var categoryName = button.getAttribute('data-product-categoryName');
            var price = button.getAttribute('data-product-price');
            
            // Logging purpose
            // console.log("Record to be deleted");
            // console.log("ProductName:", productName, " | CategoryName:", categoryName, " | Price:", price);
        
            // Set content to modal display
            document.getElementById('record-data-modal').innerHTML = `
                <p><span class="semi-bold">Order ID:</span> ${productName}</p>
                <p><span class="semi-bold">Customer ID:</span> ${categoryName}</p>
                <p><span class="semi-bold">First Name:</span> ${price}</p>
            `;

            // Display the modal
            document.getElementById('confirmModal').style.display = 'flex';

            // COnfirmed Delete Button in Modal
            var confirmedDeleteButtons = document.querySelectorAll('.confirmed-delete-button');
            confirmedDeleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('errorModal').style.display = 'none';
                    deleteProductCatalogRecord(productName, price);
                });
            });
        }
    });
});


// Form Modal Display
const formButtons = document.querySelectorAll('.form-button');
formButtons.forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        const clickedButton = event.target;
        // console.log('Clicked button: ', clickedButton.dataset.click);

        // Remove the search field value
        var inputs = document.querySelectorAll('.search-customer-id');
        inputs.forEach(function(input) {
            input.value = '';
        });

        if (clickedButton.dataset.click === 'add-customer') {
            document.getElementById('add-customer-first-name').value = '';
            document.getElementById('add-customer-last-name').value = '';
            document.getElementById('add-customer-email').value = '';
            document.getElementById('addCustomerModal').style.display = 'flex';
        } else if (clickedButton.dataset.click === 'edit-customer') {
            document.getElementById('edit-customer-first-name').value = '';
            document.getElementById('edit-customer-last-name').value = '';
            document.getElementById('edit-customer-email').value = '';
            document.getElementById('editCustomerModal').style.display = 'flex';
            document.getElementById('editCustomerDetailBackdrop').style.display = 'block';
        } else if (clickedButton.dataset.click === 'add-order') {
            document.getElementById('add-order-customer-name').value = '';
            document.getElementById('addCustomerOrderModal').style.display = 'flex';
            document.getElementById('addCustomerOrderBackdrop').style.display = 'block';
        } else if (clickedButton.dataset.click === 'edit-order') {
            document.getElementById('edit-order-customer-name').value = '';
            document.getElementById('edit-receipt-date').value = '';
            document.getElementById('edit-order-date').value = '';
            document.getElementById('edit-order-amount').value = '';
            document.getElementById('editCustomerOrderModal').style.display = 'flex';
            document.getElementById('editCustomerOrderBackdrop').style.display = 'block';
        } else if (clickedButton.dataset.click === 'add-category') {
            document.getElementById('add-category-name').value = '';
            document.getElementById('addCategoryModal').style.display = 'flex';
        } else if (clickedButton.dataset.click === 'edit-category') {
            document.getElementById('edit-category-name').value = '';
            document.getElementById('editCategoryModal').style.display = 'flex';
            document.getElementById('editCategoryBackdrop').style.display = 'block';
        } else if (clickedButton.dataset.click === 'add-product') {
            document.getElementById('add-product-name').value = '';
            document.getElementById('add-product-price').value = '';
            document.getElementById('addProductModal').style.display = 'flex';
        } else if (clickedButton.dataset.click === 'edit-product') {
            document.getElementById('editCategoryDropdown').value = '';
            document.getElementById('edit-product-name').value = '';
            document.getElementById('edit-product-price').value = '';
            document.getElementById('editProductModal').style.display = 'flex';
            document.getElementById('editProductBackdrop').style.display = 'block';
        }
        // Continue here ....
    });
});


// Form Buttons in Modal
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addCustomerDetailForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var firstName = document.getElementById('add-customer-first-name').value.trim();
        var lastName = document.getElementById('add-customer-last-name').value.trim();
        var email = document.getElementById('add-customer-email').value.trim();
        var password = document.getElementById('add-customer-password').value.trim();    

        // console.log('Customer Details to be added: First Name:', firstName, ' | Last Name:', lastName, ' | Email:', email);
    
        // Add the customer record on the database
        addCustomerDetails(firstName, lastName, email, password);
    });

    document.getElementById('editCustomerDetailForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var id = document.getElementById('edit-customer-details-id').value.trim();
        var firstName = document.getElementById('edit-customer-first-name').value.trim();
        var lastName = document.getElementById('edit-customer-last-name').value.trim();
        var email = document.getElementById('edit-customer-email').value.trim();
        var password = document.getElementById('edit-customer-password').value.trim();  

        // console.log('Updated Customer Details: ID: ', id, ' | First Name:', firstName, ' | Last Name:', lastName, ' | Email:', email);
    
        // Update the customer record on the database
        updateCustomerDetails(id, firstName, lastName, email, password);
    });

    document.getElementById('addCustomerOrderForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var customerId = document.getElementById('add-order-customer-id').value.trim();
        var customerName = document.getElementById('add-order-customer-name').value.trim();
        var today = new Date();
        var receiptDate = today.toISOString().slice(0, 10);    // Get the current date YYYY-MM-DD
        var orderDate = document.getElementById('add-order-date').value.trim();
        var totalAmount = document.getElementById('add-order-amount').value.trim();

        // console.log('Customer Order Details to be added: Customer ID:', customerId, ' | Customer Name:', customerName, ' | Order Date:', orderDate, ' | Total Amount:', totalAmount);
    
        // Add the order record on the database
        addCustomerOrder(customerId, receiptDate, orderDate, totalAmount);
    });

    document.getElementById('editCustomerOrderForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var orderId = document.getElementById('edit-order-customer-id').value.trim();
        var customerName = document.getElementById('edit-order-customer-name').value.trim();
        var receiptDate = document.getElementById('edit-receipt-date').value.trim();
        var orderDate = document.getElementById('edit-order-date').value.trim();
        var totalAmount = document.getElementById('edit-order-amount').value.trim();

        // console.log('Customer Order Details to be updated: Customer ID:', customerId, ' | Customer Name:', customerName, ' | Order Date:', orderDate, ' | Total Amount:', totalAmount);
        
        // Update the order record on the database
        updateCustomerOrder(orderId, receiptDate, orderDate, totalAmount);
    });

    document.getElementById('addCategoryForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var categoryName = document.getElementById('add-category-name').value.trim();

        // console.log('Category Name to be added: ', categoryName);

        // Add the category record on the database
        addCategory(categoryName);
    });

    document.getElementById('editCategoryForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var categoryId = document.getElementById('edit-category-id').value.trim().toUpperCase();
        var categoryName = document.getElementById('edit-category-name').value.trim();

        // console.log('Category Name to be updated: Category ID: ', categoryId, ' | Category Name: ', categoryName);

        // Update the category record on the database
        updateCategory(categoryId, categoryName);
    })

    document.getElementById('addProductForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var categoryId = document.getElementById('addCategoryDropdown').value.trim();
        var productName = document.getElementById('add-product-name').value.trim();
        var price = document.getElementById('add-product-price').value.trim();

        // console.log('Product Details to be added: Category ID:', categoryId, ' | Category Name:', productName, ' | Price:', price);
    
        // Add product record on the database
        addProduct(categoryId, productName, price);
    })

    document.getElementById('editProductForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var productId = document.getElementById('edit-product-id').value.trim().toUpperCase();
        var categoryId = document.getElementById('editCategoryDropdown').value.trim();
        var productName = document.getElementById('edit-product-name').value.trim();
        var price = document.getElementById('edit-product-price').value.trim();

        // console.log('Product Details to be updated: Product ID:', productId, ' | Category ID:', categoryId, ' | Category Name:', productName, ' | Price:', price);
        
        // Update product record on the database;\
        updateProduct(productId, categoryId, productName, price);
    })

    // Update Admin Password 
    document.getElementById('adminPersonalDetailsForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var oldPassword = document.getElementById('admin-update-old-password').value.trim();
        var newPassword = document.getElementById('admin-update-new-password').value.trim();
        var adminEmail = new URLSearchParams(window.location.search).get('email');
        
        updateAdminPassword(adminEmail, oldPassword, newPassword);
    });

    // Track the input field
    var inputs = document.querySelectorAll('.search-customer-id');
    inputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
            searchCustomerId(this.value.trim(), index);
        })
    });
    var select = this.documentElement.querySelector('#addCategoryDropdown');
    select.addEventListener('change', function() {
        searchCustomerId(this.value.trim(), 'addCategoryDropdown');
    })
    // Check password input
    checkPasswordInput();
});

// Cancel Form Buttons in Modal
var cancelButton = document.querySelectorAll('.cancel-button');
cancelButton.forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        
        const clickedButton = event.target;
        console.log('Clicked cancel button: ', clickedButton.dataset.action);

        if (clickedButton.dataset.action === 'cancel-add-customer-details') {
            document.getElementById('addCustomerModal').style.display = 'none';
        } else if (clickedButton.dataset.action === 'cancel-edit-customer-details') {
            document.getElementById('editCustomerModal').style.display = 'none';
        } else if (clickedButton.dataset.action === 'cancel-add-customer-order') {
            document.getElementById('addCustomerOrderModal').style.display = 'none';
        } else if (clickedButton.dataset.action === 'cancel-edit-customer-order') {
            document.getElementById('editCustomerOrderModal').style.display = 'none';
        } else if (clickedButton.dataset.action === 'cancel-add-category') {
            document.getElementById('addCategoryModal').style.display = 'none';
        } else if (clickedButton.dataset.action === 'cancel-edit-category') {
            document.getElementById('editCategoryModal').style.display = 'none';
        } else if (clickedButton.dataset.action === 'cancel-add-product') {
            document.getElementById('addProductModal').style.display = 'none';
            document.getElementById('addCategoryDropdown').value = '';
        } else if (clickedButton.dataset.action === 'cancel-edit-product') {
            document.getElementById('editProductModal').style.display = 'none';
        }
    });
});

// Cancel Delete Button in Modal
var cancelDeleteButtons = document.querySelectorAll('.cancel-delete-button');
cancelDeleteButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        document.getElementById('errorModal').style.display = 'none';
        document.getElementById('confirmModal').style.display = 'none';
    });
});

// Cancel Success Button in Modal
var cancelSuccessButton = document.getElementById('success-cancel-button');
cancelSuccessButton.addEventListener('click', function() {
    document.getElementById('successModal').style.display = 'none';
    location.reload();
    // Show sign in form
    document.getElementById('email-login').value = '';
    document.getElementById('password-login').value = '';
    document.getElementById('signup').style.display = 'none';
    document.getElementById('login').style.display = 'flex';
})

// Cancel Error Button in Modal
var cancelErrorButton = document.getElementById('error-cancel-button');
cancelErrorButton.addEventListener('click', function() {
    document.getElementById('errorModal').style.display = 'none';
    document.getElementById('error-text').innerText = '';
})


// Date format
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
document.getElementById('add-order-date').setAttribute('min', formattedToday);



// Check if password has value and set required attribute
function checkPasswordInput() {
    var oldPassword = document.getElementById('edit-customer-password');
    var newPassword = document.getElementById('edit-customer-password');

    function updatePasswordRequiredStatus() {
        var oldPassword = document.getElementById('edit-customer-password');
        var newPassword = document.getElementById('edit-customer-password');
    
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


function getEmailUrlParams() {
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get('email');

    $.ajax({
        type: 'POST',
        url: 'fetchRecord.php',
        data: {email: email, typeOfFetch: 'personalDetails'},
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success && data.admin) {
                document.getElementById('admin-profile-type').innerText = 'ADMIN';
            }
            else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = "Error Displaying Personal Info";
            }
        }
    })
}


document.addEventListener('DOMContentLoaded', function() {
    highlightPersonalDetailsLink();
    getEmailUrlParams();
});


