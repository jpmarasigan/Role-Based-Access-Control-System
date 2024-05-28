<?php
include 'db_connect.php';

// SQL query for category table
$sql_category = "SELECT * FROM category";
$result_category = $conn->query($sql_category);

// SQL query for customer table
$sql_customer = "SELECT * FROM customer";
$result_customer = $conn->query($sql_customer);

// SQL query for order table
$sql_order = "SELECT * FROM `order`";
$result_order = $conn->query($sql_order);

// SQL query for product table
$sql_product = "SELECT * FROM customer";
$result_product = $conn->query($sql_product);
?>