let myChart;

document.addEventListener('DOMContentLoaded', () => {
    retrieveUserData();
});

function retrieveUserData() {
    var email = new URLSearchParams(window.location.search).get('email');
    $.ajax({
        type: 'POST',
        url: 'fetchRecord.php',
        data: {email: email, typeOfFetch: 'userDataDashboard'},
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success) {
                userChart(data.data);
                getTotalPurchaseValue(data.data);
                getOrderTimes(data.data);
                getTotalOrders(data.data);
                averageSpend(data.data);
                spendingTrend(data.data);
                recentOrder(data.data);
            }
            else {
                document.getElementById('errorModal').style.display = 'flex';
                document.getElementById('error-text').innerText = "System Error! Unable to display user data.";
            }
        }
    })
}


function getMonthlySpending(userData) {
    const monthlyTotals = {};

    
    if (!userData || userData.length === 0) {       
        monthlyTotals[0] = 0;
        return monthlyTotals;
    }
    
    for (let i = 0; i < userData.length; i++) {
        let order = userData[i];

        let date = new Date(order.ReceiptDate);
        let month = date.getMonth();

        if (monthlyTotals[month] === undefined ) {
            monthlyTotals[month] = parseFloat(order.TotalAmount);
        } else {
            monthlyTotals[month] += parseFloat(order.TotalAmount);
        }
    }
    

    return monthlyTotals;
}


function userChart(userData) {
    // Get overall monthly spending
    var monthlySpending = getMonthlySpending(userData);

    // Sort the month
    var sortedMonths = Object.keys(monthlySpending).sort();
    
    // Create an array of zeros up to latest month
    var monthlyData = Array(Number(sortedMonths[sortedMonths.length - 1])).fill(0);

    const ctx = document.getElementById('myChart').getContext('2d');

    for (let month in monthlySpending) {
        monthlyData[month] = monthlySpending[month];
    }

    // Destroy existing chart instance if it exists
    if (myChart) {
        myChart.destroy();
    }

    // Create a new line chart instance
    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Monthly Spending',
                data: monthlyData,
                backgroundColor: '#C650C0',
                borderColor: '#C650C0',
                pointBackgroundColor: '#C650C0',
                pointRadius: 0,
                borderWidth: 2,
                tension: 0.2,    // line curvedness
                fill: true      // Fill the area under the line
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: new Date().getFullYear()
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}


function getTotalPurchaseValue(userData) {
    let totalPurchaseValue = 0;

    if (!userData || userData.length === 0) {
        document.getElementById('user-total-purchase-value').innerText = totalPurchaseValue.toFixed(2);
        return;
    }
    for (let i = 0; i < userData.length; i++) {
        totalPurchaseValue += parseFloat(userData[i].TotalAmount);
    }
    // Display
    document.getElementById('user-total-purchase-value').innerText = totalPurchaseValue.toFixed(2);
}


function getOrderTimes(userData) {
    if (!userData || userData.length === 0) {
        return;
    }

    // Sort from first time to last time of order
    userData.sort((a, b) => new Date(a.ReceiptDate) - new Date(b.ReceiptDate))
    
    let firstOrder = new Date(userData[0].ReceiptDate);
    let lastOrder = new Date(userData[userData.length - 1].ReceiptDate);

    // Get the English Format of the date
    let option = { year: 'numeric', month: 'long', day: 'numeric'};
    let formattedFirstOrder = firstOrder.toLocaleDateString('en-US', option);
    let formattedLastOrder = lastOrder.toLocaleDateString('en-US', option);
    
    // Display
    document.getElementById('user-order-time').innerText = formattedFirstOrder + ' - ' + formattedLastOrder;
}

function getTotalOrders(userData) {
    let previousMonthTotalOrders = 0;
    let totalOrders = 0;

    if (!userData || userData.length === 0) {
        document.getElementById('user-total-orders').innerText = totalOrders;
        document.getElementById('user-num-order-gap').innerText = previousMonthTotalOrders;
        return;
    }
    totalOrders = userData.length;

    for (let i = 0; i < userData.length; i++) {
        let date = new Date(userData[i].ReceiptDate);
        let month = date.getMonth();
        
        if (month == new Date().getMonth() - 1) {
            previousMonthTotalOrders++;
        }
    }

    // Compare previous to current orders for status
    let orderGap = totalOrders - previousMonthTotalOrders;
    orderGap = ((orderGap > 0 ? "+" : "")  + orderGap);

    // Display
    document.getElementById('user-total-orders').innerText = totalOrders;
    document.getElementById('user-num-order-gap').innerText = orderGap;
}


function averageSpend(userData) {
    let averageSpendUntilLastMonth = 0;
    let averageSpendUntilThisMonth = 0;
    let calculatePercentageChange = 0;
    let ordersUntilLastMonth = [];
    let ordersUntilThisMonth = [];
    let lastMonth = new Date().getMonth() - 1;

    if (!userData || userData.length === 0) {
        document.getElementById('user-average-spend').innerText = averageSpendUntilThisMonth;
        document.getElementById('user-order-percentage-change').innerText = calculatePercentageChange;
        return;
    }

    for (let i = 0; i < userData.length; i++) {
        var orderDate = new Date(userData[i].ReceiptDate);
        var orderSpend = parseFloat(userData[i].TotalAmount);

        if (orderDate.getMonth() <= lastMonth) {
            averageSpendUntilLastMonth += orderSpend;
            ordersUntilLastMonth.push(userData[i]);
        }
        averageSpendUntilThisMonth += orderSpend;
        ordersUntilThisMonth.push(userData[i]);
    }

    // Get average spend until last month
    if (ordersUntilLastMonth.length > 0) {
        averageSpendUntilLastMonth = (averageSpendUntilLastMonth / ordersUntilLastMonth.length).toFixed(2);
    }

    // Get average spend until this month
    if (ordersUntilThisMonth.length > 0) {
        averageSpendUntilThisMonth = (averageSpendUntilThisMonth / ordersUntilThisMonth.length).toFixed(2);
    }

    // Get the percentage change
    if (averageSpendUntilLastMonth > 0 && averageSpendUntilThisMonth > 0) {
        calculatePercentageChange = parseFloat(((averageSpendUntilThisMonth - averageSpendUntilLastMonth) / averageSpendUntilLastMonth) * 100).toFixed(2);
    }

    if (calculatePercentageChange > 0) {
        calculatePercentageChange = "+" + calculatePercentageChange + "%";
    }
    else if (calculatePercentageChange < 0){
        calculatePercentageChange = calculatePercentageChange + "%";
    }

    // Display
    document.getElementById('user-average-spend').innerText = averageSpendUntilThisMonth;
    document.getElementById('user-order-percentage-change').innerText = calculatePercentageChange;
}


function spendingTrend(userData) {
    let monthlyTotals = {};
    let totalSpending = 0;

    if (!userData || userData.length === 0) {
        document.getElementById('user-spending-trend-percentage').innerText = '0 %';
        return;
    }
    for (let i = 0; i < userData.length; i++) {
        let date = new Date(userData[i].ReceiptDate);
        let monthYear = date.getMonth() + "-" + date.getFullYear();

        // If this month has not been added to the totals yet, add it
        if (!monthlyTotals[monthYear]) {
            monthlyTotals[monthYear] = 0;
        }

        // Append or add the amount
        let amount = parseFloat(userData[i].TotalAmount);
        monthlyTotals[monthYear] += amount;
        
        // Add the amount to the total spending
        totalSpending += amount;
    }

    // Convert the monthly totals to percentages of the total spending
    for (let monthYear in monthlyTotals) {
        monthlyTotals[monthYear] = (monthlyTotals[monthYear] / totalSpending) * 100;
    }

    // Find the month with the highest spending percentage
    let highestPercentage = 0;
    for (let monthYear in monthlyTotals) {
        if (monthlyTotals[monthYear] > highestPercentage) {
            highestPercentage = monthlyTotals[monthYear];
        }
    }
    highestPercentage = parseInt(highestPercentage) + " %";
    // Display
    document.getElementById('user-spending-trend-percentage').innerText = highestPercentage;
}


function recentOrder(userData) {
    var getLastOrderId = userData[userData.length - 1].OrderID;

    if (userData.length === 0) {
        document.getElementById('user-recent-order').innerText = "No recent order";
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'fetchRecord.php',
        data: {getLastOrderId: getLastOrderId, typeOfFetch: 'userRecentOrder'},
        success: function(response) {
            var data = JSON.parse(response);

            if (data.success) {
                var length = data.orderItem.length;
                var recentOrderProduct = data.orderItem[length - 1].ProductName;
                document.getElementById('user-recent-order').innerText = recentOrderProduct;
            }
            else {
                if (data.message.includes("No recent order")) {
                    document.getElementById('user-recent-order').innerText = "No recent order";
                }
                else {
                    document.getElementById('errorModal').style.display = 'flex';
                    document.getElementById('error-text').innerText = "Invalid email or password";
                }
            }
        }   
    });
}