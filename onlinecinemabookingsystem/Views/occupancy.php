<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Occupancy Report</title>
    <link rel="stylesheet" href="../../public/css/admin-dashboard.css">
    <link rel="stylesheet" href="../../public/css/Occupancy.css">
</head>
<body>
<div class="dashboard" style="grid-template-columns: 1fr;">
    
    <header style="display:flex; justify-content:space-between; align-items:center; padding: 20px;">
        <h1>📊 Theater Occupancy Report</h1>
        <a href="/onlinecinemabookingsystem/public/admin" class="tab">← Back to Overview</a>
    </header>

    <main style="padding: 20px;">
        <div class="glassmorphism">
            <table>
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Screen</th>
                        <th>Time</th>
                        <th>Occupancy Rate</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($occupancyData as $row): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                        <td><?= htmlspecialchars($row['screen_name']) ?></td>
                        <td><?= date('M d, g:i A', strtotime($row['start_time'])) ?></td>
                        
                        <td style="width: 200px;">
                            <div style="background: #374151; height: 20px; border-radius: 10px; overflow: hidden; position: relative;">
                                <div style="width: <?= $row['percentage'] ?>%; 
                                            background: <?= ($row['percentage'] > 80) ? '#2ecc71' : (($row['percentage'] < 30) ? '#ef4444' : '#f59e0b'); ?>; 
                                            height: 100%;">
                                </div>
                                <span style="position: absolute; top:0; left:50%; transform:translateX(-50%); font-size:12px; color: white;">
                                    <?= round($row['percentage']) ?>%
                                </span>
                            </div>
                        </td>

                        <td>
                            <?php if($row['percentage'] >= 90): ?>
                                <span style="color: #2ecc71; font-weight:bold;">SOLD OUT 🔥</span>
                            <?php elseif($row['percentage'] <= 20): ?>
                                <span style="color: #ef4444;">Low Interest ⚠️</span>
                            <?php else: ?>
                                <span>Healthy</span>
                            <?php endif; ?>
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