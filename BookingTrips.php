<?php
// تضمين الاتصال بقاعدة البيانات
include 'db.php';

// بدء الجلسة

// التحقق من وجود الجلسة وطباعة محتواها
if (isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];
    
} else {
    echo "لم يتم العثور على معرف المستخدم في الجلسة.";
}

// الاستعلام لجلب بيانات الجولات الخاصة بالمستخدم
$stmtTours = $pdo->prepare("
    SELECT 
        ct.tour_id,
        ct.user_id,
        ct.start_date,
        ct.end_date,
        ct.people_count,
        ct.category,
        ct.notes,
        ct.city_id,
        ct.place_id,
        ct.created_at,
        ct.approve,
        ct.price,
        ct.priceok,
        g.name AS guide_name
    FROM 
        custom_tours AS ct
    LEFT JOIN 
        tourguide AS g ON ct.guideId = g.guideId
    WHERE 
        ct.user_id = :user_id
");
$stmtTours->execute(['user_id' => $user_id]);
$tours = $stmtTours->fetchAll(PDO::FETCH_ASSOC);

// الاستعلام لجلب بيانات الحجز الخاصة بالمستخدم
$stmtBooking = $pdo->prepare("
    SELECT 
        b.bookingId,
        b.userId,
        b.tourId,
        b.bookingDate,
        b.specialRequest,
        b.createdAt,
        g.name AS guide_name,
        b.numberOfPeople,
        b.totalPrice

    FROM 
        booking AS b
    LEFT JOIN 
        tourguide AS g ON b.guideId = g.guideId
    WHERE 
        b.userId = :user_id
");
$stmtBooking->execute(['user_id' => $user_id]);
$bookings = $stmtBooking->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_price'], $_POST['tour_id'])) {
    $tourId = $_POST['tour_id'];

    // تحديث حالة الموافقة في قاعدة البيانات
    $updateQuery = $pdo->prepare("UPDATE custom_tours SET priceok = 1 WHERE tour_id = ?");
    $updateQuery->execute([$tourId]);
    echo "<script>alert('تم الموافقة بنجاح!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";

}
?>

   
    <style>
        .custom-container {
            max-width: 900px;
            margin: 50px auto;
            background: #05676E;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .custom-title {
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .custom-table th, .custom-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #0E878F;
        }
        .custom-table th {
            background-color: #18B7C2;
            color: #fff;
        }
        .custom-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .custom-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }
        .custom-muted-text {
            text-align: center;
            font-size: 1.1em;
            color: #FFF;
        }
    </style>

<div class="custom-container">
    <h1 class="custom-title">رحلاتك الخاصة</h1>
    
    <!-- قسم عرض الرحلات -->
    <?php if (empty($tours)): ?>
        <p class="custom-muted-text">لا توجد رحلات مسجلة حتى الآن.</p>
    <?php else: ?>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>رقم الرحلة</th>
                    <th>تاريخ البداية</th>
                    <th>تاريخ النهاية</th>
                    <th>عدد الأشخاص</th>
                    <th>الفئة</th>
                    <th>ملاحظات</th>
                    <th>اسم المرشد</th>
                    <th>الحالة</th>
                    <th>سعر المرشد</th>
                    <th> موافقة السعر </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tours as $tour): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tour['tour_id']); ?></td>
                        <td><?php echo htmlspecialchars($tour['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($tour['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($tour['people_count']); ?></td>
                        <td><?php echo htmlspecialchars($tour['category']); ?></td>
                        <td><?php echo htmlspecialchars($tour['notes']); ?></td>
                        <td><?php echo htmlspecialchars($tour['guide_name'] ?? 'غير متوفر'); ?></td>
                        <td>
                            <?php echo $tour['approve'] ? '<span class="custom-badge" style="background-color: #28a745; color: #fff;">موافقة</span>' : '<span class="custom-badge" style="color: #ffc107 ; ">في انتظار الموافقة</span>'; ?>
                        </td>
                        <td>
                        
                        <?php
                        if (isset($tour['price']) && !empty($tour['price'])) {
                            echo htmlspecialchars($tour['price']) . " ريال";
                        } else {
                            echo '<span class="text-warning">في انتظار التحديد</span>';
                        }
                        ?>                        
                        </td>
                        <td>
                        <?php if (isset($tour['price']) && !empty($tour['price'])): ?>
                            <?php if ($tour['priceok'] == 1): ?>
                                <span class="text-success">تمت الموافقة</span>
                            <?php else: ?>
                                <form method="POST" action="#" style="display:inline;">
                                    <input type="hidden" name="tour_id" value="<?= htmlspecialchars($tour['tour_id']); ?>">
                                    <button type="submit" name="approve_price" class="btn btn-primary btn-sm">موافقة</button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-warning">السعر غير محدد</span>
                        <?php endif; ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- قسم عرض الحجوزات -->
    <h1 class="custom-title">حجوزاتك</h1>
    <?php if (empty($bookings)): ?>
        <p class="custom-muted-text">لا توجد حجوزات مسجلة حتى الآن.</p>
    <?php else: ?>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>رقم الحجز</th>
                    <th>رقم الرحلة</th>
                    <th>تاريخ الرحلة</th>
                    <th>طلب خاص</th>
                    <th>اسم المرشد</th>
                    <th> عدد الاشخاص</th>
                    <th>  اجمالي السعر </th>
                    <th>تاريخ الإنشاء</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['bookingId']); ?></td>
                        <td><?php echo htmlspecialchars($booking['tourId']); ?></td>
                        <td><?php echo htmlspecialchars($booking['bookingDate']); ?></td>
                        <td><?php echo htmlspecialchars($booking['specialRequest'] ?? 'لا يوجد'); ?></td>
                        <td><?php echo htmlspecialchars($booking['guide_name'] ?? 'غير متوفر'); ?></td>
                        <td><?php echo htmlspecialchars($booking['numberOfPeople']); ?></td>
                        <td><?php echo htmlspecialchars($booking['totalPrice']); ?></td>
                        <td><?php echo htmlspecialchars($booking['createdAt']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
