<?php
include '../db.php';
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'guide') {
  header("Location: ../index.php?=home");
  exit();
}

$guideId = $_GET['guideId'];

$placeQuery = $pdo->prepare("
    SELECT p.placeId, p.name, p.description, c.Name AS cityName ,p.Approve,category
    FROM Place p
    LEFT JOIN Cities c ON p.CityId = c.CityId
    WHERE p.guideId = ?
");
$placeQuery->execute([$guideId]);
$places = $placeQuery->fetchAll(PDO::FETCH_ASSOC);

$cityQuery = $pdo->query("SELECT * FROM Cities");
$cities = $cityQuery->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addSuggest'])) {
    $placeName = $_POST['placeName'];
    $placeDescription = $_POST['placeDescription'];
    $cityId = $_POST['cityId'];
    $category= $_POST['category'];

    $imagePath = null;
    if (!empty($_FILES['placeImage']['name'])) {
        $imagePath = "../uploads/" . basename($_FILES['placeImage']['name']);
        $path = "uploads/" . basename($_FILES['placeImage']['name']);
        if (!move_uploaded_file($_FILES['placeImage']['tmp_name'], $imagePath)) {
            echo "Failed to upload image!";
            exit();
        }
    }

    $insertPlace = $pdo->prepare("
        INSERT INTO Place (guideId, name, description, CityId, imageURL,category) 
        VALUES (?, ?, ?, ?, ?,?)
    ");
    $insertPlace->execute([$guideId, $placeName, $placeDescription, $cityId, $path,$category]);

    header("Location: add_suggest_form.php?guideId=" . $guideId);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletePlace'])) {
    $placeId = $_POST['placeId'];

    $deleteRelatedQuery = $pdo->prepare("DELETE FROM tour_places WHERE placeId = ?");
    $deleteRelatedQuery->execute([$placeId]);

    $deleteQuery = $pdo->prepare("DELETE FROM Place WHERE placeId = ?");
    $deleteQuery->execute([$placeId]);

    header("Location: add_suggest_form.php?guideId=" . $guideId);
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إدارة الأماكن السياحية</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body style="background-color:#055951;">
<?php include '../base_nav.php'; ?>
<div class="container mt-5">
  <h1 class="mb-4" style="color:#000;">إضافة مكان سياحي</h1>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="placeName" class="form-label" style="color:#FFF;">اسم المكان</label>
      <input type="text" class="form-control" id="placeName" name="placeName" required>
    </div>
    <div class="mb-3">
  <label for="placeCategory" class="form-label" style="color:#FFF;">تصنيف المكان</label>
  <select class="form-control" id="placeCategory" name="category" required>
    <option value="">اختر التصنيف</option>
    <option value="طبيعي">طبيعي</option>
    <option value="ترفيهي">ترفيهي</option>
    <option value="ثقافي">ثقافي</option>
  </select>
</div>
    <div class="mb-3">
      <label for="placeDescription" class="form-label" style="color:#FFF;">وصف المكان</label>
      <textarea class="form-control" id="placeDescription" name="placeDescription" required></textarea>
    </div>
    <div class="mb-3">
      <label for="placeImage" class="form-label" style="color:#FFF;">صورة المكان</label>
      <input type="file" class="form-control" id="placeImage" name="placeImage" accept="image/*" required>
    </div>

    <div class="mb-3">
      <label for="citySelect" class="form-label" style="color:#FFF;">المدينة</label>
      <select class="form-control" id="citySelect" name="cityId" required>
        <option value="" style="color:#FFF;">اختر المدينة</option>
        <?php foreach ($cities as $city): ?>
          <option value="<?= htmlspecialchars($city['CityId']) ?>">
            <?= htmlspecialchars($city['Name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary" name="addSuggest">إضافة المكان</button>
  </form>

  <h1 class="mt-5" style="color:#000;">جميع الأماكن</h1>
  <?php if (!empty($places)): ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>الرقم</th>
          <th>الاسم</th>
          <th>الوصف</th>
          <th>المدينة</th>
          <th>التنصيف</th>
          <th>هل تم اعتمادها؟</th>
          <th>حذف</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($places as $place): ?>
          <tr>
            <td><?= htmlspecialchars($place['placeId']) ?></td>
            <td><?= htmlspecialchars($place['name']) ?></td>
            <td><?= htmlspecialchars($place['description']) ?></td>
            <td><?= htmlspecialchars($place['cityName'] ?? 'غير متوفر') ?></td>
            <td><?= htmlspecialchars($place['category']) ?></td>
            <td><?= ($place['Approve'] == 1) ? 'نعم' : 'لا' ?></td>
            <td>
              <form method="POST" style="display: inline;">
                <input type="hidden" name="placeId" value="<?= htmlspecialchars($place['placeId']) ?>">
                <button type="submit" name="deletePlace" class="btn btn-danger" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا المكان؟');">حذف</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="alert alert-info" style="color:#000;">لاتوجد أماكن مقترحة حاليًا</p>
  <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
