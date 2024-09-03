# Role Based Access Control System

### Pre-requisite
- Must have XAMPP Control Panel and knowledgeable in using PHPMyAdmin
- Create schema and named it "ecommerce"
- Create 6 tables ( account, category, customer, order, orderitem, product )

### Table Structure
1. "**account**" Table
    - AccountID (*int*)(*primary*)
    - Email (*varchar*)(*foreign*)
    - Password (*varchar*)
2. "**category**" Table
    - CategoryID (*varchar*)(*primary*)
    - CategoryName (*varchar*)
3. "**customer**" Table
    - CustomerID (*int*)(*primary*)
    - FirstName (*varchar*)
    - LastName (*varchar*)
    - Email (*varchar*)(*foreign*)
4. "**order**" Table
    - OrderID (*varchar*)(*primary*)
    - CustomerID (*int*)(*foreign*)
    - OrderDate (*date*)
    - ReceiptDate (*date*)
    - TotalAmount (*decimal*)
5. "**orderitem**" Table
    - OrderItemID (*int*)(*primary*)
    - OrderID (*varchar*)(*foreign*)
    - ProductID (*varchar*)(*foreign*)
    - Quantity (*int*)
    - Price (*float*)
6. "**product**" Table
    - ProductID (*varchar*)(*primary*)
    - ProductName (*varchar*)
    - CategoryID (*varchar*)(*foreign*)
    - Price (*decimal*)

### Main file
  - Run ***index.php*** in XAMPP directory localhost or PHP Server in VSCode Extension
  

