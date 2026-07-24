<?php
include("php/dbconnect.php");

if(isset($_POST['req']) && $_POST['req']=='1') 
{

$sid = (isset($_POST['student']))?mysqli_real_escape_string($conn,$_POST['student']):'';

$sql = "select s.id,s.sname,s.balance,s.fees,s.contact,b.grade,s.joindate from student as s,grade as b where b.id=s.grade and s.delete_status='0' and s.id='".$sid."'";
$q = $conn->query($sql);
if($q->num_rows>0)
{

$res = $q->fetch_assoc();
echo '  <!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Inter", sans-serif;
            background: transparent;
        }
        
        .modern-fee-form {
            padding: 0;
        }
        
        /* Student Profile Card */
        .student-profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.8rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .student-profile-card::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .student-profile-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .student-profile-card .student-badge {
            background: rgba(255,255,255,0.2);
            border-radius: 30px;
            padding: 0.2rem 0.8rem;
            font-size: 0.7rem;
            display: inline-block;
            margin-top: 0.5rem;
        }
        
        .student-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1.2rem;
        }
        
        .detail-item {
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 0.6rem 1rem;
            backdrop-filter: blur(10px);
        }
        
        .detail-item i {
            margin-right: 8px;
            opacity: 0.9;
        }
        
        .detail-item span {
            font-size: 0.75rem;
            opacity: 0.8;
            display: block;
            margin-bottom: 4px;
        }
        
        .detail-item strong {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        /* Balance Card */
        .balance-card {
            background: white;
            border-radius: 16px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            text-align: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        
        .balance-label {
            color: #64748b;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        
        .balance-amount {
            font-size: 2rem;
            font-weight: 700;
        }
        
        .balance-amount.due {
            color: #ef4444;
        }
        
        .balance-amount.paid {
            color: #10b981;
        }
        
        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }
        
        .form-label i {
            color: #3b82f6;
            margin-right: 8px;
            width: 20px;
        }
        
        .required-star {
            color: #ef4444;
            margin-left: 3px;
        }
        
        /* Modern Input Wrapper */
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            z-index: 1;
        }
        
        .modern-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.9rem;
            font-family: "Inter", sans-serif;
            transition: all 0.2s ease;
            background: white;
        }
        
        .modern-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .modern-input:hover {
            border-color: #3b82f6;
        }
        
        .modern-input:disabled {
            background: #f8fafc;
            cursor: not-allowed;
        }
        
        textarea.modern-input {
            padding: 0.8rem 1rem;
            resize: vertical;
            min-height: 80px;
        }
        
        textarea.modern-input i {
            display: none;
        }
        
        /* Currency Symbol */
        .currency-symbol {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-weight: 600;
            z-index: 1;
            pointer-events: none;
        }
        
        .amount-input {
            padding-left: 55px !important;
        }
        
        /* Submit Button */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 0.9rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }
        
        /* Helper Text */
        .helper-text {
            font-size: 0.7rem;
            color: #64748b;
            margin-top: 0.5rem;
            display: block;
        }
        
        /* Divider */
        .form-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 1rem 0 1.5rem;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modern-fee-form {
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body>
<div class="modern-fee-form">
    <!-- Student Profile Card -->
    <div class="student-profile-card">
        <h3>
            <i class="fas fa-user-graduate"></i>
            ' . htmlspecialchars($res['sname']) . '
        </h3>
        <div class="student-badge">
            <i class="fas fa-id-card"></i> Student ID: #' . str_pad($sid, 5, "0", STR_PAD_LEFT) . '
        </div>
        <div class="student-details-grid">
            <div class="detail-item">
                <i class="fas fa-graduation-cap"></i>
                <span>Course / Grade</span>
                <strong>' . htmlspecialchars($res['grade']) . '</strong>
            </div>
            <div class="detail-item">
                <i class="fas fa-phone-alt"></i>
                <span>Contact Number</span>
                <strong>' . htmlspecialchars($res['contact']) . '</strong>
            </div>
            <div class="detail-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Date of Joining</span>
                <strong>' . date("d M Y", strtotime($res['joindate'])) . '</strong>
            </div>
        </div>
    </div>
    
    <!-- Balance Card -->
    <div class="balance-card">
        <div class="balance-label">
            <i class="fas fa-chart-line"></i> Current Outstanding Balance
        </div>
        <div class="balance-amount due">
            Le ' . number_format($res['balance'], 2) . '
        </div>
        <small style="color: #64748b;">
            Total Fees: Le ' . number_format($res['fees'], 2) . ' | Paid: Le ' . number_format($res['fees'] - $res['balance'], 2) . '
        </small>
    </div>
    
    <form method="post" action="fees.php" id="signupForm1">
        <!-- Total Fee (Read Only) -->
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-dollar-sign"></i> Total Fee
            </label>
            <div class="input-wrapper">
                <span class="currency-symbol">Le</span>
                <input type="text" class="modern-input amount-input" name="totalfee" id="totalfee" value="' . number_format($res['fees'], 2) . '" disabled>
            </div>
        </div>
        
        <!-- Balance (Read Only) -->
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-chart-line"></i> Current Balance
            </label>
            <div class="input-wrapper">
                <span class="currency-symbol">Le</span>
                <input type="text" class="modern-input amount-input" name="balance_display" id="balance_display" value="' . number_format($res['balance'], 2) . '" disabled>
                <input type="hidden" value="' . $res['id'] . '" name="sid">
                <input type="hidden" value="' . $res['balance'] . '" id="actual_balance">
            </div>
        </div>
        
        <!-- Payment Amount -->
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-hand-holding-usd"></i> Payment Amount <span class="required-star">*</span>
            </label>
            <div class="input-wrapper">
                <span class="currency-symbol">Le</span>
                <input type="number" class="modern-input amount-input" name="paid" id="paid" placeholder="Enter amount to pay" step="0.01" max="' . $res['balance'] . '" required>
            </div>
            <small class="helper-text">
                <i class="fas fa-info-circle"></i> Maximum payable: Le ' . number_format($res['balance'], 2) . '
            </small>
        </div>
        
        <!-- Payment Date -->
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-calendar-alt"></i> Payment Date <span class="required-star">*</span>
            </label>
            <div class="input-wrapper">
                <i class="fas fa-calendar-day"></i>
                <input type="text" class="modern-input" name="submitdate" id="submitdate" placeholder="Select payment date" autocomplete="off" readonly required>
            </div>
        </div>
        
        <!-- Remark -->
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-comment-alt"></i> Payment Remark
            </label>
            <textarea class="modern-input" name="transcation_remark" id="transcation_remark" rows="3" placeholder="e.g., Cash payment, Cheque #12345, Bank Transfer, etc."></textarea>
        </div>
        
        <div class="form-divider"></div>
        
        <button type="submit" name="save" class="submit-btn">
            <i class="fas fa-check-circle"></i> Process Payment
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Datepicker
    $("#submitdate").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        yearRange: "2020:2030",
        showButtonPanel: true,
        showAnim: "fadeIn",
        todayHighlight: true,
        onSelect: function(dateText) {
            $(this).val(dateText);
        }
    });
    
    // Set default date to today
    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, "0");
    var day = String(today.getDate()).padStart(2, "0");
    var formattedDate = year + "-" + month + "-" + day;
    $("#submitdate").val(formattedDate);
    
    // Payment amount validation
    var maxAmount = parseFloat($("#actual_balance").val());
    
    $("#paid").on("keyup change", function() {
        var amount = parseFloat($(this).val());
        if(amount > maxAmount) {
            $(this).val(maxAmount);
            showNotification("Amount cannot exceed balance amount (Le " + maxAmount.toLocaleString() + ")", "warning");
        }
        if(amount < 0) {
            $(this).val(0);
        }
    });
    
    // Form validation
    $("#signupForm1").validate({
        rules: {
            submitdate: "required",
            paid: {
                required: true,
                number: true,
                min: 0.01,
                max: maxAmount
            }
        },
        messages: {
            submitdate: "Please select payment date",
            paid: {
                required: "Please enter payment amount",
                number: "Please enter a valid number",
                min: "Amount must be greater than 0",
                max: "Amount cannot exceed balance of Le " + maxAmount.toLocaleString()
            }
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
            error.addClass("help-block text-danger");
            error.insertAfter(element);
        },
        highlight: function(element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
            $(element).css("border-color", "#ef4444");
        },
        unhighlight: function(element) {
            $(element).addClass("is-valid").removeClass("is-invalid");
            $(element).css("border-color", "#10b981");
        }
    });
    
    // Notification function
    function showNotification(message, type) {
        var notification = $("<div class=\"custom-toast toast-" + type + "\">" +
            "<i class=\"fas fa-" + (type === "error" ? "exclamation-circle" : "info-circle") + "\"></i>" +
            "<span>" + message + "</span>" +
            "</div>");
        $("body").append(notification);
        notification.css({
            position: "fixed",
            bottom: "20px",
            right: "20px",
            background: type === "error" ? "#ef4444" : "#f59e0b",
            color: "white",
            padding: "12px 20px",
            borderRadius: "12px",
            fontSize: "0.85rem",
            zIndex: "9999",
            boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
            fontFamily: "Inter, sans-serif"
        });
        setTimeout(function() {
            notification.fadeOut(500, function() { $(this).remove(); });
        }, 3000);
    }
});
</script>

<style>
/* Datepicker Styling */
.ui-datepicker {
    background: white;
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    padding: 12px;
    font-family: "Inter", sans-serif;
    z-index: 9999 !important;
}

.ui-datepicker-header {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: white;
    border: none;
    border-radius: 14px;
    padding: 10px;
}

.ui-datepicker-title {
    color: white;
}

.ui-datepicker-prev, .ui-datepicker-next {
    background: rgba(255,255,255,0.2);
    border-radius: 8px;
    cursor: pointer;
}

.ui-datepicker-prev:hover, .ui-datepicker-next:hover {
    background: rgba(255,255,255,0.3);
}

.ui-datepicker-calendar th {
    color: #64748b;
    font-weight: 600;
    font-size: 0.7rem;
    padding: 5px;
}

.ui-datepicker-calendar td a {
    background: #f8fafc;
    border-radius: 10px;
    transition: all 0.2s;
    text-align: center;
    padding: 5px;
}

.ui-datepicker-calendar td a:hover {
    background: #3b82f6;
    color: white;
}

.ui-datepicker-today a {
    background: #eef2ff;
    border: 1px solid #3b82f6;
}

.ui-datepicker-current-day a {
    background: #3b82f6 !important;
    color: white !important;
}

.custom-toast {
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button {
    opacity: 0.5;
}

.is-invalid {
    border-color: #ef4444 !important;
}

.is-valid {
    border-color: #10b981 !important;
}

.help-block {
    font-size: 0.7rem;
    margin-top: 0.25rem;
}
</style>
</body>
</html>';

}else
{
echo "Something Goes Wrong! Try After sometime.";
}


}

if(isset($_POST['req']) && $_POST['req']=='2') 
{

$sid = (isset($_POST['student']))?mysqli_real_escape_string($conn,$_POST['student']):'';
$sql = "select paid,submitdate,transcation_remark from fees_transaction where stdid='".$sid."'";
$fq = $conn->query($sql);
if($fq->num_rows>0)
{

$sql = "select s.id,s.sname,s.balance,s.fees,s.contact,b.grade,s.joindate from student as s,grade as b where b.id=s.grade and s.id='".$sid."'";
$sq = $conn->query($sql);
$sr = $sq->fetch_assoc();

echo '
<style>
.report-container {
    font-family: "Inter", sans-serif;
}
.student-info-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.student-info-table th, .student-info-table td {
    padding: 12px;
    border: 1px solid #e2e8f0;
    text-align: left;
}
.student-info-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #1e293b;
    width: 150px;
}
.fee-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.fee-table th, .fee-table td {
    padding: 12px;
    border: 1px solid #e2e8f0;
    text-align: left;
}
.fee-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #1e293b;
}
.summary-table {
    width: 300px;
    border-collapse: collapse;
    margin-top: 20px;
}
.summary-table th, .summary-table td {
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
}
.summary-table th {
    background: #f8fafc;
    font-weight: 600;
}
.total-fees { color: #3b82f6; font-weight: bold; }
.total-paid { color: #10b981; font-weight: bold; }
.balance { color: #ef4444; font-weight: bold; }
</style>

<div class="report-container">
    <h4 style="color: #1e293b; margin-bottom: 15px;"><i class="fas fa-user-graduate"></i> Student Information</h4>
    <table class="student-info-table">
        <tr>
            <th>Full Name</th>
            <td>' . htmlspecialchars($sr['sname']) . '</td>
            <th>Course</th>
            <td>' . htmlspecialchars($sr['grade']) . '</td>
        </tr>
        <tr>
            <th>Contact</th>
            <td>' . htmlspecialchars($sr['contact']) . '</td>
            <th>Joined On</th>
            <td>' . date("d-m-Y", strtotime($sr['joindate'])) . '</td>
        </tr>
    </table>

    <h4 style="color: #1e293b; margin-bottom: 15px;"><i class="fas fa-money-bill-wave"></i> Fee Transaction History</h4>
    <table class="fee-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Paid (Le)</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>';
        $totapaid = 0;
        while($res = $fq->fetch_assoc())
        {
            $totapaid += $res['paid'];
            echo '<tr>
                <td>' . date("d-m-Y", strtotime($res['submitdate'])) . '</td>
                <td>Le ' . number_format($res['paid'], 2) . '</td>
                <td>' . htmlspecialchars($res['transcation_remark']) . '</td>
            </tr>';
        }
      
echo '      </tbody>
    </table>
    
    <table class="summary-table">
        <tr>
            <th>Total Fees:</th>
            <td class="total-fees">Le ' . number_format($sr['fees'], 2) . '</td>
        </tr>
        <tr>
            <th>Total Paid:</th>
            <td class="total-paid">Le ' . number_format($totapaid, 2) . '</td>
        </tr>
        <tr>
            <th>Balance:</th>
            <td class="balance">Le ' . number_format($sr['balance'], 2) . '</td>
        </tr>
    </table>
</div>';
}
else
{
echo '<div style="padding: 20px; text-align: center; font-family: Inter, sans-serif;">
        <i class="fas fa-receipt" style="font-size: 48px; color: #94a3b8; margin-bottom: 15px; display: block;"></i>
        <h4 style="color: #64748b;">No fees submitted yet</h4>
        <p style="color: #94a3b8;">This student has no payment records.</p>
      </div>';
}
}
?>