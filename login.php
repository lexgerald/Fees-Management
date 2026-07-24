<?php
    include("php/dbconnect.php");

    $error = '';
    if(isset($_POST['login']))
    {

    $username =  mysqli_real_escape_string($conn,trim($_POST['username']));
    $password =  mysqli_real_escape_string($conn,$_POST['password']);

    if($username=='' || $password=='')
    {
    $error='All fields are required';
    }

    $sql = "select * from user where username='".$username."' and password = '".md5($password)."'";

    $q = $conn->query($sql);
    if($q->num_rows==1)
    {
    $res = $q->fetch_assoc();
    $_SESSION['rainbow_username']=$res['username'];
    $_SESSION['rainbow_uid']=$res['id'];
    $_SESSION['rainbow_name']=$res['name'];
    echo '<script type="text/javascript">window.location="index.php"; </script>';

    }else
    {
    $error = 'Invalid Username or Password';
    }

    }

?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>Fees Management System</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="css/font-awesome.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    
    <style>
        @font-face {
            font-family: Poppins;
            src: url("fonts/Poppins-Regular.ttf");
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: "Poppins", "Open Sans", sans-serif;
        }

        body {
            background: linear-gradient(135deg, #58bcde 0%, #424cdf 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 15px;
            overflow-y: auto;
        }

        .container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            padding: 0 10px;
        }

        /* Logo container styling - more compact */
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            margin-bottom: 10px;
        }

        .logo-circle i {
            font-size: 38px;
            color: #58bcde;
        }

        .logo-text {
            color: white;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        .logo-sub {
            color: rgba(255,255,255,0.85);
            font-size: 12px;
            margin-top: 3px;
        }

        /* Modern card styling - more compact */
        .login-card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .panel-body {
            padding: 25px 28px 30px !important;
            background: white !important;
            margin-top: 0 !important;
            box-shadow: none !important;
        }

        .myhead {
            margin: 0 0 6px 0;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
            letter-spacing: -0.3px;
            position: relative;
            padding-bottom: 10px;
        }

        .myhead:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 2px;
            background: linear-gradient(90deg, #58bcde, #424cdf);
            border-radius: 2px;
        }

        .subtitle {
            text-align: center;
            color: #718096;
            font-size: 12px;
            margin-bottom: 18px;
        }

        hr {
            margin: 15px 0 18px;
            border: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        }

        /* Form group styling - more compact */
        .form-group {
            margin-bottom: 15px;
        }

        .input-group {
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .input-group-addon {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            padding: 8px 14px;
            color: #58bcde;
            font-size: 14px;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            padding: 8px 14px;
            font-size: 14px;
            height: auto;
            transition: all 0.2s ease;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #424cdf;
            outline: none;
            box-shadow: none;
        }

        /* Button styling */
        .btn-login {
            background: linear-gradient(135deg, #58bcde 0%, #424cdf 100%);
            border: none;
            padding: 10px 18px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 40px;
            width: 100%;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
            letter-spacing: 0.3px;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Error message styling - compact */
        .alert-custom {
            background: #fed7d7;
            color: #c53030;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 12px;
            text-align: center;
            margin-bottom: 15px;
            border-left: 3px solid #e53e3e;
        }

        /* Footer links */
        .login-footer {
            text-align: center;
            margin-top: 18px;
            font-size: 11px;
            color: #a0aec0;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Make sure everything fits on screen */
        @media (max-width: 500px) {
            body {
                padding: 10px;
                align-items: flex-start;
                padding-top: 20px;
            }
            
            .container {
                max-width: 100%;
                padding: 0 5px;
            }
            
            .panel-body {
                padding: 20px 20px 25px !important;
            }
            
            .myhead {
                font-size: 18px;
                padding-bottom: 8px;
            }
            
            .logo-circle {
                width: 60px;
                height: 60px;
            }
            
            .logo-circle i {
                font-size: 32px;
            }
            
            .logo-text {
                font-size: 18px;
            }
            
            .logo-sub {
                font-size: 11px;
            }
            
            .form-control, .input-group-addon {
                padding: 7px 12px;
                font-size: 13px;
            }
            
            .btn-login {
                padding: 8px 16px;
                font-size: 14px;
            }
        }

        /* For very small heights (laptop screens) */
        @media (max-height: 650px) {
            body {
                align-items: flex-start;
                padding-top: 15px;
            }
            
            .logo-container {
                margin-bottom: 12px;
            }
            
            .logo-circle {
                width: 55px;
                height: 55px;
                margin-bottom: 6px;
            }
            
            .logo-circle i {
                font-size: 28px;
            }
            
            .logo-text {
                font-size: 16px;
            }
            
            .logo-sub {
                font-size: 10px;
            }
            
            .panel-body {
                padding: 18px 20px 22px !important;
            }
            
            .myhead {
                font-size: 18px;
                margin-bottom: 4px;
                padding-bottom: 8px;
            }
            
            .subtitle {
                font-size: 11px;
                margin-bottom: 12px;
            }
            
            .form-group {
                margin-bottom: 12px;
            }
            
            .btn-login {
                margin-top: 5px;
                padding: 8px 14px;
            }
            
            .login-footer {
                margin-top: 12px;
            }
        }

        .panel-body {
            background-color: transparent !important;
        }
        
        /* Ensure scrolling works if needed */
        body {
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo Section - Professional Logo on Top (compact) -->
        <div class="logo-container">
            <div class="logo-circle">
                <i class="fa fa-graduation-cap"></i>
                <img src="" alt="">
            </div>
            <div class="logo-text">Fees Manager</div>
            <div class="logo-sub">Secure Access Portal</div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="login-card">
                    <div class="panel-body" style="background-color: #fff; margin-top: 0px; box-shadow: none;">
                        <h3 class="myhead">Bundu's Fees Management System</h3>
                        <div class="subtitle">Sign in to your account</div>
                        
                        <form role="form" action="login.php" method="post">
                            <?php
                            if($error != '')
                            {                                   
                                echo '<div class="alert-custom"><i class="fa fa-exclamation-circle" style="margin-right: 6px;"></i>' . $error . '</div>';
                            }
                            ?>
                            
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" placeholder="Username" name="username" required />
                            </div>
                            
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" placeholder="Password" name="password" required />
                            </div>
                            
                            <button class="btn btn-login" type="submit" name="login">
                                <i class="fa fa-sign-in" style="margin-right: 6px;"></i> Login
                            </button>
                            
                            <div class="login-footer">
                                <i class="fa fa-shield"></i> Secure Login · <a href="#">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>