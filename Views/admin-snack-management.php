<?php 
    // This variable is assumed to be passed or accessed via session in AdminController
    $snackNotifications = $_SESSION['admin_snack_notifications'] ?? [];
    // $snacks is passed from the AdminController::manageSnacks()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Snack Management</title>
    <link rel="stylesheet" href="/onlinecinemabookingsystem/public/css/style.css"> 
    <link rel="stylesheet" href="/onlinecinemabookingsystem/public/css/snacksmagment.css"> 
</head>

<body>
   <div class="page-header">
    <div class="page-header-content">
        <h1>🍿 Snack Inventory Management</h1>
        <p>Manage the stock levels and pricing for all concession items.</p>
    </div>
    <a href="/onlinecinemabookingsystem/public/index.php?url=admin/dashboard" class="btn-back">
        ← Back to Dashboard
    </a>
</div>
        <div class="mb-6">
            <?php if (!empty($snackNotifications)): ?>
                <div class="low-stock-alert">
                    <h3 class="mt-0">🚨 CRITICAL STOCK ALERTS</h3>
                    <ul>
                    <?php foreach ($snackNotifications as $notification): ?>
                        <li>
                            <?php echo $notification['message']; ?>
                            <a href="#snack-<?php echo $notification['id']; ?>">(Review Item)</a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <div class="glassmorphism">
            <h2>All Concession Items</h2>
            
            <table class="stock-table"> 
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Current Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($snacks as $snack): 
                        $isLowStock = $snack['stock_quantity'] <= 10;
                        $rowClass = $isLowStock ? 'low-stock-row' : '';
                        $stockClass = $isLowStock ? 'low' : 'ok';
                    ?>
                    <tr id="snack-<?php echo $snack['id']; ?>" class="<?php echo $rowClass; ?>">
                        <td><?php echo $snack['id']; ?></td>
                        <td><?php echo htmlspecialchars($snack['name']); ?></td>
                        <td><?php echo htmlspecialchars($snack['category']); ?></td>
                        <td>$<?php echo number_format($snack['base_price'], 2); ?></td>
                        <td class="stock-quantity <?php echo $stockClass; ?>">
                            <?php echo $snack['stock_quantity']; ?>
                        </td>
                        <td>
                            <form method="POST" action="index.php?url=admin/updateSnackStock" class="stock-update-form">
                                <input type="hidden" name="snack_id" value="<?php echo $snack['id']; ?>">
                                <input type="number" name="new_stock" value="<?php echo $snack['stock_quantity']; ?>" min="0" required>
                                <button type="submit" class="btn-update">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>