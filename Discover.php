<?php
// تضمين ملف الاتصال بقاعدة البيانات
include 'db.php';

// الحصول على التصنيف المحدد للتصفية إذا تم اختياره
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
$selectedCityId = isset($_GET['cityId']) ? (int)$_GET['cityId'] : null;
$selectedPlaceCategory = isset($_GET['placeCategory']) ? $_GET['placeCategory'] : '';

// جلب قائمة التصنيفات المتاحة من قاعدة البيانات
$categoriesQuery = $pdo->query("SELECT DISTINCT category FROM place ORDER BY category ASC");
$categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

// جلب قائمة المدن المتاحة من قاعدة البيانات
$citiesQuery = $pdo->query("SELECT CityId, Name FROM Cities ORDER BY Name ASC");
$cities = $citiesQuery->fetchAll(PDO::FETCH_ASSOC);

// بناء استعلام لجلب الأماكن مع شروط المدينة والتصنيف
$sql = "SELECT p.placeId, p.name, p.description, p.ImageURL AS placeImage, c.Name AS cityName, p.category, 
        (SELECT AVG(rating) FROM review WHERE placeId = p.placeId) AS overallRating
        FROM place p
        LEFT JOIN Cities c ON p.CityId = c.CityId
        WHERE p.Approve = 1";

// إضافة شروط التصفية بناءً على المدينة والتصنيف إذا تم تحديدهما
$params = [];
if ($selectedCityId) {
    $sql .= " AND c.CityId = :cityId";
    $params[':cityId'] = $selectedCityId;
}
if ($selectedCategory) {
    $sql .= " AND p.category = :category";
    $params[':category'] = $selectedCategory;
}
if ($selectedPlaceCategory) {
    $sql .= " AND p.placeCategory = :placeCategory";
    $params[':placeCategory'] = $selectedPlaceCategory;
}

$sql .= " ORDER BY p.category ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$places = $stmt->fetchAll(PDO::FETCH_ASSOC);

function fetchOverallRating($placeName) {
    $apiKey = 'AIzaSyBeDzl0MOiEQpnwthVENf7xDdyF5rXyRio'; // Replace with your actual API key
    $googlePlacesUrl = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=" . urlencode($placeName) . "&inputtype=textquery&fields=place_id&key=" . $apiKey;
    $response = file_get_contents($googlePlacesUrl);
    $data = json_decode($response, true);

    if ($data['status'] === 'OK' && !empty($data['candidates'])) {
        $placeIdGoogle = $data['candidates'][0]['place_id'];
        $placeDetailsUrl = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . urlencode($placeIdGoogle) . "&fields=rating&key=" . $apiKey;
        $placeDetailsResponse = file_get_contents($placeDetailsUrl);
        $placeDetailsData = json_decode($placeDetailsResponse, true);

        if ($placeDetailsData['status'] === 'OK' && !empty($placeDetailsData['result'])) {
            return $placeDetailsData['result']['rating'];
        }
    }
    return null;
}

foreach ($places as &$place) {
    $place['overallRating'] = fetchOverallRating($place['name']);
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>اكت</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .m0 {
            margin: 0;
            padding: 0;
        }
        .filter-buttons .btn {
            margin: 5px;
        }
        .package-item {
            width: 100%;
            height: 500px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .package-item:hover {
            transform: translateY(-10px);
        }
        .package-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .package-item .text-center {
            flex-grow: 1;
        }
    </style>
</head>
<body>

<!-- عرض الأماكن بناءً على المدينة والتصنيف المحددين -->
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
        <h1 class="mb-5" style="color: #ffffff;">من هنا تبدأ الحكاية… إلى وجهات لا تُنسى</h1>
        <div class="container my-4">
    <h3 class="text-center" style="color: #ffffff;">اختر المدينة</h3>
    <div class="dropdown" style="text-align: center;">
        <button class="dropdown-toggle-custom" type="button" id="cityDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="menu-icon-blue">
                <span></span>
                <span></span>
                <span></span>
            </div>
            المدن
        </button>
        <ul class="dropdown-menu-custom" aria-labelledby="cityDropdown" id="cityList">
            <?php
            if (isset($places) && !empty($places)) {
                $uniqueCities = array_unique(array_column($places, 'cityName'));
                foreach ($uniqueCities as $city): ?>
                    <li><a class="dropdown-item" href="?city=<?= htmlspecialchars($city) ?>"><?= htmlspecialchars($city) ?></a></li>
                <?php endforeach;
            } else {
                echo '<li><a class="dropdown-item">لا توجد مدن متاحة</a></li>';
            }
            ?>
        </ul>
    </div>
</div>

        <!-- أزرار تصفية التصنيفات -->
        <div class="container my-4">
    <h3 class="text-center" style="color: #ffffff;">اختر التصنيف</h3>
    <div class="dropdown text-center">
        <button class="dropdown-toggle-custom" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="menu-icon-blue">
                <span></span>
                <span></span>
                <span></span>
            </div>
            التصنيفات
        </button>
        <ul class="dropdown-menu dropdown-menu-custom show-on-click" aria-labelledby="categoryDropdown" id="categoryList">
            <li>
                <a class="dropdown-item <?= $selectedCategory === '' ? 'active' : ''; ?>"
                   href="?page=Discover&cityId=<?= $selectedCityId; ?>&placeCategory=<?= urlencode($selectedPlaceCategory); ?>">
                    كل التصنيفات
                </a>
            </li>
            <?php foreach ($categories as $category): ?>
                <li>
                    <a class="dropdown-item <?= ($selectedCategory === $category['category']) ? 'active' : ''; ?>"
                       href="?page=Discover&cityId=<?= $selectedCityId; ?>&category=<?= urlencode($category['category']); ?>&placeCategory=<?= urlencode($selectedPlaceCategory); ?>">
                        <?= htmlspecialchars($category['category']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

        <div class="row g-4 justify-content-center">
            <?php if (empty($places)): ?>
                <p class="text-center">لا توجد أماكن مطابقة لهذا التصنيف أو المدينة.</p>
            <?php else: ?>
                <?php
$uniquePlaces = [];
$seenIds = [];

foreach ($places as $place) {
    if (!in_array($place['placeId'], $seenIds)) {
        $uniquePlaces[] = $place;
        $seenIds[] = $place['placeId'];
    }
}
?>
                <?php 
                    foreach ($uniquePlaces as $place): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="package-item">
                            <a href="/trips/pages/placeDetails.php?placeId=<?= htmlspecialchars($place['placeId']); ?>" style="width: 100%; height: 500px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; border: 1px solid #033954; border-radius: 8px; box-shadow: 0 4px 6px rgb(49, 172, 172); background-color: #053135;">
                                <div class="overflow-hidden" style="height: 150px; border-bottom: 1px solid #ddd;">
                                    <img class="img-fluid" src="/trips/<?= htmlspecialchars($place['placeImage']); ?>" alt="Place Image">
                                </div>
                                <div class="text-center p-4">
                                    <h3 class="mb-0" style="color: #fff"><?= htmlspecialchars($place['name']); ?></h3>
                                    <p class="m0" style=" color: #fff">اسم المدينة: <?= htmlspecialchars($place['cityName']); ?></p>
                                    <span> التصنيف: <?= htmlspecialchars($place['category']); ?></span>
                                    <?php if ($place['overallRating'] !== null): ?> 
                                        <p style=" color: #fff"><strong>التقييم العام:</strong> <?= number_format($place['overallRating'], 1); ?> <?= str_repeat('⭐', floor($place['overallRating'])); ?></p>
                                    <?php else: ?>
                                        <p style=" color: #fff"><strong>التقييم العام:</strong> غير متوفر</p>
                                    <?php endif; ?>
                                    <p style=" color: #fff"><?= htmlspecialchars($place['description']); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .dropdown-toggle-custom {
        display: inline-flex; /* Changed to inline-flex */
        align-items: center;
        gap: 10px;
        background-color: #198754;
        color: #fff;
        border: 2px solid #198754;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        margin: 10px auto; /* Center the button */
    }

    

    .dropdown-toggle-custom:hover {
        background-color: #86b817;
        color: #fff;
    }

    .menu-icon-blue {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .menu-icon-blue span {
        width: 22px;
        height: 3px;
        background-color: #ffffff;
        border-radius: 2px;
        transition: 0.3s;
    }

    .dropdown-menu-custom {
        display: none; /* Initially hidden */
        position: absolute;
        background-color: #fff;
        border: 2px solid #198754;
        border-radius: 8px;
        margin-top: 8px;
        padding: 10px;
        z-index: 1000;
        min-width: 220px;
        left: 50%; /* Position at the horizontal center */
        transform: translateX(-50%); /* Adjust to truly center */
        text-align: center; /* Center the links */
    }

    .dropdown-menu-custom.show {
        display: block; /* Show when toggled */
    }

    .dropdown-menu-custom a {
        display: block;
        padding: 8px 12px;
        text-decoration: none;
        color: #198754;
        font-weight: 500;
        border-radius: 5px;
        /* transition: 0.3s; */
    }

    .dropdown-menu-custom a:hover,
    .dropdown-menu-custom a.active {
        background-color: #198754;
        color: white;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggle = document.querySelector('.dropdown-toggle-custom');
        const dropdownMenu = document.querySelector('.dropdown-menu-custom');

        dropdownToggle.addEventListener('click', function() {
            dropdownMenu.classList.toggle('show');
        });

        // Close the dropdown when clicking outside
        window.addEventListener('click', function(event) {
            if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    });
</script>

<!-- <script>
document.getElementById('categoryDropdown').addEventListener('click', function (e) {
    e.stopPropagation();
    document.getElementById('categoryList').classList.toggle('show-on-click');
});

document.addEventListener('click', function () {
    document.getElementById('categoryList').classList.remove('show-on-click');
});
</script> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$pdo = null;
?>
