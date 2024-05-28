<?php
include 'db_connect.php';
include 'retrieve_record.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Orders</title>
    </head>
    <body>
        <form method="post" action="">
            <!-- SEARCH FILTER -->
            <input type="text" name="searchFilter" placeholder="Search here..." value="<?php echo isset($_POST['searchFilter']) ? $_POST['searchFilter'] : ''?>">
            <!-- ORDER ID DROPDOWN -->
            <select name="orderId">
                <option value="" <?php if (isset($_POST['orderId']) && $_POST['orderId'] == '') echo 'selected'; ?>>Default</option>
                <?php 
                    $sql = "SELECT DISTINCT OrderID FROM `order` ORDER BY OrderID ASC";
                    $result_product_name = $conn->query($sql);
                    if ($result_product_name->num_rows > 0) {
                        while ($row = $result_product_name->fetch_assoc()) {
                            $selected = (isset($_POST['orderId']) && $_POST['orderId'] == $row['OrderID']) ? 'selected' : '';
                            echo "<option value='".$row['OrderID']."' $selected>" . $row['OrderID'] . "</option>";
                        }
                    }
                ?> 
            </select>
            <!-- CUSTOMER ID DROPDOWN -->
            <select name="customerId">
                <option value="" <?php if (isset($_POST['customerId']) && $_POST['customerId'] == '') echo 'selected'; ?>>Default</option>
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
            <!-- ORDER DATE DROPDOWN -->
            <input type="date" name="orderDate" value="<?php echo isset($_POST['orderDate']) ? $_POST['orderDate'] : '' ?>">
            <input type="submit" name="search" value="Search">
        </form>
                    
        <!-- TABLE DISPLAY -->
        <h1>Customer</h1>
        <table border='1'>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Order Date</th>
                <th>Amount</th>
            </tr>
            <?php
                $sql = "SELECT customer.*, `order`.* FROM customer INNER JOIN `order` ON customer.CustomerID = `order`.CustomerID";
                $whereAdded = false;
                // Search filter
                if (isset($_POST['search'])) {
                    $search = $_POST['searchFilter'];
                    if (!empty($search)) {
                        $searchParts = explode(" ", $search);
                        $firstName = $searchParts[0];
                        $lastName = isset($searchParts[1]) ? $searchParts[1] : '';
                        $sql .= " WHERE (`order`.OrderID LIKE '%$search%' OR customer.CustomerID LIKE '%$search%' OR (customer.FirstName LIKE '%$firstName%' AND customer.LastName LIKE '%$lastName%') OR `order`.OrderDate LIKE '%$search%' OR `order`.TotalAmount LIKE '%$search%')";
                        $whereAdded = true;
                    }
                }
                // Order ID filter
                if (isset($_POST['orderId'])) {
                    $orderId = $_POST['orderId'];
                    if (!empty($orderId)) {
                        $sql .= $whereAdded ? " AND `order`.OrderID = '$orderId'" : " WHERE `order`.OrderID = '$orderId'";
                        $whereAdded = true;
                    }
                }
                // Customer ID filter
                if (isset($_POST['customerId'])) {
                    $customerId = $_POST['customerId'];
                    if (!empty($customerId)) {
                        $sql .= $whereAdded ? " AND customer.CustomerID = '$customerId'" : " WHERE customer.CustomerID = '$customerId'";
                        $whereAdded = true;
                    }
                }
                // Order Date filter
                if (isset($_POST['orderDate'])) {
                    $orderDate = $_POST['orderDate'];
                    if (!empty($orderDate)) {
                        $sql .= $whereAdded ? " AND `order`.OrderDate = '$orderDate'" : " WHERE `order`.OrderDate = '$orderDate'";
                    }
                }
                // Display Result
                $result_customer = $conn->query($sql);
                if ($result_customer->num_rows > 0) {
                    while ($row = $result_customer->fetch_assoc()) {
                        echo "<tr><td>" . $row['OrderID'];
                        echo "</td><td>" . $row['CustomerID'];
                        echo "</td><td>" . $row['FirstName'] . " " . $row['LastName'];
                        echo "</td><td>" . $row['OrderDate'];
                        echo "</td><td>" . $row['TotalAmount'];
                        echo "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No category data</td></tr>";
                }
            ?>      
        </table>
    </body>
</html>