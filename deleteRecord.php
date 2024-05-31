<?php
    include 'db_connect.php';
    include 'retrieve_record.php';

    $json = file_get_contents('php://input');   

    // Convert the json into an associative array
    $data = json_decode($json, true);

    // Get the value of type of deletion
    $typeOfDeletion = $data['typeOfDeletion'];

    if ($typeOfDeletion == 'customerDetail') {
    // Get the value of record data keys
        $email = $data['email'];
        
        // // Log the retrieve data to be deleted
        // error_log("CUSTOMER DETAILS DATA TO BE DELETED: ".$id." | ".$firstName." | ".$lastName." | ".$email);

        try {
            // Create query
            $stmt = $conn->prepare("DELETE FROM account WHERE Email = ?");
            $stmt->bind_param("s", $email);
            
            // Execute and give feedback to the AJAX request
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response = array (
                        'success' => true,
                        'message' => 'Customer details record deleted successfully'
                    );
                } else {
                    $response = array (
                        'success' => false,
                        'message' => 'Error deleting customer record'
                    );
                }
            }
        } catch (mysqli_sql_exception $e) {
            $response = array (
                'success' => false,
                'message' => 'Error deleting customer record'
            );
        }
        echo json_encode($response);
        $stmt->close();
    } 
    else if ($typeOfDeletion == 'customerOrder') {
        // Get the value of record data keys
        $orderId = $data['orderId'];
        $customerId = $data['customerId'];
        $orderDate = $data['orderDate'];
        $totalAmount = $data['totalAmount'];

        // // Log the retrieve data to be deleted
        // error_log("CUSTOMER ORDER DATA TO BE DELETED: ".$orderId." | ".$customerId." | ".$orderDate." | ".$totalAmount);

        try {
            // Create query
            $stmt = $conn->prepare("DELETE FROM `order` WHERE OrderID = ? AND CustomerID = ? AND OrderDate = ? AND TotalAmount = ?");
            $stmt->bind_param("iisd", $orderId, $customerId, $orderDate, $totalAmount);
            
            // Execute and give feedback to the AJAX request
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response = array (
                        'success' => true,
                        'message' => 'Order record deleted successfully'
                    );
                } else {
                    $response = array (
                        'success' => false,
                        'message' => 'Error deleting order record'
                    );
                }
            }
        } catch (mysqli_sql_exception $e) {
            $response = array (
                'success' => false,
                'message' => 'Error deleting order record'
            );
        }
        echo json_encode($response);
        $stmt->close();
    }
    else if ($typeOfDeletion == 'productCatalog') {
        // Get the value of record data keys
        $productName = $data['productName'];
        $price = $data['price'];

        // // Log the retrieve data to be deleted
        // error_log("CUSTOMER ORDER DATA TO BE DELETED: ".$productName." | ".$price);

        try {
            // Create query
            $stmt = $conn->prepare("DELETE FROM `product` WHERE ProductName = ? AND Price = ?");
            $stmt->bind_param("sd", $productName, $price);
            
            // Execute and give feedback to the AJAX request
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response = array (
                        'success' => true,
                        'message' => 'Product record deleted successfully'
                    );
                } else {
                    $response = array (
                        'success' => false,
                        'message' => 'Error deleting product record'
                    );
                }
            }
        } catch (mysqli_sql_exception $e) {
            $response = array (
                'success' => false,
                'message' => 'Error deleting product record'
            );
        }
        echo json_encode($response);
        $stmt->close();
    }
?>

