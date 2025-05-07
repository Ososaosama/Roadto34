<?php
include 'db.php'; // تضمين ملف الاتصال بقاعدة البيانات

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// عرض الأخطاء لأغراض التطوير
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = ''; // متغير لتخزين رسالة الخطأ

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['emailAddress'];
    $password = $_POST['password'];

    // استعلام لجلب المستخدم بناءً على البريد الإلكتروني
    $stmt = $pdo->prepare("SELECT * FROM user WHERE emailAddress = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // التحقق من صحة كلمة المرور
        if (password_verify($password, $user['password'])) {
            // تسجيل الدخول بنجاح
            $_SESSION['userId'] = $user['userId'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // توجيه المستخدم بناءً على الدور
            if ($user['role'] === 'admin') {
                header("Location: index.php");
                exit();
            } elseif ($user['role'] === 'guide') {
                if ($user['active'] == 1) {
                    header("Location: GuidePages/dashboard-button.php");
                    exit();
                } else {
                    
                    $error = "حسابك غير مفعل.";
                    session_destroy();
                    
                }
            } else {
                header("Location: index.php?page=home");
                exit();
            }
        } else {
            // إذا كانت كلمة المرور غير صحيحة
            $error = "كلمة المرور غير صحيحة.";
        }
    } else {
        // إذا لم يتم العثور على مستخدم بهذا البريد الإلكتروني
        $error = "لا يوجد مستخدم مسجل بهذا البريد الإلكتروني.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="card rounded-lg p-4" style="background-color: #55bc47; border: 1px solid rgba(0, 0, 0, 0.1); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);">
        <h2 class="text-center mb-3" style="color: #343a40;">تسجيل الدخول</h2>
        <form method="POST" action="index.php?page=login">
            <div class="mb-3">
                <div class="form-floating">
                    <input type="email" class="form-control rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="email" name="emailAddress" placeholder="البريد الإلكتروني" required>
                    <label for="email" class="form-label" style="color: #495057;">البريد الإلكتروني</label>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-floating">
                    <input type="password" class="form-control rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="password" name="password" placeholder="كلمة المرور" required>
                    <label for="password" class="form-label" style="color: #495057;">كلمة المرور</label>
                </div>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary rounded-md py-2" style="background-color: #007bff; border: none; color: #fff; font-weight: 500;">
                    <i class="bi bi-box-arrow-in-right me-2"></i> دخول
                </button>
            </div>
        </form>

        <?php if ($error): ?>
            <p class="text-danger mt-3"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <p class="mt-3 text-center" style="color: #ffffff;">
            ليس لديك حساب؟ <a href="index.php?page=register" style="color: #007bff; text-decoration: none;">سجل هنا</a>
        </p>
        <p class="mt-2 text-center" style="color: #ffffff;">
            هل تريد أن تصبح مرشدًا؟
            <a href="GuidePages/register.php" class="btn btn-outline-secondary rounded-md btn-sm mt-2" style="color: #ffffff; border-color: #6c757d; text-decoration: none;">
                <i class="bi bi-person-plus-fill me-1"></i> تسجيل كمرشد
            </a>
        </p>
    </div>
</div>


<style>
    .rounded-md {
        border-radius: 0.375rem; /* Equivalent to Bootstrap's rounded */
    }

    .border-gray-300 {
        border-color: #d1d5db;
    }

    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .focus\:border-indigo-500:focus {
        border-color: #6366f1;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    .focus\:ring-indigo-500:focus {
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    /* Styling for floating labels */
    .form-floating {
        position: relative;
    }

    .form-floating > .form-control {
        padding: 1rem 0.75rem;
        height: auto; /* Adjust height as needed */
        direction: rtl; /* Force right-to-left text direction */
        text-align: right; /* Align initial text to the right */
    }

    .form-floating > label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        padding: 1rem 0.75rem;
        pointer-events: none;
        border: 1px solid transparent; /* Remove border */
        transform-origin: 0 0;
        transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
        opacity: 0.65;
        direction: rtl; /* Right-to-left alignment for labels */
        text-align: right;
    }

    .form-floating.is-rtl > label {
        left: auto;
        right: 0;
        transform-origin: 100% 0;
        text-align: left;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        opacity: 0.85;
        transform: translateY(-0.75rem) scale(0.85);
    }
</style>

<script>
    // Add 'is-rtl' class to form-floating elements for right-to-left label alignment
    document.addEventListener('DOMContentLoaded', function() {
        const floatingLabels = document.querySelectorAll('.form-floating');
        floatingLabels.forEach(floatingLabel => {
            floatingLabel.classList.add('is-rtl');
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
