<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../db.php'; // تضمين الاتصال بقاعدة البيانات
include 'index.php';  // Include the navigation bar

// التحقق من وجود طلب حذف
if (isset($_GET['removePlace'])) {
    $placeId = $_GET['removePlace'];
    
    try {
        // حذف المكان من قاعدة البيانات
        $stmt = $pdo->prepare("DELETE FROM place WHERE placeId = :placeId");
        $stmt->execute(['placeId' => $placeId]);
        echo "<div class='alert alert-success'>تم حذف المكان بنجاح.</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>حدث خطأ أثناء حذف المكان: " . htmlspecialchars($e->getMessage()) . "</div>";
    }

    // إعادة التوجيه لتجنب إعادة تشغيل الحذف عند تحديث الصفحة
    header("Location: index.php?form=AddPlace");
    exit();
}

// التحقق من وجود طلب اعتماد
if (isset($_GET['approvePlace'])) {
    $placeId = $_GET['approvePlace'];
    
    try {
        // تحديث حالة الاعتماد
        $stmt = $pdo->prepare("UPDATE place SET Approve = 1 WHERE placeId = :placeId");
        $stmt->execute(['placeId' => $placeId]);
        echo "<div class='alert alert-success'>تم اعتماد المكان بنجاح.</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>حدث خطأ أثناء اعتماد المكان: " . htmlspecialchars($e->getMessage()) . "</div>";
    }

    header("Location: index.php?form=AddPlace");
    exit();
}
if (isset($_POST['addPlace'])) {
  $placeName = $_POST['placeName'];
  $placeDescription = $_POST['placeDescription'];
  $cityId = $_POST['cityId'];
  $placeCategory = $_POST['placeCategory']; // إضافة التصنيف
  $imagePaths = [];

  if (!empty($_FILES['placeImages']['name'][0])) {
      foreach ($_FILES['placeImages']['tmp_name'] as $index => $tmpName) {
          $imageName = basename($_FILES['placeImages']['name'][$index]);
          $path = 'uploads/'.$imageName;
          $imagePath = '../uploads/' . $imageName;

          if (move_uploaded_file($tmpName, $imagePath)) {
              $imagePaths[] = $imagePath;
          } else {
              echo "<div class='alert alert-danger'>حدث خطأ أثناء تحميل الصورة رقم " . ($index + 1) . ".</div>";
          }
      }

      try {
        $stmt = $pdo->prepare("INSERT INTO place (name, description, imageURL, CityId, Approve, category, guideid) VALUES (:name, :description, :imageURL, :cityId, 1, :category, 0)");
        $stmt->execute([
            'name' => $placeName,
            'description' => $placeDescription,
            'imageURL' => $path,
            'cityId' => $cityId,
            'category' => $placeCategory,
            // تأكد من وجود قيمة افتراضية أو قيمة مدخلة لهذا الحقل
        ]);
        
          echo "<div class='alert alert-success'>تمت إضافة المكان بنجاح.</div>";
      } catch (PDOException $e) {
          echo "<div class='alert alert-danger'>حدث خطأ أثناء إضافة المكان: " . htmlspecialchars($e->getMessage()) . "</div>";
      }
  } else {
      echo "<div class='alert alert-warning'>يرجى تحميل صورة واحدة على الأقل للمكان.</div>";
  }
}


// استرجاع جميع الأماكن مع أسماء المدن المرتبطة بها
$placeQuery = $pdo->query("
    SELECT p.placeId, p.name, p.description, c.Name AS cityName, p.Approve, p.category, g.name AS guideName
    FROM Place p
    LEFT JOIN Cities c ON p.CityId = c.CityId
    LEFT JOIN TourGuide g ON p.guideid = g.guideId
");

$places = $placeQuery->fetchAll(PDO::FETCH_ASSOC);

// استرجاع جميع المدن للاختيار في القائمة المنسدلة
$cityQuery = $pdo->query("SELECT * FROM Cities");
$cities = $cityQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إدارة الأماكن</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
        /* تحسين تصميم الصفحة */
        .container {
            margin-top: 50px;
            max-width: 900px;
        }

        h1 {
            color: #000;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: bold;
            color: #555;
        }

        .btn-primary, .btn-warning, .btn-danger {
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .table {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #032124;
            color: #fff;
            border: #05676E;
        }

        .table-striped tbody tr:hover {
            background-color: #05676E;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container mt-5">
  <h1>إضافة مكان</h1>
  <form method="POST" enctype="multipart/form-data" >
    <div class="mb-3">
      <label for="placeName" class="form-label" style="color: #000;">اسم المكان</label>
      <input type="text" class="form-control" id="placeName" name="placeName" required style="background-color: #CCCCCC;">
    </div>
    <div class="mb-3">
      <label for="placeDescription" class="form-label" style="color: #000;">وصف المكان</label>
      <textarea class="form-control" id="placeDescription" name="placeDescription" required style="background-color: #CCCCCC;"></textarea>
    </div>
    <div class="mb-3">
  <label for="placeCategory" class="form-label" style="color: #000;">تصنيف المكان</label>
  <select class="form-control" id="placeCategory" name="placeCategory" required style="background-color: #CCCCCC;">
  <option value="">اختر التصنيف</option>
    <option value="تاريخي">مواقع تاريخية</option>
    <option value="طبيعي">مواقع طبيعية</option>
    <option value="ترفيهي">مواقع ترفيهية</option>
    <option value="تسويق">مراكز تسويق</option>
    <option value="مغامرة">مغامرة</option>
    <option value="معرض">معرض</option>
    <option value="الرياضية">الرياضية</option>
    <option value="ثقافي">ثقافي</option>
  </select>
</div>

    <div class="mb-3">
      <label for="placeImages" class="form-label" style="color: #000;">صور المكان</label>
      <input type="file" class="form-control" id="placeImages" name="placeImages[]" accept="image/*" multiple required style="background-color: #CCCCCC;">
    </div>
    <div class="mb-3">
      <label for="citySelect" class="form-label" style="color: #000;">المدينة</label>
      <select class="form-control" id="citySelect" name="cityId" required style="background-color: #CCCCCC;">
        <option value="">اختر المدينة</option>
        <?php foreach ($cities as $city): ?>
          <option value="<?= htmlspecialchars($city['CityId']) ?>">
            <?= htmlspecialchars($city['Name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-block " name="addPlace" style="background-color: #826D42; color: #000; border: 2px solid #000;">إضافة المكان</button>
  </form>

  <h1 class="mt-5" style="color:#000;">كل الأماكن</h1>
  <?php if (!empty($places)): ?>
    <table class="table table-bordered table-striped table-hover" style="background-color:#25978B;">
      <thead>
        <tr>
          <th>المعرف</th>
          <th>الاسم</th>
          <th>الوصف</th>
          <th>التصنيف</th>
          <th>المدينة</th>
          <th>هل تم اعتمادها؟</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($places as $place): ?>
          <tr>
            <td><?= htmlspecialchars($place['placeId']) ?></td>
            <td><?= htmlspecialchars($place['name']) ?></td>
            <td><?= htmlspecialchars($place['description']) ?></td>
            <td><?= htmlspecialchars($place['category'] ?? 'غير محدد') ?></td>

            <td><?= htmlspecialchars($place['cityName'] ?? 'N/A') ?></td>
            <td><?= ($place['Approve'] == 1) ? 'نعم' : 'لا' ?></td>
            <td>
              <?php if ($place['Approve'] == 0): ?>
                <a href="?approvePlace=<?= htmlspecialchars($place['placeId']) ?>" class="btn btn-success btn-sm">اعتماد</a>
              <?php endif; ?>
              <a href="?removePlace=<?= htmlspecialchars($place['placeId']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا المكان؟');">حذف</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="alert alert-info text-center">لم يتم العثور على أماكن.</p>
  <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
