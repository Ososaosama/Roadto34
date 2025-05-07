<?php
include 'db.php';

// الحصول على قيمة البحث من المستخدم
$searchCity = isset($_GET['city']) ? $_GET['city'] : '';
$searchDate = isset($_GET['date']) ? $_GET['date'] : '';
$searchTourName = isset($_GET['tourName']) ? $_GET['tourName'] : '';

$sql = "SELECT Tour.*, TourGuide.name AS guideName FROM Tour 
        JOIN TourGuide ON Tour.guideId = TourGuide.guideId";

$conditions = [];
$params = [];

// إذا تم إدخال المدينة، أضفها كشرط
if (!empty($searchCity)) {
    $conditions[] = "Tour.cityId = :cityId";
    $params[':cityId'] = $searchCity;
}

// إذا تم إدخال التاريخ، أضفه كشرط
if (!empty($searchDate)) {
    $conditions[] = "Tour.date = :date";
    $params[':date'] = $searchDate;
}

// إذا تم إدخال اسم المسار، أضفه كشرط
if (!empty($searchTourName)) {
    $conditions[] = "Tour.title LIKE :tourName";
    $params[':tourName'] = '%' . $searchTourName . '%';
}


// إضافة الشروط إلى الاستعلام إذا كانت هناك أي شروط
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $pdo->prepare($sql);

// تمرير القيم إلى الاستعلام
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->execute();
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tourCount = count($tours);

// جلب قائمة المدن من قاعدة البيانات
$citiesQuery = $pdo->query("SELECT CityId, Name FROM Cities ORDER BY Name ASC");
$cities = $citiesQuery->fetchAll(PDO::FETCH_ASSOC);

// جلب قائمة التصنيفات من قاعدة البيانات
$categoriesQuery = $pdo->query("SELECT DISTINCT placeCategory FROM Tour ORDER BY placeCategory ASC");
$categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- أزرار التصنيفات -->
<div class="container mt-5">
    <div class="text-center mb-4">
        <h4 style="color:white">التصنيفات</h4>
        <div class="dropdown" style="text-align: center;">
            <button class="dropdown-toggle-custom" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="menu-icon-blue">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                اختر التصنيف
            </button>
            <ul class="dropdown-menu-custom" aria-labelledby="categoryDropdown" id="categoryList">
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a class="dropdown-item"
                           href="index.php?page=packages&category=<?php echo urlencode($category['placeCategory']); ?>">
                            <?php echo htmlspecialchars($category['placeCategory']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>


<!-- صندوق البحث -->
<div class="container mt-4">
    <form method="GET" action="index.php" class="rounded shadow p-4" style="background-color: #a154a2;">
        <input type="hidden" name="page" value="packages">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
            <div class="form-floating" dir="rtl">
    <select name="city" class="form-select" id="citySelect">
        <option value="">اختر المدينة</option>
        <?php foreach ($cities as $city): ?>
            <option value="<?php echo htmlspecialchars($city['CityId']); ?>" <?php echo ($searchCity == $city['CityId']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($city['Name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <label for="citySelect" class="form-label">المدينة</label>
</div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="date" name="date" class="form-control" id="dateInput" placeholder="التاريخ" value="<?php echo htmlspecialchars($searchDate); ?>">
                    <label for="dateInput" class="form-label" style="text-end; direction: rtl;">التاريخ</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="text" name="tourName" class="form-control" id="tourNameInput" style="text-end; direction: rtl;" placeholder="ابحث عن اسم المسار" value="<?php echo htmlspecialchars($searchTourName); ?>">
                    <label for="tourNameInput" class="form-label" style="text-end; direction: rtl;">اسم المسار</label>
                </div>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary w-100" style="color:#fff; background-color: #007bff; border-color: #007bff; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);">
                    <i class="fas fa-search me-2"></i> البحث
                </button>
            </div>
        </div>
    </form>
</div>

<?php
define('BASE_URL', 'http://localhost/trips/uploads/'); // استبدل `localhost/trips/uploads/` بمسار الصور الصحيح
?>

<!-- عرض النتائج -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
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
  العروض
</h6>
            <h1 class="mb-5" style="color: #ffffff">عروض رائعة</h1>
        </div>
        <!-- عرض عدد الرحلات -->
        <div class="container">
            <h4 class="text-center" style="color:#CFD0CF">(عدد المسارات :<?php echo $tourCount; ?>)</h4>
        </div>
        <div class="row g-4 justify-content-center">
    <?php if (empty($tours)): ?>
        <p class="text-center" style="color: white;">لا توجد نتائج مطابقة.</p>
    <?php else: ?>
        <?php foreach ($tours as $row): ?>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="card package-item h-100 border-0 shadow rounded-4 overflow-hidden">
                    <div class="position-relative overflow-hidden" style="height: 250px;">
                        <a href="index.php?page=tourDetails&tourId=<?php echo $row['tourId']; ?>">
                            <img class="card-img-top h-100 object-fit-cover" src="<?php echo htmlspecialchars($row['imageURL']); ?>" alt="Package Image">
                        </a>
                        <div class="position-absolute top-0 start-0 bg-dark text-white rounded-end py-2 px-3 m-2" style="opacity: 0.8;">
                            <small><i class="fa fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($row['city']); ?></small>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-4">
                        <div>
                            <h5 class="card-title text-center mb-3 text-white" style="font-weight: bold; background-color: rgba(0, 0, 0, 0.7); padding: 0.5rem; border-radius: 5px;"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text small text-muted" style="height: 60px; overflow: hidden; color: #ccc !important;"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="card-text mb-2 text-light"><strong>التصنيف:</strong> <?php echo htmlspecialchars($row['placeCategory']); ?></p>
                            <div class="mb-3 text-center">
                                <?php
                                $ratingStmt = $pdo->prepare("SELECT AVG(rating) as average_rating FROM tour_comments WHERE tourId = ?");
                                $ratingStmt->execute([$row['tourId']]);
                                $rating = $ratingStmt->fetchColumn();
                                $starRating = $rating ? round($rating) : 0;
                                echo '<span class="text-light">' . str_repeat('⭐', $starRating) . '</span>';
                                ?>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-white">السعر: ﷼<?php echo number_format($row['price'], 2); ?></h4>
                            <div>
                                <a href="index.php?page=tourDetails&tourId=<?php echo $row['tourId']; ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">المزيد</a>
                                <a href="index.php?page=booking&tourId=<?php echo $row['tourId']; ?>" class="btn btn-sm btn-success rounded-pill px-3 ms-2">احجز الآن</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top d-flex justify-content-around py-3 text-light">
                        <small><i class="fa fa-calendar-alt me-2"></i><?php echo htmlspecialchars($row['date']); ?></small>
                        <small>  <?php echo htmlspecialchars($row['guideName']); ?> <i class="fa fa-user me-2"></i>: مرشد الرحلة</small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    </div>
</div>

<?php
// Close the database connection
$pdo = null;
?>

<style>
    /* Modern select styling */
    .form-select {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        appearance: none;
        background-image: url('data:image/svg+xml;utf8,<svg fill="%236c757d" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7 7l3-3 3 3H7zm0 6l3 3 3-3H7z"/></svg>');
        background-repeat: no-repeat;
        background-position: left 1rem center;
        background-size: 1rem;
    }

    /* RTL adjustments */
    [dir="rtl"] .form-select {
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }
</style>

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

    .form-floating > .form-control:not(:placeholder-shown) ~ label::after {
        left: auto;
        right: 0;
    }

    .form-floating > label {
        text-align: end;
        direction: rtl;
        left: auto;
        right: 0.75rem;
        opacity: 0.65;
        transform: translate3d(0, 0.5rem, 0) scale(1);
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-select:focus ~ label {
        transform: translate3d(0, -0.5rem, 0) scale(0.85);
        opacity: 1;
    }

    .form-floating > .form-control:not(:placeholder-shown) ~ label,
    .form-floating > .form-select:not([value=""]):valid ~ label {
        transform: translate3d(0, -0.5rem, 0) scale(0.85);
        opacity: 1;
    }
    .package-item {
        transition: transform 0.3s ease-in-out;
        background-color: #1a1a1a; /* Dark background for the card */
    }

    .package-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3); /* More pronounced shadow on hover */
    }

    .card-text strong {
        color: #eee; /* Strong text color */
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggle = document.querySelector('#categoryDropdown');
        const dropdownMenu = document.querySelector('#categoryList');

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