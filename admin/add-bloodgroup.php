<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
header('location:index.php');
}
else{
// Code for change password	
if(isset($_POST['submit']))
{
$bloodgroup=$_POST['bloodgroup'];
$sql="INSERT INTO  tblbloodgroup(BloodGroup) VALUES(:bloodgroup)";
$query = $dbh->prepare($sql);
$query->bindParam(':bloodgroup',$bloodgroup,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$msg="Blood Group Created successfully";
}
else 
{
$error="Something went wrong. Please try again";
}

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
    
    <title>Add Blood Group | Life Savior</title>

    <!-- Favicon -->
    <link rel="icon" href="../admin/img/icon.jpg" type="image/jpeg">

    <!-- Favicon -->
    <link rel="icon" href="../admin/img/icon.jpg" type="image/jpeg">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
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
        
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-top: 1.5rem;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border: none;
        }
        
        .form-group label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.25);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.35);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .errorWrap {
            padding: 1rem;
            margin: 0 0 1.5rem 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            font-weight: 500;
        }
        
        .succWrap {
            padding: 1rem;
            margin: 0 0 1.5rem 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            font-weight: 500;
        }
        
        .blood-drop-icon {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .blood-drop-icon i {
            font-size: 3rem;
            color: #dd3d36;
            background: rgba(221, 61, 54, 0.1);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-section {
            background: rgba(67, 97, 238, 0.03);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px dashed rgba(67, 97, 238, 0.2);
        }
        
        .form-section h5 {
            color: var(--primary);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(67, 97, 238, 0.1);
        }
        
        .info-box {
            background: rgba(67, 97, 238, 0.05);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            border-left: 3px solid var(--primary);
        }
        
        .info-box h6 {
            color: var(--primary);
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
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
                            <h2 class="page-title">Add Blood Group</h2>
                            <a href="manage-bloodgroup.php" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>View All Groups
                            </a>
                        </div>
                        
                        <div class="form-container">
                            <div class="blood-drop-icon">
                                <i class="fas fa-tint"></i>
                            </div>
                            
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <?php if($error){?><div class="errorWrap"><i class="fas fa-exclamation-circle me-2"></i><?php echo htmlentities($error); ?> </div><?php } 
                                    else if($msg){?><div class="succWrap"><i class="fas fa-check-circle me-2"></i><?php echo htmlentities($msg); ?> </div><?php }?>
                                    
                                    <div class="form-section">
                                        <h5><i class="fas fa-plus-circle me-2"></i>Create New Blood Group</h5>
                                        <form method="post" name="chngpwd" class="row g-3" onSubmit="return valid();">
                                            <div class="col-md-12">
                                                <label for="bloodgroup" class="form-label">Blood Group Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-tint"></i></span>
                                                    <input type="text" class="form-control form-control-lg" name="bloodgroup" id="bloodgroup" placeholder="Enter blood group (e.g. O+, AB-)" required>
                                                </div>
                                                <div class="form-text mt-1">Enter the blood group in standard format</div>
                                            </div>
                                            
                                            <div class="col-12 mt-4">
                                                <button class="btn btn-primary w-100 py-3" name="submit" type="submit">
                                                    <i class="fas fa-plus me-2"></i>Create Blood Group
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="info-box">
                                        <h6><i class="fas fa-info-circle me-2"></i>About Blood Groups</h6>
                                        <p class="mb-0">Blood groups are classified based on the presence or absence of inherited antigenic substances on the surface of red blood cells. Ensure you're adding valid blood group types recognized by medical standards.</p>
                                    </div>
                                </div>
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
    <script src="js/main.js"></script>
    
    <script>
        // Simple animation for form
        $(document).ready(function() {
            $('.form-container').hide().fadeIn(800);
            
            // Add animation to submit button
            $('button[name="submit"]').hover(
                function() {
                    $(this).css('transform', 'translateY(-2px)');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );
        });
    </script>
</body>
</html>
<?php } ?>