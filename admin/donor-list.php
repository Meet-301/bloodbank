<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
header('location:index.php');
}
else{
if(isset($_REQUEST['hidden']))
{
$eid=intval($_GET['hidden']);
$status="0";
$sql = "UPDATE tblblooddonars SET Status=:status WHERE  id=:eid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query-> bindParam(':eid',$eid, PDO::PARAM_STR);
$query -> execute();

$msg="Donor details hidden Successfully";
}


if(isset($_REQUEST['public']))
{
$aeid=intval($_GET['public']);
$status=1;

$sql = "UPDATE tblblooddonars SET Status=:status WHERE  id=:aeid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query-> bindParam(':aeid',$aeid, PDO::PARAM_STR);
$query -> execute();

$msg="Donor details public";
}
//Code for Deletion
if(isset($_REQUEST['del']))
{
$did=intval($_GET['del']);
$sql = "delete from tblblooddonars WHERE  id=:did";
$query = $dbh->prepare($sql);
$query-> bindParam(':did',$did, PDO::PARAM_STR);
$query -> execute();

$msg="Record deleted Successfully";
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
    
    <title>Donor List | Life Savior</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../admin/img/icon.jpg" type="image/jpeg">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Admin Style -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
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
        
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            margin: 0;
            font-size: 1.2rem;
        }
        
        .errorWrap {
            padding: 1rem;
            margin: 0 0 1.5rem 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .succWrap {
            padding: 1rem;
            margin: 0 0 1.5rem 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .btn-download {
            background: linear-gradient(135deg, #38a169, #2f855a);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(56, 161, 105, 0.25);
            display: flex;
            align-items: center;
        }
        
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(56, 161, 105, 0.35);
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 0 0 12px 12px;
        }
        
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table thead th {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            font-weight: 600;
            padding: 1rem;
            border: none;
            border-bottom: 2px solid rgba(67, 97, 238, 0.2);
        }
        
        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            vertical-align: middle;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.03);
        }
        
        .badge-status {
            padding: 0.4rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .badge-public {
            background-color: rgba(56, 161, 105, 0.15);
            color: #38a169;
        }
        
        .badge-hidden {
            background-color: rgba(226, 232, 240, 0.5);
            color: #718096;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        
        .btn-action i {
            margin-right: 0.3rem;
        }
        
        .btn-hide {
            background-color: rgba(226, 232, 240, 0.5);
            color: #718096;
            border: 1px solid #e2e8f0;
        }
        
        .btn-hide:hover {
            background-color: #e2e8f0;
        }
        
        .btn-show {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            border: 1px solid rgba(67, 97, 238, 0.2);
        }
        
        .btn-show:hover {
            background-color: rgba(67, 97, 238, 0.15);
        }
        
        .btn-delete {
            background-color: rgba(229, 62, 62, 0.1);
            color: #e53e3e;
            border: 1px solid rgba(229, 62, 62, 0.2);
        }
        
        .btn-delete:hover {
            background-color: rgba(229, 62, 62, 0.15);
        }
        
        .gender-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .gender-male {
            background-color: rgba(66, 153, 225, 0.15);
            color: #4299e1;
        }
        
        .gender-female {
            background-color: rgba(236, 72, 153, 0.15);
            color: #ec4899;
        }
        
        .blood-group-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 700;
            background-color: rgba(220, 38, 38, 0.15);
            color: #dc2626;
        }
        
        @media (max-width: 992px) {
            .table-responsive {
                overflow-x: auto;
            }
            
            .action-buttons {
                flex-direction: column;
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="page-title">Blood Donors</h2>
                            <a href="download-records.php" class="btn-download">
                                <i class="fas fa-download me-2"></i>Download Donor List
                            </a>
                        </div>
                        
                        <?php if($error){?><div class="errorWrap"><i class="fas fa-exclamation-circle me-2"></i><?php echo htmlentities($error); ?> </div><?php } 
                        else if($msg){?><div class="succWrap"><i class="fas fa-check-circle me-2"></i><?php echo htmlentities($msg); ?> </div><?php }?>
                        
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Donor Information</div>
                                <div class="d-flex align-items-center">
                                    <span class="me-3">Total Donors: 
                                        <?php 
                                        $sql = "SELECT COUNT(*) as total from tblblooddonars";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $result = $query->fetch(PDO::FETCH_OBJ);
                                        echo htmlentities($result->total);
                                        ?>
                                    </span>
                                    <div class="input-group" style="width: 250px;">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Search donors...">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-container">
                                <table id="donorTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Blood Group</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * from  tblblooddonars ";
                                        $query = $dbh -> prepare($sql);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt=1;
                                        if($query->rowCount() > 0)
                                        {
                                            foreach($results as $result)
                                            {				?>	
                                                <tr>
                                                    <td><?php echo htmlentities($cnt);?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar me-3">
                                                                <div class="avatar-initial bg-light-primary rounded-circle">
                                                                    <span class="avatar-text"><?php echo substr(htmlentities($result->FullName), 0, 1); ?></span>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold"><?php echo htmlentities($result->FullName);?></div>
                                                                <div class="small text-muted"><?php echo htmlentities($result->EmailId);?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlentities($result->MobileNumber);?></td>
                                                    <td><?php echo htmlentities($result->EmailId);?></td>
                                                    <td><?php echo htmlentities($result->Age);?></td>
                                                    <td><?php echo htmlentities($result->BloodUnits);?> Units</td>
                                                    <td>
                                                        <span class="gender-badge gender-<?php echo strtolower(htmlentities($result->Gender)); ?>">
                                                            <?php echo htmlentities($result->Gender);?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="blood-group-badge">
                                                            <?php echo htmlentities($result->BloodGroup);?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="text-truncate" style="max-width: 200px;" title="<?php echo htmlentities($result->Address);?>">
                                                            <?php echo htmlentities($result->Address);?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if($result->status==1): ?>
                                                            <span class="badge-status badge-public">Public</span>
                                                        <?php else: ?>
                                                            <span class="badge-status badge-hidden">Hidden</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <?php if($result->status==1): ?>
                                                                <a href="donor-list.php?hidden=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to hide this donor?')" class="btn-action btn-hide">
                                                                    <i class="fas fa-eye-slash"></i> Hide
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="donor-list.php?public=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to make this donor public?')" class="btn-action btn-show">
                                                                    <i class="fas fa-eye"></i> Show
                                                                </a>
                                                            <?php endif; ?>
                                                            <a href="donor-list.php?del=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to delete this donor?')" class="btn-action btn-delete">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php $cnt=$cnt+1; }} else { ?>
                                            <tr>
                                                <td colspan="10" class="text-center py-4">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                                        <h5 class="mb-2">No Donors Found</h5>
                                                        <p class="text-muted">There are currently no donors in the system.</p>
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
            $('#donorTable').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search donors...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>'
                    }
                },
                dom: '<"top"<"d-flex justify-content-between align-items-center"f<"ms-3"l>>>rt<"bottom"<"d-flex justify-content-between align-items-center"ip>>',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                    $('.dataTables_length select').addClass('form-select');
                }
            });
            
            // Custom search for the search box in header
            $('#searchInput').on('keyup', function() {
                $('#donorTable').DataTable().search($(this).val()).draw();
            });
            
            // Add tooltips
            $('[title]').tooltip();
        });
    </script>
</body>
</html>
<?php } ?>