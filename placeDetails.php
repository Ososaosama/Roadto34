<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include ("../db.php");


// التحقق من وجود placeId
if (!isset($_GET['placeId']) || empty($_GET['placeId'])) {
    echo "Place ID غير متوفر.";
    exit();
}

$placeId = (int)$_GET['placeId'];

// جلب بيانات المكان
$placeStmt = $pdo->prepare("SELECT * FROM place WHERE placeId = :placeId");
$placeStmt->execute(['placeId' => $placeId]);
$place = $placeStmt->fetch(PDO::FETCH_ASSOC);

if (!$place) {
    echo "لم يتم العثور على المكان.";
    exit();
}

// التعامل مع التعليقات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $userId = $_SESSION['userId'] ?? null;

    if ($userId) {
        $commentStmt = $pdo->prepare("INSERT INTO review (placeId, userId, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        $commentStmt->execute([$placeId, $userId, $rating, $comment]);

        echo "<script>alert('تم إضافة تعليقك بنجاح.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<p>يجب عليك تسجيل الدخول لإضافة تعليق.</p>";
        header("Location: index.php?page=login");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الجولة</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <?php include '../base_nav.php'; ?>
   
</head>
<body>

<div class="container-xxl py-5" style="background-image: url('../img/background.jpg'); background-size: cover; background-position: center;">
    <div class="container">
        <?php if ($place): ?>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1 class="mb-3" style="color:#FFF;"> <?php echo htmlspecialchars($place['name']); ?></h1>
                    <div class="mb-4">
                    <img class="img-fluid rounded" src="<?php echo '../' . htmlspecialchars($place['imageURL'] ?? ''); ?>" alt="Place Image">
                    </div>
                    <p>⭐⭐⭐⭐</p>
                    <p class="mb-4" style="color:#FFF;"><?php echo htmlspecialchars($place['description']); ?></p>
                </div>

                <div class="col-lg-12 mt-4">
                    <iframe class="w-100" height="350" src="https://maps.google.com/maps?q=<?php echo urlencode($place['name']); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h4 class="mb-4" style="color:#333; font-weight: bold;">
                <i class="fas fa-comments" style="color: #007bff;"></i> التعليقات
            </h4>

           
                لا توجد تعليقات حتى الآن.
            
        </div>
    </div>
</div>

<style>

      /* body {
    background-image: url('../uploads/p3.jpg');
    background-size: cover;
    background-position: center;
} */
    .card {
        background-color: #118379;
        color: #FFF;
    }

    .btn-primary {
        color: #000;
        border: 2px solid #000;
    }

    h1, h3, h5, h4, h6, p, small, label {
        color: #000;
    }

    .place-item img {
        width: 100%;
        height: auto;
    }

    .place-item p {
        color: #000;
    }

    .form-control, textarea {
        color: #000 !important;
    }

    .container-xxl {
        background-color: rgba(0, 0, 0, 0.6); /* لو عايز الظل الخلفي */
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$pdo = null;
?>