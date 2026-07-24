<?php 
$page = 'dashboard';
include("php/dbconnect.php");
include("php/checklogin.php");

// Initialize variables
$total_students = 0;
$total_earnings = 0;
$total_courses = 0;
$active_count = 0;
$inactive_count = 0;

// Fetch Total Students (from student table where delete_status = '0' for active students)
$sql_students = "SELECT COUNT(*) as total FROM student WHERE delete_status = '0'";
$result_students = $conn->query($sql_students);
if($result_students && $row = $result_students->fetch_assoc()) {
    $total_students = $row['total'];
}

// Fetch Total Earnings (sum of all paid amounts from fees_transaction table)
$sql_earnings = "SELECT SUM(paid) as total FROM fees_transaction";
$result_earnings = $conn->query($sql_earnings);
if($result_earnings && $row = $result_earnings->fetch_assoc()) {
    $total_earnings = $row['total'] ? 'Le ' . number_format($row['total'], 2) : '$0.00';
}

// Fetch Total Courses (from grade table where delete_status = '0')
$sql_courses = "SELECT COUNT(*) as total FROM grade WHERE delete_status = '0'";
$result_courses = $conn->query($sql_courses);
if($result_courses && $row = $result_courses->fetch_assoc()) {
    $total_courses = $row['total'];
}

// Fetch Active Students (balance = 0 means fully paid, but active means delete_status = '0')
$sql_active = "SELECT COUNT(*) as total FROM student WHERE delete_status = '0'";
$result_active = $conn->query($sql_active);
if($result_active && $row = $result_active->fetch_assoc()) {
    $active_count = $row['total'];
}

// Fetch In-Active Students (delete_status = '1')
$sql_inactive = "SELECT COUNT(*) as total FROM student WHERE delete_status = '1'";
$result_inactive = $conn->query($sql_inactive);
if($result_inactive && $row = $result_inactive->fetch_assoc()) {
    $inactive_count = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Fees Management System </title>
    <!-- Google Fonts + Font Awesome + Bootstrap 5 (Modern) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
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

        /* ========== MODERN WRAPPER & SIDEBAR ========== */
        .dashboard-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* SIDEBAR - Glassmorphic / Deep elegant */
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

        /* Dashboard Cards */
        .dashboard-cards {
            padding: 2rem 1.8rem;
        }
        .card-dash {
            border: none;
            border-radius: 28px;
            background: white;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.05);
            overflow: hidden;
            height: 100%;
        }
        .card-dash:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.12);
        }
        .card-body-custom {
            padding: 1.6rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        .card-stats h3 {
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
            color: #0f172a;
        }
        .card-stats p {
            margin: 0;
            color: #475569;
            font-weight: 500;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
        }
        .card-footer-link {
            background: #f8fafc;
            padding: 0.9rem 1.5rem;
            border-top: 1px solid #edf2f7;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #3b82f6;
            transition: 0.2s;
        }
        .card-footer-link:hover {
            background: #eff6ff;
            color: #2563eb;
        }

        /* Color Variants */
        .bg-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
        .bg-green { background: linear-gradient(135deg, #10b981, #059669); }
        .bg-secondary { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .bg-dull { background: linear-gradient(135deg, #64748b, #475569); }
        .bg-maroon { background: linear-gradient(135deg, #ef4444, #b91c1c); }
        .bg-yell { background: linear-gradient(135deg, #eab308, #ca8a04); }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { margin-left: -280px; position: fixed; }
            .sidebar.open { margin-left: 0; }
            .main-content { margin-left: 0; }
            .menu-toggle-btn { display: block; }
            .top-navbar { padding: 0.8rem 1.2rem; }
        }

        .page-head-modern {
            margin-bottom: 0.5rem;
            font-weight: 700;
            font-size: 1.8rem;
            color: #0f172a;
            letter-spacing: -0.4px;
        }
        .breadcrumb-modern {
            font-size: 0.85rem;
            color: #5b6e8c;
            margin-bottom: 1.5rem;
        }
        .footer-note {
            text-align: center;
            padding: 1.5rem;
            font-size: 0.8rem;
            color: #6c7a91;
            border-top: 1px solid #e2e8f0;
            margin-top: 2rem;
        }
        
        /* Refresh Button */
        .refresh-btn {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 40px;
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
            color: #475569;
            transition: all 0.2s;
        }
        .refresh-btn:hover {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
    </style>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebarNav">
        <div class="user-profile-side">
            <div class="user-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h5><?php echo htmlspecialchars($_SESSION['rainbow_name']); ?></h5>
            <span class="user-role-badge">Administrator</span>
        </div>
        <ul class="nav-menu">
            <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="student.php"><i class="fas fa-users"></i> Student Management</a></li>
            <li class="nav-item"><a class="nav-link" href="inactivestd.php"><i class="fas fa-toggle-off"></i> In-Active Students</a></li>
            <li class="nav-item"><a class="nav-link" href="grade.php"><i class="fas fa-th-large"></i> Course</a></li>
            <li class="nav-item"><a class="nav-link" href="fees.php"><i class="fas fa-money-bill-wave"></i> Fees Section</a></li>
            <li class="nav-item"><a class="nav-link" href="report.php"><i class="fas fa-file-pdf"></i> Report Section</a></li>
            <li class="nav-item"><a class="nav-link" href="setting.php"><i class="fas fa-cogs"></i> Account Setting</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-power-off"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="top-navbar">
            <button class="menu-toggle-btn" id="mobileMenuToggle"><i class="fas fa-bars"></i></button>
            <div class="brand-title">FeesManager Pro</div>
            <div class="header-actions">
                <button class="refresh-btn" onclick="location.reload();">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="container-fluid px-0">
                <div class="row mb-3">
                    <div class="col-12">
                        <h1 class="page-head-modern">
                            <i class="fas fa-chart-line me-2" style="color: #3b82f6;"></i>
                            Dashboard
                        </h1>
                        <div class="breadcrumb-modern">
                            Welcome back, <?php echo htmlspecialchars($_SESSION['rainbow_name']); ?>! Here's your school at a glance.
                        </div>
                    </div>
                </div>

                <!-- Cards Row 1 -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card-dash">
                            <div class="card-body-custom">
                                <div class="card-stats">
                                    <h3><?php echo number_format($total_students); ?></h3>
                                    <p>Total Students</p>
                                </div>
                                <div class="card-icon bg-purple"><i class="fas fa-user-friends"></i></div>
                            </div>
                            <a href="student.php" class="card-footer-link">Manage Students <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-dash">
                            <div class="card-body-custom">
                                <div class="card-stats">
                                    <h3><?php echo $total_earnings; ?></h3>
                                    <p>Total Earnings</p>
                                </div>
                                <div class="card-icon bg-green"><i class="fas fa-coins"></i></div>
                            </div>
                            <a href="fees.php" class="card-footer-link">Collect Fees <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-dash">
                            <div class="card-body-custom">
                                <div class="card-stats">
                                    <h3><?php echo number_format($total_courses); ?></h3>
                                    <p>Active Courses</p>
                                </div>
                                <div class="card-icon bg-secondary"><i class="fas fa-book-open"></i></div>
                            </div>
                            <a href="grade.php" class="card-footer-link">Manage Courses <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Cards Row 2 -->
                <div class="row g-4 mt-2">
                    <div class="col-md-4">
                        <div class="card-dash">
                            <div class="card-body-custom">
                                <div class="card-stats">
                                    <h3><?php echo number_format($active_count); ?></h3>
                                    <p>Active Students</p>
                                </div>
                                <div class="card-icon bg-dull"><i class="fas fa-user-check"></i></div>
                            </div>
                            <a href="student.php" class="card-footer-link">View Active <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-dash">
                            <div class="card-body-custom">
                                <div class="card-stats">
                                    <h3><i class="fas fa-chart-line me-1"></i> Reports</h3>
                                    <p>Analytics & Exports</p>
                                </div>
                                <div class="card-icon bg-maroon"><i class="fas fa-chart-pie"></i></div>
                            </div>
                            <a href="report.php" class="card-footer-link">Generate Reports <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-dash">
                            <div class="card-body-custom">
                                <div class="card-stats">
                                    <h3><?php echo number_format($inactive_count); ?></h3>
                                    <p>In-Active Students</p>
                                </div>
                                <div class="card-icon bg-yell"><i class="fas fa-user-slash"></i></div>
                            </div>
                            <a href="inactivestd.php" class="card-footer-link">Review Records <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                
                
            </div>
            <div class="footer-note">
                <i class="fas fa-shield-alt"></i> Secure Bundus Fees Management System | © <?php echo date('Y'); ?>
            </div>
        </div>
    </main>
</div>

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
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php
// Helper to extract numeric value from formatted earnings
$total_earnings_num = (float)str_replace(['$', ','], '', $total_earnings);
?>
</body>
</html>