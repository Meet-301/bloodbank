<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
header('location:index.php');
}
else{
if(isset($_GET['del']))
{
$id=$_GET['del'];
$sql = "delete from tblbloodgroup  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();
$msg="Blood group deleted successfully";
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    
    <title>Manage Blood Groups | Life Savior</title>

    <!-- Favicon -->
    <link rel="icon" href="../admin/img/icon.jpg" type="image/jpeg">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Admin Style -->
    <link rel="stylesheet" href="css/style.css">
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
        
        /* Alert Messages */
        .alert-container {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        }
        
        .alert {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: none;
            position: relative;
            overflow: hidden;
            padding: 1rem 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(to right, #4cc9f0, #4895ef);
            color: white;
        }
        
        .alert-error {
            background: linear-gradient(to right, #f72585, #b5179e);
            color: white;
        }
        
        .alert .close {
            color: white;
            opacity: 0.8;
            text-shadow: none;
        }
        
        /* Table Styling */
        .data-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .card-title {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-title i {
            margin-right: 10px;
        }
        
        .table-container {
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table thead th {
            background-color: #f8fafc;
            color: var(--primary);
            font-weight: 700;
            padding: 1rem 1.25rem;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .table tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.03);
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--warning);
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            background-color: var(--warning);
            color: white;
            transform: scale(1.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #718096;
        }
        
        .empty-state i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            color: #cbd5e0;
        }
        
        .empty-state h4 {
            font-weight: 600;
            color: #4a5568;
        }
        
        .empty-state p {
            max-width: 500px;
            margin: 0 auto;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-title {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .card-title .btn {
                margin-top: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php include('includes/header.php');?>
    
    <?php if($msg){ ?>
    <div class="alert-container">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> <?php echo htmlentities($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php } ?>
    
    <?php if($error){ ?>
    <div class="alert-container">
        <div class="alert alert-error alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?php echo htmlentities($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php } ?>

    <div class="ts-main-content">
        <?php include('includes/leftbar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid pt-4">
                <div class="row mb-4">
                    <div class="col">
                        <h2 class="page-title">Manage Blood Groups</h2>
                        <p class="text-muted">Manage all available blood types in the system</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="data-card">
                            <div class="card-title">
                                <div>
                                    <i class="fas fa-tint"></i>
                                    Blood Groups List
                                </div>
                                <a href="add-bloodgroup.php" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add New Blood Group
                                </a>
                            </div>
                            
                            <div class="table-container">
                                <table id="bloodGroupsTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Blood Group</th>
                                            <th>Creation Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * from tblbloodgroup ";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { 
                                        ?>
                                        <tr>
                                            <td><?php echo htmlentities($cnt); ?></td>
                                            <td>
                                                <span class="badge bg-danger bg-opacity-10 text-danger py-2 px-3 rounded-pill">
                                                    <i class="fas fa-tint me-2"></i>
                                                    <?php echo htmlentities($result->BloodGroup); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlentities($result->PostingDate); ?></td>
                                            <td>
                                                <a href="manage-bloodgroup.php?del=<?php echo $result->id; ?>" 
                                                   onclick="return confirm('Are you sure you want to delete this blood group?');"
                                                   class="action-btn">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $cnt = $cnt + 1; }} else { ?>
                                        <tr>
                                            <td colspan="4">
                                                <div class="empty-state">
                                                    <i class="fas fa-tint-slash"></i>
                                                    <h4>No Blood Groups Found</h4>
                                                    <p>There are no blood groups registered in the system yet. Add a new blood group to get started.</p>
                                                    <a href="add-bloodgroup.php" class="btn btn-primary mt-3">
                                                        <i class="fas fa-plus me-2"></i>Add Blood Group
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="js/main.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#bloodGroupsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "<i class='fas fa-search me-2'></i>Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });
            
            // Auto-close alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
</body>
</html>
<?php } ?>