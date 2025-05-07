<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أريد أن أصبح مرشدًا</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/guideform.css">
</head>
<?php
include '../base_nav.php';
include '../db.php'; // التأكد من تضمين اتصال قاعدة البيانات

// جلب المدن من قاعدة البيانات
$cities = [];
$cityQuery = $pdo->query("SELECT CityId, Name FROM cities");
while ($row = $cityQuery->fetch(PDO::FETCH_ASSOC)) {
    $cities[] = $row;
}
?>
<body style="background-color:#055951;">

<div class="container-fluid position-relative p-0" style="margin-top:90px">
    <div class="container">
        <h2>أريد أن أصبح المرشد</h2>
        <form action="save_additional_info.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="license" style="color:#fff">رقم الرخصة / السجل التجاري</label>
                <input type="text" id="license" name="license" required>
            </div>
            
            <div class="form-group">
                <label for="languages" style="color:#fff">اللغات التي تتقنها</label>
                <div id="language-container">
                    <div class="input-group">
                        <input type="text" name="languages[]" placeholder="إضافة لغة">
                        <i class="fas fa-trash" onclick="removeField(this)"></i>
                    </div>
                </div>
                <button type="button" class="add-button" onclick="addLanguage()">+ إضافة لغة</button>
            </div>

            <div class="form-group">
                <label for="cities" style="color:#fff">المدن التي تغطيها</label>
                <div id="city-container">
                    <select id="city-select" class="form-control">
                        <option value="" style="color:#fff">اختر مدينة</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= htmlspecialchars($city['Name']) ?>"><?= htmlspecialchars($city['Name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="add-button" onclick="addCity()">+ إضافة مدينة</button>
                    <div id="selected-cities"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="about" style="color:#fff">نبذة عني</label>
                <textarea id="about" name="about" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="facebook" style="color:#fff">رابط Facebook</label>
                <input type="url" id="facebook" name="facebook" class="form-control">
            </div>

            <div class="form-group">
                <label for="twitter" style="color:#fff">رابط Twitter</label>
                <input type="url" id="twitter" name="twitter" class="form-control">
            </div>

            <div class="form-group">
                <label for="instagram" style="color:#fff">رابط Instagram</label>
                <input type="url" id="instagram" name="instagram" class="form-control">
            </div>

            <div class="form-group">
                <label for="imageURL" style="color:#fff">تحميل صورة</label>
                <input type="file" id="imageURL" name="imageURL" class="form-control">
            </div>

            <div class="form-group">
                <label for="experience" style="color:#fff">الخبرة (بالسنوات)</label>
                <input type="number" id="experience" name="experience" class="form-control">
            </div>

            <div class="form-group">
                <label for="experience" style="color:#fff"> رقم الجوال</label>
                <input type="number" id="phone" name="phone" class="form-control">
            </div>

            <div class="form-group">
                <button type="submit" style="background-color:#826D42;margin:5px; border: 2px solid #000; color: #000;">تأكيد</button>
            </div>
        </form>
    </div>
</div>

<?php include('../footer.php'); ?>
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../lib/wow/wow.min.js"></script>
<script src="../lib/easing/easing.min.js"></script>
<script src="../lib/waypoints/waypoints.min.js"></script>
<script src="../lib/owlcarousel/owl.carousel.min.js"></script>
<script src="../lib/tempusdominus/js/moment.min.js"></script>
<script src="../lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="../lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

<script src="js/main.js"></script>

<script>
    function addLanguage() {
        const container = document.getElementById('language-container');
        const div = document.createElement('div');
        div.className = 'input-group';
        div.innerHTML = `
            <input type="text" name="languages[]" placeholder="إضافة لغة">
            <i class="fas fa-trash" onclick="removeField(this)"></i>
        `;
        container.appendChild(div);
    }

    function addCity() {
        const select = document.getElementById('city-select');
        const selectedCity = select.value;
        if (selectedCity) {
            const container = document.getElementById('selected-cities');
            const div = document.createElement('div');
            div.className = 'input-group';
            div.innerHTML = `
                <input type="text" name="cities[]" value="${selectedCity}" readonly>
                <i class="fas fa-trash" onclick="removeField(this)"></i>
            `;
            container.appendChild(div);
            select.value = ''; // إعادة تعيين القائمة المنسدلة
        }
    }

    function removeField(element) {
        element.parentNode.remove();
    }
</script>

</body>
</html>

