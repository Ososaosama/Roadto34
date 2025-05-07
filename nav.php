<?php
ob_start();
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
    $id=$_SESSION["userId"] ;
} else {
    $role = 'guest'; 
}
if ($page == 'home') {
    $heroClass = 'hero-header';
    $heroBgStyle = "background-image: url('img/homeimg.jpg'); background-size: cover; background-position: center; height: 100vh;";
    $heroTitle = '';
    $heroText = '  ';
} else {
    $heroClass = 'hero-header-other';
    // تحديد خلفيات وصفحات مخصصة لكل صفحة
    switch ($page) {
        case 'packages':
            $heroBgStyle = "background-image: url('img/p1.jpg'); background-size: cover; background-position: center;height: 100vh;";
            $heroTitle = '';
            $heroText = '';
            break;
        case 'tourDetails':
            $heroBgStyle = "background-image: url('img/p2.jpg'); background-size: cover; background-position: center;height: 100vh;";
            $heroTitle = '';
            $heroText = '';
            break;
        case 'team':
            $heroBgStyle = "background-image: url('img/p3.jpg'); background-size: cover; background-position: center;height: 100vh;";
            $heroTitle = '';
            $heroText = '';
            break;
        case 'contact':
            $heroBgStyle = "background-image: url('img/p4.jpg'); background-size: cover; background-position: center; center;height: 100vh;";
            $heroTitle = '';
            $heroText = '';
            break;
        case 'Discover':
            $heroBgStyle = "background-image: url('img/p6.jpeg'); background-size: cover; background-position: center; center;height: 100vh;";
            $heroTitle = '';
            $heroText = '';
            break;
        default:
            $heroBgStyle = "background-image: url('img/p2.jpg'); background-size: cover; background-position: center; center;height: 100vh;";
            $heroTitle = '';
            $heroText = '';
            break;
    }
}

?>

<div class="container-fluid position-relative p-0">
   <nav class="custom-navbar navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0" style="
  position: fixed;
  top: 0;
  width: 100%;
  height: 88px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  /* transition: background-color 0.4s ease, box-shadow 0.4s ease; */
  background-color: transparent;
  z-index: 1000;
  ">
    
    <a href="index.php" class="navbar-brand p-0 d-flex align-items-center">
        <img src="img/mark.png" alt="Logo" style="height: 40px; margin-left: 10px;">
        <h1 class="text-primary m-0"><i class="fa fa-map--alt me-3"></i>Road To34</h1>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
    
        <div class="navbar-nav ms-auto py-0" style="padding: 15px;">
            <a href="index.php?page=contact" class="nav-item nav-link <?= $page == 'contact' ? 'active' : '' ?>">للتواصل</a>
            <a href="index.php?page=team" class="nav-item nav-link <?= $page == 'team' ? 'active' : '' ?>">المرشدين</a>
            <a href="index.php?page=packages" class="nav-item nav-link <?= $page == 'packages' ? 'active' : '' ?>">الرحلات</a>
            <a href="index.php?page=Discover" class="nav-item nav-link <?= $page == 'Discover' ? 'active' : '' ?>">استكشف</a>
            <a href="index.php?page=home" class="nav-item nav-link <?= $page == 'home' ? 'active' : '' ?>">الرئيسية</a>

            <?php if ($role == 'admin'): ?>
                    <a href="AdminPages/index.php" class="nav-item nav-link">التحكم</a>
                    
                <?php endif; ?>
                <?php if ($role == 'guide'): ?>
                    <a href="GuidePages/add_suggest_form.php?guideId=<?= $id ?>" class="nav-item nav-link">اقتراح وجهة</a>
                    <?php endif; ?>
        </div>
        <div class="custom-flag-selector" sytle="margin: -12px;">
    <img src="https://flagcdn.com/w320/us.png" alt="English" onclick="changeLanguage('en')">
  <img src="https://flagcdn.com/w320/sa.png" style="margin-right:10px;" alt="Arabic" onclick="changeLanguage('ar')">
  <!-- زود أعلام تانية حسب اللغات اللي عايزها -->
</div>
        <?php if (isset($_SESSION['userId'])): ?>
    <?php if ($_SESSION['role'] == 'guide'): ?>
        <a href="GuidePages/dashboard-button.php" class="btn rounded-pill custom-btn;"style=" margin: 25px 12px; border: 2px solid #198754; color: #ffffff;">الملف الشخصي</a>
        <?php elseif ($_SESSION['role'] == 'user'): ?>
        <a href="index.php?page=profile" class="btn btn-primary rounded-pill custom-btn;"style=" margin: 25px 12px; border: 2px solid #198754; color: #ffffff;">الملف الشخصي</a>
        
    <?php endif; ?>
    <a href="index.php?page=logout" class="btn rounded-pill py-2 px-3" style="margin-top: 10px; background-color: #dc3545; color: #ffffff; border: 2px solid #dc3545;">تسجيل خروج</a>
    <?php else: ?>
    <a href="index.php?page=register" class="btn btn-primary rounded-pill py-3 px-4.5;"style=" margin: 25px 12px; border: 2px solid #198754; color: #ffffff;">إنشاء حساب</a>
    <a href="index.php?page=login" class="btn rounded-pill custom-btn" style="margin-top: 10px; background-color: #ffffff; color: #86b817; border: 2px solid #fff;">
        تسجيل دخول
    </a>
<?php endif; ?>
    </div>
    
</nav>


    <div class="container-fluid py-5 mb-5 <?= $heroClass ?>" style="<?= $heroBgStyle ?>">
        <div class="container py-5">
            <div class="row justify-content-center py-5">
                <div class="col-lg-10 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-3 text-white mb-3 animated slideInDown"><?= $heroTitle ?></h1>
                    <p class="fs-4 text-white mb-4 animated slideInDown"><?= $heroText ?></p>
                    <?php if ($page == 'home'): ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
  :root {
    --navbar-bg: transparent;
  }

  .custom-navbar {
    background-color: var(--navbar-bg) !important;
    transition: background-color 0.4s ease, box-shadow 0.4s ease;
  }

  .scrolled {
    --navbar-bg: #a154a2;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>

<style>
    
    .navbar-brand h1 {
        font-size: 2rem;
        font-weight: bold;
    }
    .navbar-nav .nav-link {
        color: #00cf61 !important;
        margin-right: 15px;
        font-size: 1.1rem;
    }
    .navbar-nav .nav-link.active {
        color: #00cf61 !important;
    }
    .btn-primary {
        background-color: #198754!important;
    }
    .btn-secondary {
        background-color: #826D42!important;
        border-color: #000!important;
    }
    .hero-header, .hero-header-other {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .custom-btn {
        padding: 15px 20px;
        transition: all 0.3s ease;
    }

    .custom-btn:hover {
        background-color: #86b817 !important;
        color: #fff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px #86b817
    }

    .custom-btn:active {
        transform: translateY(0);
        box-shadow: none;
        opacity: 0.9;
    }
</style>

<style>
/* إخفاء نص اللغة وإظهار العلم بداله */
.goog-te-combo {
  display: none !important;
}

.custom-flag-selector {
  position: relative;
  display: inline-block;
  /* margin: 0px 25px */
}

.custom-flag-selector img {
  width: 32px;
  height: 20px;
  cursor: pointer;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>

<style>
    :root {
    --navbar-bg: transparent;
  }
  
  .navbar {
    background-color: var(--navbar-bg);
    transition: background-color 0.3s ease;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 999;
  }
</style>

<div id="google_translate_element"></div>

<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'ar'}, 'google_translate_element');
}
</script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
function changeLanguage(lang) {
  const select = document.querySelector(".goog-te-combo");
  if (select) {
    select.value = lang;
    select.dispatchEvent(new Event('change'));
  }
}
</script>

<script>
  window.addEventListener("scroll", function () {
    const navbar = document.querySelector(".custom-navbar");
    if (window.scrollY > 0) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  });
</script>