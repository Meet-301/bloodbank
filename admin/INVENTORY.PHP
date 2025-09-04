<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Fetch total blood units by blood group
$sql = "SELECT BloodGroup, SUM(BloodUnits) as total_units FROM tblblooddonars GROUP BY BloodGroup";
$query = $dbh->prepare($sql);
$query->execute();
$blood_data = $query->fetchAll(PDO::FETCH_ASSOC);

// Prepare stats and chart data
$labels = [];
$data = [];
$status_counts = ['critical' => 0, 'moderate' => 0, 'good' => 0];
$total_units = 0;
foreach ($blood_data as $row) {
    $labels[] = $row['BloodGroup'];
    $count = (int)$row['total_units'];
    $data[] = $count;
    $total_units += $count;
    if ($count < 25) $status_counts['critical']++;
    elseif ($count <= 75) $status_counts['moderate']++;
    else $status_counts['good']++;
}
// Pie chart data for inventory status
$status_pie = [$status_counts['good'], $status_counts['moderate'], $status_counts['critical']];

// Count of available donors: donors with BloodUnits > 0
$donors_count_sql = "SELECT COUNT(*) as count FROM tblblooddonars WHERE BloodUnits > 0";
$donor_count_query = $dbh->prepare($donors_count_sql);
$donor_count_query->execute();
$available_donors = $donor_count_query->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

// Fetch latest 10 donors for recent activity table
$donor_sql = "SELECT * FROM tblblooddonars ORDER BY id DESC LIMIT 10";
$donor_query = $dbh->prepare($donor_sql);
$donor_query->execute();
$recent_donors = $donor_query->fetchAll(PDO::FETCH_ASSOC);

// Identify critical blood groups (units < 25)
$critical_groups = [];
foreach ($blood_data as $row) {
    if ($row['total_units'] < 25) {
        $critical_groups[] = $row['BloodGroup'];
    }
}
$expiring_soon = 0; // Placeholder, add logic if expiry info available

// Prepare CSV for download
$csv_content = "BloodGroup,TotalUnits,Status\n";
for ($i = 0; $i < count($labels); $i++) {
    $stat = ($data[$i] < 25) ? 'Critical' : (($data[$i] <= 75) ? 'Moderate' : 'Good');
    $csv_content .= "{$labels[$i]},{$data[$i]},$stat\n";
}
$csv_content = addslashes($csv_content);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Blood Inventory Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="images/icon.jpg" type="image/jpeg" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', 'Open Sans', Arial, sans-serif;
            background: #f7f8fa;
            color: #212529;
        }
        .dashboard-header {
            background: linear-gradient(98deg, #d90429 0 60%, #ef233c 100%);
            color: #fff;
            padding: 38px 0 18px;
            border-bottom: 3.5px solid #b70c1c;
            box-shadow: 0 8px 24px -18px #d90429;
        }
        .dashboard-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        .main-content {
            max-width: 1250px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .top-actions {
            display: flex;
            gap: 14px;
            margin: 22px 0 14px 0;
            justify-content: flex-end;
        }
        .dash-btn {
            background: #d90429;
            color: #fff;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            padding: 9px 23px;
            margin: 0 1.5px;
            transition: background 0.17s;
        }
        .dash-btn:hover,
        .dash-btn:focus {
            background: #b70c1c;
            color: #fff;
        }
        .dropdown-toggle::after {
            margin-left: 7px;
        }
        .dropdown-menu {
            min-width: 9.7rem;
        }
        .dropdown-item:active {
            background: #d90429;
            color: #fff;
        }
        .widgets-row {
            display: flex;
            gap: 1.8rem;
            margin-bottom: 32px;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .widget-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1.5px 18px rgba(221, 53, 69, 0.08);
            padding: 28px 30px 18px;
            min-width: 190px;
            flex: 1 1 200px;
            transition: box-shadow 0.15s;
            border: 1.5px solid #f2f2f7;
            position: relative;
            text-align: center;
        }
        .widget-box:hover {
            box-shadow: 0 4px 36px rgba(190, 29, 46, 0.11);
        }
        .widget-icon {
            font-size: 1.65rem;
            background: #fbeaec;
            border-radius: 12px;
            margin-bottom: 17px;
            color: #d90429;
            width: 2.4em;
            height: 2.4em;
            line-height: 2.3em;
            text-align: center;
            display: inline-block;
        }
        .widget-value {
            font-size: 2.04rem;
            font-weight: 700;
            color: #222;
        }
        .widget-label {
            font-size: 1.03em;
            color: #777;
            margin-bottom: 3px;
        }
        .widget-note {
            font-size: 0.97em;
            color: #d90429;
            font-weight: 500;
            line-height: 1.9;
            margin-bottom: 0;
        }
        .charts-row {
            display: flex;
            gap: 1.6rem;
            margin-bottom: 36px;
            flex-wrap: wrap;
        }
        .dashboard-card {
            background: #fff;
            border-radius: 13px;
            box-shadow: 0 3px 18px rgba(230, 57, 70, 0.06);
            padding: 28px 22px 22px 22px;
            flex: 1 1 100px;
            min-width: 320px;
        }
        .chart-box {
            min-height: 310px;
        }
        .card-title {
            font-weight: 600;
            font-size: 1.06rem;
            color: #d90429;
            margin-bottom: 0.95rem;
        }
        .pie-legend {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 17px;
            justify-content: center;
            font-size: 0.97em;
        }
        .pie-legend li::before {
            content: '';
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 3px;
            margin-right: 6px;
        }
        .pie-legend .good::before {
            background: #138a36;
        }
        .pie-legend .moderate::before {
            background: #fb8500;
        }
        .pie-legend .critical::before {
            background: #d90429;
        }
        .activity-section {
            margin-bottom: 40px;
        }
        .table th {
            background: #d90429;
            color: #fff;
            border: none;
        }
        .table td,
        .table th {
            vertical-align: middle;
        }
        .blood-badge {
            border-radius: 5.5px;
            padding: 2px 12px;
            font-size: 1em;
            color: #fff;
            font-weight: 600;
        }
        .stat-good {
            background: #138a36;
        }
        .stat-moderate {
            background: #fb8500;
        }
        .stat-critical {
            background: #d90429;
        }
        @media (max-width: 900px) {
            .widgets-row,
            .charts-row {
                flex-direction: column;
                gap: 18px;
            }
            .widget-box,
            .dashboard-card {
                width: 100%;
                min-width: 240px;
            }
        }
        @media print {
            nav,
            .dash-btn,
            .dropdown,
            .pie-legend,
            .dashboard-header,
            .top-actions {
                display: none;
            }
            .dashboard-card,
            .widget-box {
                box-shadow: none !important;
                border: 1px solid #eee;
            }
            body {
                background: #fff;
            }
            .table {
                font-size: 12pt;
            }
        }
    </style>
     <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-bg: #1a2236;
            --card-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: #4a5568;
        }
        
        .page-title {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(67, 97, 238, 0.2);
        }
        
        /* Dashboard Cards */
        .dashboard-card {
            border-radius: 12px;
            border: none;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
            height: 100%;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .dashboard-card .card-header {
            border-bottom: none;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.25rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .dashboard-card .card-body {
            padding: 1.75rem 1.5rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
            color: var(--dark);
        }
        
        .stat-title {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #718096;
            font-weight: 600;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
        }
        
        .card-link {
            display: block;
            padding: 0.75rem 1.5rem;
            background-color: rgba(67, 97, 238, 0.05);
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .card-link:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .card-link i {
            transition: transform 0.3s ease;
        }
        
        .card-link:hover i {
            transform: translateX(3px);
        }
        
        /* Summary Section */
        .summary-section {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .summary-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary);
        }
        
        /* Activity Section */
        .activity-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            height: 100%;
        }
        
        .activity-item {
            display: flex;
            align-items: flex-start;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .bg-icon-primary {
            background-color: rgba(67, 97, 238, 0.15);
            color: var(--primary);
        }
        
        .bg-icon-success {
            background-color: rgba(76, 201, 240, 0.15);
            color: #4cc9f0;
        }
        
        .bg-icon-warning {
            background-color: rgba(247, 37, 133, 0.15);
            color: #f72585;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-time {
            font-size: 0.85rem;
            color: #a0aec0;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stat-number {
                font-size: 2rem;
            }
            
            .dashboard-card .card-body {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
   <?php include('includes/header.php');?>

    <div class="ts-main-content">
        <?php include('includes/leftbar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid pt-4">
                <div class="row mb-4">
                    <div class="col">
                        <h2 class="page-title">Dashboard Overview</h2>
                        <p class="text-muted">Welcome back! Here's what's happening with your system today.</p>
                    </div>
                </div>

    <div class="main-content">
        <div class="top-actions">
            <div class="dropdown">
                <button class="dash-btn dropdown-toggle" type="button" id="downloadBtn" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download me-1"></i><b style="color:red;"> Download</b>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadBtn">
                    <li><button class="dropdown-item" onclick="exportCSV()">Download CSV</button></li>
                    <li><button class="dropdown-item" onclick="window.print()">Save as PDF</button></li>
                </ul>
            </div>
            <button class="dash-btn" onclick="location.reload()">
                <i class="fas fa-rotate"></i> Refresh
            </button>
        </div>
        <div class="widgets-row">
            <div class="widget-box">
                <span class="widget-icon"><i class="fas fa-tint"></i></span>
                <div class="widget-value"><?= $total_units ?></div>
                <div class="widget-label">Total Blood Units</div>
            </div>
            <div class="widget-box">
                <span class="widget-icon"><i class="fas fa-user"></i></span>
                <div class="widget-value"><?= $available_donors ?></div>
                <div class="widget-label">Available Donors</div>
            </div>
            <div class="widget-box">
                <span class="widget-icon"><i class="fas fa-triangle-exclamation"></i></span>
                <div class="widget-value" style="font-size:1.15rem;"><?= count($critical_groups) ?> Groups</div>
                <div class="widget-note">Critical Level</div>
            </div>
            <div class="widget-box">
                <span class="widget-icon"><i class="fas fa-clock"></i></span>
                <div class="widget-value" style="font-size:1.08rem;"><?= $expiring_soon ?> Units</div>
                <div class="widget-label">Expiring Soon</div>
            </div>
        </div>
        <div class="charts-row">
            <div class="dashboard-card" style="min-width:350px;">
                <div class="card-title">Blood Group Distribution</div>
                <div class="chart-box">
                    <canvas id="groupChart" height="110"></canvas>
                </div>
            </div>
            <div class="dashboard-card" style="max-width:330px;">
                <div class="card-title">Inventory Status</div>
                <div class="chart-box">
                    <canvas id="pieChart" height="150"></canvas>
                </div>
                <ul class="pie-legend mt-1">
                    <li class="good">Available</li>
                    <li class="moderate">Low</li>
                    <li class="critical">Critical</li>
                </ul>
            </div>
        </div>
        <div class="dashboard-card activity-section">
            <div class="card-title mb-4">Recent Inventory Activity</div>
            <div class="table-responsive mt-1">
                <table class="table align-middle mb-0 border rounded">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Donor Name</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Blood Group</th>
                            <th>Units</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$recent_donors): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No recent activity found</td>
                            </tr>
                        <?php else:
                        foreach ($recent_donors as $donor) :
                            $blood_stat = ($donor['BloodUnits'] < 25) ? 'stat-critical' : (($donor['BloodUnits'] <= 75) ? 'stat-moderate' : 'stat-good');
                        ?>
                            <tr>
                                <td><?= htmlentities(isset($donor['PostingDate']) ? date('d-M-Y', strtotime($donor['PostingDate'])) : '-') ?></td>
                                <td><?= htmlentities($donor['FullName']) ?></td>
                                <td><?= htmlentities($donor['Gender']) ?></td>
                                <td><?= htmlentities($donor['Age']) ?></td>
                                <td><?= htmlentities($donor['BloodGroup']) ?></td>
                                <td><span class="blood-badge <?= $blood_stat ?>"><?= htmlentities($donor['BloodUnits']) ?></span></td>
                                <td><?= htmlentities($donor['EmailId']) ?></td>
                            </tr>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const groupChart = new Chart(document.getElementById('groupChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Units',
                    data: <?= json_encode($data) ?>,
                    backgroundColor: <?php
                        $colors = [];
                        foreach ($data as $count) {
                            if ($count < 25) $colors[] = "'#d90429'";
                            elseif ($count <= 75) $colors[] = "'#fb8500'";
                            else $colors[] = "'#138a36'";
                        }
                        echo '[' . implode(',', $colors) . ']';
                        ?>
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: "#aaa",
                            font: {
                                family: 'Inter'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            color: "#666",
                            font: {
                                family: 'Inter'
                            }
                        }
                    }
                }
            }
        });
        const pieChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Low', 'Critical'],
                datasets: [{
                    data: <?= json_encode($status_pie) ?>,
                    backgroundColor: ['#138a36', '#fb8500', '#d90429'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '73%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        function exportCSV() {
            var csv = `<?= $csv_content ?>`;
            var blob = new Blob([csv], {
                type: 'text/csv'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'blood_inventory_stats.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
