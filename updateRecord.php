<?php
    include 'db_connect.php';
    include 'retrieve_record.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve what to fetch
        $typeOfFetch = isset($_POST['typeOfFetch']) ? $_POST['typeOfFetch'] : '';

        if ($typeOfFetch == 'updateCustomerDetails') {
            // Retrieve the request data
            $customerId = $_POST['id'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $changesMade = false;

            // Retrieve the Account Id using Email from Customer Table 
            $stmt = $conn->prepare("SELECT Email FROM customer WHERE CustomerID = ?");
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            $stmt = $conn->prepare("SELECT AccountID FROM account WHERE Email = ?");
            $stmt->bind_param("s", $row['Email']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            // Update Account Table
            if ($password != '') {
                $stmt = $conn->prepare("UPDATE account SET Email = ?, `Password` = ? WHERE AccountID = ?");
                $stmt->bind_param("ssi", $email, $password, $row['AccountID']);
            } else {
                $stmt = $conn->prepare("UPDATE account SET Email = ? WHERE AccountID = ?");
                $stmt->bind_param("si", $email, $row['AccountID']);
            }
    
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $changesMade = true;
                } 
                $stmt->close();   
                
                // Update Customer Table
                $stmt = $conn->prepare("UPDATE customer SET FirstName = ?, LastName = ? WHERE CustomerID = ?");
                $stmt->bind_param("ssi", $firstName, $lastName, $customerId);

                // Execute the statement
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0 && $changesMade == false || $stmt->affected_rows > 0 && $changesMade == true) {
                        echo "Customer details updated successfully";  
                    } 
                    else {
                        echo "Same data entered. No changes made.";
                    }
                } else {
                    echo "Error updating customer details";
                }
            }
            else {
                echo "Error updating customer details";
            }
            $stmt->close();
        } 
        else if ($typeOfFetch == 'updateCustomerOrder') {
            // Retrieve the request data
            $orderId = $_POST['id'];
            $receiptDate = $_POST['receiptDate'];
            $orderDate = $_POST['orderDate'];
            $totalAmount = $_POST['totalAmount'];
            
            // Create query
            $stmt = $conn->prepare("UPDATE `order` SET `order`.ReceiptDate = ?, `order`.OrderDate = ?, `order`.TotalAmount = ? WHERE `order`.OrderID = ?");
            $stmt->bind_param("ssds", $receiptDate, $orderDate, $totalAmount, $orderId);

            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Customer order updated successfully";
                } else {
                    echo "Same data entered. No changes made.";
                }
            } else {
                echo "Error updating customer order";
            }
            $stmt->close();
        }
        else if ($typeOfFetch == 'updateCategory') {
            // Retrieve the request data
            $categoryId = $_POST['id'];
            $categoryName = $_POST['categoryName'];

            // Create query
            $stmt = $conn->prepare("UPDATE category SET CategoryName = ? WHERE CategoryID = ?");
            $stmt->bind_param("ss", $categoryName, $categoryId);

            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Category updated successfully";
                } else {
                    echo "Same data entered. No changes made.";
                }
            } else {
                echo "Error updating category";
            }
            $stmt->close();
        }
        else if ($typeOfFetch == 'updateProduct') {
            // Retrieve the request data
            $productId = $_POST['productId'];
            $categoryId = $_POST['categoryId'];
            $productName = $_POST['productName'];
            $price = $_POST['price'];

            // Create query
            $stmt = $conn->prepare("UPDATE product SET ProductName = ?, CategoryID = ?, Price = ? WHERE ProductID = ?");
            $stmt->bind_param("ssds", $productName, $categoryId, $price, $productId);

            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Product catalog updated successfully";
                } else {
                    echo "Same data entered. No changes made.";
                }
            } else {
                echo "Error updating product catalog";
            }
            $stmt->close();
        }
        else if ($typeOfFetch == 'updateUserPersonalDetails') {
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $oldEmail = $_POST['oldEmail'];
            $newEmail = $_POST['newEmail'];
            $oldPassword = $_POST['oldPassword'];
            $newPassword = $_POST['newPassword'];
            
            // Create query
            if ($oldPassword == '' && $newPassword == '') {
                $stmt = $conn->prepare("SELECT AccountID FROM account WHERE Email = ?");
                $stmt->bind_param("s", $oldEmail);

                // Execute the statement
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $stmt->close();

                    // Update Account Table
                    $stmt = $conn->prepare("UPDATE account SET Email = ? WHERE AccountID = ?");
                    $stmt->bind_param("ss", $newEmail, $row['AccountID']);
                    $stmt->execute();
                    $stmt->close();
                    
                    // Update Customer Table 
                    $stmt = $conn->prepare("UPDATE customer SET FirstName = ?, LastName = ? WHERE Email = ?");
                    $stmt->bind_param("sss", $firstName, $lastName, $newEmail);

                    // Execute the statement
                    if ($stmt->execute()) {
                        $response = array (
                            'success' => true, 
                            'newEmail' => $newEmail
                        );
                    } else {
                        $response = array (
                            'success' => false
                        );
                    }
                    $stmt->close();
                }
            } 
            else {
                if ($oldPassword == $newPassword) {
                    $response = array (
                        'success' => false, 
                        'passwordEqual' => true
                    );
                    echo json_encode($response);
                    return;
                }
                
                $stmt = $conn->prepare("SELECT AccountID FROM account WHERE Email = ?");
                $stmt->bind_param("s", $oldEmail);
                // Execute the statement
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $stmt->close();

                    // Update Account Table
                    $stmt = $conn->prepare("UPDATE account SET Email = ?, `Password` = ? WHERE AccountID = ? AND `Password` = ?");
                    $stmt->bind_param("ssis", $newEmail, $newPassword, $row['AccountID'], $oldPassword);

                    // Execute the statement
                    if (($stmt->execute())) {
                        if ($stmt->affected_rows == 0) {
                            $response = array (
                                'success' => false, 
                                'oldPassword' => false
                            );
                            echo json_encode($response);
                            return;
                        }
                    }   
                    // Update Customer Table 
                    $stmt = $conn->prepare("UPDATE customer SET FirstName = ?, LastName = ? WHERE Email = ?");
                    $stmt->bind_param("sss", $firstName, $lastName, $newEmail);

                    // Execute the statement
                    if ($stmt->execute()) {
                        $response = array (
                            'success' => true, 
                            'newEmail' => $newEmail
                        );
                    } else {
                        $response = array (
                            'success' => false
                        );
                    }
                    $stmt->close();
                }
            }
            echo json_encode($response);
        }
        else if ($typeOfFetch == 'updateAdminPassword') {
            // Retrieve the request data
            $email = $_POST['email'];
            $oldPassword = $_POST['oldPassword'];
            $newPassword = $_POST['newPassword'];

            if ($oldPassword == $newPassword) {
                echo "New password must differ";
                return;
            }

            // Create query
            $stmt = $conn->prepare("UPDATE account SET `Password` = ? WHERE Email = ? AND `Password` = ?");
            $stmt->bind_param("sss", $newPassword, $email, $oldPassword);
            
            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Admin password updated successfully";
                } else {
                    echo "Invalid old password entered";
                }
            } else {
                echo "Error updating admin password";
            }
        }
    }
?>