let myChart;

document.addEventListener('DOMContentLoaded', function() {
    retrieveAdminData();
});

function retrieveAdminData() {
    $.ajax({
        type: 'POST',
        url: 'fetchRecord.php',
        data: {typeOfFetch: "adminDataDashboard"},
        success: function(response) {
            var data = JSON.parse(response);

            if (data.success) {
                adminChart(data.data);
                getTopCustomer(data.data.topCustomer);
                getAverageOrderValue(data.data.order);
                getTotalOrders(data.data.order);
                getTopSellingProduct(data.data.topProduct);
            }
            else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = "System Error! Unable to display user data.";
            }
        }
    });
}


function adminChart(adminData) {
    const ctx = document.getElementById('myChart').getContext('2d');

    var monthlyRevenue = getMonthlyRevenue(adminData.order);

    if (myChart) {
        myChart.destroy();
    }

    // Create data
    var data = {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        datasets: [{
            label: "Sales Revenue Over Time",
            data: monthlyRevenue,
            backgroundColor: [
                'rgba(185, 81, 194, 0.2)',
                'rgba(178, 81, 194, 0.2)',
                'rgba(159, 82, 196, 0.2)',
                'rgba(151, 83, 198, 0.2)',
                'rgba(122, 84, 201, 0.2)',
                'rgba(107, 86, 203, 0.2)',
                'rgba(107, 86, 203, 0.2)',
                'rgba(122, 84, 201, 0.2)',
                'rgba(151, 83, 198, 0.2)',
                'rgba(159, 82, 196, 0.2)',
                'rgba(178, 81, 194, 0.2)',
                'rgba(185, 81, 194, 0.2)'
            ],
            borderColor: [
                'rgb(185, 81, 194)',
                'rgb(178, 81, 194)',
                'rgb(159, 82, 196)',
                'rgb(151, 83, 198)',
                'rgb(122, 84, 201)',
                'rgb(107, 86, 203)',
                'rgb(107, 86, 203)',
                'rgb(122, 84, 201)',
                'rgb(151, 83, 198)',
                'rgb(159, 82, 196)',
                'rgb(178, 81, 194)',
                'rgb(185, 81, 194)'
            ],
            borderWidth: 1
        }]
    }
    // Create a new bar chart instance
    myChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          },
    })
}


function getMonthlyRevenue(orderData) {
    var monthlyRevenue = Array(12).fill(0);

    orderData.forEach(function(order) {
        var date = new Date(order.OrderDate);
        var month = date.getMonth();
        var revenue = Number(order.TotalAmount);

        monthlyRevenue[month] += revenue;
    })

    // Round off to 2 decimal
    monthlyRevenue = monthlyRevenue.map(function(revenue) {
        return Number(revenue.toFixed(2));
    });

    // Display the overall total revenue
    var totalRevenue = Number(monthlyRevenue.reduce((a, b) => a + b, 0)).toFixed(2);
    document.getElementById('admin-total-revenue').innerText =  totalRevenue;
    return monthlyRevenue;
}


function getTopCustomer(topCustomer) {
    var name = topCustomer.name.FirstName + " " + topCustomer.name.LastName;
    var totalSpend = topCustomer.totalPurchase;
    var totalOrders = topCustomer.totalOrders;

    // Display the top customer details
    document.getElementById('admin-top-customer').innerText = name;
    document.getElementById('admin-total-purchase').innerText = totalSpend;
    document.getElementById('admin-top-customer-total-orders').innerText = totalOrders;
}


function getAverageOrderValue(order) {
    var totalOrders = order.length;
    var totalRevenue = order.reduce(function(acc, order) {
        return acc + Number(order.TotalAmount);
    }, 0);

    var averageOrderValue = (totalRevenue / totalOrders).toFixed(2);

    // Get the increased or decreased percentage of average order value from previous month average
    var currentMonth = new Date().getMonth();
    var previousTotalOrders = order.filter(function(order) {
        var orderDate = new Date(order.OrderDate);
        return orderDate.getMonth() != currentMonth;
    });

    var previousTotalRevenue = previousTotalOrders.reduce(function(acc, order) {
        return acc + Number(order.TotalAmount);
    }, 0);

    var percentageGap = parseFloat((totalRevenue - previousTotalRevenue) / previousTotalOrders.length).toFixed(2);
    
    if (percentageGap >= 0) {
        percentageGap = "+ " + percentageGap;
    }

    // Display the average order value
    document.getElementById('admin-average-order-value').innerText = averageOrderValue;
    document.getElementById('admin-average-order-value-percentage-gap').innerText = percentageGap;
}


function getTotalOrders(order) {
    var totalOrders = order.length;

    // Calculate previous month total orders (excluding current month)
    var currentMonth = new Date().getMonth();
    var previousTotalOrders = order.filter(function(order) {
        var orderDate = new Date(order.OrderDate);
        return orderDate.getMonth() != currentMonth;
    });

    // Get the gap
    var gap = totalOrders - previousTotalOrders.length;

    // Add +symbol
    if (gap >= 0) {
        gap = "+ " + gap;
    }
    
    document.getElementById('admin-total-orders').innerText = totalOrders;
    document.getElementById('admin-total-orders-gap').innerText = gap;
}


function getTopSellingProduct(product) {
    document.getElementById('admin-top-product').innerText = product.productName;
}