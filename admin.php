<?php
include 'db_connect.php';
include 'retrieve_record.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/formStyles.css">
    <link rel="stylesheet" href="css/tableStyles.css">
    <link rel="stylesheet" href="css/modalStyles.css">
    <link rel="stylesheet" href="css/userStyles.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
    </style>
</head>
<body>
    <header>
        <nav class="ht-max">
            <ul class="ht-max">
                <div class="list-container ht-max">
                    <div class="profile-div">
                        <li id="admin-nav-item-1" class="nav-item "><a href=""><img src='./assets/user/profile-icon.svg' class='svg-icon'></a></li>
                    </div>
                    <div>
                        <li id="admin-nav-item-2" class="nav-item "><a href="#">Customer Form</a></li>
                        <li id="admin-nav-item-3" class="nav-item "><a href="#">Customer Details Report</a></li>
                        <li id="admin-nav-item-4" class="nav-item "><a href="#">Customer Order Report</a></li>
                        <li id="admin-nav-item-5" class="nav-item "><a href="#">Product Catalog Report</a></li>
                    </div>
                </div>
            </ul>
        </nav>
    </header>
    <main>
        <!-- User Profile -->
        <div id="adminProfileContainer" class="container pd-main">
            <div class='profile-icon mg-bottom-40'>
                <img src='./assets/user/profile-details-icon.svg'>
            </div>
            <div class="profile-details mg-bottom-60">
                <p id="admin-profile-type" style="margin-top: -20px;"><p>    
            </div>
            <div class="details-link-container">
                <a id="admin-details-link-1" class='details-link '>Settings</a>
                <!-- <a id="admin-details-link-2" class='details-link '></a> -->
            </div>
            <button id="admin-logout-button" class='semi-bold border-rad-form' data-action='log-out'>Log out</button>
        </div>

        <div id="adminPersonalDetailsContainer" class="container pd-main">
        <div class="form-container">
                <form id="adminPersonalDetailsForm" method="post" action=""> 
                    <div class="block-label-input mg-bottom-40 text-left">
                        <p class="header-details mg-bottom-10">Change Password</p>
                        <div class="input-container">
                            <input type="password" id="admin-update-old-password" class="box-styled-input" placeholder="Enter Old Password" required>             
                            <img src="./assets/user/password-icon.svg" alt="password-input-icon">
                        </div>
                        <div class="input-container">
                            <input type="password" id="admin-update-new-password" class="box-styled-input" placeholder="Enter New Password" required>             
                            <img src="./assets/user/password-icon.svg" alt="password-input-icon">
                        </div>
                    </div>   
                    <button id="admin-update-personal-details-button" type="submit" class="semi-bold border-rad-filter" data-action="sign-in">Save</button>
                </form>
            </div>
        </div>

        <!-- Backdrop -->
        <div id="profileDisplayBackDrop" class="modal" style="z-index: 2;"></div>

        <!-- FORMS -->
        <div id="inputForm" class="container pd-main">
            <div class="choice-interface corner-smooth border-rad-form">
                <img src="./assets/form-option/customer-icon.svg">
                <button class="form-button semi-bold corner-smooth border-rad-filter" data-click="add-customer">Add Customer</button>
                <button class="form-button semi-bold corner-smooth border-rad-filter" data-click="edit-customer">Edit</button>
            </div>
            <div class="choice-interface corner-smooth border-rad-form">
                <img src="./assets/form-option/order-icon.svg">
                <button class="form-button semi-bold corner-smooth border-rad-filter" data-click="add-order">Add Order</button>
                <button class="form-button semi-bold corner-smooth border-rad-filter" data-click="edit-order">Edit</button>
            </div>
            <div class="choice-interface corner-smooth border-rad-form">
                <img src="./assets/form-option/category-icon.svg">
                <button class="form-button semi-bold corner-smooth border-rad-filter" data-click="add-category">Add Category</button>
                <button class="form-button semi-bold corner-smooth border-rad-filter" data-click="edit-category">Edit</button>
            </div>
            <div class="choice-interface corner-smooth border-rad-form">
                <img src="./assets/form-option/catalog-icon.svg">
                <button class="form-button semi-bold corner-smooth border-rad-filter" data-click="add-product">Add Product</button>
                <button class="form-button semi-bold corner-smooth border-rad-filter" data-click="edit-product">Edit</button>
            </div>
        </div>

        <!-- CUSTOMER DETAILS REPORT -->
        <div id="customerDetailReport" class="container pd-main">
            <section class="filter-container">
                <div class="ht-max border-rad">
                    <form id="customerDetailForm" class="ht-max" method="post" action="">
                        <!-- SORT BY DROPDOWN -->
                        <select id="sortByCustomerDetails" class="select-filter ht-max pd-filter border-rad-filter corner-smooth mg-right" name="sortBy">
                            <option value="" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == '') echo 'selected'; ?>>Sort By</option>
                            <option value="CustomerID" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'CustomerID') echo 'selected'; ?>>Customer ID</option>
                            <option value="FirstName" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'FirstName') echo 'selected'; ?>>First Name</option>
                            <option value="LastName" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'LastName') echo 'selected'; ?>>Last Name</option>
                            <option value="Email" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'Email') echo 'selected'; ?>>Email</option>
                        </select>
                        <div class='dropdown-icon ht-max pd-filter border-rad-filter' style="right: 71%;"></div>
                        <!-- SEARCH FILTER -->
                        <input id="searchFilterCustomerDetails" class="search-filter ht-max pd-filter border-rad-filter corner-smooth" type="text" name="searchFilterCustomerDetails" placeholder="Search here..." value="<?php echo isset($_POST['searchFilterCustomerDetails']) ? $_POST['searchFilterCustomerDetails'] : ''?>">
                        <!-- SEARCH LOGO -->
                        <div class="search-logo ht-max pd-filter border-rad-filter" name="search" value="">
                    </form>
                </div>
            </section>
            <section class="report-container corner-smooth border-rad-form">
                <table id="customerDetailTable" class="scroll-bar">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="customerDetailBody">
                        <!-- Display table record dynamically -->
                    </tbody>        
                </table>
            </section>  
        </div>

        <!-- CUSTOMER ORDERS REPORT -->
        <div id="customerOrderReport" class="container pd-main"> 
            <section class="filter-container">
                <div class="ht-max border-rad">
                    <form id="customerOrderForm" class="ht-max" method="post" action="">
                        <!-- CUSTOMER ID DROPDOWN -->
                        <select id="dropdownCustomerId" class="select-filter ht-max pd-filter border-rad-filter mg-right corner-smooth" name="customerId" style="width: 170px;">
                            <option value="" <?php if (isset($_POST['customerId']) && $_POST['customerId'] == '') echo 'selected'; ?>>Customer ID</option>
                            <?php 
                                $sql = "SELECT DISTINCT CustomerID FROM `customer` ORDER BY CustomerID ASC";
                                $result_product_name = $conn->query($sql);
                                if ($result_product_name->num_rows > 0) {
                                    while ($row = $result_product_name->fetch_assoc()) {
                                        $selected = (isset($_POST['customerId']) && $_POST['customerId'] == $row['CustomerID']) ? 'selected' : '';
                                        echo "<option value='".$row['CustomerID']."' $selected>" . $row['CustomerID'] . "</option>";
                                    }
                                }
                            ?>     
                        </select>
                        <div id="customerIdDropdown" class='dropdown-icon ht-max pd-filter border-rad-filter'></div>
                        <!-- ORDER DATE FILTER -->
                        <input id="orderDateDropdown" type="date" class="ht-max select-filter pd-filter border-rad-filter corner-smooth mg-right" name="orderDate" style='width: 180px;' value="<?php echo isset($_POST['orderDate']) ? $_POST['orderDate'] : '' ?>">
                        <!-- SORT BY DROPDOWN -->
                        <select id="sortByCustomerOrder" class="select-filter ht-max pd-filter border-rad-filter corner-smooth mg-right" name="sortBy">
                            <option value="" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == '') echo 'selected'; ?>>Sort By</option>
                            <option value="OrderID" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'OrderID') echo 'selected'; ?>>Order ID</option>
                            <option value="CustomerID" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'CustomerID') echo 'selected'; ?>>Customer ID</option>
                            <option value="CustomerName" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'CustomerName') echo 'selected'; ?>>Customer Name</option>
                            <option value="OrderDate" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'OrderDate') echo 'selected'; ?>>Order Date</option>
                            <option value="ReceiptDate" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'ReceiptDate') echo 'selected'; ?>>Receipt Date</option>
                            <option value="TotalAmount" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'TotalAmount') echo 'selected'; ?>>Amount</option>    
                        </select>
                        <div id="orderIdDropdown" class='dropdown-icon ht-max pd-filter border-rad-filter'></div>
                        <!-- SEARCH FILTER -->
                        <input id="searchFilterCustomerOrder" class="search-filter ht-max pd-filter border-rad-filter corner-smooth" type="text" name="searchFilterCustomerOrder" placeholder="Search here..." value="<?php echo isset($_POST['searchFilterCustomerOrder']) ? $_POST['searchFilterCustomerOrder'] : ''?>">
                        <!-- SEARCH LOGO -->
                        <div class="search-logo ht-max pd-filter border-rad-filter" name="search" value="" style="left: 55.95%;">
                    </form>
                </div>
            </section>
            <section class="report-container corner-smooth border-rad-form">
                <table id="customerOrderTable" class="scroll-bar">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
                            <th>Order Date</th>
                            <th>Receipt Date</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="customerOrderBody">
                        <!-- Display table record dynamically -->
                    </tbody>
                </table>
            </section>
        </div>

        <!-- PRODUCT CATALOG REPORT -->
        <div id="productCatalogReport" class="container pd-main"> 
            <section class="filter-container">
                <div class="ht-max border-rad">
                    <form id="productCatalogForm" class="ht-max" method="post" action="">
                        <!-- PRODUCT NAME DROPDOWN -->
                        <select id="sortByProductCatalog" class="select-filter ht-max pd-filter border-rad-filter mg-right corner-smooth" name="sortByProductCatalog" style="width: 180px;">
                            <option value="" <?php if (isset($_POST['sortByProductCatalog']) && $_POST['sortByProductCatalog'] == '') echo 'selected'; ?>>Sort By</option>
                            <option value="ProductName" <?php if (isset($_POST['sortByProductCatalog']) && $_POST['sortByProductCatalog'] == 'ProductName') echo 'selected'; ?>>Product Name</option>
                            <option value="CategoryName" <?php if (isset($_POST['sortByProductCatalog']) && $_POST['sortByProductCatalog'] == 'CategoryName') echo 'selected'; ?>>Category Name</option>
                            <option value="Price" <?php if (isset($_POST['sortByProductCatalog']) && $_POST['sortByProductCatalog'] == 'Price') echo 'selected'; ?>>Price</option>
                        </select>
                        <div id="productNameDropdown" class='dropdown-icon ht-max pd-filter border-rad-filter'></div>
                        <!-- CATEGORY NAME DROPDOWN -->
                        <select id="categoryNameDropdown" class="select-filter ht-max pd-filter border-rad-filter mg-right corner-smooth" name="categoryName" style="width: 190px;">
                            <option value="" <?php if (isset($_POST['categoryName']) && $_POST['categoryName'] == '') echo 'selected'; ?>>Category Name</option>
                            <?php 
                                $sql = 'SELECT DISTINCT CategoryName FROM `category` ORDER BY CategoryName ASC';
                                $result_product_name = $conn->query($sql);
                                if ($result_product_name->num_rows > 0) {
                                    while ($row = $result_product_name->fetch_assoc()) {
                                        $selected = (isset($_POST['categoryName']) && $_POST['categoryName'] == $row['CategoryName']) ? 'selected' : '';
                                        echo "<option value='".$row['CategoryName']."' $selected>" . $row['CategoryName'] . "</option>";
                                    }
                                }
                            ?>    
                        </select>
                        <div id="categoryNameDropdown" class='dropdown-icon ht-max pd-filter border-rad-filter'></div>
                        <!-- SEARCH FILTER -->
                        <input id="searchFilterProductCatalog" class="search-filter ht-max pd-filter border-rad-filter corner-smooth" type="text" name="searchFilterProductCatalog" placeholder="Search here..." value="<?php echo isset($_POST['searchFilterProductCatalog']) ? $_POST['searchFilterProductCatalog'] : ''?>">
                        <!-- SEARCH LOGO -->
                        <div class="search-logo ht-max pd-filter border-rad-filter" name="search" value="" style="left: 46.9%;">
                    </form>
                </div>
            </section>
            <section class="report-container corner-smooth border-rad-form">
                <table id="productCatalogTable" class="scroll-bar">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="productCatalogBody"> 
                        <!-- Display table record dynamically -->
                    </tbody>
                </table>
            </section>
        </div>

        <!-- FORM INPUT MODAL -->
        <!-- Add Customer Details -->
        <div id="addCustomerModal" class="modal">
            <div class="form-modal-container">
                <img src="./assets/add-form/customer-add-icon.svg" alt="customer icon" class="customer-icon">
                
                <form id="addCustomerDetailForm">
                    <div class="block-label-input mg-bottom-40">
                        <label for="add-customer-first-name">First Name</label> 
                        <input type="text" id="add-customer-first-name" class="underline-input" pattern="^[a-zA-Z\s]*$" required>        
                    </div>    
                    <div class="block-label-input mg-bottom-40">
                        <label for="add-customer-last-name">Last Name</label> 
                        <input type="text" id="add-customer-last-name" class="underline-input" pattern="^[a-zA-Z\s]*$" required>        
                    </div>   
                    <div class="block-label-input mg-bottom-40">
                        <label for="add-customer-email">Email</label> 
                        <input type="text" id="add-customer-email" class="underline-input" required>        
                    </div>     
                    <div class="block-label-input mg-bottom-40">
                        <label for="add-customer-password">Password</label> 
                        <input type="password" id="add-customer-password" class="underline-input" required>        
                    </div> 

                    <button type="submit" class="confirm-button modal-button semi-bold corner-smooth border-rad-filter" data-action="add-customer-details">Add Customer</button>
                    <button type="button" class="cancel-button modal-button mg-bottom-20 semi-bold corner-smooth border-rad-filter" data-action="cancel-add-customer-details">Cancel</button>
                </form>
            </div>                      
        </div>

        <!-- Edit Customer Details -->
        <div id="editCustomerModal" class="modal">
            <div class="form-modal-container">
                <img src="./assets/add-form/customer-edit-icon.svg" alt="customer icon" class="customer-icon">
                
                <form>
                    <div class="search-area"> 
                        <label>
                            Search Customer ID:
                            <input id="edit-customer-details-id" type="text" class="search-customer-id underline-input" pattern="^\d+$" required>
                        </label>
                    </div>
                </form> 
                <form id="editCustomerDetailForm">
                    <div id="editCustomerDetailBackdrop" class="backdrop" style="height: 415px; top: 59%;"></div>
                    <div class="block-label-input mg-bottom-40">
                        <label for="edit-customer-first-name">First Name</label> 
                        <input type="text" id="edit-customer-first-name" class="underline-input" pattern="^[a-zA-Z\s]*$" required>        
                    </div>    
                    <div class="block-label-input mg-bottom-40">
                        <label for="edit-customer-last-name">Last Name</label> 
                        <input type="text" id="edit-customer-last-name" class="underline-input" pattern="^[a-zA-Z\s]*$" required>        
                    </div>   
                    <div class="block-label-input mg-bottom-40">
                        <label for="edit-customer-email">Email</label> 
                        <input type="text" id="edit-customer-email" class="underline-input" required>        
                    </div>     
                    <div class="block-label-input mg-bottom-40">
                        <label for="edit-customer-password">New Password</label> 
                        <input type="password" id="edit-customer-password" class="underline-input" placeholder="(Optional)" minlength="8">        
                    </div> 

                    <button type="submit" class="confirm-button modal-button semi-bold corner-smooth border-rad-filter" data-action="edit-customer-details">Save</button>
                    <button class="cancel-button modal-button mg-bottom-20 semi-bold corner-smooth border-rad-filter" data-action="cancel-edit-customer-details">Cancel</button>
                </form>
            </div>                      
        </div>

        <!-- Add Customer Order -->
        <div id="addCustomerOrderModal" class="modal">
            <div class="form-modal-container">
                <img src="./assets/add-form/order-add-icon.svg" alt="customer icon" class="customer-icon">
                
                <form>
                    <div class="search-area"> 
                        <label>
                            Search Customer ID:
                            <input id="add-order-customer-id" type="text" class="search-customer-id underline-input" pattern="^\d+$" required>
                        </label>
                    </div>
                </form> 
                <form id="addCustomerOrderForm">
                    <div id="addCustomerOrderBackdrop" class="backdrop" style="height: 370px;"></div>
                    <div class="block-label-input mg-bottom-60">
                        <label for="add-order-customer-name">Customer Name</label> 
                        <input type="text" id="add-order-customer-name" class="underline-input" readonly>        
                    </div>    
                    <div class="block-label-input mg-bottom-60">
                        <label for="add-order-date">Order Date</label> 
                        <input type="date" id="add-order-date" class="underline-input" style="padding-right: 285px;" required>        
                    </div>   
                    <div class="block-label-input mg-bottom-40">
                        <label for="add-order-amount">Amount</label> 
                        <input type="text" id="add-order-amount" class="underline-input" pattern="^\d*(\.\d{0,2})?$" required>        
                    </div>     
                    <button type="submit" class="confirm-button modal-button semi-bold corner-smooth border-rad-filter" data-action="add-customer-order">Add Order</button>
                    <button class="cancel-button modal-button mg-bottom-20 semi-bold corner-smooth border-rad-filter" data-action="cancel-add-customer-order">Cancel</button>
                </form>
            </div>                      
        </div>

        <!-- Edit Customer Order -->
        <div id="editCustomerOrderModal" class="modal">
            <div class="form-modal-container">
                <img src="./assets/add-form/order-add-icon.svg" alt="customer icon" class="customer-icon">
                
                <form>
                    <div class="search-area"> 
                        <label>
                            Search Order ID:
                            <input id="edit-order-customer-id" type="text" class="search-customer-id underline-input" pattern="^\d+$" required>
                        </label>
                    </div>
                </form> 
                <form id="editCustomerOrderForm">
                    <div id="editCustomerOrderBackdrop" class="backdrop" style="height: 380px;"></div>
                    <div class="block-label-input mg-bottom-30">
                        <label for="edit-order-customer-name">Customer Name</label> 
                        <input type="text" id="edit-order-customer-name" class="underline-input" readonly>        
                    </div>    
                    <div class="block-label-input mg-bottom-30">
                        <label for="edit-receipt-date">Receipt Date</label> 
                        <input type="date" id="edit-receipt-date" class="underline-input" style="padding-right: 285px;" pattern="^\d{4}-\d{2}-\d{2}$" required>        
                    </div>   
                    <div class="block-label-input mg-bottom-30">
                        <label for="edit-order-date">Order Date</label> 
                        <input type="date" id="edit-order-date" class="underline-input" style="padding-right: 285px;" pattern="^\d{4}-\d{2}-\d{2}$" required>        
                    </div>   
                    <div class="block-label-input mg-bottom-30">
                        <label for="edit-order-amount">Amount</label> 
                        <input type="text" id="edit-order-amount" class="underline-input" pattern="^\d*(\.\d{0,2})?$" required>        
                    </div>     
                    <button type="submit" class="confirm-button modal-button semi-bold corner-smooth border-rad-filter" data-action="edit-customer-order">Save</button>
                    <button class="cancel-button modal-button mg-bottom-20 semi-bold corner-smooth border-rad-filter" data-action="cancel-edit-customer-order">Cancel</button>
                </form>
            </div>                      
        </div>

        <!-- Add Category -->
        <div id="addCategoryModal" class="modal">
            <div class="form-modal-container">
                <img src="./assets/add-form/category-add-icon.svg" alt="customer icon" class="customer-icon">
                
                <form id="addCategoryForm">
                    <div class="block-label-input mg-bottom-60">
                        <label for="add-category-name">Category Name</label> 
                        <input type="text" id="add-category-name" class="underline-input" pattern="^[^\d]*$" required>        
                    </div>       

                    <button type="submit" class="confirm-button modal-button semi-bold corner-smooth border-rad-filter" data-action="add-category">Add Category</button>
                    <button type="button" class="cancel-button modal-button mg-bottom-20 semi-bold corner-smooth border-rad-filter" data-action="cancel-add-category">Cancel</button>
                </form>
            </div>                      
        </div>

        <!-- Edit Category Name -->
        <div id="editCategoryModal" class="modal">
            <div class="form-modal-container">
                <img src="./assets/add-form/category-add-icon.svg" alt="customer icon" class="customer-icon">
                
                <form>
                    <div class="search-area"> 
                        <label>
                            Search Category ID:
                            <input id="edit-category-id" type="text" class="search-customer-id underline-input" required>
                        </label>
                    </div>
                </form> 
                <form id="editCategoryForm">
                    <div id="editCategoryBackdrop" class="backdrop" style="height: 170px;"></div>
                    <div class="block-label-input mg-bottom-60">
                        <label for="edit-category-name">Category Name</label> 
                        <input type="text" id="edit-category-name" class="underline-input" pattern="^[^\d]*$" required>        
                    </div>    

                    <button type="submit" class="confirm-button modal-button semi-bold corner-smooth border-rad-filter" data-action="edit-category">Save</button>
                    <button class="cancel-button modal-button mg-bottom-20 semi-bold corner-smooth border-rad-filter" data-action="cancel-edit-category">Cancel</button>
                </form>
            </div>                      
        </div>

        <!-- Add Product -->
        <div id="addProductModal" class="modal">
            <div class="form-modal-container">
                <img src="./assets/add-form/catalog-add-icon.svg" alt="customer icon" class="customer-icon">
                
                <form id="addProductForm">
                    <div class="block-label-input mg-bottom-40">
                        <select id="addCategoryDropdown" class="select-filter ht-max pd-filter border-rad-filter corner-smooth mg-right" name="addCategoryDropdown" style="width: 250px;" required>
                            <option value='' selected disabled>Category Name</option>
                            <?php
                                $sql = "SELECT * FROM category ORDER BY CategoryName ASC";
                                $result_category = $conn->query($sql);
                                if ($result_category->num_rows > 0) {
                                    while($row = $result_category->fetch_assoc()) {
                                        echo "<option value='".$row['CategoryID']."'>" . $row['CategoryName'] . "</option>";
                                    }
                                }
                            ?>
                        </select>    
                    </div>
                    <div id="addProductBackdrop" class="backdrop" style="height: 265px;"></div>
                                
                    <div class="block-label-input mg-bottom-60">
                        <label for="add-product-name">Product Name</label> 
                        <input type="text" id="add-product-name" class="underline-input" required>        
                    </div>    
                    
                    <div class="block-label-input mg-bottom-40">
                        <label for="add-product-price">Price</label> 
                        <input type="text" id="add-product-price" class="underline-input" pattern="^\d*(\.\d+)?$" required>        
                    </div>     

                    <button type="submit" class="confirm-button modal-button semi-bold corner-smooth border-rad-filter" data-action="add-product">Add Product</button>
                    <button type="button" class="cancel-button modal-button mg-bottom-20 semi-bold corner-smooth border-rad-filter" data-action="cancel-add-product">Cancel</button>
                </form>
            </div>                      
        </div>
        
        <!-- Edit Product -->
        <!-- Edit Category Name -->
        <div id="editProductModal" class="modal">
            <div class="form-modal-container">
                <img src="./assets/add-form/catalog-add-icon.svg" alt="customer icon" class="customer-icon">
                
                <form>
                    <div class="search-area"> 
                        <label>
                            Search Product ID:
                            <input id="edit-product-id" type="text" class="search-customer-id underline-input" required>
                        </label>
                    </div>
                </form> 
                <form id="editProductForm">
                    <div id="editProductBackdrop" class="backdrop" style="height: 380px;"></div>
                    <div class="block-label-input mg-bottom-40">
                        <select id="editCategoryDropdown" class="select-filter ht-max pd-filter border-rad-filter corner-smooth mg-right" name="addCategoryDropdown" style="width: 250px;" required>
                            <option value='' selected disabled>Category Name</option>
                            <?php
                                $sql = "SELECT * FROM category ORDER BY CategoryName ASC";
                                $result_category = $conn->query($sql);
                                if ($result_category->num_rows > 0) {
                                    while($row = $result_category->fetch_assoc()) {
                                        echo "<option value='".$row['CategoryID']."'>" . $row['CategoryName'] . "</option>";
                                    }
                                }
                            ?>
                        </select>    
                    </div>

                    <div class="block-label-input mg-bottom-60">
                        <label for="edit-product-name">Product Name </label> 
                        <input type="text" id="edit-product-name" class="underline-input" required>        
                    </div>    

                    <div class="block-label-input mg-bottom-60">
                        <label for="edit-product-price">Price</label> 
                        <input type="text" id="edit-product-price" class="underline-input" pattern="^\d*(\.\d+)?$" required>        
                    </div>    

                    <button type="submit" class="confirm-button modal-button semi-bold corner-smooth border-rad-filter" data-action="edit-product">Save</button>
                    <button class="cancel-button modal-button mg-bottom-20 semi-bold corner-smooth border-rad-filter" data-action="cancel-edit-product">Cancel</button>
                </form>
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

        <!-- CONFIRM MODAL -->
        <div id="confirmModal" class="modal">
            <div class="confirm-modal-container">
                <img src="./assets/alert-icon.svg" alt="alert icon" class="alert-icon">                  
                <h4>Are you sure?</h4>
                <p id="warning-text">This action cannot be undone. All values associated with field will be lost.</p>
                
                <!-- JQuery to display record dynamically -->
                <div id="record-data-modal" class="record-data-display"></div>

                <button class="confirmed-delete-button modal-button semi-bold corner-smooth border-rad-filter">Delete field</button>
                <button class="cancel-delete-button modal-button semi-bold corner-smooth border-rad-filter">Cancel</button>
            </div>
        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./js/ajaxRequests.js"></script>
    <script src="./js/adminScripts.js"></script>
</body>
</html>