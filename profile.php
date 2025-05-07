<?php

if (!isset($_SESSION['userId'])) {
    header("Location: index.php?page=login"); 
    exit();
}

$userId = $_SESSION['userId'];
$role=$_SESSION['role'];

$stmt = $pdo->prepare("SELECT name, emailAddress, role FROM user WHERE userId = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المستخدم</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .dashboard-container {
            max-width: 100%;
            margin: 150px auto;
            text-align: center;
            display: flex;
            justify-content: center;
        }
        .dashboard-button {
            width: 100%;
            margin: 10px 0;
            padding: 20px 200px;
            font-size: 18px;
            background-color: #826D42;
            border: 1px solid #000;
            border-radius: 8px;
            transition: background-color 0.3s;
            cursor: pointer;
        }
        .dashboard-button:hover {
            background-color: #5B4B2F;
        }
        .dashboard-button a {
            text-decoration: none;
            color: #000 !important;
            display: block;
        }
        h2 {
            color: #000;
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>


<h2>لوحة تحكم المستخدم</h2>
<div class="dashboard-container">
    <div class="top">
        <div class="dashboard-button">
            <a href="index.php?page=edit-profile">تعديل الملف الشخصي</a>
        </div>
        <div class="dashboard-button">
            <a href="index.php?page=BookingTrips">طلباتي</a>
        </div>
    </div>
    <div class="bottom">
        <div class="dashboard-button">
            <a href="index.php?page=change-password">تعديل كلمة المرور</a>
        </div>
        
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
