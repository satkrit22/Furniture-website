<?php
session_start();
include 'includes/config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header('location:login.php');
    exit();
}

// Get date range
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Sales Report
$sales_query = "SELECT DATE(created_at) as date, SUM(total_price) as total_sales, COUNT(*) as order_count 
               FROM orders 
               WHERE created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' 
               AND status != 'cancelled' 
               GROUP BY DATE(created_at) 
               ORDER BY date";
$sales_result = mysqli_query($conn, $sales_query);

// Top Products
$products_query = "SELECT p.id, p.name, p.image, SUM(oi.quantity) as total_quantity, SUM(oi.subtotal) as total_sales 
                  FROM order_items oi 
                  JOIN products p ON oi.product_id = p.id 
                  JOIN orders o ON oi.order_id = o.id 
                  WHERE o.created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' 
                  AND o.status != 'cancelled' 
                  GROUP BY p.id 
                  ORDER BY total_sales DESC 
                  LIMIT 5";
$products_result = mysqli_query($conn, $products_query);

// Top Categories
$categories_query = "SELECT c.id, c.name, SUM(oi.quantity) as total_quantity, SUM(oi.subtotal) as total_sales 
                    FROM order_items oi 
                    JOIN products p ON oi.product_id = p.id 
                    JOIN categories c ON p.category_id = c.id 
                    JOIN orders o ON oi.order_id = o.id 
                    WHERE o.created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' 
                    AND o.status != 'cancelled' 
                    GROUP BY c.id 
                    ORDER BY total_sales DESC";
$categories_result = mysqli_query($conn, $categories_query);

// Summary
$summary_query = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(total_price) as total_revenue,
                    AVG(total_price) as average_order_value
                  FROM orders 
                  WHERE created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' 
                  AND status != 'cancelled'";
$summary_result = mysqli_query($conn, $summary_query);
$summary = mysqli_fetch_assoc($summary_result);

// Get total customers who placed orders in this period
$customers_query = "SELECT COUNT(DISTINCT user_id) as total_customers 
                   FROM orders 
                   WHERE created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
$customers_result = mysqli_query($conn, $customers_query);
$customers_data = mysqli_fetch_assoc($customers_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <?php include 'includes/header.php'; ?>

            <!-- Reports Content -->
            <div class="content">
                <div class="content-header">
                    <h2>Sales Reports</h2>
                    <button onclick="printReport()" class="btn btn-primary"><i class="fas fa-print"></i> Print Report</button>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <form action="" method="GET" class="date-filter-form">
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group" style="margin-top: 24px;">
                                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="report-summary">
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="summary-info">
                            <h3>Total Orders</h3>
                            <p><?php echo $summary['total_orders']; ?></p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="summary-info">
                            <h3>Total Revenue</h3>
                            <p>NPR.<?php echo number_format($summary['total_revenue'] ?? 0, 2); ?></p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="summary-info">
                            <h3>Average Order Value</h3>
                            <p>NPR.<?php echo number_format($summary['average_order_value'] ?? 0, 2); ?></p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="summary-info">
                            <h3>Total Customers</h3>
                            <p><?php echo $customers_data['total_customers']; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="report-charts">
                    <div class="card">
                        <div class="card-header">
                            <h3>Sales Trend</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="report-tables">
                    <div class="card">
                        <div class="card-header">
                            <h3>Top Selling Products</h3>
                        </div>
                        <div class="card-body">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity Sold</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(mysqli_num_rows($products_result) > 0){
                                        while($product = mysqli_fetch_assoc($products_result)){
                                            echo "<tr>";
                                            echo "<td class='product-cell'>";
                                            echo "<img src='../uploads/" . $product['image'] . "' alt='" . $product['name'] . "' width='40'>";
                                            echo "<span>" . $product['name'] . "</span>";
                                            echo "</td>";
                                            echo "<td>" . $product['total_quantity'] . "</td>";
                                            echo "<td>NPR." . number_format($product['total_sales'], 2) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='no-data'>No data available</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Sales by Category</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart"></canvas>
                            <table class="mt-20">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Quantity Sold</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(mysqli_num_rows($categories_result) > 0){
                                        while($category = mysqli_fetch_assoc($categories_result)){
                                            echo "<tr>";
                                            echo "<td>" . $category['name'] . "</td>";
                                            echo "<td>" . $category['total_quantity'] . "</td>";
                                            echo "<td>NPR." . number_format($category['total_sales'], 2) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='no-data'>No data available</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
        // Sales Chart
        const salesData = {
            labels: [
                <?php
                mysqli_data_seek($sales_result, 0);
                while($row = mysqli_fetch_assoc($sales_result)) {
                    echo "'" . date('M d', strtotime($row['date'])) . "',";
                }
                ?>
            ],
            datasets: [{
                label: 'Sales ($)',
                data: [
                    <?php
                    mysqli_data_seek($sales_result, 0);
                    while($row = mysqli_fetch_assoc($sales_result)) {
                        echo $row['total_sales'] . ",";
                    }
                    ?>
                ],
                backgroundColor: 'rgba(58, 110, 165, 0.2)',
                borderColor: 'rgba(58, 110, 165, 1)',
                borderWidth: 2,
                tension: 0.4
            }]
        };

        const salesConfig = {
            type: 'line',
            data: salesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Sales Trend'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };

        const salesChart = new Chart(
            document.getElementById('salesChart'),
            salesConfig
        );
        
        // Category Chart
        const categoryData = {
            labels: [
                <?php
                mysqli_data_seek($categories_result, 0);
                while($row = mysqli_fetch_assoc($categories_result)) {
                    echo "'" . $row['name'] . "',";
                }
                ?>
            ],
            datasets: [{
                label: 'Sales by Category',
                data: [
                    <?php
                    mysqli_data_seek($categories_result, 0);
                    while($row = mysqli_fetch_assoc($categories_result)) {
                        echo $row['total_sales'] . ",";
                    }
                    ?>
                ],
                backgroundColor: [
                    'rgba(58, 110, 165, 0.7)',
                    'rgba(255, 107, 107, 0.7)',
                    'rgba(255, 193, 7, 0.7)',
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(23, 162, 184, 0.7)',
                    'rgba(108, 117, 125, 0.7)'
                ],
                borderWidth: 1
            }]
        };

        const categoryConfig = {
            type: 'pie',
            data: categoryData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Sales by Category'
                    }
                }
            },
        };

        const categoryChart = new Chart(
            document.getElementById('categoryChart'),
            categoryConfig
        );
        
        // Print Report
        function printReport() {
            window.print();
        }
    </script>
    <style>
        .date-filter-form .form-row {
            align-items: flex-end;
        }
        
        .report-summary {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .summary-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }
        
        .summary-icon {
            width: 60px;
            height: 60px;
            background-color: rgba(58, 110, 165, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .summary-icon i {
            font-size: 24px;
            color: var(--primary-color);
        }
        
        .summary-info h3 {
            font-size: 14px;
            color: var(--gray-color);
            margin-bottom: 5px;
        }
        
        .summary-info p {
            font-size: 24px;
            font-weight: bold;
            color: var(--dark-color);
        }
        
        .report-charts {
            margin-bottom: 20px;
        }
        
        .report-tables {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .product-cell {
            display: flex;
            align-items: center;
        }
        
        .product-cell img {
            margin-right: 10px;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        @media (max-width: 992px) {
            .report-tables {
                grid-template-columns: 1fr;
            }
        }
        
        @media print {
            .sidebar, .header, .content-header button, .card-header form {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</body>
</html>