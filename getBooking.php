<?php
include '../db.php'; 
include 'index.php'; 

$bookingsQuery = $pdo->prepare("SELECT b.*, u.name AS userName, t.title AS tourTitle 
                                  FROM Booking b 
                                  JOIN User u ON b.userId = u.userId 
                                  JOIN Tour t ON b.tourId = t.tourId 
                                  ORDER BY b.bookingDate DESC");
$bookingsQuery->execute();
$bookings = $bookingsQuery->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteBooking'])) {
    $bookingId = $_POST['bookingId'];
    $deleteQuery = $pdo->prepare("DELETE FROM Booking WHERE bookingId = ?");
    $deleteQuery->execute([$bookingId]);
    
    echo "Booking deleted successfully!";
    header("Location: admin.php?form=getBooking.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* تحسين تصميم الجدول */
        .container {
            margin-top: 50px;
        }

        h1 {
            color: #000;
            text-align: center;
            margin-bottom: 30px;
        }

        .table-striped tbody tr:hover {
            background-color: #73B6BA;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            transition: background-color 0.3s;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .text-center {
            font-weight: bold;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>الحجوزات</h1>
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th style="background-color: #032124;">رقم الحجز</th>
                    <th style="background-color: #032124;">اسم المستخدم</th>
                    <th style="background-color: #032124;">عنوان الجولة</th>
                    <th style="background-color: #032124;">تاريخ الحجز</th>
                    <th style="background-color: #032124;">طلب خاص</th>
                    <th style="background-color: #032124;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="6" class="text-center">لم يتم العثور على حجوزات.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr style="background-color: #25978B;">
                            <td><?= htmlspecialchars($booking['bookingId']) ?></td>
                            <td><?= htmlspecialchars($booking['userName']) ?></td>
                            <td><?= htmlspecialchars($booking['tourTitle']) ?></td>
                            <td><?= htmlspecialchars($booking['bookingDate']) ?></td>
                            <td><?= htmlspecialchars($booking['specialRequest']) ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="bookingId" value="<?= htmlspecialchars($booking['bookingId']) ?>">
                                    <button type="submit" name="deleteBooking" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الحجز؟');">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
