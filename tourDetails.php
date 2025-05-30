<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'db.php';

// تحقق من وجود tourId في URL
if (!isset($_GET['tourId']) || empty($_GET['tourId'])) {
    echo "Tour ID غير متوفر.";
    exit();
}

$tourId = (int)$_GET['tourId'];


$tourStmt = $pdo->prepare("
    SELECT Tour.*, TourGuide.name AS guideName, TourGuide.imageURL AS guideImage, TourGuide.rating AS guideRating 
    FROM Tour 
    JOIN TourGuide ON Tour.guideId = TourGuide.guideId 
    WHERE Tour.tourId = :tourId
");
$tourStmt->execute(['tourId' => $tourId]);
$tour = $tourStmt->fetch(PDO::FETCH_ASSOC);
$guideId = $tour['guideId'];
if (!$tour) {
    echo "لم يتم العثور على الجولة.";
    exit();
}


$placesStmt = $pdo->prepare("
    SELECT Place.* 
    FROM Place 
    JOIN Tour_Places ON Place.placeId = Tour_Places.placeId 
    WHERE Tour_Places.tourId = :tourId
");
$placesStmt->execute(['tourId' => $tourId]);
$places = $placesStmt->fetchAll(PDO::FETCH_ASSOC);

// إذا تم تقديم النموذج، احفظ التعليق في قاعدة البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $userId = $_SESSION['userId'] ?? null; // تأكد من أن المستخدم مسجل الدخول

    if ($userId) {
        $commentStmt = $pdo->prepare("INSERT INTO tour_comments (tourId, userId, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        $commentStmt->execute([$tourId, $userId, $rating, $comment]);

        echo "<script>alert('تم إضافة تعليقك بنجاح.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<p>يجب عليك تسجيل الدخول لإضافة تعليق.</p>";
        header("Location: index.php?page=login");
    }
}

// جلب التعليقات المرتبطة بالجولة من قاعدة البيانات
$commentsStmt = $pdo->prepare("SELECT tour_comments.*, user.name AS userName FROM tour_comments JOIN user ON tour_comments.userId = user.userId WHERE tourId = ? ORDER BY created_at DESC");
$commentsStmt->execute([$tourId]);
$comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);

$rating = $pdo->prepare("SELECT AVG(rating) as average_rating FROM tour_comments WHERE tourId = ?");
$rating->execute([$tourId]);
$rating_trip = $rating->fetchColumn();
$starRating_trips = $rating_trip ? round($rating_trip) : 0;


$ratingStmt = $pdo->prepare("SELECT AVG(rating) as average_rating FROM review WHERE guideId = ?");
$ratingStmt->execute([$guideId]);
$rating = $ratingStmt->fetchColumn();
$starRating = $rating ? round($rating) : 0;
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
</head>
<body>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-lg-7" style="color:#FFF;">
                <?php if ($tour): ?>
                    <div class="tour-img mb-4">
                        <img class="img-fluid" src="<?php echo htmlspecialchars($tour['imageURL'] ?? ''); ?>" alt="Tour Image">
                    </div>
                    <h1 class="mb-3" style="color:#000;"><?php echo htmlspecialchars($tour['title'] ?? ''); ?></h1>
                    <p><?php echo $starRating_trips ? str_repeat('⭐', $starRating_trips) . "/5" : "غير متوفر"; ?></p>
                    <p class="mb-4"><?php echo htmlspecialchars($tour['description'] ?? ''); ?></p>
                    <div class="d-flex mb-4">
                        <small class="me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i><?php echo htmlspecialchars($tour['city'] ?? ''); ?></small>
                        <small class="me-3"><i class="fa fa-calendar-alt text-primary me-2"></i><?php echo htmlspecialchars($tour['duration'] ?? ''); ?> days</small>
                        <small><i class="fa fa-user text-primary me-2"></i>2-6 Persons</small>
                    </div>
                    <div class="d-flex justify-content-center mb-4">
                        <a href="index.php?page=booking&tourId=<?php echo $tour['tourId']; ?>" class="btn btn-primary" style="color:#000;border:2px solid #000;">احجز الآن</a>
                    </div>
                <?php else: ?>
                    <p>لم يتم العثور على الجولة.</p>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-5">
                <div class="tour-guide-details mb-4 text-center">
                    <h3 class="mb-3" style="color:#000;">المرشد السياحي</h3>
                    <?php if (!empty($tour['guideImage'])): ?>
                        <img src="<?php echo htmlspecialchars($tour['guideImage']); ?>" alt="Guide Image" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px;">
                    <?php else: ?>
                        <p style="color:#000;">الصورة غير متوفرة</p>
                    <?php endif; ?>
                    <h5 style="color:#000;"><?php echo htmlspecialchars($tour['guideName']); ?></h5>
                    <div class="mb-2">
                                                
                    </div>
                    <p style="color:#FFF;">تقييم  <h5 class="mb-4" style="color:#FFF;"> <?php
                        echo str_repeat('⭐', $starRating); 
                        ?>/5</p>
                </div>

                
                <div class="tour-map mb-4">
                    <iframe class="w-100" src="https://maps.google.com/maps?q=<?php echo urlencode($tour['city']); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>

                <h3 class="mb-3" style="color:#000;">الأماكن لزيارتها</h3>
                <div class="row g-3">
                    <?php foreach ($places as $place): ?>
                        <div class="col-6">
                            <div class="place-item">
                                <a href="http://localhost:3000/trips/pages/placeDetails.php?placeId=<?= htmlspecialchars($place['placeId']); ?>" style="text-decoration: none; color: inherit;">
                                    <?php if (!empty($place['imageURL'])): ?>
                                        <img class="img-fluid" src="<?php echo htmlspecialchars($place['imageURL']); ?>" alt="Place Image">
                                    <?php else: ?>
                                        <p style="color:#000;">الصورة غير متوفرة</p>
                                    <?php endif; ?>
                                    <h6><?php echo htmlspecialchars($place['name']); ?></h6>
                                    <p><?php echo htmlspecialchars($place['description']); ?></p>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<h4 class="mt-5" style="color:#000;">أضف تعليقك</h4>
<form method="POST" action="">
    <div class="form-group" style="color:#FFF;">
        <label for="rating">التقييم</label>
        <select name="rating" id="rating" class="form-control" required>
            <option value="5">5 - ممتاز</option>
            <option value="4">4 - جيد جداً</option>
            <option value="3">3 - جيد</option>
            <option value="2">2 - مقبول</option>
            <option value="1">1 - سيء</option>
        </select>
    </div>
    <div class="form-group" style="color:#FFF;">
        <label for="comment">التعليق</label>
        <textarea name="comment" id="comment" rows="3" class="form-control" required style="color:#000;"></textarea>
    </div>
    <button type="submit" name="add_comment" class="btn btn-primary mt-3" style="color:#000;border:2px solid #000;">إرسال التعليق</button>
</form>

<!-- Display Comments -->
<h4 class="mt-5" style="color:#000;">التعليقات</h4>
<?php
if ($comments):
    foreach ($comments as $comment): ?>
        <div class="card mb-3" style="color:#FFF;background-color:#118379;">
            <div class="card-body" style="color:#FFF;">
                <h5 class="card-title" style="color:#000;"><?php echo htmlspecialchars($comment['userName']); ?></h5>
                <p class="text-warning" ><?php echo str_repeat('★', $comment['rating']) . str_repeat('☆', 5 - $comment['rating']); ?></p>
                <p class="card-text"><?php echo htmlspecialchars($comment['comment']); ?></p>
                <p class="text-white">أضيف في: <?php echo htmlspecialchars($comment['created_at']); ?></p>
            </div>
        </div>
    <?php endforeach;
else: ?>
    <p style="color:#FFF;">لا توجد تعليقات بعد.</p>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$pdo = null;
?>
