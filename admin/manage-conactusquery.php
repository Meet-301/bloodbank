<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_REQUEST['eid'])) {
        $eid = intval($_GET['eid']);
        $status = 1;
        $sql = "UPDATE tblcontactusquery SET status=:status WHERE  id=:eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();

        $msg = "Query marked as read successfully";
    }

    if (isset($_REQUEST['del'])) {
        $did = intval($_GET['del']);
        $sql = "delete from tblcontactusquery WHERE  id=:did";
        $query = $dbh->prepare($sql);
        $query->bindParam(':did', $did, PDO::PARAM_STR);
        $query->execute();

        $msg = "Query deleted successfully";
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

        <title>Manage Contact Queries | Life Savior</title>

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
                --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

            /* Card Styling */
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

            /* Table Styling */
            .table-container {
                overflow: hidden;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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

            /* Status Badges */
            .status-badge {
                padding: 0.35rem 0.75rem;
                border-radius: 50px;
                font-size: 0.85rem;
                font-weight: 600;
            }

            .badge-pending {
                background-color: rgba(247, 37, 133, 0.15);
                color: #f72585;
            }

            .badge-read {
                background-color: rgba(76, 201, 240, 0.15);
                color: #4cc9f0;
            }

            /* Action Buttons */
            .action-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background-color: rgba(67, 97, 238, 0.1);
                color: var(--primary);
                transition: all 0.3s ease;
                margin-right: 5px;
            }

            .action-btn:hover {
                background-color: var(--primary);
                color: white;
                transform: scale(1.1);
            }

            .action-btn.delete {
                background-color: rgba(247, 37, 133, 0.1);
                color: var(--warning);
            }

            .action-btn.delete:hover {
                background-color: var(--warning);
                color: white;
            }

            /* Message Preview */
            .message-preview {
                max-width: 300px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
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
        <?php include('includes/header.php'); ?>

        <?php if (isset($msg)) { ?>
            <div class="alert-container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?php echo htmlentities($msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php } ?>

        <?php if (isset($error)) { ?>
            <div class="alert-container">
                <div class="alert alert-error alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?php echo htmlentities($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php } ?>

        <div class="ts-main-content">
            <?php include('includes/leftbar.php'); ?>
            <div class="content-wrapper">
                <div class="container-fluid pt-4">
                    <div class="row mb-4">
                        <div class="col">
                            <h2 class="page-title">Manage Contact Queries</h2>
                            <p class="text-muted">View and manage all user inquiries and support requests</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="data-card">
                                <div class="card-title">
                                    <div>
                                        <i class="fas fa-question-circle"></i>
                                        User Queries List
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-file-export me-2"></i>Export
                                        </button>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-sync-alt me-2"></i>Refresh
                                        </button>
                                    </div>
                                </div>

                                <?php
                                $sql = "SELECT * from tblcontactusquery ";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                ?>

                                <div class="table-container">
                                    <?php
                                    $sql = "SELECT * from tblcontactusquery ";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;

                                    if ($query->rowCount() > 0) { ?>
                                        <table id="queriesTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Contact</th>
                                                    <th>Message</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($results as $result) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt); ?></td>
                                                        <td><?php echo htmlentities($result->name); ?></td>
                                                        <td>
                                                            <a href="mailto:<?php echo htmlentities($result->EmailId); ?>">
                                                                <?php echo htmlentities($result->EmailId); ?>
                                                            </a>
                                                        </td>
                                                        <td><?php echo htmlentities($result->ContactNumber); ?></td>
                                                        <td class="message-preview"
                                                            title="<?php echo htmlentities($result->Message); ?>">
                                                            <?php echo htmlentities($result->Message); ?>
                                                        </td>
                                                        <td><?php echo htmlentities($result->PostingDate); ?></td>
                                                        <td>
                                                            <?php if ($result->status == 1): ?>
                                                                <span class="status-badge badge-read">
                                                                    <i class="fas fa-check-circle me-1"></i>Read
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="status-badge badge-pending">
                                                                    <i class="fas fa-clock me-1"></i>Pending
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($result->status == 0): ?>
                                                                <a href="manage-conactusquery.php?eid=<?php echo htmlentities($result->id); ?>"
                                                                    onclick="return confirm('Mark this query as read?')"
                                                                    class="action-btn" title="Mark as Read">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                            <a href="manage-conactusquery.php?del=<?php echo htmlentities($result->id); ?>"
                                                                onclick="return confirm('Are you sure you want to delete this query?')"
                                                                class="action-btn delete" title="Delete Query">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                            <a href="#" class="action-btn" title="View Details"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#messageModal<?php echo $cnt; ?>">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <!-- Message Modal -->
                                                    <div class="modal fade" id="messageModal<?php echo $cnt; ?>" tabindex="-1"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-envelope me-2"></i>
                                                                        Query Details
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label text-muted">From:</label>
                                                                        <p><?php echo htmlentities($result->name); ?></p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label text-muted">Email:</label>
                                                                        <p><?php echo htmlentities($result->EmailId); ?></p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label text-muted">Contact:</label>
                                                                        <p><?php echo htmlentities($result->ContactNumber); ?></p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label text-muted">Date:</label>
                                                                        <p><?php echo htmlentities($result->PostingDate); ?></p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label text-muted">Message:</label>
                                                                        <div class="border p-3 rounded bg-light">
                                                                            <?php echo htmlentities($result->Message); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <a href="mailto:<?php echo htmlentities($result->EmailId); ?>"
                                                                        class="btn btn-primary">
                                                                        <i class="fas fa-reply me-2"></i>Reply
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tbody>
                                            </table>

                                            <?php $cnt = $cnt + 1;
                                                }
                                    } else { ?>
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <h4>No Queries Found</h4>
                                            <p>There are no contact queries in the system at this time.</p>
                                        </div>
                                    <?php } ?>
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
            $(document).ready(function () {
                // Initialize DataTable
                $('#queriesTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "order": [[5, 'desc']],
                    "language": {
                        "search": "<i class='fas fa-search me-2'></i>Search:",
                        "lengthMenu": "Show _MENU_ entries",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "paginate": {
                            "previous": "<i class='fas fa-chevron-left'></i>",
                            "next": "<i class='fas fa-chevron-right'></i>"
                        }
                    },
                    "columnDefs": [
                        { "orderable": false, "targets": [0, 7] },
                        { "width": "5%", "targets": 0 },
                        { "width": "10%", "targets": 7 }
                    ]
                });

                // Auto-close alerts after 5 seconds
                setTimeout(function () {
                    $('.alert').alert('close');
                }, 5000);

                // Tooltip initialization
                $('[title]').tooltip({
                    placement: 'top',
                    trigger: 'hover'
                });
            });
        </script>
    </body>

    </html>
<?php } ?>