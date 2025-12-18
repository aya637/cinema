<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <link rel="stylesheet" href="../../public/css/admin-dashboard.css">
    <link rel="stylesheet" href="../../public/css/Sales.css">
</head>
<body>
<div class="dashboard" style="grid-template-columns: 1fr;"> <header style="display:flex; justify-content:space-between; align-items:center; padding: 20px;">
        <h1>💰 Financial Sales Report</h1>
        <a href="/onlinecinemabookingsystem/public/admin" class="tab">← Back to Overview</a>
    </header>

    <main style="padding: 20px;">
        
        <div class="glassmorphism" style="margin-bottom: 30px;">
            <h2>Revenue by Movie</h2>
            <table>
                <thead>
                    <tr><th>Movie</th><th>Tickets Sold</th><th>Total Revenue</th></tr>
                </thead>
                <tbody>
                    <?php foreach($revenueByMovie as $row): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                        <td><?= $row['tickets_sold'] ?></td>
                        <td class="money-col">$<?= number_format($row['revenue'] ?? 0, 2) ?></td>>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="glassmorphism">
            <h2>Daily Sales Breakdown</h2>
            <table>
                <thead>
                    <tr><th>Date</th><th>Tickets Sold</th><th>Revenue</th></tr>
                </thead>
                <tbody>
                    <?php foreach($dailySales as $day): ?>
                    <tr>
                        <td><?= date('F j, Y', strtotime($day['date'])) ?></td>
                        <td><?= $day['total_tickets'] ?></td>
                        <td style="color: #2ecc71; font-weight: bold;">
                            $<?= number_format($day['total_revenue'], 2) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button onclick="window.print()" class="btn primary" style="margin-top: 20px;">Print Report</button>
    </main>
</div>
</body>
</html>