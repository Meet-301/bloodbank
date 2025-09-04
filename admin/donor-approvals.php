<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Approve donor
if (isset($_REQUEST['approve'])) {
    $did = intval($_GET['approve']);
    $status = 1;
    $sql = "UPDATE tblblooddonars SET Status = :status WHERE id = :did";
    $query = $dbh->prepare($sql);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':did', $did, PDO::PARAM_STR);
    $query->execute();
    $msg = "Donor approved successfully.";

    if ($query->rowCount() > 0) {
        // Redirect to send-mail.php with donor id and type=approve
        header("Location: status_mail.php?id=$did&type=approve");
        exit();
    }
}

// Delete donor
if (isset($_REQUEST['reject'])) {
    $did = intval($_GET['reject']);
    $sql = "DELETE FROM tblblooddonars WHERE id = :did";
    $query = $dbh->prepare($sql);
    $query->bindParam(':did', $did, PDO::PARAM_STR);
    $query->execute();
    $msg = "Donor rejected and removed.";
    if ($query->rowCount() > 0) {
        // Redirect to send-mail.php with donor id and type=reject
        header("Location: status_mail.php?id=$did&type=reject");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Donors Approvals | Life Savior</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Favicon -->
    <link rel="icon" href="../admin/img/icon.jpg" type="image/jpeg">

    <!-- DataTables + Responsive Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .page-title {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .badge-hidden {
            background-color: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 10px;
        }

        .btn-approve {
            background-color: #198754;
            color: white;
        }

        .btn-reject {
            background-color: #dc3545;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 576px) {
            .action-buttons a {
                flex: 1 1 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <?php include('includes/header.php'); ?>

    <div class="ts-main-content">
        <?php include('includes/leftbar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid pt-4">

                <h2 class="page-title">Pending Donor Approvals</h2>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <strong>New Donor Requests</strong>
                    </div>
                    <div class="card-body">
                        <?php
                        $sql = "SELECT * FROM tblblooddonars WHERE status = 0";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;

                        if ($query->rowCount() > 0) {
                            // ✅ Show the table only if we have records
                            ?>
                            <table id="approvalTable" class="table table-bordered table-striped align-middle"
                                style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th></th> <!-- control column for responsive icon -->
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Blood Group</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $row) { ?>
                                        <tr>
                                            <td></td>
                                            <td><?php echo htmlentities($cnt); ?></td>
                                            <td><?php echo htmlentities($row->FullName); ?></td>
                                            <td><?php echo htmlentities($row->EmailId); ?></td>
                                            <td><?php echo htmlentities($row->MobileNumber); ?></td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <?php echo htmlentities($row->BloodGroup); ?>
                                                </span>
                                            </td>
                                            <td><span class="badge-hidden">Pending</span></td>
                                            <td class="action-buttons">
                                                <a href="donor-approvals.php?approve=<?php echo $row->id; ?>"
                                                    onclick="return confirm('Approve this donor?');"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Approve
                                                </a>
                                                <a href="donor-approvals.php?reject=<?php echo $row->id; ?>"
                                                    onclick="return confirm('Reject and delete this donor?');"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Reject
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $cnt++;
                                    } ?>
                                </tbody>
                            </table>
                            <?php
                        } else {
                            // ❌ No records - show big centred message
                            ?>
                            <div class="text-center my-5">
                                <i class="fas fa-info-circle text-secondary" style="font-size: 60px;"></i>
                                <h3 class="mt-3 text-secondary">No Pending Approvals Found</h3>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- JS Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables + Responsive Bootstrap 5 JS -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- DataTables Init -->
    <script>
        $(document).ready(function () {
            if ($('#approvalTable').length) {
                $('#approvalTable').DataTable({
                    responsive: {
                        details: {
                            type: 'column',
                            target: 0 // first column for control
                        }
                    },
                    columnDefs: [
                        { className: 'dtr-control', orderable: false, targets: 'nosort' }
                    ],
                    order: [1, 'asc'],
                    autoWidth: false,
                    language: {
                        searchPlaceholder: "Search donors...",
                        lengthMenu: "Show _MENU_ entries",
                        paginate: {
                            previous: '<i class="fas fa-angle-left"></i>',
                            next: '<i class="fas fa-angle-right"></i>'
                        }
                    }
                });
            }
        });
    </script>

</body>

</html>