<?php

include 'db.php';

if (!isset($_SESSION['userId'])) {
    header('Location: index.php?page=login');
    exit;
}

$userId = $_SESSION['userId'];
$userQuery = $pdo->prepare("SELECT * FROM user WHERE userId = ?");
$userQuery->execute([$userId]);
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

$tourId = isset($_GET['tourId']) ? $_GET['tourId'] : null;
if ($tourId) {
    $tourQuery = $pdo->prepare("SELECT * FROM Tour WHERE tourId = ?");
    $tourQuery->execute([$tourId]);
    $tour = $tourQuery->fetch(PDO::FETCH_ASSOC);
    $guideId = $tour['guideId'];
    $pricePerPerson = $tour['price']; 
} else {
    echo "Tour not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $specialRequest = $_POST['specialRequest'];
    $numberOfPeople = $_POST['numberOfPeople'];
    $totalPrice = $numberOfPeople * $pricePerPerson; 

    $bookingQuery = $pdo->prepare("INSERT INTO Booking (userId, tourId, bookingDate, specialRequest, guideId, numberOfPeople, totalPrice) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $bookingQuery->execute([$userId, $tourId, $date, $specialRequest, $guideId, $numberOfPeople, $totalPrice]);

    echo "Booking confirmed! Total Price: " . htmlspecialchars($totalPrice) . " SAR";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الحجز عبر الإنترنت</title>
</head>
<body>
 <body style="color:#FFF;background-color:#0fa193;">
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container" >
            <div class="booking p-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-6 text-white">
                        <h6 class="text-white text-uppercase">الحجز</h6>
                        <h1 class="text-white mb-4">الحجز عبر الإنترنت</h1>
                        <p class="mb-4">استمتع برحلتك إلى <?php echo htmlspecialchars($tour['title']); ?>.</p>
                    </div>
 </body>
                    <div class="col-md-6" style="color:#FFF;">
                        <form method="post">
                            <div class="row g-3" >
                                <div class="col-md-6">
                                    <div class="form-floating" style="background-color: #0b7168;border:2px solid #0d645c;">
                                        <input type="text" class="form-control bg-transparent" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                                        <label for="name">اسمك</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating" style="background-color: #0b7168;border:2px solid #0d645c;">
                                        <input type="email" class="form-control bg-transparent" id="email" value="<?php echo htmlspecialchars($user['emailAddress']); ?>" readonly>
                                        <label for="email">بريدك الإلكتروني</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating" style="background-color: #0b7168;border:2px solid #0d645c;">
                                        <input type="date" name="date" class="form-control bg-transparent" id="datetime" value="<?php echo htmlspecialchars($tour['date']) ?>" readonly>
                                        <label for="datetime">التاريخ</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating" style="background-color: #0b7168;border:2px solid #0d645c;">
                                        <input type="text" class="form-control bg-transparent" id="destination" value="<?php echo htmlspecialchars($tour['title']); ?>" readonly>
                                        <label for="destination">الوجهة</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating" style="background-color: #0b7168;border:2px solid #0d645c;">
                                        <input type="text" class="form-control bg-transparent" id="price" value="<?php echo htmlspecialchars($tour['price']); ?>" readonly>
                                        <label for="price">سعر الشخص الواحد</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating" style="background-color: #0b7168;border:2px solid #0d645c;">
                                        <input type="number" name="numberOfPeople" class="form-control bg-transparent" id="numberOfPeople" min="1" max="10" required>
                                        <label for="numberOfPeople">عدد الأشخاص (1-10)</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating" style="background-color: #0b7168;border:2px solid #0d645c;">
                                        <input type="text" class="form-control bg-transparent" id="totalPrice" readonly>
                                        <label for="totalPrice">السعر الإجمالي</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating" style="background-color: #0b7168;border:2px solid #0d645c;">
                                        <textarea name="specialRequest" class="form-control bg-transparent" placeholder="طلب خاص" id="message" style="height: 100px" ></textarea>
                                        <label for="message">طلب خاص</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-outline-light w-100 py-3" type="submit" style=" color:#000;background-color: #826D42;border:2px solid #000;">احجز الآن</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const numberOfPeopleInput = document.getElementById('numberOfPeople');
            const totalPriceInput = document.getElementById('totalPrice');
            const pricePerPerson = <?php echo $pricePerPerson; ?>;

            numberOfPeopleInput.addEventListener('input', function () {
                const numberOfPeople = parseInt(numberOfPeopleInput.value) || 0;
                const totalPrice = numberOfPeople * pricePerPerson;
                totalPriceInput.value = totalPrice + ' SAR';
            });
        });
    </script>
</body>
</html>
