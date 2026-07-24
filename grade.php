<?php 
$page = 'grade';
include("php/dbconnect.php");
include("php/checklogin.php");

$errormsg = '';
$action = "add";
$grade = '';
$detail = '';
$id = '';

// Handle form submission
if(isset($_POST['save'])) {
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);

    if($_POST['action'] == "add") {
        $sql = $conn->query("INSERT INTO grade (grade, detail) VALUES ('$grade', '$detail')");
        echo '<script type="text/javascript">window.location="grade.php?act=1";</script>';
    } else if($_POST['action'] == "update") {
        $id = mysqli_real_escape_string($conn, $_POST['id']);	
        $sql = $conn->query("UPDATE grade SET grade = '$grade', detail = '$detail' WHERE id = '$id'");
        echo '<script type="text/javascript">window.location="grade.php?act=2";</script>';
    }
}

// Handle delete (soft delete)
if(isset($_GET['action']) && $_GET['action'] == "delete") {
    $conn->query("UPDATE grade set delete_status = '1' WHERE id='".$_GET['id']."'");
    header("location: grade.php?act=3");
}

// Handle edit
$action = "add";
if(isset($_GET['action']) && $_GET['action'] == "edit") {
    $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
    $sqlEdit = $conn->query("SELECT * FROM grade WHERE id='".$id."'");
    if($sqlEdit->num_rows) {
        $rowsEdit = $sqlEdit->fetch_assoc();
        extract($rowsEdit);
        $action = "update";
    } else {
        $_GET['action'] = "";
    }
}

// Success messages
if(isset($_REQUEST['act']) && @$_REQUEST['act'] == "1") {
    $errormsg = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='fas fa-check-circle me-2'></i>Grade has been added successfully!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
} else if(isset($_REQUEST['act']) && @$_REQUEST['act'] == "2") {
    $errormsg = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='fas fa-check-circle me-2'></i>Grade has been updated successfully!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
} else if(isset($_REQUEST['act']) && @$_REQUEST['act'] == "3") {
    $errormsg = "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='fas fa-check-circle me-2'></i>Grade has been deleted successfully!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>Course Management | Bundus Fees Management System</title>
    
    <!-- Bootstrap 5 + Font Awesome + Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
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
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #3b82f6;
            color: white !important;
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
        
        /* Action Buttons */
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
        
        /* Course Card for detail display */
        .course-detail {
            max-width: 300px;
            white-space: normal;
            word-wrap: break-word;
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
        
        /* Badge */
        .badge-course {
            background: #eef2ff;
            color: #3b82f6;
            padding: 0.25rem 0.75rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
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
            <li class="nav-item"><a class="nav-link" href="student.php"><i class="fas fa-users"></i> Student Management</a></li>
            <li class="nav-item"><a class="nav-link" href="inactivestd.php"><i class="fas fa-toggle-off"></i> In-Active Students</a></li>
            <li class="nav-item"><a class="nav-link active" href="grade.php"><i class="fas fa-th-large"></i> Course</a></li>
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
                <!-- Add/Edit Grade Form -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="page-head-modern">
                            <span><i class="fas fa-graduation-cap me-2" style="color: #3b82f6;"></i> <?php echo ($action == "add") ? "Add New Course" : "Edit Course"; ?></span>
                            <a href="grade.php" class="btn btn-back"><i class="fas fa-arrow-left me-1"></i> Go Back</a>
                        </div>
                        <div class="breadcrumb-modern"><?php echo ($action == "add") ? "Create a new course/grade level" : "Update course information"; ?></div>
                    </div>
                </div>

                <?php echo $errormsg; ?>

                <div class="form-section">
                    <form action="grade.php" method="post" id="signupForm1">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Course / Grade Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-custom" id="grade" name="grade" value="<?php echo htmlspecialchars($grade); ?>" placeholder="e.g., Grade 1, Class 10, BCA, etc.">
                                <small class="text-muted">Enter the name of the course or grade level</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Course Description / Details</label>
                                <textarea class="form-control form-control-custom" name="detail" id="detail" rows="3" placeholder="Enter course description, subjects, or additional details..."><?php echo htmlspecialchars($detail); ?></textarea>
                                <small class="text-muted">Optional: Provide additional information about this course</small>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="action" value="<?php echo $action; ?>">
                            <button type="submit" name="save" class="btn btn-save"><i class="fas fa-save me-2"></i> Save Course</button>
                        </div>
                    </form>
                </div>

                <?php else: ?>
                <!-- Grade List View -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="page-head-modern">
                            <span><i class="fas fa-th-large me-2" style="color: #3b82f6;"></i> Course Management</span>
                            <a href="grade.php?action=add" class="btn btn-add"><i class="fas fa-plus me-1"></i> Add New Course</a>
                        </div>
                        <div class="breadcrumb-modern">Manage all courses and grade levels offered in your institution</div>
                    </div>
                </div>

                <?php echo $errormsg; ?>

                <div class="panel-modern">
                    <div class="panel-heading-modern">
                        <h4>
                            <i class="fas fa-table me-2"></i> Course List
                            <span class="badge-course ms-2"><i class="fas fa-book me-1"></i> All Courses</span>
                        </h4>
                    </div>
                    <div class="panel-body-modern">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tSortable22" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%"><i class="fas fa-graduation-cap me-1"></i> Course / Grade</th>
                                        <th><i class="fas fa-align-left me-1"></i> Description / Details</th>
                                        <th width="15%"><i class="fas fa-cog me-1"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM grade WHERE delete_status='0' ORDER BY grade ASC";
                                    $q = $conn->query($sql);
                                    $i = 1;
                                    while($r = $q->fetch_assoc()) {
                                        $detailText = !empty($r['detail']) ? htmlspecialchars($r['detail']) : '<span class="text-muted fst-italic">No description provided</span>';
                                        echo '<tr>
                                                <td>' . $i . '</td>
                                                <td>
                                                    <strong>' . htmlspecialchars($r['grade']) . '</strong>
                                                </td>
                                                <td class="course-detail">' . $detailText . '</td>
                                                <td>
                                                    <a href="grade.php?action=edit&id=' . $r['id'] . '" 
                                                       class="btn btn-action-edit" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Edit Course">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a onclick="return confirm(\'Are you sure you want to delete this course? Students assigned to this course may be affected.\');" 
                                                       href="grade.php?action=delete&id=' . $r['id'] . '" 
                                                       class="btn btn-action-delete" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Delete Course">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </a>
                                                </td>
                                             </tr>';
                                        $i++;
                                    }
                                    
                                    // Show message if no records found
                                    if($i == 1) {
                                        echo '<tr>
                                            <td colspan="4" class="text-center py-5">
                                                <i class="fas fa-book-open fa-3x text-muted mb-3 d-block"></i>
                                                <h5 class="text-muted">No Courses Found</h5>
                                                <p class="text-muted">Click the "Add New Course" button to create your first course.</p>
                                            </td>
                                         </tr>';
                                    }
                                    ?>
                                </tbody>
                             </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="footer-note">
                	<i class="fas fa-shield-alt"></i> Secure Bundus Fees Management System | © <?php echo date('Y'); ?>
            	</div>
            </div>
        </div>
    </main>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
    // Form validation
    if($("#signupForm1").length > 0) {
        $("#signupForm1").validate({
            rules: {
                grade: {
                    required: true,
                    minlength: 2,
                    maxlength: 100
                }
            },
            messages: {
                grade: {
                    required: "Please enter course/grade name",
                    minlength: "Course name must be at least 2 characters",
                    maxlength: "Course name cannot exceed 100 characters"
                }
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
    }
    <?php else: ?>
    // Initialize DataTable
    $('#tSortable22').dataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": true,
        "bAutoWidth": true,
        "pageLength": 10,
        "language": {
            "search": "<i class='fas fa-search me-1'></i> Search Courses:",
            "lengthMenu": "Show _MENU_ courses per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ courses",
            "infoEmpty": "No courses found",
            "paginate": {
                "first": '<i class="fas fa-angle-double-left"></i>',
                "last": '<i class="fas fa-angle-double-right"></i>',
                "next": '<i class="fas fa-angle-right"></i>',
                "previous": '<i class="fas fa-angle-left"></i>'
            }
        },
        "order": [[0, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": 3 }
        ]
    });
    <?php endif; ?>
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>

</body>
</html>