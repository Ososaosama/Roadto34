<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['emailAddress'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  

    try {
        $stmt = $pdo->prepare("INSERT INTO user (name, emailAddress, password, role,active) VALUES (?, ?, ?, 'user','1')");
        if ($stmt->execute([$name, $email, $password])) {
            
            
            echo "<script>alert('تم انشاء الحساب بنجاح!'); window.location.href='index.php?page=login';</script>";


        } else {
            echo "Error occurred during registration.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
    
}
?>
<div class="container mt-5">
    <div class="card rounded-lg p-4 shadow-md" style="background-color: rgb(52 175 146); border: 1px solid rgba(0, 0, 0, 0.1);">
        <h2 class="text-center mb-3" style="color: #343a40;">إنشاء حساب جديد</h2>
        <form method="POST" action="index.php?page=register">
            <div class="mb-3 form-floating is-rtl">
                <input type="text" class="form-control rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="name" name="name" placeholder="الاسم" required>
                <label for="name" class="form-label" style="color: #495057;">الاسم</label>
            </div>
            <div class="mb-3 form-floating is-rtl">
                <input type="email" class="form-control rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="email" name="emailAddress" placeholder="البريد الإلكتروني" required>
                <label for="email" class="form-label" style="color: #495057;">البريد الإلكتروني</label>
            </div>
            <div class="mb-3 form-floating is-rtl">
                <input type="password" class="form-control rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="password" name="password" placeholder="كلمة المرور" required>
                <label for="password" class="form-label" style="color: #495057;">كلمة المرور</label>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success rounded-md py-2" style="border: none; font-weight: 500;">
                    <i class="bi bi-person-plus-fill me-2"></i> تسجيل
                </button>
            </div>
        </form>
        <p class="mt-3 text-center" style="color: #ffffff;">
            لديك حساب بالفعل؟ <a href="index.php?page=login" style="color: #000000; text-decoration: none;">تسجيل الدخول هنا</a>
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


