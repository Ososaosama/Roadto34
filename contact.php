<?php
include 'db.php';

if (!$pdo) {
    die("Could not connect to the database.");
}

$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");

    try {
        $stmt->execute([$name, $email, $subject, $message]);
        $successMessage = "Message sent successfully!";
    } catch (PDOException $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>

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
   تواصل معنا
</h6>
            <h1 class="mb-5" style="color: #ffffff">للمساعدة أو الاستفسار، نحن في خدمتك</h1>
        </div>

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-center">
            <div class="col-lg-6 col-md-12 wow fadeInUp" data-wow-delay="0.5s">  
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="بريدك الإلكتروني" required style="height: 100px; background-color: #5B5B5B; color: white;">
                            <label for="email" style="color:beige">بريدك الإلكتروني</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" placeholder="اسمك" required style="height: 100px; background-color: #5B5B5B; color: white;">
                            <label for="name" style="color:beige">اسمك</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="الموضوع" required style="height: 100px; background-color: #5B5B5B; color: white;">
                                <label for="subject" style="color:beige">الموضوع</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="message" placeholder="اترك رسالتك هنا" id="message" style="height: 100px; background-color: #5B5B5B; color: white;" required></textarea>
                                <label for="message" style="color:beige">الرسالة</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" type="submit" style="color: black;">أرسل الرسالة</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>


