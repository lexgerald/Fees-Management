<?php 
$page = 'report';
include("php/dbconnect.php");
include("php/checklogin.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>Reports | Bundus Fees Management System</title>
    
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
        
        .btn-view-report {
            background: #eef2ff;
            border: none;
            border-radius: 10px;
            padding: 0.4rem 0.9rem;
            color: #3b82f6;
            transition: all 0.2s;
            font-size: 0.8rem;
        }
        .btn-view-report:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-2px);
        }
        
        /* Balance highlight */
        .has-balance {
            color: #ef4444;
            font-weight: 600;
        }
        .no-balance {
            color: #10b981;
            font-weight: 500;
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
        
        /* Print Button Styling */
        .btn-print {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-print:hover {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }
        
        /* Print Styles - Hide navigation when printing */
        @media print {
            .sidebar, .top-navbar, .search-section, .footer-note, .btn-print, .btn-close, .modal-footer .btn-reset, .dataTables_filter, .dataTables_length, .dataTables_paginate, .dataTables_info {
                display: none !important;
            }
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            .modal-content {
                box-shadow: none !important;
                border: none !important;
            }
            .modal-dialog {
                margin: 0 !important;
                max-width: 100% !important;
            }
            .modal-header-modern {
                background: #1e293b !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            body {
                background: white !important;
            }
            table {
                width: 100% !important;
            }
            .table-responsive {
                overflow: visible !important;
            }
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
        
        /* Info Banner */
        .info-banner {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-left: 4px solid #3b82f6;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
        }
        
        /* Hide calendar for month picker */
        .ui-datepicker-calendar {
            display: none;
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
            <li class="nav-item"><a class="nav-link" href="grade.php"><i class="fas fa-th-large"></i> Course</a></li>
            <li class="nav-item"><a class="nav-link" href="fees.php"><i class="fas fa-money-bill-wave"></i> Fees Section</a></li>
            <li class="nav-item"><a class="nav-link active" href="report.php"><i class="fas fa-file-pdf"></i> Report Section</a></li>
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
                <div class="row mb-3">
                    <div class="col-12">
                        <h1 class="page-head-modern">
                            <i class="fas fa-chart-line me-2" style="color: #3b82f6;"></i>
                            Financial Reports
                        </h1>
                        <div class="breadcrumb-modern">View and analyze student fee reports with detailed breakdowns</div>
                    </div>
                </div>

                <!-- Info Banner -->
                <div class="info-banner">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Report Information:</strong> This section displays student fee details. Click <i class="fas fa-eye ms-1 me-1"></i> "View Report" to see complete fee transaction history for each student.
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <fieldset>
                        <legend><i class="fas fa-search me-2"></i> Filter Reports</legend>
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
                                <label class="form-label fw-semibold">Course / Grade</label>
                                <select class="form-select form-select-custom" id="grade" name="grade">
                                    <option value="">All Courses</option>
                                    <?php
                                    $sql = "SELECT * FROM grade WHERE delete_status='0' ORDER BY grade.grade ASC";
                                    $q = $conn->query($sql);
                                    while($r = $q->fetch_assoc()) {
                                        echo '<option value="'.$r['id'].'">'.htmlspecialchars($r['grade']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="button" class="btn btn-filter" id="find">
                                    <i class="fas fa-filter me-1"></i> Generate Report
                                </button>
                                <button type="reset" class="btn btn-reset" id="clear">
                                    <i class="fas fa-undo-alt me-1"></i> Reset
                                </button>
                            </div>
                        </form>
                    </fieldset>
                </div>

                <!-- Report Table Panel -->
                <div class="panel-modern">
                    <div class="panel-heading-modern">
                        <h4>
                            <i class="fas fa-table me-2"></i> Student Fee Report
                            <span class="badge bg-primary bg-opacity-10 text-primary ms-2 px-3 py-1 rounded-pill" style="font-size: 0.7rem;">
                                <i class="fas fa-dollar-sign me-1"></i> Fee Details
                            </span>
                        </h4>
                    </div>
                    <div class="panel-body-modern">
                        <div class="table-responsive" id="subjectresult">
                            <table class="table table-striped table-bordered" id="tSortable22" width="100%">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-user me-1"></i> Name / Contact</th>
                                        <th><i class="fas fa-dollar-sign me-1"></i> Total Fees</th>
                                        <th><i class="fas fa-chart-line me-1"></i> Balance</th>
                                        <th><i class="fas fa-graduation-cap me-1"></i> Course</th>
                                        <th><i class="fas fa-calendar me-1"></i> DOJ</th>
                                        <th><i class="fas fa-eye me-1"></i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via DataTables server-side -->
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

<!-- Modal for Fee Report -->
<div class="modal fade" id="myModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar me-2"></i> Detailed Fee Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="formcontent" style="padding: 1.5rem;">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading fee details...</p>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-print" id="printReportBtn">
                    <i class="fas fa-print me-2"></i> Print Report
                </button>
                <button type="button" class="btn btn-reset" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
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

    $("#doj").focus(function() {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    // Autocomplete for student name
    $('#student').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajx.php',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'report'
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

    // Initialize DataTable with server-side processing
    function mydatatable() {
        if ($.fn.DataTable.isDataTable('#tSortable22')) {
            $('#tSortable22').DataTable().destroy();
        }
        
        $("#tSortable22").DataTable({
            "pagingType": "full_numbers",
            "lengthChange": true,
            "bFilter": false,
            "bInfo": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "datatable.php?" + $('#searchform').serialize() + "&type=report",
                "type": "GET",
                "error": function(xhr, error, thrown) {
                    console.log("DataTable error: " + error);
                }
            },
            "columnDefs": [{
                "orderable": false,
                "targets": -1
            }],
            "language": {
                "processing": '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">Loading report data...</p></div>',
                "paginate": {
                    "first": '<i class="fas fa-angle-double-left"></i>',
                    "last": '<i class="fas fa-angle-double-right"></i>',
                    "next": '<i class="fas fa-angle-right"></i>',
                    "previous": '<i class="fas fa-angle-left"></i>'
                },
                "search": "<i class='fas fa-search me-1'></i> Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ students",
                "infoEmpty": "No students found"
            },
            "pageLength": 10,
            "responsive": true
        });
    }

    // Initial DataTable load
    $("#tSortable22").DataTable({
        "pagingType": "full_numbers",
        "lengthChange": true,
        "bFilter": false,
        "bInfo": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "datatable.php?type=report",
            "type": "GET"
        },
        "columnDefs": [{
            "orderable": false,
            "targets": -1
        }],
        "language": {
            "processing": '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">Loading report data...</p></div>',
            "paginate": {
                "first": '<i class="fas fa-angle-double-left"></i>',
                "last": '<i class="fas fa-angle-double-right"></i>',
                "next": '<i class="fas fa-angle-right"></i>',
                "previous": '<i class="fas fa-angle-left"></i>'
            }
        },
        "pageLength": 10
    });
    
    // Print functionality for the modal content
    $('#printReportBtn').on('click', function() {
        var printContent = $('#formcontent').html();
        var originalTitle = $('title').text();
        var studentName = $('#formcontent').find('h4, h5, strong').first().text() || 'Fee Report';
        
        var printWindow = window.open('', '_blank', 'width=900,height=700');
        printWindow.document.write('<!DOCTYPE html>');
        printWindow.document.write('<html>');
        printWindow.document.write('<head>');
        printWindow.document.write('<title>Fee Report - ' + studentName + '</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">');
        printWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@300;400;500;600;700&display=swap" rel="stylesheet">');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: "Inter", sans-serif; padding: 30px; background: white; }');
        printWindow.document.write('.print-header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #3b82f6; }');
        printWindow.document.write('.print-header h2 { color: #1e293b; margin-bottom: 5px; }');
        printWindow.document.write('.print-header p { color: #64748b; margin: 0; }');
        printWindow.document.write('.print-footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
        printWindow.document.write('th { background: #f8fafc; padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0; }');
        printWindow.document.write('td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; }');
        printWindow.document.write('.amount { text-align: right; }');
        printWindow.document.write('.total-row { background: #f1f5f9; font-weight: bold; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head>');
        printWindow.document.write('<body>');
        printWindow.document.write('<div class="print-header">');
        printWindow.document.write('<h2><i class="fas fa-file-invoice-dollar"></i> Bundus Fee Payment Report</h2>');
        printWindow.document.write('<p>Generated on: ' + new Date().toLocaleString() + '</p>');
        printWindow.document.write('</div>');
        printWindow.document.write('<div class="print-content">');
        printWindow.document.write(printContent);
        printWindow.document.write('</div>');
        printWindow.document.write('<div class="print-footer">');
        printWindow.document.write('<p>This is a system-generated report from Bundus Management System</p>');
        printWindow.document.write('</div>');
        printWindow.document.write('</body>');
        printWindow.document.write('</html>');
        printWindow.document.close();
        
        printWindow.onload = function() {
            printWindow.print();
            // Optional: close after print
            // printWindow.close();
        };
    });
});

// Get Fee Report Form for Modal
function GetFeeForm(sid) {
    $('#formcontent').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">Loading fee details...</p></div>');
    
    $.ajax({
        type: 'post',
        url: 'getfeeform.php',
        data: {student: sid, req: '2'},
        success: function(data) {
            $('#formcontent').html(data);
            var myModal = new bootstrap.Modal(document.getElementById('myModal'));
            myModal.show();
        },
        error: function() {
            $('#formcontent').html('<div class="alert alert-danger m-3"><i class="fas fa-exclamation-triangle me-2"></i> Error loading fee details. Please try again.</div>');
        }
    });
}
</script>

</body>
</html>