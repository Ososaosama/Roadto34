-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 28 أبريل 2025 الساعة 19:05
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roadto34`
--

-- --------------------------------------------------------

--
-- بنية الجدول `booking`
--

CREATE TABLE `booking` (
  `bookingId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `tourId` int(11) NOT NULL,
  `bookingDate` date NOT NULL,
  `specialRequest` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `guideId` int(11) NOT NULL,
  `numberOfPeople` int(255) NOT NULL,
  `totalPrice` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `booking`
--

INSERT INTO `booking` (`bookingId`, `userId`, `tourId`, `bookingDate`, `specialRequest`, `createdAt`, `guideId`, `numberOfPeople`, `totalPrice`) VALUES
(6, 63, 48, '2025-06-21', 'أرجو توفير سيارة نقل مريحه وتتسع لعدد كبير', '2025-04-27 16:52:36', 53, 5, 1250);

-- --------------------------------------------------------

--
-- بنية الجدول `cities`
--

CREATE TABLE `cities` (
  `CityId` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `ImageURL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `cities`
--

INSERT INTO `cities` (`CityId`, `Name`, `ImageURL`) VALUES
(14, 'الرياض', 'uploads/صورة لمدينة الرياض.jpg'),
(28, 'الخبر', 'uploads/الخبر.jpg'),
(29, 'جدة', 'uploads/جدة.jpg'),
(30, 'أبها', 'uploads/ابها.jpg'),
(31, 'نيوم', 'uploads/نيوم.jpg');

-- --------------------------------------------------------

--
-- بنية الجدول `custom_tours`
--

CREATE TABLE `custom_tours` (
  `tour_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `people_count` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `guideid` int(255) NOT NULL,
  `approve` int(255) NOT NULL,
  `price` int(255) NOT NULL,
  `priceok` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(5, 'أسامة', 'osa@gmail.com', 'طريقة الدفع', 'السلام عليكم كيف سيتم الدفع للرحلات؟', '2025-04-27 16:55:41');

-- --------------------------------------------------------

--
-- بنية الجدول `place`
--

CREATE TABLE `place` (
  `placeId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `imageURL` varchar(255) DEFAULT NULL,
  `CityId` int(11) NOT NULL,
  `Approve` bit(1) NOT NULL,
  `guideid` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `place`
--

INSERT INTO `place` (`placeId`, `name`, `description`, `imageURL`, `CityId`, `Approve`, `guideid`, `category`) VALUES
(56, 'ملعب الملك سلمان الدولي', 'هو أحد أهم المشاريع الرياضية في المملكة\r\nالعربية السعودية، ويُعد تحفة معمارية حديثة. يتميز بتصميمه العصري\r\nوسعته الكبيرة، ما يجعله الملعب الرئيسي لبطولة كأس العالم 2034.', 'uploads/ملعب الملك سلمان الدولي.jpg', 14, b'1', 0, 'الرياضية'),
(58, 'أستاد الأمير محمد بن سلمان', 'هو واحد من أبرز المشاريع الرياضية المستقبلية في المملكة العربية السعودية. يأتي هذا الاستاد كجزء من رؤية\r\nالمملكة 2030 لتطوير البنية التحتية الرياضية وتعزيز مكانة المملكة كوجهة\r\nرياضية عالمية. يتميز الاستاد بموقعه وتصميمه الحديث الذي يجمع بين\r\nالابتكار والهندسة المستدامة.', 'uploads/أستاد الأمير محمد بن سلمان.jpg', 14, b'1', 0, 'الرياضية'),
(59, 'استاد وسط جدة', 'هو أحد المشاريع الرياضية الجديدة في المملكة العربية\r\nالسعودية، ويقع ضمن مشروع \"وسط جدة\" التطويري. يُعد الاستاد جزءًا من رؤية\r\nالمملكة 2030 لتعزيز البنية التحتية الرياضية والسياحية، حيث يمزج بين التصميم\r\nالحديث والمرافق المتطورة التي تجعل منه وجهة رياضية وترفيهية عالمية.', 'uploads/استاد وسط جدة.jpg', 29, b'1', 0, 'الرياضية'),
(67, 'استاد جنوب الرياض', 'يستوحي الملعب الجديد تصميمه الفريد من مبادئ العمارة السلمانية التي تمزج روح\r\nالأصالة والتراث والحداثة في آن معًا. ومن المقرر أن يصبح الملعب الرئيسي لأحد الأندية الكروية ومستضيف لمختلف\r\nالفعاليات الرياضي والترفيهية.', 'uploads/استاد جنوب الرياض.jpg', 14, b'1', 0, 'الرياضية'),
(69, 'استاد جامعة الملك سعود', 'يتواجد هذا الملعب في موقع مميّز بجوار مجمع \"يو ووك\" النابض بالحياة\r\nويشكل أحد أهم المشاريع متعددة الاستخدامات التابعة لجامعة الملك سعود. يستضيف الملعب حالياً مباريات\r\nالدوري السعودي للمحترفين وغيرها من الأحداث الرياضية الكبرى، ومن المقرر زيادة سعته لاستضافة عدد\r\nمن مباريات البطولة.', 'uploads/استاد جامعة الملك سعود.jpg', 14, b'1', 0, 'الرياضية'),
(89, 'ملعب جامعة الملك خالد	', 'استاد جامعة الملك خالد هو أحد المشاريع الرياضية الجديدة التي تُجسد رؤية المملكة العربية السعودية 2030 لتعزيز البنية التحتية الرياضية. يقع هذا الاستاد في مدينة أبها، ويعد إضافة مهمة إلى المشهد الرياضي والترفيهي في منطقة عسير.	', 'uploads/استاد جامعة الملك خالد.jpg', 30, b'1', 0, 'الرياضية'),
(90, 'استاد الملك فهد الدولي	', 'يعتبر هذا الملعب الحالي واحدًا من أشهر الملاعب في المنطقة، ويمتاز سقفه بتصميم هندسي فريد مستوحى من الخيمة العربية التقليدية. ويقع الملعب بالقرب من أهم وجهات المدينة مثل مشروع المسار الرياضي، الذي سيوفر وصولًا سهلًا إلى المساحات الخضراء. وسيشهد مشروع تجديد الملعب زيادة كبيرة في القدرة الاستيعابية، وإمكانية استخدامه لأغراض متنوعة بدءًا من مباريات كرة القدم وصولًا إلى الحفلات الموسيقية.	', 'uploads/استاد مدينة الملك فهد الرياضية.jpg', 14, b'1', 0, 'الرياضية'),
(92, 'KAEC Stadium	', 'من المقرر بناء هذا الملعب الجديد في مدينة الملك عبدالله الاقتصادية على ساحل البحر الأحمر. ويتميز الملعب بتصميم متعدد الاستخدامات مع لمسات جمالية مستوحاة من الشعاب المرجانية المحلية.	', 'uploads/استاد مدينة الملك عبد الله الاقتصادية.jpg', 29, b'1', 0, 'الرياضية'),
(93, 'Aramco Stadium', 'يُعد أحد المشاريع الرياضية الكبرى في المملكة العربية السعودية. يتم تطويره ليكون جاهزًا لاستضافة بعض مباريات كأس العالم2034 , وينفرد بتصميم ديناميكي مستوحى من الدوامات البحرية، ويأتي كجزء من جهود المملكة لتعزيز البنية التحتية الرياضية والاقتصادية في المنطقة الشرقية.	', 'uploads/استاد أرامكو.jpg', 28, b'1', 0, 'الرياضية');

-- --------------------------------------------------------

--
-- بنية الجدول `review`
--

CREATE TABLE `review` (
  `reviewId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `guideId` int(11) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `suggestedplaces`
--

CREATE TABLE `suggestedplaces` (
  `SuggestId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `placeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `tour`
--

CREATE TABLE `tour` (
  `tourId` int(11) NOT NULL,
  `guideId` int(11) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `imageURL` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `placeCategory` varchar(255) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tour`
--

INSERT INTO `tour` (`tourId`, `guideId`, `location`, `date`, `price`, `imageURL`, `description`, `city`, `duration`, `title`, `placeCategory`, `startDate`, `endDate`) VALUES
(45, 51, 'Boulevard World', '2025-06-01', 400.00, 'uploads/بوليفارد ورد .png', 'Boulevard World هو أحد أبرز الوجهات الترفيهية في موسم الرياض بالمملكة العربية السعودية، ويعد من أكبر المناطق الترفيهية في الموسم. يتميز بتصميمه الفريد الذي يجمع بين الثقافات العالمية، حيث يضم أحياءً تمثل دولًا مختلفة، تقدم كل منها تجارب ثقافية، وفنية، ومأكولات مميزة', 'الرياض ', 5, 'Boulevard World', 'ترفيهي', NULL, NULL),
(47, 53, 'الرياض ', '2025-06-01', 150.00, 'uploads/حديقة الحيوانات.png', 'حديقة الحيوانات في الرياض (Riyadh Zoo)\r\n\r\nحديقة الحيوانات في الرياض هي واحدة من أبرز المعالم الترفيهية في العاصمة السعودية، حيث توفر تجربة ممتعة وتعليمية للزوار من جميع الأعمار. تقع الحديقة في حي الملز، وتمتد على مساحة واسعة تضم مجموعة متنوعة من الحيوانات من مختلف البيئات حول العالم.', 'الرياض ', 2, 'Riyadh Zoo', 'ترفيهي', NULL, NULL),
(48, 53, 'الرياض ', '2025-06-21', 250.00, 'uploads/الملك فهد.png', 'سرنا أن نأخذكم في جولة مميزة داخل \"درة الملاعب\"، أحد أبرز المنشآت الرياضية في المملكة العربية السعودية، والذي شهد العديد من البطولات والفعاليات الكبرى.\r\n\r\nنبذة عن الاستاد:\r\nافتُتح عام 1987م ويُعد من أكبر الملاعب في المنطقة.\r\n\r\nيتسع لأكثر من 68,000 متفرج، ويتميز بسقفه المظلي الفريد.\r\nاستضاف مباريات محلية ودولية، بما في ذلك لقاءات المنتخب السعودي.', 'الرياض ', 2, 'استاد الملك فهد ', 'ترفيهي', NULL, NULL),
(49, 51, 'Al Khobar Corniche', '2025-07-01', 150.00, 'uploads/كورنيش الخبر.png', 'كورنيش الخبر (Al Khobar Corniche)\r\n\r\nيُعد كورنيش الخبر من أجمل الوجهات الساحلية في المملكة العربية السعودية، حيث يتميز بإطلالته الخلابة على مياه الخليج العربي، ويوفر بيئة مثالية للعائلات والزوار الباحثين عن الاسترخاء والترفيه.', 'Al Khobar Corniche', 2, 'Al Khobar Corniche', 'سياحي', NULL, NULL),
(50, 54, 'King Abdulaziz Center for World Culture - Ithra', '2025-06-11', 200.00, 'uploads/اثراء.png', 'ركز الملك عبدالعزيز الثقافي العالمي (إثراء) هو معلم ثقافي بارز في المملكة العربية السعودية، يقع في مدينة الظهران. أنشأته شركة أرامكو السعودية ليكون منصة للإبداع والمعرفة، حيث يهدف إلى تعزيز الثقافة والفنون والعلوم من خلال مجموعة متنوعة من البرامج والفعاليات.', 'الخبر ', 3, 'King Abdulaziz Center for World Culture - Ithra', 'ثقافي ', NULL, NULL),
(51, 55, 'جدة', '2025-06-23', 300.00, 'uploads/البلد جدة.png', 'حي البلد - جدة هو القلب التاريخي لمدينة جدة وأحد أهم المعالم التراثية في المملكة العربية السعودية. يتميز بمبانيه التقليدية المبنية من الحجر المرجاني، وأزقته الضيقة، وأسواقه الشعبية التي تعكس أصالة وتاريخ المدينة العريق.', 'جدة', 3, 'Old Jeddah, Al Balad', 'تراثي', NULL, NULL),
(52, 52, 'جدة ', '2025-04-04', 350.00, 'uploads/الرمال الفضية .png', 'شاطئ سيلفر ساندز - جدة هو واحد من أجمل الشواطئ الخاصة في المدينة، ويتميز بأجوائه الفاخرة ومياهه الفيروزية ورماله الناعمة. يقع الشاطئ على ساحل البحر الأحمر، ويُعد وجهة مثالية لمحبي الاسترخاء والأنشطة البحرية.', 'جدة', 4, 'Silver Sands Beach', 'سياحي', NULL, NULL),
(53, 52, 'King Abdullah Sport City', '2025-09-13', 300.00, 'uploads/استاد الملك عبدالله الرياضي.png', 'مدينة الملك عبدالله الرياضية، المعروفة بـ\"الجوهرة المشعة\"، هي أحد أكبر وأحدث المنشآت الرياضية في المملكة العربية السعودية، وتقع في شمال مدينة جدة. افتُتحت عام 2014 وتعتبر معلمًا رياضيًا متطورًا يستضيف العديد من البطولات المحلية والدولية.', 'جدة', 2, 'King Abdullah Sport City', 'رياضي ', NULL, NULL),
(55, 52, 'ابها', '2025-12-18', 400.00, 'uploads/جبل السودة.png', 'جبل السودة هو أحد أعلى القمم الجبلية في المملكة العربية السعودية، ويعد من أبرز المعالم الطبيعية في منطقة عسير. يقع جبل السودة بالقرب من مدينة أبها، ويُعتبر وجهة سياحية رائعة للزوار الذين يبحثون عن تجربة فريدة وسط الطبيعة الخلابة.', 'ابها', 3, 'Jabal Sawda', 'سياحي', NULL, NULL),
(56, 57, 'ابها', '2025-05-03', 300.00, 'uploads/شمسان.png', 'قلعة شمسان هي قلعة تاريخية تقع في منطقة عسير، جنوب غرب المملكة العربية السعودية، وتعتبر واحدة من المعالم الأثرية الهامة في المنطقة. تشتهر القلعة بموقعها الاستراتيجي على قمة جبل شمسان، مما يوفر إطلالات رائعة على المناظر الطبيعية المحيطة بها.', 'ابها', 3, 'Shamsan Castle', 'تراثي', NULL, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `tourguide`
--

CREATE TABLE `tourguide` (
  `guideId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `cities` text DEFAULT NULL,
  `about` text DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `imageURL` varchar(500) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `phone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tourguide`
--

INSERT INTO `tourguide` (`guideId`, `name`, `languages`, `cities`, `about`, `experience`, `rating`, `imageURL`, `facebook`, `twitter`, `instagram`, `license_number`, `phone`) VALUES
(51, 'Mohammed Al-Hakami', 'العربية, الانجليزية, الإسبانية', 'الرياض', 'مرشد سياحي متخصص في تقديم تجارب استثنائية للزوار، مع خبرة طويلة ومعرفة عميقة بالثقافة الاسبانية. أتمتع بقدرة عالية على التواصل باللغات العربية، الإنجليزية، والاسبانية بمستوى متوسط. أبحث دائمًا عن أماكن جديدة بعيدة عن التقليدي لتقديم تجارب سياحية استثنائية لا تُنسى.\r\n\r\n', 6, NULL, 'uploads/المرشد محمد.jpg', '', '', '', '11112', 536733873),
(52, 'Ahmed Al-Faiz', 'العربية, الانجليزية, اليابانية', 'جدة, أبها', 'رحالة محترف ومرشد سياحي معتمد، أسافر حول العالم وأحب اكتشاف الثقافات المختلفة. لدي خبرة كبيرة في استضافة الزوار ، وأنا متحمس لنقل هذه التجارب الفريدة إليهم من خلال جولات سياحية مبتكرة ومليئة بالمفاجآت الثقافية والتاريخية.', 7, NULL, 'uploads/Ahmed.jpg', '', '', '', '11113', 598278383),
(53, 'Osama Al-Qarni', 'العربية, الانجليزية, الايطالية', 'الرياض, الخبر', 'مسافر عالمي ومرشد سياحي محترف، أتمتع بخبرة غنية في تنظيم جولات سياحية مميزة للمسافرين من مختلف أنحاء العالم. أستمتع بمشاركة تجارب ثقافية وتاريخية استثنائية، أقدم للزوار رحلة تنبض بالحياة والمعرفة. خبرتي في التواصل بعدة لغات تساعدني في تقديم جولات سلسة وشاملة.', 10, NULL, 'uploads/osama.jpg', '', '', '', '11111', 532839238),
(54, 'Saud Al-Khareef', 'العربية, الانجليزية, التركية', 'جدة, نيوم', 'مرشد سياحي محترف ومسافر شغوف، أسافر حول العالم لاستكشاف الثقافات المختلفة ومشاركتها مع الآخرين. بفضل خبرتي الكبيرة في تنظيم جولات فريدة، أحرص على تقديم تجارب سياحية تجمع بين التعليم والترفيه، حيث أُطلع الزوار على تاريخ الأماكن وأسرارها الخفية. مهاراتي اللغوية المتعددة تجعلني قادرًا على التواصل بفعالية مع الزوار من مختلف الجنسيات وتقديم جولات سلسة وممتعة.', 8, NULL, 'uploads/saud.jpg', '', '', '', '11114', 562732839),
(55, 'Ayidh Al-ALBAQAMI', 'العربية, الانجليزية , الصينية', 'الرياض, نيوم', 'مرشد سياحي عالمي ذو خبرة متميزة، أتمتع بشغف كبير في تقديم جولات سياحية تتناغم فيها الثقافة، التاريخ، والطبيعة. أسعى دائمًا لإضفاء طابع خاص على كل رحلة، حيث أخلق تجارب فريدة تجعل الزوار يشعرون وكأنهم يعيشون لحظات من تاريخ المدينة. بفضل إجادتي للغات متعددة، أقدم جولات متكاملة بكل سلاسة وتفصيل، مما يساعد على توفير تجربة سياحية استثنائية.', 4, NULL, 'uploads/Ayidh.jpg', '', '', '', '11115', 536273893),
(57, 'zeyad Al-Ghamdi', 'العربية, الانجليزية , الروسية', 'جدة, أبها', 'مسافر عالمي ومرشد سياحي محترف، أستمتع بتنظيم جولات سياحية مبتكرة وملهمة تسلط الضوء على أجمل جوانب الثقافة المحلية. من خلال تجربتي العميقة ومعرفتي الواسعة، أقدم للزوار رحلات ثقافية وتاريخية لا مثيل لها. بفضل مهاراتي في عدة لغات، يمكنني تقديم جولات مخصصة تلبي احتياجات كل زائر بكل سلاسة ودقة.', 3, NULL, 'uploads/zeyad.jpg', '', '', '', '11116', 562837239);

-- --------------------------------------------------------

--
-- بنية الجدول `tour_comments`
--

CREATE TABLE `tour_comments` (
  `commentId` int(11) NOT NULL,
  `tourId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `tour_places`
--

CREATE TABLE `tour_places` (
  `tourId` int(11) NOT NULL,
  `placeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tour_places`
--

INSERT INTO `tour_places` (`tourId`, `placeId`) VALUES
(48, 90),
(49, 93);

-- --------------------------------------------------------

--
-- بنية الجدول `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','guide','admin') DEFAULT 'user',
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `user`
--

INSERT INTO `user` (`userId`, `name`, `emailAddress`, `password`, `role`, `active`) VALUES
(51, 'Mohammed Al-Hakami', 'moh@gmail.com', '$2y$10$jQ2JlYLaF4Wd8cGsq/hQfOOhhRV/aq7Vz.Pr/CnCRkkNfHl0GmYx2', 'guide', 1),
(52, 'Ahmed Al-Fayez', 'ahm@gmail.com', '$2y$10$EokGWdsb5Q0m8r12O7/O7OcbMYOe22XX5UZPvm7ooibQXKR5rCIoS', 'guide', 1),
(53, 'Osama Al-Qarni', 'os@gmail.com', '$2y$10$WkCs9yfPiG7f.UynefWgKOcSRioLhgOOULCw0jpSJtWE3hosZy/uq', 'guide', 1),
(54, 'Saud Al-Kharif', 'sau@gmail.com', '$2y$10$nxYefkuRal9tLBloFLY13.p4F2U8LVtR/L8qXhP0akEEvYkG1md4y', 'guide', 1),
(55, 'Ayed Al-Qahtani', 'Aye@gmail.com', '$2y$10$h0pQl76BK5GPwaG6Kv8/iu///hbWIo7bxsAEAqTIvKx27xzVwA5oe', 'guide', 1),
(57, 'Ziad Al-Ghamdi', 'Zia@gmail.com', '$2y$10$RBZUjM5BKIq0/2iQJOI7quqVAq/Bdu0cxr96wulOc7wqLoK3e55WK', 'guide', 1),
(60, 'we', 'we@gmail.com', '$2y$10$P8mwfbHIRZblNxkK082wuOzBp2kw01nw5zE/RyETsxscBG9HIGqB6', 'admin', 1),
(63, 'osama', 'osa@gmail.com', '$2y$10$uF2ia7E7RhyB/IQjpRVWFeoV1x.rmaSa473nK.LK4hzgQ2Vuy.IOC', 'user', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookingId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `tourId` (`tourId`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`CityId`);

--
-- Indexes for table `custom_tours`
--
ALTER TABLE `custom_tours`
  ADD PRIMARY KEY (`tour_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`placeId`),
  ADD KEY `FK_City_Place` (`CityId`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`reviewId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `guideId` (`guideId`);

--
-- Indexes for table `suggestedplaces`
--
ALTER TABLE `suggestedplaces`
  ADD PRIMARY KEY (`SuggestId`),
  ADD KEY `FK_sug_Place` (`placeId`),
  ADD KEY `FK_sug_User` (`userId`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`tourId`),
  ADD KEY `guideId` (`guideId`);

--
-- Indexes for table `tourguide`
--
ALTER TABLE `tourguide`
  ADD PRIMARY KEY (`guideId`);

--
-- Indexes for table `tour_comments`
--
ALTER TABLE `tour_comments`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `tourId` (`tourId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `tour_places`
--
ALTER TABLE `tour_places`
  ADD PRIMARY KEY (`tourId`,`placeId`),
  ADD KEY `placeId` (`placeId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `CityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `custom_tours`
--
ALTER TABLE `custom_tours`
  MODIFY `tour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `place`
--
ALTER TABLE `place`
  MODIFY `placeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `reviewId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suggestedplaces`
--
ALTER TABLE `suggestedplaces`
  MODIFY `SuggestId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `tourId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tourguide`
--
ALTER TABLE `tourguide`
  MODIFY `guideId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tour_comments`
--
ALTER TABLE `tour_comments`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`tourId`) REFERENCES `tour` (`tourId`) ON DELETE CASCADE;

--
-- قيود الجداول `custom_tours`
--
ALTER TABLE `custom_tours`
  ADD CONSTRAINT `custom_tours_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`userId`),
  ADD CONSTRAINT `custom_tours_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`CityId`),
  ADD CONSTRAINT `custom_tours_ibfk_4` FOREIGN KEY (`place_id`) REFERENCES `place` (`placeId`);

--
-- قيود الجداول `place`
--
ALTER TABLE `place`
  ADD CONSTRAINT `FK_City_Place` FOREIGN KEY (`CityId`) REFERENCES `cities` (`CityId`);

--
-- قيود الجداول `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`guideId`) REFERENCES `tourguide` (`guideId`);

--
-- قيود الجداول `tour`
--
ALTER TABLE `tour`
  ADD CONSTRAINT `tour_ibfk_1` FOREIGN KEY (`guideId`) REFERENCES `tourguide` (`guideId`);

--
-- قيود الجداول `tour_comments`
--
ALTER TABLE `tour_comments`
  ADD CONSTRAINT `tour_comments_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tour` (`tourId`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_comments_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
