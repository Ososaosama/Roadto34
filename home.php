<!-- Spinner Start -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<!-- Spinner End -->

<?php
include 'db.php'; // تضمين الاتصال بقاعدة البيانات

// جلب الأماكن من قاعدة البيانات
$placesStmt = $pdo->query("SELECT placeId, name, description, imageURL, CityId FROM place WHERE Approve = 1 ORDER BY placeId DESC LIMIT 6");
$places = $placesStmt->fetchAll(PDO::FETCH_ASSOC);

// جلب الرحلات من قاعدة البيانات
$toursStmt = $pdo->query("SELECT tourId, title, description, imageURL, city, date, price FROM tour ORDER BY tourId DESC LIMIT 6");
$tours = $toursStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- قسم الأماكن -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center">
        <h6 style="
 display: inline-block;
  color: white;
  padding: 6px 22px;
  border: 3px solid #055951;
  border-radius: 25px;
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  margin: 30px auto 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
">
  الوجهات 
</h6>
            <h1 class="mb-5" style="color:rgb(255, 255, 255); margin-top: 17px">وجهتك… أقرب مما تتخيل</h1>
        </div>
        <div class="row">
            <?php if (empty($places)): ?>
                <p class="text-center">لا توجد وجهات متاحة في الوقت الحالي.</p>
            <?php else: ?>
                <?php foreach ($places as $place): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="package-item" style="width: 100%; height: 500px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; border: 1px solid #033954; border-radius: 8px; box-shadow: 0 4px 6px rgb(49, 172, 51); background-color: #053135;"!important>
                        <a href="pages/placeDetails.php?placeId=<?= htmlspecialchars($place['placeId']); ?>" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;">
                        <div class="overflow-hidden" style="height: 200px; border-bottom: 1px solid #ddd;">
                                    <img class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" src="<?= htmlspecialchars($place['imageURL']); ?>" alt="Place Image">
                                </div>
                                <div class="text-center p-4" style="flex-grow: 1;">
                                    <h3 class="mb-0" style="color: #fff"><?= htmlspecialchars($place['name']); ?></h3>
                                    <p style="color: #fff; margin: 9px 0px"><?= htmlspecialchars($place['description']); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- قسم الرحلات -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center">
        <h6 style="
 display: inline-block;
  color: white;
  padding: 6px 22px;
  border: 3px solid #055951;
  border-radius: 25px;
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  margin: 30px auto 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
">
  الرحلات
</h6>
            <h1 class="mb-5" style="color: #ffffff;">رحلتك تبدأ بخطوة</h1>
        </div>
        <div class="row">
            <?php if (empty($tours)): ?>
                <p class="text-center">لا توجد رحلات متاحة في الوقت الحالي.</p>
            <?php else: ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="package-item" style="width: 100%; height: 600px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; border: 1px solid #000; border-radius: 8px; box-shadow: 0 4px 6px rgb(49, 172, 51); background-color: #053135;">
                            <a href="index.php?page=tourDetails&tourId=<?= htmlspecialchars($tour['tourId']); ?>" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;">
                                <div class="overflow-hidden" style="height: 200px; border-bottom: 1px solid #ddd;">
                                    <img class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" src="<?= htmlspecialchars($tour['imageURL']); ?>" alt="Tour Image">
                                </div>
                                <div class="text-center p-4" style="flex-grow: 1;">
                                    <h3 class="mb-0" style="color: #ffffff"><?= htmlspecialchars($tour['title']); ?></h3>
                                    <p ><?= htmlspecialchars($tour['description']); ?></p>
                                    <p style="color: #ffffff"><strong>المدينة:</strong> <?= htmlspecialchars($tour['city']); ?></p>
                                    <p style="color: #ffffff"><strong>التاريخ:</strong> <?= htmlspecialchars($tour['date']); ?></p>
                                    <p style="color: #ffffff"><strong>السعر:</strong> ﷼<?= number_format(htmlspecialchars($tour['price']), 2); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>


