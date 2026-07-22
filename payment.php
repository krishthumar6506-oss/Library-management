<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include 'components/connect.php';

$student_id = (int) $_SESSION['student_id'];
$success = '';
$error = '';
$payments = [];
$penalties = [];
$penalty_rate = 10;

$fee_types = [
    'membership' => 'Membership Fee',
    'late_fine' => 'Late Fine',
    'security_deposit' => 'Security Deposit',
    'other' => 'Other Charges',
];

$payment_methods = [
    'upi' => 'UPI',
    'debit_card' => 'Debit Card',
    'credit_card' => 'Credit Card',
    'net_banking' => 'Net Banking',
    'cash' => 'Cash',
];

try {
    $conn->exec("
        CREATE TABLE IF NOT EXISTS payments (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            student_id INT(11) NOT NULL,
            payment_for VARCHAR(50) NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            penalty_id INT(11) DEFAULT NULL,
            transaction_ref VARCHAR(100) DEFAULT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'Completed',
            paid_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");

    $payment_columns = $conn->query("SHOW COLUMNS FROM payments LIKE 'penalty_id'");
    if ($payment_columns->rowCount() === 0) {
        $conn->exec("ALTER TABLE payments ADD COLUMN penalty_id INT(11) DEFAULT NULL AFTER payment_method");
    }

    $conn->exec("
        CREATE TABLE IF NOT EXISTS penalties (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            borrow_id INT(11) NOT NULL,
            student_id INT(11) NOT NULL,
            book_id INT(11) NOT NULL,
            due_date DATE NOT NULL,
            return_date DATE NOT NULL,
            late_days INT(11) NOT NULL DEFAULT 0,
            amount DECIMAL(10,2) NOT NULL DEFAULT 0,
            status VARCHAR(20) NOT NULL DEFAULT 'Unpaid',
            paid_at DATETIME DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");

    if (isset($_POST['pay_penalty'])) {
        $penalty_id = (int) ($_POST['penalty_id'] ?? 0);
        $payment_method = trim($_POST['payment_method'] ?? '');
        $transaction_ref = trim($_POST['transaction_ref'] ?? '');

        if (!isset($payment_methods[$payment_method])) {
            $error = 'Please choose a valid payment method for the penalty.';
        } elseif ($payment_method !== 'cash' && $transaction_ref === '') {
            $error = 'Transaction reference is required for online penalty payments.';
        } else {
            $penalty_stmt = $conn->prepare("
                SELECT penalties.*, books.title, books.isbn
                FROM penalties
                JOIN books ON books.id = penalties.book_id
                WHERE penalties.id = ? AND penalties.student_id = ?
                LIMIT 1
            ");
            $penalty_stmt->execute([$penalty_id, $student_id]);
            $penalty = $penalty_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$penalty) {
                $error = 'Penalty record not found.';
            } elseif ($penalty['status'] === 'Paid') {
                $error = 'This penalty has already been paid.';
            } else {
                $conn->beginTransaction();

                $insert_payment = $conn->prepare("
                    INSERT INTO payments (student_id, payment_for, amount, payment_method, penalty_id, transaction_ref, status)
                    VALUES (?, 'late_fine', ?, ?, ?, ?, 'Completed')
                ");
                $insert_payment->execute([
                    $student_id,
                    number_format((float) $penalty['amount'], 2, '.', ''),
                    $payment_method,
                    $penalty_id,
                    $transaction_ref !== '' ? $transaction_ref : null,
                ]);

                $update_penalty = $conn->prepare("
                    UPDATE penalties
                    SET status = 'Paid', paid_at = NOW()
                    WHERE id = ?
                ");
                $update_penalty->execute([$penalty_id]);

                $conn->commit();
                $success = 'Penalty paid successfully for "' . $penalty['title'] . '".';
            }
        }
    }

    if (isset($_POST['pay_now'])) {
        $payment_for = trim($_POST['payment_for'] ?? '');
        $amount = trim($_POST['amount'] ?? '');
        $payment_method = trim($_POST['payment_method'] ?? '');
        $transaction_ref = trim($_POST['transaction_ref'] ?? '');

        if (!isset($fee_types[$payment_for])) {
            $error = 'Please choose a valid payment type.';
        } elseif (!isset($payment_methods[$payment_method])) {
            $error = 'Please choose a valid payment method.';
        } elseif ($amount === '' || !is_numeric($amount) || (float) $amount <= 0) {
            $error = 'Please enter a valid amount.';
        } elseif ($payment_method !== 'cash' && $transaction_ref === '') {
            $error = 'Transaction reference is required for online payments.';
        } else {
            $stmt = $conn->prepare("
                INSERT INTO payments (student_id, payment_for, amount, payment_method, penalty_id, transaction_ref, status)
                VALUES (?, ?, ?, ?, NULL, ?, 'Completed')
            ");
            $stmt->execute([
                $student_id,
                $payment_for,
                number_format((float) $amount, 2, '.', ''),
                $payment_method,
                $transaction_ref !== '' ? $transaction_ref : null,
            ]);

            $success = 'Payment submitted successfully.';
        }
    }

    $student_stmt = $conn->prepare("SELECT firstname, lastname, email FROM students WHERE id = ?");
    $student_stmt->execute([$student_id]);
    $student = $student_stmt->fetch(PDO::FETCH_ASSOC);

    $penalty_stmt = $conn->prepare("
        SELECT penalties.*, books.title, books.isbn
        FROM penalties
        JOIN books ON books.id = penalties.book_id
        WHERE penalties.student_id = ? AND penalties.status = 'Unpaid'
        ORDER BY penalties.return_date DESC, penalties.id DESC
    ");
    $penalty_stmt->execute([$student_id]);
    $penalties = $penalty_stmt->fetchAll(PDO::FETCH_ASSOC);

    $history_stmt = $conn->prepare("
        SELECT
            payments.payment_for,
            payments.amount,
            payments.payment_method,
            payments.transaction_ref,
            payments.status,
            payments.paid_at,
            books.title AS penalty_book_title,
            books.isbn AS penalty_book_isbn
        FROM payments
        LEFT JOIN penalties ON penalties.id = payments.penalty_id
        LEFT JOIN books ON books.id = penalties.book_id
        WHERE payments.student_id = ?
        ORDER BY payments.paid_at DESC, payments.id DESC
    ");
    $history_stmt->execute([$student_id]);
    $payments = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Payment service is not available right now.';
    $student = [
        'firstname' => $_SESSION['student_name'] ?? 'Student',
        'lastname' => '',
        'email' => '',
    ];
    $payments = [];
    $penalties = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Payment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{
    background:url("img/backgroundd.jpg") no-repeat center center fixed;
    background-size:cover;
}

.payment-wrapper{
    max-width:1150px;
    margin:40px auto;
    padding:0 15px 40px;
}

.payment-grid{
    display:grid;
    grid-template-columns:1fr 1.3fr;
    gap:24px;
}

.panel{
    background:rgba(238, 229, 229, 0.94);
    border-radius:22px;
    box-shadow:0 18px 40px rgba(0,0,0,0.15);
    padding:28px;
}

.panel h2{
    font-size:30px;
    margin-bottom:10px;
    color:#2f2525;
}

.panel p{
    color:#5b5050;
    margin-bottom:20px;
}

.summary-card{
    background:linear-gradient(135deg, #5f4b8b, #9f6f6f);
    color:#fff;
    border-radius:18px;
    padding:22px;
    margin-bottom:22px;
}

.summary-card h3{
    font-size:24px;
    margin-bottom:10px;
}

.summary-meta{
    display:grid;
    gap:10px;
    font-size:15px;
}

.method-list{
    display:grid;
    grid-template-columns:repeat(2, minmax(0, 1fr));
    gap:12px;
    margin-top:12px;
}

.method-item{
    border:1px solid rgba(0,0,0,0.1);
    border-radius:14px;
    background:#fff;
    padding:14px 16px;
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:600;
    color:#413636;
}

.form-label{
    font-weight:600;
    color:#413636;
}

.form-control, .form-select{
    border-radius:14px;
    padding:12px 14px;
}

.btn-pay{
    width:100%;
    border:none;
    border-radius:14px;
    padding:13px 18px;
    background:linear-gradient(135deg, #5f4b8b, #d07d54);
    color:#fff;
    font-weight:700;
}

.table-wrap{
    overflow-x:auto;
}

.penalty-list{
    display:grid;
    gap:16px;
}

.penalty-card{
    background:#fff;
    border-radius:18px;
    border:1px solid rgba(0,0,0,0.08);
    box-shadow:0 10px 24px rgba(0,0,0,0.06);
    padding:18px;
}

.penalty-card h3{
    font-size:20px;
    margin-bottom:8px;
    color:#2f2525;
}

.penalty-meta{
    display:grid;
    grid-template-columns:repeat(2, minmax(0, 1fr));
    gap:10px;
    margin-bottom:16px;
    font-size:14px;
    color:#5b5050;
}

.penalty-amount{
    font-size:26px;
    font-weight:800;
    color:#c2410c;
}

.inline-form{
    display:grid;
    grid-template-columns:1fr 1fr auto;
    gap:12px;
    align-items:end;
}

.inline-form .btn-pay{
    margin:0;
    min-width:180px;
}

table{
    width:100%;
    background:#fff;
    border-radius:16px;
    overflow:hidden;
}

th{
    background:#6d5959;
    color:#fff;
    font-weight:600;
    white-space:nowrap;
}

td, th{
    padding:14px 12px !important;
    vertical-align:middle;
}

.badge-status{
    background:#d7f6df;
    color:#1a7f37;
    border-radius:30px;
    padding:7px 12px;
    font-size:12px;
    font-weight:700;
}

.empty-state{
    text-align:center;
    padding:34px 20px;
    color:#6b6060;
    background:#fff;
    border-radius:16px;
}

@media (max-width: 991px){
    .payment-grid{
        grid-template-columns:1fr;
    }
}

@media (max-width: 575px){
    .method-list,
    .penalty-meta,
    .inline-form{
        grid-template-columns:1fr;
    }

    .panel{
        padding:20px;
    }

    .panel h2{
        font-size:24px;
    }
}
</style>
</head>
<body>

<?php include 'components/hearder.php'; ?>

<div class="payment-wrapper">
    <div class="payment-grid">
        <section class="panel">
            <h2>Library Payment</h2>
            <p>Students can pay library fees here and keep a simple payment record on their account.</p>

            <div class="summary-card">
                <h3><?php echo htmlspecialchars(trim(($student['firstname'] ?? 'Student') . ' ' . ($student['lastname'] ?? ''))); ?></h3>
                <div class="summary-meta">
                    <span><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($student['email'] ?? ''); ?></span>
                    <span><i class="fa-solid fa-wallet"></i> Secure record of your library payments</span>
                </div>
            </div>

            <div class="method-list">
                <div class="method-item"><i class="fa-brands fa-google-pay"></i> UPI</div>
                <div class="method-item"><i class="fa-regular fa-credit-card"></i> Card</div>
                <div class="method-item"><i class="fa-solid fa-building-columns"></i> Net Banking</div>
                <div class="method-item"><i class="fa-solid fa-money-bill-wave"></i> Cash</div>
            </div>
        </section>

        <section class="panel">
            <h2>Pay Now</h2>
            <p>Choose a fee type, amount, and payment method to save the payment on your account.</p>

            <?php if ($success !== ''): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

           <form method="POST" id="paymentForm">
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Payment For</label>
            <select class="form-select" name="payment_for" id="payment_for">
                <option value="">Select payment type</option>
                <?php foreach ($fee_types as $value => $label): ?>
                    <option value="<?= $value; ?>"><?= $label; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Amount</label>
            <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter Amount">
        </div>

        <div class="col-md-6">
            <label class="form-label">Payment Method</label>
            <select class="form-select" name="payment_method" id="payment_method">
                <option value="">Select payment method</option>
                <?php foreach ($payment_methods as $value => $label): ?>
                    <option value="<?= $value; ?>"><?= $label; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Transaction Reference</label>
            <input type="text" class="form-control" name="transaction_ref" id="transaction_ref" placeholder="Optional for cash">
        </div>

        <div class="col-12">
            <button type="submit" name="pay_now" class="btn-pay">
                Submit Payment
            </button>
        </div>

    </div>
</form>


        </section>
    </div>

    <section class="panel mt-4">
        <h2>Penalty Payments</h2>
        <p>Late return penalties are calculated at Rs. <?php echo htmlspecialchars((string) $penalty_rate); ?> per late day and can be paid here.</p>

        <?php if (!empty($penalties)): ?>
            <div class="penalty-list">
                <?php foreach ($penalties as $penalty): ?>
                    <div class="penalty-card">
                        <h3><?php echo htmlspecialchars($penalty['title']); ?></h3>
                        <div class="penalty-meta">
                            <span><strong>ISBN:</strong> <?php echo htmlspecialchars($penalty['isbn']); ?></span>
                            <span><strong>Due Date:</strong> <?php echo htmlspecialchars(date('d M Y', strtotime($penalty['due_date']))); ?></span>
                            <span><strong>Returned:</strong> <?php echo htmlspecialchars(date('d M Y', strtotime($penalty['return_date']))); ?></span>
                            <span><strong>Late Days:</strong> <?php echo htmlspecialchars((string) $penalty['late_days']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                            <span class="penalty-amount">Rs. <?php echo htmlspecialchars(number_format((float) $penalty['amount'], 2)); ?></span>
                            <span class="badge bg-danger">Unpaid Penalty</span>
                        </div>

                        <form method="POST" class="inline-form">
                            <input type="hidden" name="penalty_id" value="<?php echo (int) $penalty['id']; ?>">

                            <div>
                                <label class="form-label" for="payment_method_<?php echo (int) $penalty['id']; ?>">Method</label>
                                <select class="form-select" name="payment_method" id="payment_method_<?php echo (int) $penalty['id']; ?>" required>
                                    <option value="">Select method</option>
                                    <?php foreach ($payment_methods as $value => $label): ?>
                                        <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="form-label" for="transaction_ref_<?php echo (int) $penalty['id']; ?>">Reference</label>
                                <input type="text" class="form-control" name="transaction_ref" id="transaction_ref_<?php echo (int) $penalty['id']; ?>" placeholder="Optional for cash">
                            </div>

                            <button type="submit" name="pay_penalty" class="btn-pay">
                                <i class="fa-solid fa-wallet"></i> Pay Penalty
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                No unpaid penalties right now. You are all clear.
            </div>
        <?php endif; ?>
    </section>

    <section class="panel mt-4">
        <h2>Payment History</h2>
        <p>Your recent payments are listed below.</p>

        <?php if (!empty($payments)): ?>
            <div class="table-wrap">
                <table class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Payment For</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($fee_types[$payment['payment_for']] ?? $payment['payment_for']); ?>
                                    <?php if (!empty($payment['penalty_book_title'])): ?>
                                        <div class="small text-muted">
                                            <?php echo htmlspecialchars($payment['penalty_book_title']); ?> (ISBN: <?php echo htmlspecialchars($payment['penalty_book_isbn']); ?>)
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>Rs. <?php echo htmlspecialchars(number_format((float) $payment['amount'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($payment_methods[$payment['payment_method']] ?? $payment['payment_method']); ?></td>
                                <td><?php echo htmlspecialchars($payment['transaction_ref'] ?: 'N/A'); ?></td>
                                <td><span class="badge-status"><?php echo htmlspecialchars($payment['status']); ?></span></td>
                                <td><?php echo htmlspecialchars(date('d M Y, h:i A', strtotime($payment['paid_at']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                No payments found yet. Your payment history will appear here after the first transaction.
            </div>
        <?php endif; ?>
    </section>
</div>
<script>
$(document).ready(function(){

$("#paymentForm").submit(function(e){

var valid=true;

var payment_for=$("#payment_for").val().trim();
var amount=$("#amount").val().trim();
var payment_method=$("#payment_method").val().trim();
var transaction_ref=$("#transaction_ref").val().trim();

var amount_pattern=/^[0-9]+(\.[0-9]{1,2})?$/;
var ref_pattern=/^[a-zA-Z0-9]+$/;

$(".error").text("");
$(".form-control, .form-select").css("border","1px solid #ccc");

if(payment_for==""){
$("#payment_for_error").text("Select payment type");
$("#payment_for").css("border","2px solid red");
valid=false;
}
else{
$("#payment_for").css("border","2px solid green");
}

if(amount==""){
$("#amount_error").text("Amount required");
$("#amount").css("border","2px solid red");
valid=false;
}
else if(!amount_pattern.test(amount) || parseFloat(amount)<=0){
$("#amount_error").text("Enter valid amount");
$("#amount").css("border","2px solid red");
valid=false;
}
else{
$("#amount").css("border","2px solid green");
}

if(payment_method==""){
$("#payment_method_error").text("Select payment method");
$("#payment_method").css("border","2px solid red");
valid=false;
}
else{
$("#payment_method").css("border","2px solid green");
}

if(payment_method!="cash"){
if(transaction_ref==""){
$("#transaction_ref_error").text("Reference required");
$("#transaction_ref").css("border","2px solid red");
valid=false;
}
else if(!ref_pattern.test(transaction_ref)){
$("#transaction_ref_error").text("Letters & numbers only");
$("#transaction_ref").css("border","2px solid red");
valid=false;
}
else{
$("#transaction_ref").css("border","2px solid green");
}
}

if(!valid){
e.preventDefault();
}

});

});
</script>

<?php include 'components/footer.php'; ?>

</body>
</html>
