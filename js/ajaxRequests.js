// ACCOUNT DETAILS
function signUpAccount(firstName, lastName, email, password) {
    $.ajax({
        type: 'POST',
        url: 'addRecord.php',
        data: {firstName: firstName, lastName: lastName, email: email, password: password, typeOfFetch: 'signup'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
            }
            else {
                if (response.includes('Duplicate')) {
                    document.getElementById('errorModal').style.display = 'flex';
                    document.getElementById('error-text').innerText = "Email already taken";    
                } else {
                    document.getElementById('errorModal').style.display = 'flex';
                    document.getElementById('error-text').innerText = response;
                }
            }
        }
    })
}

function signInAccount(email, password) {
    $.ajax({
        type: 'POST',
        url: 'fetchRecord.php',
        data: {email: email, password: password, typeOfFetch: 'signin'},
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                if (data.admin){
                    location.href = `admin.php?email=${encodeURIComponent(data.email)}`;
                }
                else {
                    location.href = `user.php?email=${encodeURIComponent(data.email)}`;
                }
            }
            else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = "Invalid email or password";
            }
        }
    })
}


// CUSTOMER DETAIL RECORD DELETION
function deleteCustomerDetailRecord(email){
    // Create an object to store the record data
    var recordData = {
        email: email,
        typeOfDeletion: "customerDetail"
    };
    
    // Convert the object to a JSON string
    var recordDataJson = JSON.stringify(recordData);

    // AJAX request to server
    $.ajax({
        type: 'POST',
        url: 'deleteRecord.php',
        data: recordDataJson,
        success: function(response) {
            var data = JSON.parse(response);
            console.log(data);
            if (data.success) {
                document.getElementById('confirmModal').style.display = 'none';
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = data.message;
            } else {
                if (data.message.includes('foreign key constraint fails')) {
                    document.getElementById('confirmModal').style.display = 'none';
                    document.getElementById('errorModal').style.display = 'flex';
                    document.getElementById('error-text').innerText = "Cannot delete customer detail record because it is associated with a customer order record";
                }
            }
        }
    });
}


// CUSTOMER ORDER RECORD DELETION
function deleteCustomerOrderRecord(orderId, customerId, orderDate, totalAmount){
    // Create an object to store the record data
    var recordData = {
        orderId: orderId,
        customerId: customerId,
        orderDate: orderDate,
        totalAmount: totalAmount,
        typeOfDeletion: "customerOrder"
    };
    
    // Convert the object to a JSON string
    var recordDataJson = JSON.stringify(recordData);

    // AJAX request to server
    $.ajax({
        type: 'POST',
        url: 'deleteRecord.php',
        data: recordDataJson,
        success: function(response) {
            var data = JSON.parse(response);
            console.log(data);
            if (data.success) {
                document.getElementById('confirmModal').style.display = 'none';
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = data.message;
            } else {
                if (data.message.includes('foreign key constraint fails')) {
                    document.getElementById('confirmModal').style.display = 'none';
                    document.getElementById('errorModal').style.display = 'flex';
                    document.getElementById('error-text').innerText = "Cannot delete customer detail record because it is associated with a customer order record";
                }
            }
        }
    });
}


// PRODUCT CATALOG RECORD DELETION
function deleteProductCatalogRecord(productName, price){
    // Create an object to store the record data
    var recordData = {
        productName: productName,
        price: price,
        typeOfDeletion: "productCatalog"
    };
    
    // Convert the object to a JSON string
    var recordDataJson = JSON.stringify(recordData);

    // AJAX request to server
    $.ajax({
        type: 'POST',
        url: 'deleteRecord.php',
        data: recordDataJson,
        success: function(response) {
            var data = JSON.parse(response);
            console.log(data);
            if (data.success) {
                document.getElementById('confirmModal').style.display = 'none';
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = data.message;
            } else {
                if (data.message.includes('foreign key constraint fails')) {
                    document.getElementById('confirmModal').style.display = 'none';
                    document.getElementById('errorModal').style.display = 'flex';
                    document.getElementById('error-text').innerText = "Cannot delete customer detail record because it is associated with a customer order record";
                }
            }
        }
    });
}


// FORMS AJAX REQUEST
function searchCustomerId(id, index) {
    $.ajax({
        type: 'POST',
        url: 'fetchRecord.php',
        data: {id: id, index: index, typeOfFetch: 'searchCustomerId'},
        success: function(response) {
            if (response != 'none') {
                var data = JSON.parse(response);
                var index = data.index;
                var customerDetails = data.customerDetails;

                // Edit Customer Details Values
                if (index == 0) {
                    document.getElementById('editCustomerDetailBackdrop').style.display = 'none';
                    console.log("Searched Customer Details: ", customerDetails);
                    document.getElementById('edit-customer-first-name').value = customerDetails.FirstName;
                    document.getElementById('edit-customer-last-name').value = customerDetails.LastName;
                    document.getElementById('edit-customer-email').value = customerDetails.Email;    
                }
                // Add Customer Order Values
                else if (index == 1) {
                    document.getElementById('addCustomerOrderBackdrop').style.display = 'none';
                    document.getElementById('add-order-customer-name').value = customerDetails.FirstName + " " + customerDetails.LastName;
                }
                // Edit Customer Order Values   
                else if (index == 2) {
                    document.getElementById('editCustomerOrderBackdrop').style.display = 'none';
                    document.getElementById('edit-order-customer-name').value = customerDetails.FirstName + " " + customerDetails.LastName;
                    var formattedReceiptDate = formatDate(customerDetails.ReceiptDate);
                    var formattedOrderDate = formatDate(customerDetails.OrderDate);
                    document.getElementById('edit-receipt-date').value = formattedReceiptDate;
                    document.getElementById('edit-order-date').value = formattedOrderDate;
                    document.getElementById('edit-order-amount').value = customerDetails.TotalAmount;
                }
                // Edit Category Name
                else if (index == 3) {
                    document.getElementById('editCategoryBackdrop').style.display = 'none';
                    document.getElementById('edit-category-name').value = customerDetails.CategoryName;
                }
                // Add Product Catalog
                else if (index == 'addCategoryDropdown') {
                    document.getElementById('addProductBackdrop').style.display = 'none';
                }
                // Edit Product Catalog
                else if (index == 4) {
                    document.getElementById('editCategoryDropdown').value = customerDetails.CategoryID;
                    document.getElementById('edit-product-name').value = customerDetails.ProductName;
                    document.getElementById('edit-product-price').value = customerDetails.Price;
                    document.getElementById('editProductBackdrop').style.display = 'none';
                }
            }
            else {
                // Clear the input fields
                document.getElementById('edit-customer-first-name').value = '';
                document.getElementById('edit-customer-last-name').value = '';
                document.getElementById('edit-customer-email').value = '';
                document.getElementById('add-order-customer-name').value = '';
                document.getElementById('edit-order-customer-name').value = '';
                document.getElementById('edit-order-date').value = '';
                document.getElementById('edit-order-amount').value = '';
                document.getElementById('edit-category-name').value = '';
                document.getElementById('editCategoryDropdown').value = '';
                document.getElementById('edit-product-name').value = '';
                document.getElementById('edit-product-price').value = '';
                // Place the overlay backdrop on the screen
                document.getElementById('editCustomerDetailBackdrop').style.display = 'block';
                document.getElementById('addCustomerOrderBackdrop').style.display = 'block';
                document.getElementById('editCustomerOrderBackdrop').style.display = 'block';
                document.getElementById('editCategoryBackdrop').style.display = 'block';
                document.getElementById('editProductBackdrop').style.display = 'block';
            }
        }
    })
}


// CUSTOMER DETAILS RECORD ADDITION
function addCustomerDetails(firstName, lastName, email, password) {
    $.ajax({
        type: 'POST',
        url: 'addRecord.php',
        data: {firstName: firstName, lastName: lastName, email: email, password: password, typeOfFetch: 'addCustomerDetails'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
                document.getElementById('addCustomerModal').style.display = 'none';
            } else if (response.includes('No data')){
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else if (response.includes('Duplicate')) {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = "Email already taken";
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            }  
        }
    });
}


// CUSTOMER DETAILS RECORD UPDATE
function updateCustomerDetails(id, firstName, lastName, email, password) {
    $.ajax({
        type: 'POST',
        url: 'updateRecord.php',
        data: {id: id, firstName: firstName, lastName: lastName, email: email, password: password, typeOfFetch: 'updateCustomerDetails'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
                document.getElementById('editCustomerModal').style.display = 'none';
                document.getElementById('editCustomerDetailBackdrop').style.display = 'none';
            } else if (response.includes('Same')) {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            }  
        }
    });
}


// CUSTOMER ORDER RECORD ADDITION
function addCustomerOrder(id, orderList, orderDate, receiptDate, totalAmount) {
    $.ajax({
        type: 'POST',
        url: 'addRecord.php',
        data: {id: id, orderList: orderList, receiptDate: receiptDate, orderDate: orderDate, totalAmount: totalAmount, typeOfFetch: 'addCustomerOrder'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
                document.getElementById('addCustomerOrderModal').style.display = 'none';
                document.getElementById('addCustomerOrderBackdrop').style.display = 'none';
            } else if (response.includes('No data')) {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            }   
        }
    });
}

// CUSTOMER ORDER RECORD UPDATE
function updateCustomerOrder(orderId, receiptDate, orderDate, totalAmount) {
    $.ajax({
        type: 'POST',
        url: "updateRecord.php",
        data: {id: orderId, receiptDate: receiptDate, orderDate: orderDate, totalAmount: totalAmount, typeOfFetch: 'updateCustomerOrder'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
                document.getElementById('editCustomerOrderModal').style.display = 'none';
                document.getElementById('editCustomerOrderBackdrop').style.display = 'none';
            } else if (response.includes('Same')){
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            }   
        }
    })
}

// CATEGORY RECORD ADDITION
function addCategory(categoryName) {
    $.ajax({
        type: 'POST',
        url: 'addRecord.php',
        data: {categoryName: categoryName, typeOfFetch: 'addCategory'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
                document.getElementById('addCategoryModal').style.display = 'none';
            } else if (response.includes('No data')){
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            }  
        }
    })
}

// CATEGORY RECORD UPDATE
function updateCategory(id, categoryName) {
    $.ajax({
        type: 'POST',
        url: 'updateRecord.php',
        data: {id: id, categoryName: categoryName, typeOfFetch: 'updateCategory'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
                document.getElementById('editCategoryModal').style.display = 'none';
                document.getElementById('editCategoryBackdrop').style.display = 'none';
            } else if (response.includes('Same')){
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            }  
        }
    })
}

// PRODUCT RECORD ADDITION
function addProduct(id, productName, price) {
    $.ajax({
        type: 'POST',
        url: 'addRecord.php',
        data: {id: id, productName: productName, price: price, typeOfFetch: 'addProduct'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
                document.getElementById('addProductModal').style.display = 'none';
                document.getElementById('addProductBackdrop').style.display = 'none';
            } else if (response.includes('No data')){
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            }  
        }
    })
}

// PRODUCT RECORD UPDATE
function updateProduct(productId, categoryId, productName, price) {
    $.ajax({
        type: 'POST',
        url: 'updateRecord.php',
        data: {productId: productId, categoryId: categoryId, productName: productName, price: price, typeOfFetch: 'updateProduct'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
                document.getElementById('editProductModal').style.display = 'none';
                document.getElementById('editProductBackdrop').style.display = 'none';
            } else if (response.includes('Same')){
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            }  
        }
    })
}


// UPDATE USER PERSONAL DETAILS
function updateUserPersonalDetails(firstName, lastName, oldEmail, newEmail, oldPassword, newPassword) {
    $.ajax({
        type: 'POST',
        url: 'updateRecord.php',
        data: {firstName: firstName, lastName: lastName, oldEmail: oldEmail, newEmail: newEmail, oldPassword: oldPassword, newPassword: newPassword, typeOfFetch: 'updateUserPersonalDetails'},
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = 'User personal details updated successfully';
                // Change the email in the URL
                var url = new URL(window.location.href);
                url.searchParams.set('email', data.newEmail);
                window.history.replaceState({}, '', url);
            } else {
                if (data.oldPassword) {
                    document.getElementById('errorModal').style.display = 'flex';
                    document.getElementById('error-text').innerText = 'Error updating user personal details';
                } else if (data.passwordEqual) {
                    document.getElementById('errorModal').style.display = 'flex';
                    document.getElementById('error-text').innerText = 'New password must differ';
                } else {
                    document.getElementById('errorModal').style.display = 'flex';
                    document.getElementById('error-text').innerText = 'Invalid or Incorrect Old Password';        
                }
            } 
        }
    });
}


// ADD USER ORDER 
function userAddOrder(email, receiptDate, orderDate, amount) {
    $.ajax({
        type: 'POST',
        url: 'addRecord.php',
        data: {email: email, receiptDate: receiptDate, orderDate: orderDate, amount: amount, typeOfFetch: 'userAddOrder'},
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                var shippingFee = document.getElementById('user-shipping').innerText;

                document.getElementById('user-add-order-date').value = '';
                document.getElementById('user-add-order-amount').value = '';

                // Apply user details and order details to receipt
                data['email'] = email;
                data['amount'] = Number(amount).toFixed(2);
                data['shippingFee'] = shippingFee;
                localStorage.setItem('userReceipt', JSON.stringify(data));
                location.reload();
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = data.message;
            }
        }
    })
}


window.onload = function() {
    var data = JSON.parse(localStorage.getItem('userReceipt'));
    var today = new Date();
    var formattedDate = today.toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: '2-digit'});

    if (data) {
        document.getElementById('user-receipt-date').innerText = formattedDate;
        document.getElementById('user-receipt-order-id').innerText = data.orderId;
        document.getElementById('user-receipt-name').innerText = data.name;
        document.getElementById('user-receipt-email').innerText = data.email;
        document.getElementById('user-receipt-amount').innerText = data.amount;
        document.getElementById('user-receipt-total').innerText = (Number(data.amount) + Number(data.shippingFee)).toFixed(2);
        
        // Display the receipt modal
        document.getElementById('receiptSuccessModal').style.display = 'flex';
        document.getElementById('receipt-success-text').innerText = data.message;

        localStorage.removeItem('userReceipt');
    }
}


// SHOW USER ORDER HISTORY
function userDisplayOrder(email) {
    $.ajax({
        type: 'POST',
        url: 'fetchRecord.php',
        data: {email: email, typeOfFetch: 'showUserOrderHistory'},
        success: function(response) {
            if (response.includes('Error')) {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else {
                $('#userOrderHistoryTableBody').html(response);
            }
        }
    });
}

// UPDATE ADMIN PASSWORD
function updateAdminPassword(email, oldPassword, newPassword) {
    $.ajax({
        type: 'POST',
        url: 'updateRecord.php',
        data: {email: email, oldPassword: oldPassword, newPassword: newPassword, typeOfFetch: 'updateAdminPassword'},
        success: function(response) {
            if (response.includes('successfully')) {
                document.getElementById('successModal').style.display = 'flex';
                document.getElementById('success-text').innerText = response;
            } else if (response.includes('Invalid old password')) {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else if (response.includes('differ')) {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            } else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = response;
            }
        }
    })
}


var totalAmount = 0;
// GET TOTAL AMOUNT OF ORDER
function getTotalAmountOrder(productData) {
    var requests = []; // Array to hold all AJAX requests
    totalAmount = 0;

    for (let i = 0; i < Object.keys(productData).length; i++) {
        var request = $.ajax({
            type: 'POST',
            url: 'fetchRecord.php',
            data: {productId: productData[i].productId, typeOfFetch: 'getProductPrice'},
            success: function(response) {
                if (!(response.includes('Error'))) {
                    if (!isNaN(productData[i].quantity)) {
                        totalAmount += (Number(response) * productData[i].quantity);
                    }
                }
            }
        });

        requests.push(request); // Add the AJAX request to the array
    }

    // Wait for all AJAX requests to complete
    Promise.all(requests).then(function() {
        document.getElementById('add-order-price').value = totalAmount.toFixed(2);
    });
}


function getTotalOrderAmount() {
    return totalAmount.toFixed(2);
}