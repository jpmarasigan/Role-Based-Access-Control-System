<?php
include 'db_connect.php';
include 'retrieve_record.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Catalog</title>
    </head>
    <body>
        <form method="post" id="productCatalogForm">
            <!-- SEARCH FILTER -->
            <input type="text" name="searchFilter" placeholder="Search here..." value="<?php echo isset($_POST['searchFilter']) ? $_POST['searchFilter'] : ''?>">
            <!-- SORT BY DROPDOWN -->
            <select name="sortBy">
                <option value="" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == '') echo 'selected'; ?>>Default</option>
                <option value="ProductName" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'ProductName') echo 'selected'; ?>>Product Name</option>
                <option value="CategoryName" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'CategoryName') echo 'selected'; ?>>Category Name</option>
                <option value="Price" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'Price') echo 'selected'; ?>>Price</option>
            </select>
            <!-- CATEGORY NAME DROPDOWN -->
            <select name="categoryName">
                <option value="" <?php if (isset($_POST['categoryName']) && $_POST['categoryName'] == '') echo 'selected'; ?>>Default</option>
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
            <input type="submit" name="search" value="Search">
        </form>

        <!-- TABLE DISPLAY -->
        <table border='1'>
            <tr>
                <th>Product Name</th>
                <th>Category Name</th>
                <th>Price</th>
            </tr>
            <?php 
                $sql = "SELECT `product`.*, `category`.CategoryName FROM `category` INNER JOIN `product` ON `category`.CategoryID = `product`.CategoryID";
                $whereAdded = false;
                
                // Search filter
                if (isset($_POST['search'])) {
                    $search = $_POST['searchFilter'];
                    if (!empty($search)) {
                        $sql .= " WHERE `product`.ProductName LIKE '%$search%' OR `category`.CategoryName LIKE '%$search%' OR `product`.Price LIKE '%$search%'";
                        $whereAdded = true;
                    }
                }
                // Category filter
                if (isset($_POST['categoryName'])) {
                    $categoryName = $_POST['categoryName'];
                    if (!empty($categoryName)) {
                        $sql .= $whereAdded ? " AND `category`.CategoryName = '$categoryName'" : " WHERE `category`.CategoryName =  '$categoryName'";
                        $whereAdded = true;
                    }
                }
                // Sort by
                if (isset($_POST['sortBy'])) {
                    $sort = $_POST['sortBy'];
                    if (!empty($sort)) {
                        $sql .= " ORDER BY $sort";
                    }
                }

                // Display result
                $result_product = $conn->query($sql);
                if ($result_product->num_rows > 0) {
                    while ($row = $result_product->fetch_assoc()) {
                        echo "<tr><td>" . $row['ProductName'];
                        echo "</td><td>" . $row['CategoryName'];
                        echo "</td><td>" . $row['Price'];
                        echo "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No category data</td></tr>";
                }
            ?>
        </table>
    </body>
</html>