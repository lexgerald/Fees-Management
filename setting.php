<?php 
$page = 'setting';
include("php/dbconnect.php");
include("php/checklogin.php");

$error = '';

if(isset($_POST['save'])) {
    $oldpassword = mysqli_real_escape_string($conn, $_POST['oldpassword']);
    $newpassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
    
    $sql = "SELECT * FROM user WHERE id = '".$_SESSION['rainbow_uid']."' AND password = '".md5($oldpassword)."'";
    $q = $conn->query($sql);
    
    if($q->num_rows > 0) {
        $sql = "UPDATE user SET password = '".md5($newpassword)."' WHERE id = '".$_SESSION['rainbow_uid']."'";
        $r = $conn->query($sql);
        echo '<script type="text/javascript">window.location="setting.php?act=1"; </script>';
    } else {
        $error = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Error!</strong> Wrong old password
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>Account Settings | Bundus Fees Management System</title>
    
    <!-- Bootstrap 5 + Font Awesome + Google Fonts -->
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

        /* Settings Panel */
        .settings-panel {
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            border: 1px solid #e9eef3;
            overflow: hidden;
            max-width: 700px;
            margin: 0 auto;
        }
        .settings-header {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            padding: 1.8rem 2rem;
            text-align: center;
            color: white;
        }
        .settings-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #3b82f6;
        }
        .settings-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }
        .settings-header p {
            margin: 0.5rem 0 0;
            opacity: 0.8;
            font-size: 0.85rem;
        }
        .settings-body {
            padding: 2rem;
        }

        /* Form Styling */
        .form-group-custom {
            margin-bottom: 1.5rem;
        }
        .form-label-custom {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: block;
        }
        .form-control-custom {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
            width: 100%;
        }
        .form-control-custom:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
            outline: none;
        }
        .input-group-icon {
            position: relative;
        }
        .input-group-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .input-group-icon .form-control-custom {
            padding-left: 45px;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16,185,129,0.3);
        }

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.75rem;
        }
        .strength-weak { color: #ef4444; }
        .strength-medium { color: #f59e0b; }
        .strength-strong { color: #10b981; }

        /* Validation Styles */
        label.error {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: block;
        }
        .has-error .form-control-custom {
            border-color: #ef4444;
        }
        .has-success .form-control-custom {
            border-color: #10b981;
        }

        /* Info Banner */
        .info-banner {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-left: 4px solid #3b82f6;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
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
        
        /* Security Tips */
        .security-tips {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.2rem;
            margin-top: 1.5rem;
            border: 1px solid #e2e8f0;
        }
        .security-tips h6 {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        .security-tips ul {
            margin: 0;
            padding-left: 1.2rem;
        }
        .security-tips li {
            color: #475569;
            font-size: 0.8rem;
            margin-bottom: 0.4rem;
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
            <li class="nav-item"><a class="nav-link" href="report.php"><i class="fas fa-file-pdf"></i> Report Section</a></li>
            <li class="nav-item"><a class="nav-link active" href="setting.php"><i class="fas fa-cogs"></i> Account Setting</a></li>
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
                    <div class="col-12 text-center">
                        <h1 class="page-head-modern">
                            <i class="fas fa-cogs me-2" style="color: #3b82f6;"></i>
                            Account Settings
                        </h1>
                        <div class="breadcrumb-modern">Manage your account security and password</div>
                    </div>
                </div>

                <!-- Success Message -->
                <?php
                if(isset($_REQUEST['act']) && @$_REQUEST['act'] == '1') {
                    echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="max-width: 700px; margin: 0 auto 1.5rem auto;">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Success!</strong> Password changed successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                }
                echo $error;
                ?>

                <!-- Info Banner -->
                <div class="info-banner">
                    <i class="fas fa-shield-alt me-2"></i>
                    <strong>Security Notice:</strong> For your account security, please choose a strong password that you haven't used before. Never share your password with anyone.
                </div>

                <!-- Settings Form Panel -->
                <div class="settings-panel">
                    <div class="settings-header">
                        <i class="fas fa-lock"></i>
                        <h3>Change Password</h3>
                        <p>Update your password to keep your account secure</p>
                    </div>
                    
                    <div class="settings-body">
                        <form action="setting.php" method="post" id="signupForm1">
                            <div class="form-group-custom">
                                <label class="form-label-custom" for="oldpassword">
                                    <i class="fas fa-key me-1"></i> Current Password
                                </label>
                                <div class="input-group-icon">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" class="form-control-custom" id="oldpassword" name="oldpassword" placeholder="Enter your current password">
                                </div>
                            </div>
                            
                            <div class="form-group-custom">
                                <label class="form-label-custom" for="newpassword">
                                    <i class="fas fa-plus-circle me-1"></i> New Password
                                </label>
                                <div class="input-group-icon">
                                    <i class="fas fa-shield-alt"></i>
                                    <input type="password" class="form-control-custom" id="newpassword" name="newpassword" placeholder="Enter new password (min. 6 characters)">
                                </div>
                                <div class="password-strength" id="passwordStrength"></div>
                            </div>
                            
                            <div class="form-group-custom">
                                <label class="form-label-custom" for="confirmpassword">
                                    <i class="fas fa-check-circle me-1"></i> Confirm New Password
                                </label>
                                <div class="input-group-icon">
                                    <i class="fas fa-redo-alt"></i>
                                    <input type="password" class="form-control-custom" id="confirmpassword" name="confirmpassword" placeholder="Re-enter your new password">
                                </div>
                            </div>
                            
                            <button type="submit" name="save" class="btn btn-save">
                                <i class="fas fa-save me-2"></i> Update Password
                            </button>
                        </form>
                        
                        <!-- Security Tips -->
                        <div class="security-tips">
                            <h6><i class="fas fa-tips me-1"></i> Password Security Tips:</h6>
                            <ul>
                                <li><i class="fas fa-check-circle text-success me-1" style="font-size: 0.7rem;"></i> Use at least 8 characters</li>
                                <li><i class="fas fa-check-circle text-success me-1" style="font-size: 0.7rem;"></i> Include uppercase and lowercase letters</li>
                                <li><i class="fas fa-check-circle text-success me-1" style="font-size: 0.7rem;"></i> Add numbers and special characters (!@#$%)</li>
                                <li><i class="fas fa-check-circle text-success me-1" style="font-size: 0.7rem;"></i> Avoid using common words or personal information</li>
                                <li><i class="fas fa-check-circle text-success me-1" style="font-size: 0.7rem;"></i> Never share your password with anyone</li>
                            </ul>
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
    
    // Password strength indicator
    $('#newpassword').on('keyup', function() {
        var password = $(this).val();
        var strength = 0;
        
        if (password.length >= 6) strength++;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!]+/)) strength++;
        
        var strengthText = '';
        var strengthClass = '';
        
        if (password.length === 0) {
            strengthText = '';
        } else if (strength <= 2) {
            strengthText = 'Weak password';
            strengthClass = 'strength-weak';
        } else if (strength <= 4) {
            strengthText = 'Medium password';
            strengthClass = 'strength-medium';
        } else {
            strengthText = 'Strong password!';
            strengthClass = 'strength-strong';
        }
        
        $('#passwordStrength').html('<i class="fas fa-info-circle me-1"></i>' + strengthText).attr('class', 'password-strength ' + strengthClass);
    });
    
    // Form validation
    $("#signupForm1").validate({
        rules: {
            oldpassword: {
                required: true
            },
            newpassword: {
                required: true,
                minlength: 6
            },
            confirmpassword: {
                required: true,
                minlength: 6,
                equalTo: "#newpassword"
            }
        },
        messages: {
            oldpassword: {
                required: "Please enter your current password"
            },
            newpassword: {
                required: "Please enter a new password",
                minlength: "Password must be at least 6 characters"
            },
            confirmpassword: {
                required: "Please confirm your new password",
                minlength: "Password must be at least 6 characters",
                equalTo: "Passwords do not match"
            }
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
            error.addClass("help-block text-danger");
            error.insertAfter(element);
        },
        highlight: function(element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
            $(element).css('border-color', '#ef4444');
        },
        unhighlight: function(element) {
            $(element).addClass("is-valid").removeClass("is-invalid");
            $(element).css('border-color', '#10b981');
        }
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>

</body>
</html>