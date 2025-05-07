<?php
session_start();
include '../db.php'; 

if (!isset($_SESSION['userId'])) {
    echo "يجب عليك التسجيل أولاً!";
    exit();
}

$userId = $_SESSION['userId'];
$name = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // استقبال البيانات من النموذج
    $license_number = $_POST['license'];

    // تحقق من وجود القيم وتحديد قيمة افتراضية في حالة عدم وجودها
    $languages = isset($_POST['languages']) ? implode(', ', $_POST['languages']) : '';
    $cities = isset($_POST['cities']) ? implode(', ', $_POST['cities']) : '';
    $about = $_POST['about'];
    $facebook = $_POST['facebook'];
    $twitter = $_POST['twitter'];
    $instagram = $_POST['instagram'];
    $experience = $_POST['experience'];
    $phone= $_POST['phone'];

    // معالجة رفع الصورة
    $imageURL = null;
    if (isset($_FILES['imageURL']) && $_FILES['imageURL']['error'] == 0) {
        $targetDir = "../uploads/";
        $imageURL = $targetDir . basename($_FILES['imageURL']['name']);
        
        if (move_uploaded_file($_FILES['imageURL']['tmp_name'], $imageURL)) {
            // مسار الصورة سيتم تخزينه في قاعدة البيانات
            $imageURL = "uploads/" . basename($_FILES['imageURL']['name']);
        } else {
            echo "حدث خطأ أثناء رفع الصورة.";
            exit();
        }
    }

    try {
        $checkQuery = $pdo->prepare("SELECT COUNT(*) FROM tourguide WHERE guideId = ?");
        $checkQuery->execute([$userId]);
        $guideExists = $checkQuery->fetchColumn() > 0;

        if ($guideExists) {
            $query = $pdo->prepare("
                UPDATE tourguide 
                SET name = ?, license_number = ?, languages = ?, cities = ?, about = ?, facebook = ?, twitter = ?, instagram = ?, imageURL = ?, experience = ?, phone = ?
                WHERE guideId = ?
            ");
            $query->execute([$name, $license_number, $languages, $cities, $about, $facebook, $twitter, $instagram, $imageURL, $experience, $phone, $userId]);
        } else {
            $query = $pdo->prepare("
                INSERT INTO tourguide (guideId, name, license_number, languages, cities, about, facebook, twitter, instagram, imageURL, experience, phone) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $query->execute([$userId, $name, $license_number, $languages, $cities, $about, $facebook, $twitter, $instagram, $imageURL, $experience, $phone]);
        }

        echo "تم حفظ البيانات بنجاح!";
        header("Location: ../index.php");
        exit();
    } catch (PDOException $e) {
        echo "حدث خطأ أثناء حفظ البيانات: " . htmlspecialchars($e->getMessage());
    }
}
?>
