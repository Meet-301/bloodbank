<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
header('location:index.php');
}
else{ 

if(isset($_POST['submit']))
  {
$fullname=$_POST['fullname'];
$mobile=$_POST['mobileno'];
$email=$_POST['emailid'];
$age=$_POST['age'];
$gender=$_POST['gender'];
$blodgroup=$_POST['bloodgroup'];
$address=$_POST['address'];
$message=$_POST['message'];
$status=1;
$sql="INSERT INTO  tblblooddonars(FullName,MobileNumber,EmailId,Age,Gender,BloodGroup,Address,Message,status) VALUES(:fullname,:mobile,:email,:age,:gender,:blodgroup,:address,:message,:status)";
$query = $dbh->prepare($sql);
$query->bindParam(':fullname',$fullname,PDO::PARAM_STR);
$query->bindParam(':mobile',$mobile,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':age',$age,PDO::PARAM_STR);
$query->bindParam(':gender',$gender,PDO::PARAM_STR);
$query->bindParam(':blodgroup',$blodgroup,PDO::PARAM_STR);
$query->bindParam(':address',$address,PDO::PARAM_STR);
$query->bindParam(':message',$message,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$msg="Your info submitted successfully";
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
    
    <title>Life Savior | Add Donor</title>

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
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
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
        
        .btn-outline-secondary {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.35);
        }
        
        .btn-outline-secondary:hover {
            background: #f8f9fa;
            border-color: #d1d9e0;
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
        
        .icon-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .icon-container i {
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
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
        }
        
        .required-star {
            color: #dd3d36;
            margin-left: 3px;
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border: 2px solid #e2e8f0;
            border-right: none;
        }
        
        .form-control:focus + .input-group-text {
            border-color: var(--primary);
        }
        
        .form-text {
            color: #718096;
            font-size: 0.85rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
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
                            <h2 class="page-title">Add New Blood Donor</h2>
                            <a href="donor-list.php" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>View All Donors
                            </a>
                        </div>
                        
                        <div class="form-container">
                            <div class="icon-container">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            
                            <div class="row justify-content-center">
                                <div class="col-lg-10">
                                    <?php if($error){?><div class="errorWrap"><i class="fas fa-exclamation-circle me-2"></i><?php echo htmlentities($error); ?> </div><?php } 
                                    else if($msg){?><div class="succWrap"><i class="fas fa-check-circle me-2"></i><?php echo htmlentities($msg); ?> </div><?php }?>
                                    
                                    <div class="form-section">
                                        <h5><i class="fas fa-user-circle me-2"></i>Donor Information</h5>
                                        <form method="post" class="row g-3" enctype="multipart/form-data">
                                            <div class="col-md-6">
                                                <label for="fullname" class="form-label">Full Name<span class="required-star">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    <input type="text" name="fullname" class="form-control" placeholder="Enter donor's full name" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="mobileno" class="form-label">Mobile No<span class="required-star">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                    <input type="text" name="mobileno" onKeyPress="return isNumberKey(event)" maxlength="10" class="form-control" placeholder="Enter 10-digit mobile number" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="emailid" class="form-label">Email id</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <input type="email" name="emailid" class="form-control" placeholder="Enter email address">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="age" class="form-label">Age<span class="required-star">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                                                    <input type="text" name="age" class="form-control" placeholder="Enter age" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="gender" class="form-label">Gender<span class="required-star">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                                    <select name="gender" class="form-select" required>
                                                        <option value="">Select Gender</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="bloodgroup" class="form-label">Blood Group<span class="required-star">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-tint"></i></span>
                                                    <select name="bloodgroup" class="form-select" required>
                                                        <option value="">Select Blood Group</option>
                                                        <?php $sql = "SELECT * from  tblbloodgroup ";
                                                        $query = $dbh -> prepare($sql);
                                                        $query->execute();
                                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                        $cnt=1;
                                                        if($query->rowCount() > 0)
                                                        {
                                                            foreach($results as $result)
                                                            {				?>	
                                                                <option value="<?php echo htmlentities($result->BloodGroup);?>"><?php echo htmlentities($result->BloodGroup);?></option>
                                                        <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="address" class="form-label">Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <textarea class="form-control" name="address" placeholder="Enter full address" rows="2"></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="message" class="form-label">Message<span class="required-star">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                                    <textarea class="form-control" name="message" placeholder="Enter any important message" required rows="3"></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 mt-4">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <button class="btn btn-outline-secondary px-4" type="reset">
                                                        <i class="fas fa-times me-2"></i>Cancel
                                                    </button>
                                                    <button class="btn btn-primary px-4" name="submit" type="submit">
                                                        <i class="fas fa-save me-2"></i>Save Donor
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
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
            
            // Number key validation
            function isNumberKey(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode!=46)
                   return false;
                 return true;
            }
        });
    </script>
</body>
</html>
<?php } ?>