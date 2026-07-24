<?php $page='fees';
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg= '';
if(isset($_POST['save']))
{
$paid = mysqli_real_escape_string($conn,$_POST['paid']);
$submitdate = mysqli_real_escape_string($conn,$_POST['submitdate']);
$transcation_remark = mysqli_real_escape_string($conn,$_POST['transcation_remark']);
$sid = mysqli_real_escape_string($conn,$_POST['sid']);

$sql = "select fees,balance  from student where id = '$sid'";
$sq = $conn->query($sql);
$sr = $sq->fetch_assoc();
$totalfee = $sr['fees'];
if($sr['balance']>0)
{
$sql = "insert into fees_transaction(stdid,submitdate,transcation_remark,paid) values('$sid','$submitdate','$transcation_remark','$paid') ";
$conn->query($sql);
$sql = "SELECT sum(paid) as totalpaid FROM fees_transaction WHERE stdid = '$sid'";
$tpq = $conn->query($sql);
$tpr = $tpq->fetch_assoc();
$totalpaid = $tpr['totalpaid'];
$tbalance = $totalfee - $totalpaid;

$sql = "update student set balance='$tbalance' where id = '$sid'";
$conn->query($sql);

 echo '<script type="text/javascript">window.location="fees.php?act=1";</script>';
}
}

if(isset($_REQUEST['act']) && @$_REQUEST['act']=="1")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Fees has been submitted</div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>Bundus Fees Management System </title>
    
    <!-- Bootstrap 5 + Font Awesome + Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    
    <!-- jQuery UI for Datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
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
        }
        .breadcrumb-modern {
            font-size: 0.85rem;
            color: #5b6e8c;
            margin-bottom: 1.5rem;
        }

        /* Search Section */
        .search-section {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #e9eef3;
        }
        .search-section legend {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
        }
        .search-section fieldset {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1rem 1.5rem;
        }
        .form-control-custom, .form-select-custom {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
            outline: none;
        }
        .btn-filter {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }
        .btn-reset {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
        }
        .btn-reset:hover {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        /* Panel */
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

        /* DataTable Styling */
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
        .btn-action {
            background: #eef2ff;
            border: none;
            border-radius: 10px;
            padding: 0.4rem 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #3b82f6;
            transition: all 0.2s;
        }
        .btn-action:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-2px);
        }

        /* Modal Styling */
        .modal-content-modern {
            border-radius: 24px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        .modal-header-modern {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            border-radius: 24px 24px 0 0;
            padding: 1rem 1.5rem;
        }
        .modal-header-modern .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { margin-left: -280px; position: fixed; }
            .sidebar.open { margin-left: 0; }
            .main-content { margin-left: 0; }
            .menu-toggle-btn { display: block; }
            .top-navbar { padding: 0.8rem 1.2rem; }
        }

        /* Footer */
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
            <li class="nav-item"><a class="nav-link <?php echo ($page=='dashboard')?'active':''; ?>" href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link <?php echo ($page=='student')?'active':''; ?>" href="student.php"><i class="fas fa-users"></i> Student Management</a></li>
            <li class="nav-item"><a class="nav-link <?php echo ($page=='inact')?'active':''; ?>" href="inactivestd.php"><i class="fas fa-toggle-off"></i> In-Active Students</a></li>
            <li class="nav-item"><a class="nav-link <?php echo ($page=='grade')?'active':''; ?>" href="grade.php"><i class="fas fa-th-large"></i> Course</a></li>
            <li class="nav-item"><a class="nav-link active" href="fees.php"><i class="fas fa-money-bill-wave"></i> Fees Section</a></li>
            <li class="nav-item"><a class="nav-link <?php echo ($page=='report')?'active':''; ?>" href="report.php"><i class="fas fa-file-pdf"></i> Report Section</a></li>
            <li class="nav-item"><a class="nav-link <?php echo ($page=='setting')?'active':''; ?>" href="setting.php"><i class="fas fa-cogs"></i> Account Setting</a></li>
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
                <div class="row mb-3">
                    <div class="col-12">
                        <h1 class="page-head-modern">
                            <i class="fas fa-money-bill-wave" style="color: #3b82f6; margin-right: 10px;"></i>
                            Fees Management
                        </h1>
                        <div class="breadcrumb-modern">Collect and manage student fees</div>
                    </div>
                </div>

                <?php if($errormsg): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $errormsg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Search Section -->
                <div class="search-section">
                    <fieldset>
                        <legend><i class="fas fa-search me-2"></i> Search Students</legend>
                        <form class="row g-3" id="searchform">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Student Name</label>
                                <input type="text" class="form-control form-control-custom" id="student" name="student" placeholder="Search by name...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Date of Joining</label>
                                <input type="text" class="form-control form-control-custom" id="doj" name="doj" placeholder="MM/YY">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Grade / Course</label>
                                <select class="form-select form-select-custom" id="grade" name="grade">
                                    <option value="">All Grades</option>
                                    <?php
                                    $sql = "select * from grade where delete_status='0' order by grade.grade asc";
                                    $q = $conn->query($sql);
                                    while($r = $q->fetch_assoc()) {
                                        echo '<option value="'.$r['id'].'">'.htmlspecialchars($r['grade']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="button" class="btn btn-filter" id="find">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <button type="reset" class="btn btn-reset" id="clear">
                                    <i class="fas fa-undo-alt me-1"></i> Reset
                                </button>
                            </div>
                        </form>
                    </fieldset>
                </div>

                <!-- Fees Table Panel -->
                <div class="panel-modern">
                    <div class="panel-heading-modern">
                        <h4><i class="fas fa-table me-2"></i> Student Fees Records</h4>
                    </div>
                    <div class="panel-body-modern">
                        <div class="table-responsive" id="subjectresult">
                            <table class="table table-striped table-bordered" id="tSortable22" width="100%">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-user me-1"></i> Name / Contact</th>
                                        <th><i class="fas fa-dollar-sign me-1"></i> Fees</th>
                                        <th><i class="fas fa-chart-line me-1"></i> Balance</th>
                                        <th><i class="fas fa-graduation-cap me-1"></i> Course</th>
                                        <th><i class="fas fa-calendar me-1"></i> DOJ</th>
                                        <th><i class="fas fa-cog me-1"></i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="footer-note">
                    <i class="fas fa-shield-alt"></i> Secure Bundus Fees Management System | © <?php echo date('Y'); ?>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal for Fee Collection -->
<div class="modal fade" id="myModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title"><i class="fas fa-hand-holding-usd me-2"></i> Collect Fee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="formcontent" style="padding: 1.5rem;">
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

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
    
    // Datepicker for Month/Year only
    $("#doj").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));
        }
    });

    $("#doj").focus(function () {
        $(".ui-datepicker-calendar").hide();
    });

    // Autocomplete for student name
    $('#student').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajx.php',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'studentname'
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return { label: item, value: item };
                    }));
                }
            });
        },
        minLength: 1
    });

    // Filter button click
    $('#find').click(function() {
        mydatatable();
    });

    // Reset button click
    $('#clear').click(function() {
        $('#searchform')[0].reset();
        mydatatable();
    });

    // Initialize DataTable
    function mydatatable() {
        $("#tSortable22").DataTable().destroy();
        
        $("#tSortable22").DataTable({
            "pagingType": "full_numbers",
            "lengthChange": false,
            "bFilter": false,
            "bInfo": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "datatable.php?" + $('#searchform').serialize() + "&type=feesearch",
                "type": "GET"
            },
            "columnDefs": [{
                "orderable": false,
                "targets": -1
            }],
            "language": {
                "processing": '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                "paginate": {
                    "first": '<i class="fas fa-angle-double-left"></i>',
                    "last": '<i class="fas fa-angle-double-right"></i>',
                    "next": '<i class="fas fa-angle-right"></i>',
                    "previous": '<i class="fas fa-angle-left"></i>'
                }
            }
        });
    }

    // Initial DataTable load
    $("#tSortable22").DataTable({
        "pagingType": "full_numbers",
        "lengthChange": false,
        "bFilter": false,
        "bInfo": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "datatable.php?type=feesearch",
            "type": "GET"
        },
        "columnDefs": [{
            "orderable": false,
            "targets": -1
        }],
        "language": {
            "processing": '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
        }
    });
});

// Get Fee Form for Modal
function GetFeeForm(sid) {
    $.ajax({
        type: 'post',
        url: 'getfeeform.php',
        data: {student: sid, req: '1'},
        success: function(data) {
            $('#formcontent').html(data);
            var myModal = new bootstrap.Modal(document.getElementById('myModal'));
            myModal.show();
        }
    });
}
</script>

<style>
/* Additional custom styles */
.ui-datepicker-calendar {
    display: none;
}
.ui-datepicker-title select {
    padding: 2px 5px;
    border-radius: 6px;
}
.dataTables_wrapper .dataTables_paginate {
    margin-top: 15px;
}
.dataTables_info {
    color: #64748b;
    font-size: 0.85rem;
}
.btn-filter, .btn-reset {
    transition: all 0.2s ease;
}
</style>

</body>
</html>