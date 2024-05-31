<?php
include 'db_connect.php';
include 'retrieve_record.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Details</title>
    </head>
    <body>
        <form method="post" action="">
            <!-- SEARCH FILTER -->
            <input type="text" name="searchFilter" placeholder="Search here..." value="<?php echo isset($_POST['searchFilter']) ? $_POST['searchFilter'] : ''?>">
            <!-- SORT BY DROPDOWN -->
            <select name="sortBy">
                <option value="" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == '') echo 'selected'; ?>>Default</option>
                <option value="CustomerID" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'CustomerID') echo 'selected'; ?>>Customer ID</option>
                <option value="FirstName" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'FirstName') echo 'selected'; ?>>First Name</option>
                <option value="LastName" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'LastName') echo 'selected'; ?>>Last Name</option>
                <option value="Email" <?php if (isset($_POST['sortBy']) && $_POST['sortBy'] == 'Email') echo 'selected'; ?>>Email</option>
            </select>
            <input type="submit" name="search" value="Search">
        </form>

        <!-- TABLE DISPLAY -->
        <h1>Category</h1>
        <table border='1'>
            <tr>
                <th>Customer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
            <?php
                // Initialize default SQL query
                $sql = "SELECT * FROM customer";
                // Search filter
                if (isset($_POST['search'])) {
                    $search = $_POST['searchFilter'];
                    if (!empty($search)) {
                        $sql .= " WHERE CustomerID LIKE '%$search%' OR FirstName LIKE '%$search%' OR LastName LIKE '%$search%' OR Email LIKE '%$search%'";
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
                $result_category = $conn->query($sql);
                if ($result_category->num_rows > 0) {
                    while ($row = $result_category->fetch_assoc()) {
                        echo "<tr><td>" . $row['CustomerID'] . "</td><td>" . $row['FirstName'] . "</td><td>" . $row['LastName'] . "</td><td>" . $row['Email'] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No category data</td></tr>";
                }
            ?>
        </table>
    </body>
</html>
