<?php
include 'db_connect.php';
include 'retrieve_record.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/userStyles.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tableStyles.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/modalStyles.css">
    <link rel="stylesheet" href="css/formStyles.css">
    <title>OrderMaster</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
    </style>
    <!-- NEED TO LOAD FIRST -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./node_modules/chart.js/dist/chart.umd.js"></script>
</head>
<body>
    <header>
        <nav class="ht-max">
            <ul class="ht-max">
                <div class="list-container ht-max">
                    <div class="profile-div">
                        <li id="user-nav-item-1" class="user-nav-item "><a href=""><img src='./assets/user/profile-icon.svg' class='svg-icon'></a></li>
                    </div>
                    <div>
                        <li id="user-nav-item-2" class="user-nav-item "><a href="">Dashboard</a></li>
                        <li id="user-nav-item-3" class="user-nav-item "><a href="">Add Order</a></li>
                        <li id="user-nav-item-4" class="user-nav-item "><a href="">View Order History</a></li>
                    </div>
                </div>
            </ul>
        </nav>
    </header>
    <main>
        <!-- User Profile -->
        <div id="userProfileContainer" class="container pd-main">
            <div class='profile-icon mg-bottom-40'>
                <img src='./assets/user/profile-details-icon.svg'>
            </div>
            <div class="profile-details mg-bottom-60">
                <h1 id="user-profile-full-name" class="mg-bottom-10"></h1>
                <p id="user-profile-type"><p>    
            </div>
            <div class="details-link-container">
                <a id="user-details-link-1" class='details-link '>Personal Details</a>
                <a id="user-details-link-2" class='details-link '>Notification</a>
            </div>
            <button id="user-logout-button" class='semi-bold border-rad-form' data-action='log-out'>Log out</button>
        </div>

        <div id="userPersonalDetailsContainer" class="container pd-main">
            <div class="form-container">
                <form id="userPersonalDetailsForm" method="post" action="">
                    <div class="block-label-input mg-bottom-40 text-left">
                        <p class="header-details mg-bottom-10">Change Email</p>
                        <div class="input-container">
                            <input type="text" id="user-update-email" class="box-styled-input" placeholder="Email" required>        
                            <img src="./assets/user/email-icon.svg" alt="email-input-icon">
                        </div>
                    </div>  
                    <div class="block-label-input mg-bottom-40 text-left">
                        <p class="header-details mg-bottom-10">Change Name</p>
                        <div class="input-container">
                            <input type="text" id="user-update-first-name" class="box-styled-input" placeholder="First Name" required>        
                            <img src="./assets/user/user-icon.svg" alt="username-input-icon">
                        </div>
                        <div class="input-container">
                            <input type="text" id="user-update-last-name" class="box-styled-input" placeholder="Last Name" required>        
                            <img src="./assets/user/user-icon.svg" alt="username-input-icon">
                        </div>
                    </div> 
                    <div class="block-label-input mg-bottom-40 text-left">
                        <p class="header-details mg-bottom-10">Change Password</p>
                        <div class="input-container">
                            <input type="password" id="user-update-old-password" class="box-styled-input" placeholder="Enter Old Password" minlength="8">             
                            <img src="./assets/user/password-icon.svg" alt="password-input-icon">
                        </div>
                        <div class="input-container">
                            <input type="password" id="user-update-new-password" class="box-styled-input" placeholder="Enter New Password" minlength="8">             
                            <img src="./assets/user/password-icon.svg" alt="password-input-icon">
                        </div>
                    </div>   
                    <button id="user-update-personal-details-button" type="submit" class="semi-bold border-rad-filter" data-action="sign-in">Save</button>
                </form>
            </div>
        </div>

        <!-- Backdrop -->
        <div id="profileDisplayBackDrop" class="modal" style="z-index: 2;"></div>

        <!-- Add Order -->
        <div id="userAddOrderContainer" class="container pd-main">
            <div class="user-form-container" style="z-index: 1;">
                <form id="userAddOrderForm">
                    <img src="./assets/add-form/order-add-icon.svg" alt="customer icon" class="customer-icon">
                    
                    <div class="user-flex">
                        <div class="user-inner-flex">
                            <div class="block-label-input">
                                <select class="select-filter ht-max pd-filter border-rad-filter corner-smooth mg-right" name="addOrderProductDropdown" style="width: 250px;" required>
                                    <option value='' selected disabled>Order Product</option>
                                    <?php
                                        $sql = "SELECT * FROM `product` ORDER BY ProductName ASC";
                                        $result_category = $conn->query($sql);
                                        if ($result_category->num_rows > 0) {
                                            while($row = $result_category->fetch_assoc()) {
                                                echo "<option value='".$row['ProductID']."'>" . $row['ProductName'] . "</option>";
                                            }
                                        }
                                    ?>
                                </select>    
                            </div>
                            <div class="block-label-input">
                                <label for="add-quantity">Qty</label> 
                                <input type="number" name="add-quantity" class="add-order-quantity underline-input" min="1" value="1" required>        
                            </div>
                            <div class="user-flex-button">
                                <button type="button" class="add-order-product semi-bold border-rad-filter">+</button>
                                <button type="button" class="delete-order-product semi-bold border-rad-filter">-</button>
                            </div>
                        </div>
                    </div>
                    <div class="block-label-input mg-bottom-40" style="text-align: left;">
                        <label for="add-order-price">Total Amount</label> 
                        <input type="text" id="add-order-price" class="underline-input" pattern="^\d*(\.\d{0,2})?$" readonly required>        
                    </div>  
                    <button type="submit" class="confirm-button modal-button semi-bold corner-smooth border-rad-filter" data-action="user-add-order">Add Order</button>
                </form>
            </div>                      
        </div>

        <!-- View Order History -->
        <div id="userViewEditOrderContainer" class="container pd-main">
            <div class="form-container">
                <section class="filter-container">
                    <div class="header">
                        <h1 id="order-owner"></h1>
                    </div>
                    <div class="filter">
                        <form id="userOrderHistoryForm" class="ht-max" method="post" action="">
                            <!-- SORT BY DROPDOWN -->
                            <select id="sortByUserOrder" class="select-filter ht-max pd-filter border-rad-filter mg-right" name="sortBy">
                                <option value="" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == '') echo 'selected'; ?>>Sort By</option>
                                <option value="OrderID" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'OrderID') echo 'selected'; ?>>Order ID</option>
                                <option value="OrderDate" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'OrderDate') echo 'selected'; ?>>Order Date</option>
                                <option value="TotalAmount" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'TotalAmount') echo 'selected'; ?>>Price</option>
                                <option value="ReceiptDate" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'ReceiptDate') echo 'selected'; ?>>Receipt Date</option>
                            </select>
                            <div class='dropdown-icon ht-max pd-filter border-rad-filter' style="right: 71%;"></div>
                            <!-- SEARCH FILTER -->
                            <input id="searchFilterUserOrder" class="search-filter ht-max pd-filter border-rad-filter" type="text" name="searchFilterCustomerDetails" placeholder="Search here..." value="<?php echo isset($_POST['searchFilterCustomerDetails']) ? $_POST['searchFilterCustomerDetails'] : ''?>">
                            <!-- SEARCH LOGO -->
                            <div class="search-logo ht-max pd-filter border-rad-filter" name="search" value="">
                        </form>
                    </div>
                </section>
                <section class="report-container">
                    <table id="userOrderHistoryTable" class="scroll-bar">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Price</th>
                                <th>Receipt Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="userOrderHistoryTableBody">
                            <!-- Display table record dynamically -->
                        </tbody>        
                    </table>
                </section>  
            </div>
        </div>

        <!-- DASHBOARD -->
        <div id="userDashboardContainer" class="pd-main">
            <div class="user-form-container">
                <div class="main-data">
                    <div>
                        <p>Financial</p>
                        <p id="user-order-time"></p>
                        <p>Total Purchase Value</p>
                        <p>&#8369; <span id="user-total-purchase-value"></span></h1>
                    </div>
                </div>
                <div class="graph">
                    <div class=sub-header>
                        <div class="sub-1">
                            <p>Spending Trend</p>
                        </div>
                        <div class="sub-2">
                            
                        </div>
                        <div class="sub-3">
                            <div>
                                <p id="user-spending-trend-percentage"></p>    
                            </div>
                            <div class="growth-container">
                                <p>Growth</p>
                            </div>
                        </div>
                    </div>
                    <div class="sub-graph">
                        <!-- GRAPH HERE -->
                        <canvas id="myChart" width="650" height="250"></canvas>
                    </div>
                </div>
                <div class="sub-data">
                    <div class="sub-data-1">
                        <p>Total Orders</p>
                        <p id="user-total-orders"></p>
                        <p><span id="user-num-order-gap"></span> compared to last month</p>
                    </div>
                    <div class="sub-data-2">
                        <p>Average Spend</p>
                        <p>&#8369; <span id="user-average-spend"></span></p>
                        <p><span id="user-order-percentage-change"></span> compared to last month</p>
                    </div>
                    <div class="sub-data-3">
                        <p>Recent Order</p>
                        <p id="user-recent-order"></p>
                    </div>
                </div>
            </div>                      
        </div>
    
        <!-- RECEIPT MODAL -->
        <div id="receiptModal" class="modal">
            <div class="confirm-modal-container">
                <h1 class="mg-bottom-30">RECEIPT</h1>
                <div class="receipt-header">
                    <p id="user-receipt-date" class="receipt-bold mg-bottom-30"></p>
                    <h2 class="mg-bottom-10">Thanks for your order!</h2>
                    <p class="receipt-sub-header mg-bottom-30" style="font-size: 14px;">This receipt is your proof of purchase. Please keep it in a safe place.</p>
                    <div class="order-id receipt-border-bottom mg-bottom-50">
                        <p class="receipt-bold">Order ID:</p>
                        <p id="user-receipt-order-id" class="receipt-bold"></p>
                    </div>
                </div>
                <div class="receipt-grid mg-bottom-50">
                    <div class="receipt-personal-info">
                        <p class="receipt-sub-header mg-bottom-10">Billing to:<p>
                        <p id="user-receipt-name" class="receipt-bold"></p>
                        <p id="user-receipt-email" class="receipt-bold"></p>
                    </div>
                    <div class="receipt-billing-info">
                        <div class="billing-left">
                            <p class="mg-bottom-10">Amount:</p>
                            <p class="receipt-border-bottom mg-bottom-10">Shipping:</p>
                            <p style="font-size: 19px;">Total:</p>
                        </div>
                        <div class="billing-right">
                            <p id="user-receipt-amount" class="mg-bottom-10"></p>
                            <p id="user-shipping" class="receipt-border-bottom mg-bottom-10">0.00</p>
                            <p id="user-receipt-total" style="font-size: 19px;"></p>
                        </div>
                    </div>
                </div>
                <div class="receipt-exit">
                    <div class="receipt-exit-button">X</div>
                </div>
                <a id="downloadReceipt" href="#" class="receipt-download">Download Receipt</a>
            </div> 
        </div>

        <!-- RECEIPT SUCCESS MODAL -->
        <div id="receiptSuccessModal" class="modal">
            <div class="confirm-modal-container">
                <img src="./assets/check-icon.svg" alt="alert icon" class="alert-icon">                  
                <h4>SUCCESS STATUS</h4>
                <p id="receipt-success-text"></p>
                <button id='receipt-success-cancel-button' class="cancel-delete-button modal-button semi-bold corner-smooth border-rad-filter" style="width: 100px;">Ok</button>
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
    </main>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="./js/ajaxRequests.js"></script>
    <script src="./js/userChartScripts.js"></script>
    <script src="./js/userScripts.js"></script>
</body>
</html>