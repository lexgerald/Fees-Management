<?php $page='student';
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';
$action = "add";

$id="";
$emailid='';
$sname='';
$joindate = '';
$remark='';
$contact='';
$balance = 0;
$fees='';
$about = '';
$grade='';


if(isset($_POST['save']))
{

$sname = mysqli_real_escape_string($conn,$_POST['sname']);
$joindate = mysqli_real_escape_string($conn,$_POST['joindate']);

$contact = mysqli_real_escape_string($conn,$_POST['contact']);
$about = mysqli_real_escape_string($conn,$_POST['about']);
$emailid = mysqli_real_escape_string($conn,$_POST['emailid']);
$grade = mysqli_real_escape_string($conn,$_POST['grade']);


 if($_POST['action']=="add")
 {
 $remark = mysqli_real_escape_string($conn,$_POST['remark']);
 $fees = mysqli_real_escape_string($conn,$_POST['fees']);
 $advancefees = mysqli_real_escape_string($conn,$_POST['advancefees']);
 $balance = $fees-$advancefees;
 
  $q1 = $conn->query("INSERT INTO student (sname,joindate,contact,about,emailid,grade,balance,fees) VALUES ('$sname','$joindate','$contact','$about','$emailid','$grade','$balance','$fees')") ;
  
  $sid = $conn->insert_id;
  
 $conn->query("INSERT INTO  fees_transaction (stdid,paid,submitdate,transcation_remark) VALUES ('$sid','$advancefees','$joindate','$remark')") ;
    
   echo '<script type="text/javascript">window.location="student.php?act=1";</script>';
 
 }else
  if($_POST['action']=="update")
 {
 $id = mysqli_real_escape_string($conn,$_POST['id']);	
   $sql = $conn->query("UPDATE  student  SET  grade  = '$grade', sname = '$sname', contact = '$contact', about = '$about', emailid = '$emailid'  WHERE  id  = '$id'");
   echo '<script type="text/javascript">window.location="student.php?act=2";</script>';
 }



}




if(isset($_GET['action']) && $_GET['action']=="delete"){

$conn->query("UPDATE  student set delete_status = '1'  WHERE id='".$_GET['id']."'");	
header("location: student.php?act=3");

}


$action = "add";
if(isset($_GET['action']) && $_GET['action']=="edit" ){
$id = isset($_GET['id'])?mysqli_real_escape_string($conn,$_GET['id']):'';

$sqlEdit = $conn->query("SELECT * FROM student WHERE id='".$id."'");
if($sqlEdit->num_rows)
{
$rowsEdit = $sqlEdit->fetch_assoc();
extract($rowsEdit);
$action = "update";
}else
{
$_GET['action']="";
}

}


if(isset($_REQUEST['act']) && @$_REQUEST['act']=="1")
{
$errormsg = "<div class='alert alert-success'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Student record has been added!</div>";
}else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="2")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Student record has been updated!</div>";
}
else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="3")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Student has been deleted!</div>";
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>Student Management | School Fees Management System</title>
    
    <!-- Bootstrap 5 + Font Awesome + Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    <!-- jQuery UI for Datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f8;
            overflow-x: hidden;
        }

        /* Dashboard Wrapper */
        .dashboard-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color: #e2e8f0;
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 8px 0 25px rgba(0, 0, 0, 0.08);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-track { background: #1e293b; }
        .sidebar::-webkit-scrollbar-thumb { background: #475569; border-radius: 10px; }

        .user-profile-side {
            padding: 1.8rem 1.5rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 1rem;
        }
        .user-avatar {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 18px rgba(0,0,0,0.2);
            border: 3px solid #3b82f6;
        }
        .user-avatar i { font-size: 3rem; color: white; }
        .user-profile-side h5 { color: white; font-weight: 600; font-size: 1.1rem; margin-bottom: 0.2rem; }
        .user-role-badge { background: rgba(59,130,246,0.2); display: inline-block; padding: 0.2rem 0.8rem; border-radius: 30px; font-size: 0.7rem; color: #93c5fd; }

        .sidebar .nav-menu { list-style: none; padding: 0 1rem 2rem; }
        .sidebar .nav-item { margin-bottom: 0.4rem; }
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 0.75rem 1rem;
            border-radius: 14px;
            color: #cbd5e1;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .sidebar .nav-link i { width: 24px; font-size: 1.2rem; text-align: center; }
        .sidebar .nav-link:hover { background: rgba(59,130,246,0.15); color: white; transform: translateX(4px); }
        .sidebar .nav-link.active { background: #3b82f6; color: white; box-shadow: 0 6px 12px rgba(59,130,246,0.25); }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            transition: all 0.3s;
            background: #f8fafc;
            min-height: 100vh;
        }

        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 0.9rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e9eef3;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .menu-toggle-btn { display: none; background: none; border: none; font-size: 1.6rem; color: #1e293b; cursor: pointer; }
        .brand-title { font-weight: 700; font-size: 1.5rem; background: linear-gradient(135deg, #1e293b, #2d3a5e); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .header-actions { display: flex; align-items: center; gap: 1.2rem; }
        .logout-btn { background: #f1f5f9; border-radius: 40px; padding: 0.45rem 1.2rem; font-weight: 500; color: #0f172a; text-decoration: none; border: 1px solid #e2e8f0; transition: 0.2s; }
        .logout-btn i { margin-right: 6px; }
        .logout-btn:hover { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }

        /* Page Content */
        .page-content {
            padding: 2rem 1.8rem;
        }
        .page-head-modern {
            font-weight: 700;
            font-size: 1.8rem;
            color: #0f172a;
            margin-bottom: 0.25rem;
            letter-spacing: -0.4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .breadcrumb-modern {
            font-size: 0.85rem;
            color: #5b6e8c;
            margin-bottom: 1.5rem;
        }

        /* Panel Modern */
        .panel-modern {
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #e9eef3;
            overflow: hidden;
        }
        .panel-heading-modern {
            background: white;
            padding: 1.2rem 1.5rem;
            border-bottom: 2px solid #eef2ff;
        }
        .panel-heading-modern h4 {
            margin: 0;
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
        }
        .panel-body-modern {
            padding: 1.5rem;
        }

        /* Form Styling */
        .form-section {
            background: white;
            border-radius: 20px;
            padding: 1.8rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #e9eef3;
        }
        .form-section legend {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            width: auto;
            padding: 0 12px;
            border-bottom: none;
            font-family: 'Inter', sans-serif;
        }
        .form-section fieldset {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        .form-control-custom, .form-select-custom {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
            outline: none;
        }
        .btn-save {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.7rem 2rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16,185,129,0.3);
        }
        .btn-add {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
        }
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239,68,68,0.3);
        }
        .btn-back {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
        }

        /* Table Styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white !important;
            border: none;
            border-radius: 8px;
        }
        table.dataTable thead th {
            background: #f8fafc;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.85rem;
            border-bottom: 2px solid #e2e8f0;
        }
        table.dataTable tbody td {
            padding: 0.9rem 0.8rem;
            vertical-align: middle;
        }
        .btn-action-edit {
            background: #eef2ff;
            border: none;
            border-radius: 10px;
            padding: 0.4rem 0.9rem;
            margin: 0 3px;
            color: #3b82f6;
            transition: all 0.2s;
        }
        .btn-action-delete {
            background: #fef2f2;
            border: none;
            border-radius: 10px;
            padding: 0.4rem 0.9rem;
            margin: 0 3px;
            color: #ef4444;
            transition: all 0.2s;
        }
        .btn-action-edit:hover, .btn-action-delete:hover {
            transform: translateY(-2px);
        }
        .has-balance-row {
            background-color: #fffbeb !important;
        }

        /* Validation Styles */
        label.error {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        .has-error .form-control-custom {
            border-color: #ef4444;
        }
        .has-success .form-control-custom {
            border-color: #10b981;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { margin-left: -280px; position: fixed; }
            .sidebar.open { margin-left: 0; }
            .main-content { margin-left: 0; }
            .menu-toggle-btn { display: block; }
            .top-navbar { padding: 0.8rem 1.2rem; }
        }

        .footer-note {
            text-align: center;
            padding: 1.5rem;
            font-size: 0.8rem;
            color: #6c7a91;
            border-top: 1px solid #e2e8f0;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebarNav">
        <div class="user-profile-side">
            <div class="user-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h5><?php echo htmlspecialchars($_SESSION['rainbow_name']); ?></h5>
            <span class="user-role-badge">Administrator</span>
        </div>
        <ul class="nav-menu">
            <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="student.php"><i class="fas fa-users"></i> Student Management</a></li>
            <li class="nav-item"><a class="nav-link" href="inactivestd.php"><i class="fas fa-toggle-off"></i> In-Active Students</a></li>
            <li class="nav-item"><a class="nav-link" href="grade.php"><i class="fas fa-th-large"></i> Course</a></li>
            <li class="nav-item"><a class="nav-link" href="fees.php"><i class="fas fa-money-bill-wave"></i> Fees Section</a></li>
            <li class="nav-item"><a class="nav-link" href="report.php"><i class="fas fa-file-pdf"></i> Report Section</a></li>
            <li class="nav-item"><a class="nav-link" href="setting.php"><i class="fas fa-cogs"></i> Account Setting</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-power-off"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="top-navbar">
            <button class="menu-toggle-btn" id="mobileMenuToggle"><i class="fas fa-bars"></i></button>
            <div class="brand-title">FeesManager Pro</div>
            <div class="header-actions">
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <div class="page-content">
            <div class="container-fluid px-0">
                
                <?php if(isset($_GET['action']) && ($_GET['action'] == "add" || $_GET['action'] == "edit")): ?>
                <!-- Add/Edit Student Form -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="page-head-modern">
                            <span><i class="fas fa-user-graduate me-2" style="color: #3b82f6;"></i> <?php echo ($action == "add") ? "Add New Student" : "Edit Student Details"; ?></span>
                            <a href="student.php" class="btn btn-back"><i class="fas fa-arrow-left me-1"></i> Go Back</a>
                        </div>
                        <div class="breadcrumb-modern"><?php echo ($action == "add") ? "Enter student information to register" : "Update student information"; ?></div>
                    </div>
                </div>

                <?php if($errormsg): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $errormsg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="form-section">
                    <form action="student.php" method="post" id="signupForm1">
                        <fieldset>
                            <legend><i class="fas fa-user me-2"></i> Personal Information</legend>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-custom" id="sname" name="sname" value="<?php echo htmlspecialchars($sname); ?>" placeholder="Enter student's full name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-custom" id="contact" name="contact" value="<?php echo htmlspecialchars($contact); ?>" maxlength="10" placeholder="10-digit mobile number">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Grade / Course <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-custom" id="grade" name="grade">
                                        <option value="">Select Grade Level</option>
                                        <?php
                                        $sql = "select * from grade where delete_status='0' order by grade.grade asc";
                                        $q = $conn->query($sql);
                                        while($r = $q->fetch_assoc()) {
                                            echo '<option value="'.$r['id'].'" '.(($grade == $r['id']) ? 'selected' : '').'>'.htmlspecialchars($r['grade']).'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Date of Joining <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-custom" placeholder="Select date" id="joindate" name="joindate" value="<?php echo ($joindate != '') ? date("Y-m-d", strtotime($joindate)) : ''; ?>" readonly style="background-color: #fff;">
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend><i class="fas fa-money-bill-wave me-2"></i> Fee Information</legend>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Total Fees <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-custom" id="fees" name="fees" value="<?php echo htmlspecialchars($fees); ?>" <?php echo ($action == "update") ? "disabled" : ""; ?> placeholder="Enter total fee amount">
                                </div>
                                <?php if($action == "add"): ?>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Advance Fee <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-custom" id="advancefees" name="advancefees" readonly placeholder="Will be auto-calculated">
                                </div>
                                <?php endif; ?>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Balance Amount</label>
                                    <input type="text" class="form-control form-control-custom" id="balance" name="balance" value="<?php echo htmlspecialchars($balance); ?>" disabled placeholder="Remaining balance">
                                </div>
                                <?php if($action == "add"): ?>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Fee Remark</label>
                                    <textarea class="form-control form-control-custom" id="remark" name="remark" rows="2" placeholder="Any remarks about fee payment..."><?php echo htmlspecialchars($remark); ?></textarea>
                                </div>
                                <?php endif; ?>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend><i class="fas fa-info-circle me-2"></i> Optional Information</legend>
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">About Student</label>
                                    <textarea class="form-control form-control-custom" id="about" name="about" rows="3" placeholder="Additional information about the student..."><?php echo htmlspecialchars($about); ?></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Email ID</label>
                                    <input type="email" class="form-control form-control-custom" id="emailid" name="emailid" value="<?php echo htmlspecialchars($emailid); ?>" placeholder="student@example.com">
                                </div>
                            </div>
                        </fieldset>

                        <div class="text-center mt-4">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="action" value="<?php echo $action; ?>">
                            <button type="submit" name="save" class="btn btn-save"><i class="fas fa-save me-2"></i> Save Student</button>
                        </div>
                    </form>
                </div>

                <?php else: ?>
                <!-- Student List View -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="page-head-modern">
                            <span><i class="fas fa-users me-2" style="color: #3b82f6;"></i> Manage Students</span>
                            <a href="student.php?action=add" class="btn btn-add"><i class="fas fa-plus me-1"></i> Add New Student</a>
                        </div>
                        <div class="breadcrumb-modern">View, edit, and manage all student records</div>
                    </div>
                </div>

                <?php if($errormsg): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $errormsg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="panel-modern">
                    <div class="panel-heading-modern">
                        <h4><i class="fas fa-table me-2"></i> Student Records</h4>
                    </div>
                    <div class="panel-body-modern">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tSortable22" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><i class="fas fa-user me-1"></i> Name | Contact</th>
                                        <th><i class="fas fa-graduation-cap me-1"></i> Course</th>
                                        <th><i class="fas fa-calendar me-1"></i> Joined On</th>
                                        <th><i class="fas fa-dollar-sign me-1"></i> Fees</th>
                                        <th><i class="fas fa-chart-line me-1"></i> Balance</th>
                                        <th><i class="fas fa-cog me-1"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "select s.*, g.grade as grade_name from student s left join grade g on s.grade = g.id where s.delete_status='0'";
                                    $q = $conn->query($sql);
                                    $i = 1;
                                    while($r = $q->fetch_assoc()) {
                                        $rowClass = ($r['balance'] > 0) ? 'has-balance-row' : '';
                                        echo '<tr class="' . $rowClass . '">
                                                <td>' . $i . '</td>
                                                <td><strong>' . htmlspecialchars($r['sname']) . '</strong><br><small class="text-muted">' . htmlspecialchars($r['contact']) . '</small></td>
                                                <td>' . htmlspecialchars($r['grade_name']) . '</td>
                                                <td>' . date("d M Y", strtotime($r['joindate'])) . '</td>
                                                <td>Le' . number_format($r['fees'], 2) . '</td>
                                                <td class="' . (($r['balance'] > 0) ? 'text-danger fw-bold' : 'text-success') . '">Le' . number_format($r['balance'], 2) . '</td>
                                                <td>
                                                    <a href="student.php?action=edit&id=' . $r['id'] . '" class="btn btn-action-edit" data-bs-toggle="tooltip" title="Edit Student"><i class="fas fa-edit"></i></a>
                                                    <a onclick="return confirm(\'Are you sure you want to deactivate this student?\');" href="student.php?action=delete&id=' . $r['id'] . '" class="btn btn-action-delete" data-bs-toggle="tooltip" title="Deactivate"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>';
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="footer-note">
                    <i class="fas fa-shield-alt"></i> Secure Fees Management System | © 2025
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
// Mobile menu toggle
const toggleBtn = document.getElementById('mobileMenuToggle');
const sidebar = document.getElementById('sidebarNav');
if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });
}
document.addEventListener('click', function(event) {
    const isClickInside = sidebar.contains(event.target) || toggleBtn.contains(event.target);
    if (!isClickInside && window.innerWidth <= 992 && sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
    }
});

$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    <?php if(isset($_GET['action']) && ($_GET['action'] == "add" || $_GET['action'] == "edit")): ?>
    // Datepicker
    $("#joindate").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        yearRange: "1970:<?php echo date('Y'); ?>"
    });
    
    // Form validation
    $("#signupForm1").validate({
        rules: {
            sname: "required",
            joindate: "required",
            emailid: "email",
            grade: "required",
            contact: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            <?php if($action == 'add'): ?>
            fees: {
                required: true,
                digits: true,
                min: 1
            },
            advancefees: {
                required: true,
                digits: true
            }
            <?php endif; ?>
        },
        messages: {
            sname: "Please enter student name",
            contact: {
                required: "Please enter contact number",
                digits: "Please enter valid digits",
                minlength: "Contact must be 10 digits",
                maxlength: "Contact must be 10 digits"
            },
            grade: "Please select grade / course",
            joindate: "Please select date of joining"
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
            error.addClass("help-block text-danger");
            error.insertAfter(element);
        },
        highlight: function(element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        }
    });
    
    // Fee calculation
    $("#fees").keyup(function() {
        $("#advancefees").val("");
        $("#balance").val(0);
        var fee = $.trim($(this).val());
        if(fee != '' && !isNaN(fee)) {
            $("#advancefees").removeAttr("readonly");
            $("#balance").val(fee);
            $("#advancefees").rules("add", { max: parseInt(fee) });
        } else {
            $("#advancefees").attr("readonly", "readonly");
        }
    });
    
    $("#advancefees").keyup(function() {
        var advancefees = parseInt($.trim($(this).val()));
        var totalfee = parseInt($("#fees").val());
        if(advancefees != '' && !isNaN(advancefees) && advancefees <= totalfee) {
            var balance = totalfee - advancefees;
            $("#balance").val(balance);
        } else {
            $("#balance").val(totalfee);
        }
    });
    <?php else: ?>
    // DataTable initialization
    $('#tSortable22').dataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": true,
        "bAutoWidth": true,
        "pageLength": 10,
        "language": {
            "search": "<i class='fas fa-search'></i> Search:",
            "paginate": {
                "first": '<i class="fas fa-angle-double-left"></i>',
                "last": '<i class="fas fa-angle-double-right"></i>',
                "next": '<i class="fas fa-angle-right"></i>',
                "previous": '<i class="fas fa-angle-left"></i>'
            }
        }
    });
    <?php endif; ?>
});
</script>

</body>
</html>