    <style>
		.mrg{
			margin-left: 30px;
		}
		.mrg img{
			width: 96px;
		height: 96px;
		border-radius: 100%;
		object-fit: cover;
		}
		.style{
			flex-direction: row-reverse;
			padding: 10px;
			border-radius: 20px;
		}
		.shape{
			align-items: self-end;
			display: flex;
			width: 75%;
			flex-direction: column;
			justify-content: center;
		}
		.shape a{
			align-self: baseline;
		}
		.style-mid{
			display: flex;
			flex-direction: row-reverse;
			justify-content: space-between;
			flex-direction: row-reverse;
			width: 60%;
		}
		.items{
			display: flex;
			flex-direction: row-reverse;
		}
		.bulid-icon{
			display: flex;
			align-items: center;
			margin-left: 10px;
			
		}
		.bulid-icon i{
			font-size: 28px;
			color: #000000c4;
		}
		.build-text{
			display: flex;
			flex-direction: column;
			align-items: end;
		}
		.build-text span{
			margin: 0;
			padding: 0;
		}
		.lang{
		background: #4c4c4c;
		color: white;
		border-radius: 100px;
		font-weight: 500;
		padding: .35em .65em;
		}
		.new{
			padding: 10px;
		}
	.Describe{
		font-size: 16px;
		line-height: 19px;
		color: #000000;
		text-align: justify;
		margin-top: 4px;
	}
	.button-details{
		border: 1px solid #6b9312;
		border-radius: 10px;
		padding: 5px 67px;
		transition: 0.7s;
	}
	.button-details:hover{
		color:white;
		font-weight: bold;
		background-color: #6b9312;
	}
	.team-item {
	   box-shadow :0px 4px 10px rgba(0, 0, 0, 0.1);
	}
	.Search{
		width: 85%;
	 

	}
	.input-group{
		flex-direction: row-reverse;
		margin-bottom: 20px;
	}
	 .Search .btn {
		width: 146px;
		height: 48px;
		color: white;
		border: 0;
		background: #6b9312;
		border-radius: 15px 0px 0px 15px;
	}

	 </style>
	 <head><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?php $cityQuery = $pdo->query("SELECT CityId, Name FROM Cities");
	$cities = $cityQuery->fetchAll(PDO::FETCH_ASSOC);
	$languageQuery = $pdo->query("SELECT DISTINCT languages FROM tourguide");
	$languagesList = [];

	while ($row = $languageQuery->fetch(PDO::FETCH_ASSOC)) {
		$row['languages'] = ltrim($row['languages'], ', ');
		$languages = explode(', ', $row['languages']);
		$languagesList = array_merge($languagesList, $languages);
	}
	$languagesList = array_unique($languagesList); // إزالة التكرارات
	?>


	</head>
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
  المرشدين
</h6>
				<h1 class="mb-5" style="color: #FFF;">مرشدينا، شريكك في اكتشاف أفضل الأماكن</h1>
			</div>
			<div class="container mt-5">
    <div class="row justify-content-center rounded-4 shadow-lg p-4" style="background-color: #a154a2;">
        <div class="col-md-12">
            <div class="row g-3 align-items-center justify-content-end flex-row-reverse">

                <!-- Search Field -->
                <div class="col-md-3">
                    <div class="form-floating">
                        <input name="search" id="search" type="text" class="form-control rounded-pill" placeholder="ابحث عن مرشد" style="direction: rtl;">
                        <label for="search" class="form-label" style="direction: rtl; text-align: right; color : #000000">ابحث عن مرشد</label>
                    </div>
                </div>

                <!-- City Dropdown -->
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select rounded-pill" id="CityId" name="CityId" style="color: #495057; direction: rtl; text-align: right;">
                            <option value="0">المدينة</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= htmlspecialchars($city['Name']) ?>"><?= htmlspecialchars($city['Name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="CityId" class="form-label" style="direction: rtl; text-align: right;">المدينة</label>
                    </div>
                </div>

                <!-- Language Dropdown -->
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select rounded-pill" id="Language" name="Language" style="color: #495057; direction: rtl; text-align: right;">
                            <option value="">اللغة</option>
                            <?php foreach ($languagesList as $language): ?>
                                <option value="<?= htmlspecialchars($language) ?>"><?= htmlspecialchars($language) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="Language" class="form-label" style="direction: rtl; text-align: right;">اللغة</label>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="col-md-3 d-flex align-items-center">
                    <button class="btn btn-primary rounded-pill w-100" type="button" onclick="filterGuides()" style="color:#fff; background-color: #007bff; border-color: #007bff;">
                        <i class="fas fa-search"></i> ابحث
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="guide-list">
        <?php
        $stmt = $pdo->query("
            SELECT
                tourguide.guideId,
                tourguide.name AS guideName,
                tourguide.imageURL,
                tourguide.facebook,
                tourguide.twitter,
                tourguide.instagram,
                tourguide.languages,
                tourguide.about,
                user.name AS userName
            FROM
                tourguide
            JOIN
                user ON tourguide.guideId = user.userId
            WHERE
                user.active = 1
        ");

        while ($guide = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $guide['guideId'];
            $tourStmt = $pdo->prepare("SELECT * FROM tour WHERE guideId = ?");
            $pladeStmt = $pdo->prepare("SELECT * FROM place WHERE guideid = ?");
            $tourStmt->execute([$id]);
            $pladeStmt->execute([$id]);
            $place = $pladeStmt->fetchAll(PDO::FETCH_ASSOC);
            $tours = $tourStmt->fetchAll(PDO::FETCH_ASSOC);
            $tourCount = count($tours);
            $placeCount = count($place);
            $languages = explode(', ', ltrim($guide['languages'], ', '));
            $languagesCount = count($languages) - 1;

            $ratingStmt = $pdo->prepare("SELECT AVG(rating) as average_rating FROM review WHERE guideId = ?");
            $ratingStmt->execute([$id]);
            $rating = $ratingStmt->fetchColumn();
            $starRating = round($rating);
        ?>
            <div class="col wow fadeInUp guide-item" data-wow-delay="0.1s"
                 data-name="<?= htmlspecialchars($guide['guideName']) ?>"
                 data-city="<?= htmlspecialchars($guide['cityName'] ?? '') ?>"
                 data-languages="<?= htmlspecialchars(implode(',', $languages)) ?>">
                <div class="card h-100 shadow-sm rounded-4 border-0">
                    <div class="overflow-hidden rounded-top-4 position-relative" style="height: 200px;">
                        <img src="<?php echo $guide['imageURL']; ?>" alt="Guide Image" class="card-img-top h-100 object-fit-cover">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between p-3">
                        <h5 class="card-title mb-2" style="color: #007bff;"><?php echo $guide['guideName']; ?></h5>
                        <div class="mb-2">
                            <span class="text-warning"><?php echo str_repeat('⭐', $starRating); ?></span>
                        </div>
                        <div class="d-flex justify-content-around mb-3">
                            <div class="text-center">
                                <i class="fas fa-route fa-lg text-secondary mb-1"></i>
                                <p class="mb-0 small text-muted">المسارات: <?php echo $tourCount ?></p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-map-marked-alt fa-lg text-secondary mb-1"></i>
                                <p class="mb-0 small text-muted">الأماكن: <?php echo $placeCount ?></p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-language fa-lg text-secondary mb-1"></i>
                                <p class="mb-0 small text-muted">اللغات: <?php echo $languagesCount + 1 ?></p>
                            </div>
                        </div>
                        <p class="card-text small text-muted mb-3" style="height: 60px; overflow: hidden;"><?php echo htmlspecialchars($guide['about']); ?></p>
                        <p class="card-text small text-secondary mb-2">
                            <i class="fas fa-globe me-2"></i> يتحدث: <?php echo implode(', ', $languages); ?>
                        </p>
                        <div class="d-grid">
                            <a href="GuidePages/guide_details.php?guideId=<?php echo $guide['guideId']; ?>&rating=<?php echo $starRating; ?>&languagesCount=<?php echo $languagesCount; ?>&tourCount=<?php echo $tourCount; ?>&placeCount=<?php echo $placeCount; ?>" class="btn btn-outline-primary rounded-pill">
                                <i class="fas fa-info-circle me-2"></i> المزيد من التفاصيل
                            </a>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top d-flex justify-content-around py-2">
                        <?php if (!empty($guide['facebook'])): ?>
                            <a href="<?php echo htmlspecialchars($guide['facebook']); ?>" class="text-secondary"><i class="fab fa-facebook fa-lg"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($guide['twitter'])): ?>
                            <a href="<?php echo htmlspecialchars($guide['twitter']); ?>" class="text-secondary"><i class="fab fa-twitter fa-lg"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($guide['instagram'])): ?>
                            <a href="<?php echo htmlspecialchars($guide['instagram']); ?>" class="text-secondary"><i class="fab fa-instagram fa-lg"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<style>
    .form-floating > .form-control:not(:placeholder-shown) ~ label::after {
        left: auto;
        right: 0;
    }

    .form-floating > label {
        text-align: right;
        direction: rtl;
        left: auto;
        right: 0.75rem;
        opacity: 0.65;
        transform: translate3d(0, 0.5rem, 0) scale(1);
        pointer-events: none;
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
</style>


<style>
    /* Search Bar Styling */
    .form-floating > .form-control:not(:placeholder-shown) ~ label::after {
        left: auto;
        right: 0;
    }

    .form-floating > label {
        text-align: right;
        direction: rtl;
        left: auto;
        right: 0.75rem;
        opacity: 0.65;
        transform: translate3d(0, 0.5rem, 0) scale(1);
        pointer-events: none;
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

    /* Guide Card Styling */
    .guide-item .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .guide-item .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .guide-item .card-title {
        height: 50px; /* Adjust as needed to prevent layout shifts */
        overflow: hidden;
    }
</style>

	<script>
		function filterGuides() {
			const searchInput = document.getElementById('search').value.toLowerCase();
			const selectedCity = document.getElementById('CityId').value;
			const selectedLanguage = document.getElementById('Language').value;

			document.querySelectorAll('.guide-item').forEach(guide => {
				const guideName = guide.getAttribute('data-name').toLowerCase();
				const guideCity = guide.getAttribute('data-city');
				const guideLanguages = guide.getAttribute('data-languages');

				const nameMatch = !searchInput || guideName.includes(searchInput);
				const cityMatch = selectedCity === '0' || guideCity === selectedCity;
				const languageMatch = !selectedLanguage || guideLanguages.includes(selectedLanguage);

				if (nameMatch && cityMatch && languageMatch) {
					guide.style.display = 'block';
				} else {
					guide.style.display = 'none';
				}
			});
		}
	</script>
