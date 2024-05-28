<?php
    include 'db_connect.php';
    include 'retrieve_record.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve what to fetch
        $typeOfFetch = isset($_POST['typeOfFetch']) ? $_POST['typeOfFetch'] : '';

        // Account 
        if ($typeOfFetch == 'signup') {
            // Retrieve the request data
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Create query for account table
            $stmt = $conn->prepare("INSERT INTO account (Email, `Password`) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $stmt->close();

            // Create query for customer details table
            $stmt = $conn->prepare("INSERT INTO customer (FirstName, LastName, Email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $firstName, $lastName, $email);

            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Account created successfully";
                } else {
                    echo "Account not created";
                }
            } else {
                echo "Error creating account:\n" . $stmt->error;
            }
        }
        // Add Customer Details (Admin)
        else if ($typeOfFetch == 'addCustomerDetails'){
            //Retrieve the request data
            // Retrieve the request data
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Create query for account table
            $stmt = $conn->prepare("INSERT INTO account (Email, `Password`) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $stmt->close();

            // Create query for customer details table
            $stmt = $conn->prepare("INSERT INTO customer (FirstName, LastName, Email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $firstName, $lastName, $email);

            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Account created successfully";
                } else {
                    echo "Account not created";
                }
            } else {
                echo "Error creating account:\n" . $stmt->error;
            }
        }
        // Add Customer Order (Admin)
        else if ($typeOfFetch == 'addCustomerOrder') {
            $id = $_POST['id'];
            $receiptDate = $_POST['receiptDate'];
            $orderDate = $_POST['orderDate'];  
            $totalAmount = $_POST['totalAmount'];

            // Retrieve the latest order ID first
            $stmt = $conn->prepare("SELECT OrderID FROM `order` ORDER BY OrderID DESC LIMIT 1");
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $latestOrderID = $result->fetch_assoc()['OrderID'];
                // Parse the latestOrderID 
                list($letters, $number) = explode('-', $latestOrderID);
                $letter1 = $letters[0];
                $letter2 = $letters[1];

                error_log("Letter 1: ". $letter1);
                error_log("Letter 2: ". $letter2);
                error_log("Number: ". $number);
            }
            else {
                $latestOrderID = '';
            }
            
            // Generate an Order ID (with algorithm)
            if ($latestOrderID != '') {
                if($number < 9) {
                    $number++;
                } else {
                    if ($letter2 != 'Z') {
                        $letter2++;
                    } else {
                        if ($letter1 != 'Z') {
                            $letter1++;
                        } else {
                            $letter1 = 'A';
                            $letter2 = 'A';
                            $letter3 = 'A' . $letters;
                        }
                    }
                    $number = 1;
                }
                // Combine the Order ID components
                $latestOrderID = $letter1 . $letter2 . '-' . $number;

                // Create query
                $stmt = $conn->prepare("INSERT INTO `order` (OrderID, CustomerID, ReceiptDate, OrderDate, TotalAmount) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssd", $latestOrderID, $id, $receiptDate, $orderDate, $totalAmount);

                // Execute the statement
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo "Customer order added successfully";
                    } else {
                        echo "No data inserted";
                    }
                } else {
                    echo "Error adding customer order:\n" . $stmt->error;
                }
                $stmt->close();
            }
        }
        // Add Category (Admin)
        else if ($typeOfFetch == 'addCategory') {
            $categoryName = $_POST['categoryName'];

            // Retrieve the latest category ID first
            $stmt = $conn->prepare("SELECT CategoryID FROM category ORDER BY CategoryID DESC LIMIT 1");
            $stmt->execute();

            $retrieved = $stmt->get_result();
            if ($retrieved->num_rows > 0) {
                $latestCategoryID = $retrieved->fetch_assoc()['CategoryID'];
                $numPart = intVal(substr($latestCategoryID, 3));

                // Increment for new category ID
                $numPart++;

                if ($numPart < 100) {
                    $numPart = '0' . $numPart;
                }
                // Combine the Category ID components
                $latestCategoryID = 'CAT' . $numPart;
            }
            else {
                $latestCategoryID = '';
            }

            if ($latestCategoryID != '') {
                error_log("Latest Category ID: ". $latestCategoryID);
                // Create query
                $stmt = $conn->prepare("INSERT INTO category (CategoryID, CategoryName) VALUES (?, ?)");
                $stmt->bind_param("ss", $latestCategoryID, $categoryName);

                // Execute the statement
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo "Category added successfully";
                    } else {
                        echo "No data inserted";
                    }
                } else {
                    echo "Error adding category:\n" . $stmt->error;
                }
                $stmt->close();
            }
        }   
        // Add Product (Admin)
        else if ($typeOfFetch == 'addProduct') {
            $categoryId = $_POST['id'];
            $productName = $_POST['productName'];
            $price = number_format($_POST['price'], 2);

            do {
                $productId = chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . rand(10,99);

                $stmt = $conn->prepare("SELECT ProductID FROM product WHERE ProductID = ?");
                $stmt->bind_param("s", $productId);
                
                // Execute the query
                $stmt->execute();

                // Fetch the result
                $result = $stmt->get_result();
            } while ($result->num_rows > 0);
            $stmt->close();

            // Create query for insert new product
            $stmt = $conn->prepare("INSERT INTO product (ProductID, ProductName, CategoryID, Price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssd", $productId, $productName, $categoryId, $price);

            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Product catalog added successfully";
                } else {
                    echo "No data inserted";
                }
            } else {
                echo "Error adding product catalog:\n" . $stmt->error;
            }
            $stmt->close();
        }
        // User Add Order
        else if ($typeOfFetch == 'userAddOrder') {
            // Retrieve the request data
            $email = $_POST['email'];
            $receiptDate = $_POST['receiptDate'];
            $orderDate = $_POST['orderDate'];
            $amount = $_POST['amount'];

            // Retrieve the customer ID
            $stmt = $conn->prepare("SELECT * FROM customer WHERE Email = ?");
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $stmt->close();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $customerId = $row['CustomerID'];
                    $customerName = $row['FirstName'] . " " . $row['LastName'];

                    // Retrieve the latest order ID first
                    $stmt = $conn->prepare("SELECT OrderID FROM `order` ORDER BY OrderID DESC LIMIT 1");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    if ($result->num_rows > 0) {
                        $latestOrderID = $result->fetch_assoc()['OrderID'];
                        // Parse the latestOrderID 
                        list($letters, $number) = explode('-', $latestOrderID);
                        $letter1 = $letters[0];
                        $letter2 = $letters[1];

                        error_log("Letter 1: ". $letter1);
                        error_log("Letter 2: ". $letter2);
                        error_log("Number: ". $number);
                    }
                    else {
                        $latestOrderID = '';
                    }
                    
                    // Generate an Order ID (with algorithm)
                    if ($latestOrderID != '') {
                        if($number < 9) {
                            $number++;
                        } else {
                            if ($letter2 != 'Z') {
                                $letter2++;
                            } else {
                                if ($letter1 != 'Z') {
                                    $letter1++;
                                } else {
                                    $letter1 = 'A';
                                    $letter2 = 'A';
                                    $letter3 = 'A' . $letters;
                                }
                            }
                            $number = 1;
                        }
                        // Combine the Order ID components
                        $latestOrderID = $letter1 . $letter2 . '-' . $number;
                    }
                    
                    // Create new statement
                    $stmt = $conn->prepare("INSERT INTO `order` (OrderID, CustomerID, OrderDate, ReceiptDate, TotalAmount) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssd", $latestOrderID, $customerId, $orderDate, $receiptDate, $amount);

                    // Execute the statement
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows > 0) {
                            $response = array (
                                'success' => true,

                                'orderId' => $latestOrderID,
                                'name' => $customerName,
                                'message' => "Customer order added successfully"
                            );
                        } else {
                            $response = array (
                                'success' => false,
                                'message' => "No data inserted"
                            );
                        }
                    } else {
                        $response = array (
                            'success' => false,
                            'message' => "Error adding customer order:\n" . $stmt->error
                        );
                    }
                } else {
                    $response = array (
                        'success' => false,
                        'message' => "No data retrieved"
                    );
                }
            } else {
                $response = array (
                    'success' => false,
                    'message' => "Error retrieving customer ID: \n" . $stmt->error
                );
            }
            $stmt->close();
            echo json_encode($response);
        }
    }
?>