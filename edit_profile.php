<?php



// تحقق من تسجيل دخول المستخدم
if (!isset($_SESSION['userId'])) {
    header("Location: index.php?page=login");
    exit();
}

$userId = $_SESSION['userId'];

// جلب بيانات المستخدم من قاعدة البيانات
$query = $pdo->prepare("
    SELECT 
        name,
        emailAddress
    FROM 
        user
    WHERE 
        userId = ?
");

$query->execute([$userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

$message = ""; // رسالة خطأ أو نجاح

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال البيانات من النموذج
    $name = $_POST['name'];
    $email = $_POST['emailAddress'];
    
    // التحقق من أن البريد الإلكتروني غير مستخدم من قبل شخص آخر
    $checkEmailQuery = $pdo->prepare("SELECT userId FROM user WHERE emailAddress = ? AND userId != ?");
    $checkEmailQuery->execute([$email, $userId]);
    $existingUser = $checkEmailQuery->fetch();

    if ($existingUser) {
        $message = "البريد الإلكتروني مستخدم من قبل شخص آخر.";
    } else {
        // تحديث بيانات المستخدم
        $updateUserQuery = $pdo->prepare("UPDATE user SET name = ?, emailAddress = ? WHERE userId = ?");
        $updateUserQuery->execute([$name, $email, $userId]);
        $message = "تم تحديث البيانات بنجاح!";
        header("Location: index.php?page=edit-profile");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الملف الشخصي</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-label {
            font-weight: bold;
            color: #FFF;
        }
        .form-control {
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .btn-primaryy {
            color: #000;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #826D42;
            border: 2px solid #000; 
            border-radius: 5px;
        }
        .btn-primaryy:hover {
            background-color: #5B4B2F;
            color: white;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            color: red;
            font-weight: bold;
        }
        .message.success {
            color: green;
        }
    </style>
</head>
<body>



<div class="container" style="
    margin: 100px auto;
    height: fit-content;
">
    <h2 style="color:#000;">تعديل الملف الشخصي</h2>
    <?php if ($message): ?>
        <p class="message <?= strpos($message, 'نجاح') !== false ? 'success' : '' ?>"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <label for="name" class="form-label">الاسم الكامل</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="emailAddress" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="emailAddress" name="emailAddress" value="<?= htmlspecialchars($user['emailAddress']) ?>" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primaryy">تأكيد</button>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
