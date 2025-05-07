<?php
session_start(); 
include '../db.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['userId'])) {
    echo "خطأ: لم يتم العثور على هوية المستخدم. يرجى تسجيل الدخول.";
    exit;
}

if (isset($_GET['guideId'])) {
    $guideId = $_GET['guideId'];
} else {
    echo "خطأ: لم يتم تحديد guideId.";
    exit;
}

$bookingsQuery = $pdo->prepare("
    SELECT 
        b.bookingId, 
        b.bookingDate, 
        b.specialRequest, 
        b.numberOfPeople,
        b.totalprice,
        u.name AS userName, 
        t.title AS tourTitle, 
        g.name AS guideName, 
        g.experience, 
        g.rating, 
        g.languages 
    FROM 
        Booking b 
    JOIN 
        User u ON b.userId = u.userId 
    JOIN 
        Tour t ON b.tourId = t.tourId 
    JOIN 
        TourGuide g ON t.guideId = g.guideId 
    WHERE 
        b.guideId = :guideId 
    ORDER BY 
        b.bookingDate DESC
");

$bookingsQuery->execute(['guideId' => $guideId]);
$bookings = $bookingsQuery->fetchAll(PDO::FETCH_ASSOC);

$specialRoutesQuery = $pdo->prepare("
    SELECT 
        sr.tour_id, 
        sr.created_at, 
        p.name AS place_name, 
        c.Name AS city_name, 
        sr.notes, 
        sr.category, 
        sr.people_count, 
        sr.end_date, 
        sr.start_date ,
        sr.approve,
        sr.price,
        sr.priceok
    FROM 
        custom_tours sr
    JOIN 
        place p ON sr.place_id = p.placeId
    JOIN 
        cities c ON sr.city_id = c.CityId
    WHERE 
        sr.guideid = :guideId 
    ORDER BY 
        sr.created_at DESC
");

$specialRoutesQuery->execute(['guideId' => $guideId]);
$specialRoutes = $specialRoutesQuery->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteBooking'])) {
        $bookingId = $_POST['bookingId'];
        $deleteBookingQuery = $pdo->prepare("DELETE FROM Booking WHERE bookingId = ? AND guideId = ?");
        $deleteBookingQuery->execute([$bookingId, $guideId]);
        
        echo "<script>alert('تم حذف الحجز بنجاح!'); window.location.href='" . $_SERVER['PHP_SELF'] . "?guideId=" . $guideId . "';</script>";
        exit;
    }
    if (isset($_POST['deleteRoute'])) {
        $routeId = $_POST['routeId'];
        $deleteRouteQuery = $pdo->prepare("DELETE FROM custom_tours WHERE tour_id = ? AND guideid = ?");
        $deleteRouteQuery->execute([$routeId, $guideId]);
        
        echo "<script>alert('تم حذف المسار بنجاح!'); window.location.href='" . $_SERVER['PHP_SELF'] . "?guideId=" . $guideId . "';</script>";
        exit;
    }
    if (isset($_POST['approveRoute'])) {    
        $routeId = $_POST['routeId'];
        $approveRouteQuery = $pdo->prepare("UPDATE custom_tours SET approve = 1 WHERE tour_id = ? AND guideid = ?");
        $approveRouteQuery->execute([$routeId, $guideId]);
        echo "<script>alert('تمت الموافقة على المسار بنجاح!'); window.location.href='" . $_SERVER['PHP_SELF'] . "?guideId=" . $guideId . "';</script>";
        exit;
    }
    if (isset($_POST['priceok'])) {    
        $routeId = $_POST['routeId'];
        $price=$_POST['price'];
        $approveRouteQuery = $pdo->prepare("UPDATE custom_tours SET price = ?  WHERE tour_id = ? AND guideid = ?");
        $approveRouteQuery->execute([$price,$routeId, $guideId]);
        echo "<script>alert('تمت اضافة السعر بنجاح!'); window.location.href='" . $_SERVER['PHP_SELF'] . "?guideId=" . $guideId . "';</script>";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجوزاتي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #055951;">

<?php include '../base_nav.php'; ?> 

<div class="container-fluid position-relative p-0" style="margin-top: 90px;">
    <div class="container" style="max-width: 800px; margin-top: 50px;">
        <h2 class="text-center mb-4" style="color:#000;">حجوزاتي</h2>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr style="color:#FFF;">
                    <th>رقم الحجز</th>
                    <th>اسم المستخدم</th>
                    <th>عنوان الجولة</th>
                    <th>تاريخ الرحلة</th>
                    <th>طلب خاص</th>
                    <th> عدد الاشخاص</th>
                    <th> السعر الاجمالي</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr style="background-color:#006E65;">
                        <td colspan="6" class="text-center" style="color:#FFF;">لم يتم العثور على حجوزات.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['bookingId']) ?></td>
                            <td><?= htmlspecialchars($booking['userName']) ?></td>
                            <td><?= htmlspecialchars($booking['tourTitle']) ?></td>
                            <td><?= htmlspecialchars($booking['bookingDate']) ?></td>
                            <td><?= htmlspecialchars($booking['specialRequest']) ?></td>
                            <td><?= htmlspecialchars($booking['numberOfPeople']) ?></td>
                            <td><?= htmlspecialchars($booking['totalprice']) ?></td>
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

        <h2 class="text-center mt-5 mb-4" style="color:#000;">مسارات خاصة</h2>
        <table class="table table-striped table-hover">
            <thead>
                <tr style="color:#FFF;">
                <th>تاريخ الإنشاء</th>
                    <th>المكان</th>
                    <th>المدينة</th>
                    <th>ملاحظات</th>
                    <th>التصنيف</th>
                    <th>عدد الأشخاص</th>
                    <th>تاريخ النهاية</th>
                    <th>تاريخ البداية</th>
                    <th>الحالة</th>
                    <th>حدد السعر</th>
                    <th>إجراءات</th>
                    <th>موافقة العميل   </th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($specialRoutes)): ?>
                    <tr style="background-color:#006E65;">
                        <td colspan="10" class="text-center" style="color:#FFF;">لم يتم العثور على مسارات خاصة.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($specialRoutes as $route): ?>
                        <tr>
                            <td><?= htmlspecialchars($route['created_at']) ?></td>
                            <td><?= htmlspecialchars($route['place_name']) ?></td>
                            <td><?= htmlspecialchars($route['city_name']) ?></td>
                            <td><?= htmlspecialchars($route['notes']) ?></td>
                            <td><?= htmlspecialchars($route['category']) ?></td>
                            <td><?= htmlspecialchars($route['people_count']) ?></td>
                            <td><?= htmlspecialchars($route['end_date']) ?></td>
                            <td><?= htmlspecialchars($route['start_date']) ?></td>
                            
                            <td>
                                <?php if ($route['approve'] == 1): ?>
                                    <span class="badge bg-success">تمت الموافقة</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">في انتظار الموافقة</span>
                                <?php endif; ?>
                            </td>
                            <td> 
                            <?php
// تحقق مما إذا تم تحديد السعر مسبقًا
                                $existingPrice = !empty($route['price']); // تحقق إذا كان هناك سعر محدد
                                ?>

                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="routeId" value="<?= htmlspecialchars($route['tour_id']) ?>">
                                    
                                    <?php if ($existingPrice): ?>
                                        <!-- عرض السعر فقط إذا كان محددًا مسبقًا -->
                                        <input type="text" value="<?= htmlspecialchars($route['price']) ?>" readonly>
                                    <?php else: ?>
                                        <!-- عرض حقل إدخال السعر وزر الإكمال إذا لم يكن السعر محددًا -->
                                        <input type="text" placeholder="حدد السعر" name="price" value="">
                                        <button type="submit" name="priceok" class="btn btn-primary btn-sm">اكمال</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                            <td>
                                <?php if ($route['approve'] == 0): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="routeId" value="<?= htmlspecialchars($route['tour_id']) ?>">
                                        <button type="submit" name="approveRoute" class="btn btn-primary btn-sm">موافقة</button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="routeId" value="<?= htmlspecialchars($route['tour_id']) ?>">
                                    <button type="submit" name="deleteRoute" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا المسار؟');">حذف</button>
                                </form>
                            </td>
                            <td>
                            <?php if ($route['priceok'] == 1): ?>
                                    <span class="badge bg-success">تمت الموافقة</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">في انتظار الموافقة</span>
                                <?php endif; ?>
                            </td>
                            
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../footer.php'); ?> 

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
