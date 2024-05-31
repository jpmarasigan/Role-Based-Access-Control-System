<?php
    include 'db_connect.php';
    include 'retrieve_record.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve the request data
        $typeOfFetch = isset($_POST['typeOfFetch']) ? $_POST['typeOfFetch'] : '';

        // Account
        if ($typeOfFetch == 'signin') {
            // Retrieve the request data
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Create query for account table
            $sql = "SELECT * FROM account WHERE Email = '$email' AND `Password` = '$password'";
            $result_query = $conn->query($sql);

            // Execute the statement
            if ($result_query->num_rows > 0) {
                $row = $result_query->fetch_assoc();
                if ($email == 'admin') {
                    $response = array(
                        'success' => true,
                        'admin' => true,
                        'email' => $row['Email']
                    );
                } else {
                    $response = array(
                        'success' => true,
                        'admin' => false,
                        'email' => $row['Email']
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                );
            }
            echo json_encode($response);
        }
        // Personal Info Display in other .php
        else if ($typeOfFetch == 'personalDetails') {
            // Retrieve the data
            $email = $_POST['email'];

            // Create query for account table
            if($email == 'admin') {
                $sql = "SELECT AccountID FROM account WHERE Email = '$email'";
            } else {
                $sql = "SELECT * FROM customer WHERE Email = '$email'";
            }
            $result_query = $conn->query($sql);
            
            // Execute the statement
            if ($result_query->num_rows > 0) {
                $row = $result_query->fetch_assoc();
                if ($email == 'admin') {
                    $response = array(
                        'success' => true,
                        'admin' => true
                    );
                } else {
                    $response = array(
                        'success' => true,
                        'admin' => false,
                        'firstName' => $row['FirstName'],
                        'lastName' => $row['LastName'],
                        'email' => $row['Email']
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                );
            }
            echo json_encode($response);
        }
        // Customer Details Query
        else if ($typeOfFetch == 'customerDetails') {
            $sortBy = isset($_POST['sortBy']) ? $_POST['sortBy'] : '';
            $searchFilter = isset($_POST['searchFilter']) ? $_POST['searchFilter'] : '';
            
            // General query
            $sql = "SELECT * FROM customer";

            // Search filter
            if (!empty($searchFilter)) {
                $sql .= " WHERE CustomerID LIKE '%$searchFilter%' OR FirstName LIKE '%$searchFilter%' OR LastName LIKE '%$searchFilter%' OR Email LIKE '%$searchFilter%'";
            }
            // Sort By Filter
            if (!empty($sortBy)) {
                $sql .= " ORDER BY $sortBy";
            }
            // Execute the query
            $result_query = $conn->query($sql);

            if ($result_query->num_rows > 0) {
                while ($row = $result_query->fetch_assoc()) {
                    echo "<tr><td>" . $row['CustomerID'];
                    echo "</td><td>" . $row['FirstName'];
                    echo "</td><td>" . $row['LastName'];
                    echo "</td><td>" . $row['Email'];
                    echo "</td><td><button type='button' "; 
                        echo "data-customer-customerId='".$row['CustomerID']."' ";
                        echo "data-customer-firstName='".$row['FirstName']."' ";
                        echo "data-customer-lastName='".$row['LastName']."' ";
                        echo "data-customer-email='".$row['Email']."' ";
                        echo "class='customer-detail-button delete-button border-rad-form'>Delete</button>";
                    echo "</td></tr>";
                }
            } else {
                echo "<div class='no-record'><p>No Customer Details Found</p></div>";
            }
        }
        // Customer Order Table Query
        else if ($typeOfFetch == 'customerOrder') {
            $sortBy = isset($_POST['sortBy']) ? $_POST['sortBy'] : '';
            $searchFilter = isset($_POST['searchFilter']) ? $_POST['searchFilter'] : '';
            $dateFilter = isset($_POST['dateFilter']) ? $_POST['dateFilter'] : '';
            $customerId = isset($_POST['customerId']) ? $_POST['customerId'] : '';

            // General query
            $sql = "SELECT customer.*, `order`.* FROM customer INNER JOIN `order` ON customer.CustomerID = `order`.CustomerID";
            $whereAdded = false;

            // Search filter
            if (!empty($searchFilter)) {
                $searchParts = explode(" ", $searchFilter);
                $firstName = $searchParts[0];
                $lastName = isset($searchParts[1]) ? $searchParts[1] : '';
                $sql .= " WHERE (`order`.OrderID LIKE '%$searchFilter%' OR customer.CustomerID LIKE '%$searchFilter%' OR (customer.FirstName LIKE '%$firstName%' AND customer.LastName LIKE '%$lastName%') OR `order`.OrderDate LIKE '%$searchFilter%' OR `order`.ReceiptDate LIKE '%$searchFilter%' OR `order`.TotalAmount LIKE '%$searchFilter%')";
                $whereAdded = true;
            }

            // Customer ID filter
            if (!empty($customerId)) {
                $sql .= $whereAdded ? " AND customer.CustomerID = '$customerId'" : " WHERE customer.CustomerID = '$customerId'";
                $whereAdded = true;
            }
            // Order Date filter
            if (!empty($dateFilter)) {
                $sql .= $whereAdded ? " AND (`order`.OrderDate = '$dateFilter' OR `order`.ReceiptDate = '$dateFilter')" : " WHERE (`order`.OrderDate = '$dateFilter' OR `order`.ReceiptDate = '$dateFilter')";
            }
            // Sort By Filter
            if (!empty($sortBy)) {
                if ($sortBy == 'CustomerName') {
                    $sortBy = 'customer.FirstName';
                }
                else if ($sortBy == 'CustomerID') {
                    $sortBy = 'customer.CustomerID';
                }
                $sql .= " ORDER BY $sortBy";
            }

            // Execute the query
            $result_query = $conn->query($sql);
            if ($result_query->num_rows > 0) {
                while($row = $result_query->fetch_assoc()) {
                    echo "<tr><td>" . $row['OrderID'];
                    echo "</td><td>" . $row['CustomerID'];
                    echo "</td><td>" . $row['FirstName'] . " " . $row['LastName'];
                    echo "</td><td>" . $row['OrderDate'];
                    echo "</td><td>" . $row['ReceiptDate'];
                    echo "</td><td>" . $row['TotalAmount'];
                    echo "</td><td><button type='button' "; 
                        echo "data-order-orderId='".$row['OrderID']."' ";
                        echo "data-order-customerId='".$row['CustomerID']."' ";
                        echo "data-order-firstName='".$row['FirstName']."' ";
                        echo "data-order-lastName='".$row['LastName']."' ";
                        echo "data-order-orderDate='".$row['OrderDate']."' ";
                        echo "data-order-receiptDate='".$row['ReceiptDate']."' ";
                        echo "data-order-totalAmount='".$row['TotalAmount']."' ";
                        echo "class='customer-order-button delete-button border-rad-form'>Delete</button>";
                    echo "</td></tr>";
                }
            } else {
                echo "<div class='no-record'><p>No Customer Orders Found</p></div>";
            }
        }
        // Product Catalog Table Query
        else if ($typeOfFetch == 'productCatalog') {
            $sortBy = isset($_POST['sortBy']) ? $_POST['sortBy'] : '';
            $searchFilter = isset($_POST['searchFilter']) ? $_POST['searchFilter'] : '';
            $categoryName = isset($_POST['categoryName']) ? $_POST['categoryName'] : '';
        
            // General query
            $sql = "SELECT `product`.*, `category`.CategoryName FROM `category` INNER JOIN `product` ON `category`.CategoryID = `product`.CategoryID";
            $whereAdded = false;

            // Search Filter
            if (!empty($searchFilter)) {
                $sql .= " WHERE `product`.ProductName LIKE '%$searchFilter%' OR `category`.CategoryName LIKE '%$searchFilter%' OR `product`.Price LIKE '%$searchFilter%'";
                $whereAdded = true;
            }
            // Category Name Filter
            if (!empty($categoryName)) {
                $sql .= $whereAdded ? " AND `category`.CategoryName = '$categoryName'" : " WHERE `category`.CategoryName = '$categoryName'";
                $whereAdded = true;
            }
            // Sort By Filter
            if (!empty($sortBy)) {
                if ($sortBy == 'CategoryName') {
                    $sortBy = 'category.CategoryName';
                }
                $sql .= " ORDER BY $sortBy";
            }

            // Execute the query
            $result_query = $conn->query($sql);
            if ($result_query->num_rows > 0) {
                while($row = $result_query->fetch_assoc()) {
                    echo "<tr><td>" . $row['ProductName'];
                    echo "</td><td>" . $row['CategoryName'];
                    echo "</td><td>" . $row['Price'];
                    echo "</td><td><button type='button' "; 
                        echo "data-product-productName='".$row['ProductName']."' ";
                        echo "data-product-categoryName='".$row['CategoryName']."' ";
                        echo "data-product-price='".$row['Price']."' ";
                        echo "class='product-catalog-button delete-button border-rad-form'>Delete</button>";
                    echo "</td></tr>";
                }
            } else {
                echo "<div class='no-record'><p>No Product Catalog Found</p></div>";
            }
        }
        // User Order History Details Query 
        else if ($typeOfFetch == 'showUserOrderHistory') {
            // Retrieve the data
            $email = $_POST['email'];

            // Create statement
            $stmt = $conn->prepare("SELECT `order`.*, customer.* FROM `order` JOIN customer ON customer.CustomerID = `order`.CustomerID WHERE customer.Email = ?");
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                $result_query = $stmt->get_result();
                if($result_query->num_rows > 0) {
                    while ($row = $result_query->fetch_assoc()) {
                        echo "<tr><td>" . $row['OrderID'];
                        echo "</td><td>" . $row['OrderDate'];
                        echo "</td><td>" . $row['TotalAmount'];
                        echo "</td><td>" . $row['ReceiptDate'];
                        echo "</td><td><button type='button' "; 
                            echo "data-user-order-orderId='".$row['OrderID']."' ";
                            echo "data-user-order-totalAmount='".$row['TotalAmount']."' ";
                            echo "data-user-order-receiptDate='".$row['ReceiptDate']."' ";
                            echo "data-user-order-email='".$row['Email']."' ";
                            echo "data-user-order-name='".$row['FirstName']." ".$row['LastName']."' ";
                            echo "class='receipt-button border-rad-form'>Receipt</button>";
                        echo "</td></tr>";
                    }
                } else {
                    echo "<div class='no-record'><p>No Order Found</p></div>";  
                }
            } else {
                echo "Error retrieving order history query";
            }
            $stmt->close();
        }
        else if ($typeOfFetch == 'userOrderDetails') {
            // Retrieve the data
            $email = $_POST['email'];
            $sortBy = isset($_POST['sortBy']) ? $_POST['sortBy'] : '';
            $searchFilter = isset($_POST['searchFilter']) ? $_POST['searchFilter'] : '';

            // Retrieve Customer ID first
            $sql = "SELECT CustomerID FROM customer WHERE Email = '$email'";
            $result_query = $conn->query($sql);
            $customerID = $result_query->fetch_assoc()['CustomerID'];

            // Create query
            $sql = "SELECT * FROM `order` WHERE CustomerID = '$customerID'";

            if (!empty($searchFilter)) {
                $sql .= " AND (OrderID LIKE '%$searchFilter%' OR OrderDate LIKE '%$searchFilter%' OR TotalAmount LIKE '%$searchFilter%' OR ReceiptDate LIKE '%$searchFilter%')";
            }

            if (!empty($sortBy)) {
                $sql .= " ORDER BY $sortBy";
            }

            // Execute the query
            $result_query = $conn->query($sql);
            if ($result_query->num_rows > 0) {
                while ($row = $result_query->fetch_assoc()) {
                    echo "<tr><td>" . $row['OrderID'];
                    echo "</td><td>" . $row['OrderDate'];
                    echo "</td><td>" . $row['TotalAmount'];
                    echo "</td><td>" . $row['ReceiptDate'];
                    echo "</td><td><button type='button' "; 
                        echo "data-user-order-orderId='".$row['OrderID']."' ";
                        echo "data-user-order-orderDate='".$row['OrderDate']."' ";
                        echo "data-user-order-totalAmount='".$row['TotalAmount']."' ";
                        echo "data-user-order-receiptDate='".$row['ReceiptDate']."' ";
                        echo "class='receipt-button border-rad-form'>Receipt</button>";
                    echo "</td></tr>";
                }
            } else {
                echo "<div class='no-record'><p>No Order Found</p></div>";
            }
        }
        else if ($typeOfFetch == 'userDataDashboard') {
            // Retrieve the data
            $email = $_POST['email'];

            // Create statement
            $stmt = $conn->prepare("SELECT CustomerID FROM customer WHERE Email = ?");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $result_query = $stmt->get_result();
                $customerID = $result_query->fetch_assoc()['CustomerID'];
                $stmt->close();

                // Get user overall order record
                $stmt = $conn->prepare("SELECT * FROM `order` WHERE CustomerID = ?");
                $stmt->bind_param("s", $customerID);
                if ($stmt->execute()) {
                    $response = array ( 'success' => true );
                    $result_query = $stmt->get_result();
                    if ($result_query->num_rows > 0) {
                        while ($row = $result_query->fetch_assoc()) {
                            $response['data'][] = $row;
                        }
                    }
                    else {
                        $response['retrievedData'] = false;
                    }
                }
                else {
                    $response = array (
                        'success' => false
                    );
                }
            }
            else {
                $response = array (
                    'success' => false
                );
            }
            $stmt->close();
            echo json_encode($response);
        }
        else if ($typeOfFetch == 'addProductDropdown') {
            $sql = "SELECT * FROM `product` ORDER BY ProductName ASC";
            $result_product = $conn->query($sql);
            $options = "";

            if ($result_product->num_rows > 0) {
                while($row = $result_product->fetch_assoc()) {
                    $options .= "<option value='".$row['ProductID']."'>" . $row['ProductName'] . "</option>";
                }
            }
            echo $options;         
        }
        else if ($typeOfFetch == 'getProductPrice') {
            // Retrieve the data
            $productID = $_POST['productId'];

            $stmt = $conn->prepare("SELECT Price FROM product WHERE ProductID = ?");
            $stmt->bind_param("s", $productID);

            // Execute the statement
            if ($stmt->execute()) {
                $result_query = $stmt->get_result();
                $productPrice = $result_query->fetch_assoc()['Price'];
                
                echo $productPrice;
            }
            else {
                echo "Error retrieving product price query";
            }
        }

        // Search Record
        if ($typeOfFetch == 'searchCustomerId') {
            $id = $_POST['id'];
            $index = $_POST['index'];

            if ($index == 0) {
                // Create query
                $sql = "SELECT * FROM customer WHERE CustomerID = '$id'";
                // Execute the query
                $result_query = $conn->query($sql);
            } 
            else if ($index == 1) {
                // Create query
                $sql = "SELECT FirstName, LastName FROM customer WHERE CustomerID = '$id'";
                // Execute the query
                $result_query = $conn->query($sql);
            }
            else if ($index == 2) {
                // Create query
                $sql = "SELECT customer.FirstName, customer.LastName, `order`.ReceiptDate, `order`.OrderDate, `order`.TotalAmount FROM customer INNER JOIN `order` ON customer.CustomerID = `order`.CustomerID WHERE `order`.orderID = '$id'";
                // Execute the query
                $result_query = $conn->query($sql);
            }
            else if ($index == 3) {
                // Create query
                $sql = "SELECT CategoryName FROM category WHERE CategoryID = '$id'";
                // Execute the query
                $result_query = $conn->query($sql);
            }
            else if ($index == 'addCategoryDropdown') {
                // Create query
                $sql = "SELECT * FROM category WHERE CategoryID = '$id'";
                // Execute the query
                $result_query = $conn->query($sql);
            }
            else if ($index == 4) {
                 // Create query
                $sql = "SELECT category.CategoryName, product.CategoryID, product.ProductName, product.Price FROM category INNER JOIN product ON category.CategoryID = product.CategoryID WHERE product.ProductID = '$id'";
                // Execute the query
                $result_query = $conn->query($sql);
            }

            if ($result_query->num_rows > 0) {
                $customerDetails = $result_query->fetch_assoc();
                $response = array('index' => $index, 'customerDetails' => $customerDetails);
                echo json_encode($response);
            }
            else {
                echo 'none';
            }
        }
    }
?>