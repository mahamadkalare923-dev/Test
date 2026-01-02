<?php
include 'config.php';
session_start();

// دەستکاری زمانی سایتی (default: Kurdish)
$language = isset($_GET['lang']) ? $_GET['lang'] : 'ku';

// ئەو زمانانەی سایتی پشتیوانی دەکات
$languages = ['ku', 'en', 'ar', 'fa', 'tr'];

// چالاککردنی ڕەنگی تەمە (ئۆتۆماتیک یان دەستی)
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'auto';

// داواکاری SQL
$query = mysqli_query($db, "SELECT * FROM `users` ORDER BY `id` DESC");

// پشتڕاستکردنەوەی ئەنجامی داواکاری
if ($query === false) {
    die("Error in query: " . mysqli_error($db));
}

// پشتڕاستکردنەوەی ئەنجامەکان
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>" dir="<?php echo in_array($language, ['ar', 'ku', 'fa']) ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="<?php echo htmlspecialchars($row['bio'] ?? 'پرۆفایلی کەسی'); ?>">
    <meta name="theme-color" content="#022">
    <meta property="og:title" content="<?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($row['bio'] ?? ''); ?>">
    <meta property="og:image" content="profile.png">
    
    <title><?php echo htmlspecialchars($row['title'] ?? $row['fname'] . ' ' . $row['lname']); ?></title>
    
    <!-- فۆنتەکان بەپێی زمان -->
    <?php if(in_array($language, ['ar', 'ku', 'fa'])): ?>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
        <style>:root { --font-family: 'Tajawal', sans-serif; }</style>
    <?php elseif($language == 'tr'): ?>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>:root { --font-family: 'Poppins', sans-serif; }</style>
    <?php else: ?>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>:root { --font-family: 'Inter', sans-serif; }</style>
    <?php endif; ?>
    
    <!-- ئایکۆنەکان -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- بوتستراپ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ستایلی تایبەت -->
    <style>
    :root {
        --primary-color: #17a2b8;
        --dark-bg: #022;
        --light-bg: #f8f9fa;
        --dark-text: #f0f0f0;
        --light-text: #333;
        --card-bg: rgba(255, 255, 255, 0.05);
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }
    
    [data-theme="light"] {
        --dark-bg: #f8f9fa;
        --light-bg: #022;
        --dark-text: #333;
        --light-text: #f0f0f0;
        --card-bg: rgba(0, 34, 34, 0.05);
    }
    
    /* ستایلی گشتی */
    body {
        font-family: var(--font-family);
        background: var(--dark-bg);
        color: var(--dark-text);
        direction: <?php echo in_array($language, ['ar', 'ku', 'fa']) ? 'rtl' : 'ltr'; ?>;
        padding: 0;
        margin: 0;
        transition: var(--transition);
        min-height: 100vh;
    }
    
    /* بارتەکانی سەرەوە */
    .top-bar {
        background: rgba(0, 34, 34, 0.9);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        position: sticky;
        top: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow);
    }
    
    .language-switcher, .theme-switcher {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .lang-btn, .theme-btn {
        background: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        padding: 5px 15px;
        border-radius: 20px;
        cursor: pointer;
        transition: var(--transition);
        font-size: 14px;
    }
    
    .lang-btn:hover, .theme-btn:hover {
        background: var(--primary-color);
        color: white;
    }
    
    .lang-btn.active {
        background: var(--primary-color);
        color: white;
    }
    
    /* کاوەر پڕۆفایل */
    .cover-container {
        position: relative;
        width: 100%;
        overflow: hidden;
    }
    
    .cover-img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .cover-container:hover .cover-img {
        transform: scale(1.02);
    }
    
    /* وێنەی پڕۆفایل */
    .profile-img-container {
        position: relative;
        width: 180px;
        height: 180px;
        margin: -90px auto 20px;
    }
    
    .profile-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 5px solid var(--dark-bg);
        box-shadow: var(--shadow);
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .profile-img:hover {
        transform: scale(1.05);
    }
    
    .online-status {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 20px;
        height: 20px;
        background: #4CAF50;
        border-radius: 50%;
        border: 3px solid var(--dark-bg);
    }
    
    /* ناو */
    .profile-name {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        background: linear-gradient(45deg, var(--primary-color), #8e44ad);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* بایۆ */
    .profile-bio {
        text-align: center;
        color: var(--primary-color);
        max-width: 700px;
        margin: 0 auto 30px;
        line-height: 1.8;
        padding: 0 20px;
        font-size: 1.1rem;
    }
    
    /* کارتەکانی زانیاری */
    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 20px;
    }
    
    .info-card {
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    
    .card-title {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* کۆنتەینەری سۆشیاڵ میدیا */
    .social-media-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    /* ڕیزەکانی ئایکۆنەکان */
    .icons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    /* ستایلی ئایکۆنەکان */
    .social-icon {
        display: block;
        text-decoration: none;
        text-align: center;
        transition: var(--transition);
    }
    
    .icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto 10px;
        position: relative;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        background: var(--card-bg);
    }
    
    .social-icon:hover .icon-wrapper {
        transform: translateY(-10px) rotate(5deg);
    }
    
    .social-icon i {
        font-size: 30px;
        transition: var(--transition);
    }
    
    .icon-text {
        font-size: 14px;
        font-weight: 500;
        color: var(--dark-text);
        transition: var(--transition);
    }
    
    .social-icon:hover .icon-text {
        color: var(--primary-color);
    }
    
    /* ڕەنگەکان بۆ هەر ئایکۆنێک */
    .facebook { color: #4267B2; }
    .twitter { color: #1DA1F2; }
    .instagram { color: #E1306C; }
    .snapchat { color: #FFFC00; }
    .youtube { color: #FF0000; }
    .linkedin { color: #2867B2; }
    .telegram { color: #0088cc; }
    .pinterest { color: #E60023; }
    .tiktok { color: #000000; }
    .github { color: #333; }
    .whatsapp { color: #25D366; }
    .discord { color: #5865F2; }
    
    /* زانیاری پەیوەندی */
    .contact-info {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        margin: 40px 20px;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--primary-color);
        text-decoration: none;
        padding: 12px 25px;
        background: var(--card-bg);
        border-radius: 50px;
        transition: var(--transition);
        border: 1px solid transparent;
    }
    
    .contact-item:hover {
        border-color: var(--primary-color);
        transform: translateY(-3px);
    }
    
    .contact-item i {
        font-size: 20px;
    }
    
    /* کاتی ڕاستەقینە و ڕۆژ */
    .time-date-container {
        background: var(--card-bg);
        padding: 25px;
        border-radius: 15px;
        margin: 40px auto;
        max-width: 500px;
        text-align: center;
        backdrop-filter: blur(10px);
    }
    
    #current-date {
        font-size: 1.2rem;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    #current-time {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--dark-text);
        font-family: monospace;
    }
    
    /* تەگەرەکانی زانیاری -->
    .skills-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .skill-item {
        margin-bottom: 20px;
    }
    
    .skill-name {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    
    .skill-bar {
        height: 10px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
        overflow: hidden;
    }
    
    .skill-progress {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color), #8e44ad);
        border-radius: 5px;
        transition: width 1s ease-in-out;
    }
    
    /* خوێندنەوەی QR کۆد -->
    .qr-container {
        text-align: center;
        margin: 40px auto;
        padding: 20px;
        background: var(--card-bg);
        border-radius: 15px;
        max-width: 300px;
    }
    
    .qr-code {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        background: white;
        padding: 10px;
        border-radius: 10px;
    }
    
    /* فۆتەر -->
    .footer {
        text-align: center;
        padding: 30px;
        margin-top: 50px;
        background: rgba(0, 34, 34, 0.8);
        color: var(--dark-text);
    }
    
    /* loading -->
    #loader {
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--dark-bg);
        transition: opacity 0.5s ease;
    }
    
    .loading-content {
        text-align: center;
    }
    
    .loading-img {
        width: 100px;
        height: 100px;
        margin-bottom: 20px;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    .loading-text {
        font-size: 1.2rem;
        margin-bottom: 15px;
        animation: blink 1.5s infinite;
    }
    
    .spinner {
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-top: 5px solid var(--primary-color);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        margin: 0 auto;
        animation: spin 1s linear infinite;
    }
    
    /* کۆد بۆ ئامێرە بچووکەکان */
    @media (max-width: 768px) {
        .cover-img {
            height: 200px;
        }
        
        .profile-img-container {
            width: 140px;
            height: 140px;
            margin-top: -70px;
        }
        
        .profile-name {
            font-size: 2rem;
        }
        
        .profile-bio {
            font-size: 1rem;
        }
        
        .icons-grid {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
        }
        
        .icon-wrapper {
            width: 70px;
            height: 70px;
        }
        
        .social-icon i {
            font-size: 25px;
        }
        
        .contact-info {
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        
        .contact-item {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
        
        #current-time {
            font-size: 2rem;
        }
        
        .top-bar {
            flex-direction: column;
            gap: 10px;
            padding: 15px;
        }
        
        .language-switcher, .theme-switcher {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .profile-name {
            font-size: 1.8rem;
        }
        
        .icons-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .info-cards {
            grid-template-columns: 1fr;
        }
        
        .icon-wrapper {
            width: 60px;
            height: 60px;
        }
        
        .social-icon i {
            font-size: 22px;
        }
        
        .icon-text {
            font-size: 12px;
        }
    }
    
    /* بۆ پرینت -->
    @media print {
        .top-bar,
        .social-media-container,
        .theme-btn,
        .lang-btn {
            display: none !important;
        }
        
        body {
            background: white !important;
            color: black !important;
        }
        
        .profile-img {
            border-color: white !important;
        }
    }
    
    /* بۆ دەستکاریکردنی خێرایی ئینتەرنێت -->
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
    </style>
</head>
<body data-theme="<?php echo $theme; ?>">

<!-- بارتەکانی سەرەوە -->
<div class="top-bar">
    <div class="language-switcher">
        <span><?php echo $language == 'ku' ? 'زمان:' : ($language == 'ar' ? 'اللغة:' : ($language == 'fa' ? 'زبان:' : ($language == 'tr' ? 'Dil:' : 'Language:'))); ?></span>
        <?php foreach($languages as $lang): ?>
            <button class="lang-btn <?php echo $language == $lang ? 'active' : ''; ?>" 
                    onclick="changeLanguage('<?php echo $lang; ?>')">
                <?php echo strtoupper($lang); ?>
            </button>
        <?php endforeach; ?>
    </div>
    
    <div class="theme-switcher">
        <button class="theme-btn" onclick="toggleTheme()">
            <i class="fas fa-moon"></i> 
            <?php echo $language == 'ku' ? 'ڕەنگی تەمە' : ($language == 'ar' ? 'المظهر' : ($language == 'fa' ? 'حالت تاریک' : ($language == 'tr' ? 'Tema' : 'Theme'))); ?>
        </button>
        <button class="theme-btn" onclick="toggleAutoTheme()">
            <i class="fas fa-robot"></i> Auto
        </button>
    </div>
</div>

<!-- loading -->
<div id="loader">
    <div class="loading-content">
        <i class="fas fa-user-circle loading-img" style="font-size: 100px; color: var(--primary-color);"></i>
        <p class="loading-text">
            <?php echo $language == 'ku' ? 'چاوەروان بکە...' : ($language == 'ar' ? 'جاري التحميل...' : ($language == 'fa' ? 'لطفا صبر کنید...' : ($language == 'tr' ? 'Yükleniyor...' : 'Loading...'))); ?>
        </p>
        <div class="spinner"></div>
    </div>
</div>

<div class="container-fluid p-0">
    <!-- وێنەی کاوەر -->
    <div class="cover-container">
        <img src="image/cover2.png" class="cover-img" alt="کاوەر پڕۆفایل" onerror="this.src='https://via.placeholder.com/1200x300/022/17a2b8?text=Profile+Cover'">
    </div>
    
    <!-- وێنەی پڕۆفایل -->
    <div class="profile-img-container">
        <img src="profile.png" class="profile-img" alt="وێنەی پڕۆفایل" onerror="this.src='https://via.placeholder.com/180/022/17a2b8?text=Profile'">
        <div class="online-status" title="<?php echo $language == 'ku' ? 'سەرلاین' : 'Online'; ?>"></div>
    </div>
    
    <!-- ناو و بایۆ -->
    <h1 class="profile-name"><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></h1>
    
    <p class="profile-bio">
        <?php 
        $bios = [
            'ku' => "ڕۆژێ قەدرم دەزانی، بەڵام شوێنم نازانیت<br>زانکۆی گەرمیان بەشی ئەندازیاری نێتۆرک - IT",
            'en' => "You know my value one day, but you don't know my place<br>Garmian University, Network Engineering - IT Department",
            'ar' => "ستعرف قيمتي يوما، لكنك لا تعرف مكاني<br>جامعة كرميان، قسم هندسة الشبكات - تكنولوجيا المعلومات",
            'fa' => "روزی ارزشم را می‌دانی، اما جایم را نمی‌دانی<br>دانشگاه گرمیان، رشته مهندسی شبکه - فناوری اطلاعات",
            'tr' => "Bir gün değerimi bilirsin ama yerimi bilmezsin<br>Garmian Üniversitesi, Ağ Mühendisliği - BT Bölümü"
        ];
        echo $bios[$language] ?? $bios['ku'];
        ?>
    </p>
    
    <!-- کارتەکانی زانیاری -->
    <div class="info-cards">
        <div class="info-card">
            <h3 class="card-title">
                <i class="fas fa-graduation-cap"></i>
                <?php echo $language == 'ku' ? 'خوێندن' : ($language == 'ar' ? 'التعليم' : ($language == 'fa' ? 'تحصیلات' : ($language == 'tr' ? 'Eğitim' : 'Education'))); ?>
            </h3>
            <p><?php echo $language == 'ku' ? 'زانکۆی گەرمیان - ئەندازیاری نێتۆرک' : 'Garmian University - Network Engineering'; ?></p>
        </div>
        
        <div class="info-card">
            <h3 class="card-title">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo $language == 'ku' ? 'شوێن' : ($language == 'ar' ? 'الموقع' : ($language == 'fa' ? 'موقعیت' : ($language == 'tr' ? 'Konum' : 'Location'))); ?>
            </h3>
            <p><?php echo $language == 'ku' ? 'هەرێمی کوردستان - عێراق' : 'Kurdistan Region - Iraq'; ?></p>
        </div>
        
        <div class="info-card">
            <h3 class="card-title">
                <i class="fas fa-code"></i>
                <?php echo $language == 'ku' ? 'توانایەکان' : ($language == 'ar' ? 'المهارات' : ($language == 'fa' ? 'مهارت‌ها' : ($language == 'tr' ? 'Yetenekler' : 'Skills'))); ?>
            </h3>
            <p>PHP, MySQL, HTML/CSS, JavaScript, Network Security</p>
        </div>
    </div>
    
    <!-- ئایکۆنەکانی سۆشیاڵ میدیا -->
    <div class="social-media-container">
        <h2 class="text-center mb-4" style="color: var(--primary-color);">
            <i class="fas fa-share-alt"></i>
            <?php echo $language == 'ku' ? 'پەیوەندی کۆمەڵایەتی' : ($language == 'ar' ? 'وسائل التواصل الاجتماعي' : ($language == 'fa' ? 'شبکه‌های اجتماعی' : ($language == 'tr' ? 'Sosyal Medya' : 'Social Media'))); ?>
        </h2>
        
        <div class="icons-grid">
            <?php
            $socialLinks = [
                ['facebook', 'https://m.facebook.com/mahamadmahmood68', 'Facebook'],
                ['twitter', 'https://mobile.twitter.com/mahamadmahmod68', 'Twitter'],
                ['instagram', 'https://instagram.com/mahamadmahmood68', 'Instagram'],
                ['snapchat', 'https://t.snapchat.com/WvLzYWff', 'Snapchat'],
                ['youtube', 'https://m.youtube.com/mahamadmahmood', 'YouTube'],
                ['linkedin', 'https://www.linkedin.com/in/mahamad-mahmood-1ab01b153', 'LinkedIn'],
                ['telegram', 'https://t.me/mahamadmahmood68', 'Telegram'],
                ['pinterest', 'https://www.pinterest.com/mahamadmahmood68', 'Pinterest'],
                ['tiktok', 'https://www.tiktok.com/@mahamadmahmood68', 'TikTok'],
                ['github', 'https://github.com/MahamadMahmood', 'GitHub'],
                ['whatsapp', 'https://wa.me/<?php echo $row[\'phone_number\']; ?>', 'WhatsApp'],
                ['discord', '#', 'Discord']
            ];
            
            foreach($socialLinks as $link):
                if($link[1] != '#' && !empty($link[1])):
            ?>
            <a href="<?php echo $link[1]; ?>" class="social-icon" target="_blank" rel="noopener noreferrer">
                <div class="icon-wrapper <?php echo $link[0]; ?>">
                    <i class="fab fa-<?php echo $link[0]; ?>"></i>
                </div>
                <div class="icon-text"><?php echo $link[2]; ?></div>
            </a>
            <?php
                endif;
            endforeach;
            ?>
        </div>
    </div>
    
    <!-- تەگەرەکانی زانیاری -->
    <div class="skills-container">
        <h2 class="text-center mb-4" style="color: var(--primary-color);">
            <i class="fas fa-chart-bar"></i>
            <?php echo $language == 'ku' ? 'توانایەکان' : ($language == 'ar' ? 'المهارات' : ($language == 'fa' ? 'مهارت‌ها' : ($language == 'tr' ? 'Yetenekler' : 'Skills'))); ?>
        </h2>
        
        <?php
        $skills = [
            ['PHP', 90],
            ['MySQL', 85],
            ['HTML/CSS', 95],
            ['JavaScript', 80],
            ['Network Security', 75],
            ['Web Development', 88]
        ];
        
        foreach($skills as $skill):
        ?>
        <div class="skill-item">
            <div class="skill-name">
                <span><?php echo $skill[0]; ?></span>
                <span><?php echo $skill[1]; ?>%</span>
            </div>
            <div class="skill-bar">
                <div class="skill-progress" style="width: <?php echo $skill[1]; ?>%"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- زانیاری پەیوەندی -->
    <div class="contact-info">
        <a href="#" class="contact-item">
            <i class="fas fa-user"></i>
            <?php echo htmlspecialchars($row['username']); ?>
        </a>
        
        <a href="mailto:<?php echo $row['config_email']; ?>" class="contact-item">
            <i class="fas fa-envelope"></i>
            <?php echo htmlspecialchars($row['config_email']); ?>
        </a>
        
        <a href="tel:<?php echo $row['phone_number']; ?>" class="contact-item">
            <i class="fas fa-phone"></i>
            <?php echo htmlspecialchars($row['phone_number']); ?>
        </a>
        
        <a href="https://maps.google.com/?q=Garmian+University" class="contact-item" target="_blank">
            <i class="fas fa-map-marker-alt"></i>
            <?php echo $language == 'ku' ? 'شوێن' : 'Location'; ?>
        </a>
    </div>
    
    <!-- کاتی ڕاستەقینە و ڕۆژ -->
    <div class="time-date-container">
        <div id="current-date"></div>
        <div id="current-time"></div>
        <div id="current-timezone" class="mt-2" style="color: var(--primary-color); font-size: 0.9rem;"></div>
    </div>
    
    <!-- QR کۆد -->
    <div class="qr-container">
        <h3 style="color: var(--primary-color); margin-bottom: 15px;">
            <i class="fas fa-qrcode"></i>
            <?php echo $language == 'ku' ? 'QR کۆد' : 'QR Code'; ?>
        </h3>
        <div class="qr-code">
            <!-- لێرە دەتوانیت QR کۆدێک دانێیت بۆ پرۆفایلەکەت -->
            <canvas id="qrCanvas"></canvas>
        </div>
        <p class="mt-3" style="color: var(--dark-text);">
            <?php echo $language == 'ku' ? 'سکەین بکە بۆ بینینی پرۆفایل' : 'Scan to view profile'; ?>
        </p>
    </div>
</div>

<!-- فۆتەر -->
<footer class="footer">
    <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></p>
    <p class="mt-2" style="opacity: 0.8; font-size: 0.9rem;">
        <?php echo $language == 'ku' ? 'هەموو مافەکان پارێزراون' : 'All rights reserved'; ?>
    </p>
    <p style="opacity: 0.6; font-size: 0.8rem; margin-top: 10px;">
        <?php echo $language == 'ku' ? 'دروستکراوە بە PHP و MySQL' : 'Made with PHP & MySQL'; ?>
    </p>
</footer>

<!-- سکریپتەکان -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.0/build/qrcode.min.js"></script>
<script>
// زمانی سیستەم
const translations = {
    ku: {
        online: "سەرلاین",
        offline: "ئۆفلاین",
        darkMode: "ڕەنگی تەمە",
        lightMode: "ڕەنگی ڕووناک"
    },
    en: {
        online: "Online",
        offline: "Offline",
        darkMode: "Dark Mode",
        lightMode: "Light Mode"
    },
    ar: {
        online: "متصل",
        offline: "غير متصل",
        darkMode: "الوضع الداكن",
        lightMode: "الوضع المضيء"
    },
    fa: {
        online: "آنلاین",
        offline: "آفلاین",
        darkMode: "حالت تاریک",
        lightMode: "حالت روشن"
    },
    tr: {
        online: "Çevrimiçi",
        offline: "Çevrimdışı",
        darkMode: "Karanlık Mod",
        lightMode: "Aydınlık Mod"
    }
};

// گۆڕینی زمان
function changeLanguage(lang) {
    document.cookie = `language=${lang}; path=/; max-age=31536000`;
    window.location.href = `?lang=${lang}`;
}

// گۆڕینی ڕەنگی تەمە
function toggleTheme() {
    const body = document.body;
    const currentTheme = body.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    body.setAttribute('data-theme', newTheme);
    document.cookie = `theme=${newTheme}; path=/; max-age=31536000`;
    
    updateThemeButton(newTheme);
}

// ئۆتۆماتیک ڕەنگی تەمە
function toggleAutoTheme() {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const newTheme = prefersDark ? 'dark' : 'light';
    
    document.body.setAttribute('data-theme', 'auto');
    document.cookie = `theme=auto; path=/; max-age=31536000`;
    
    // دیاریکردنی ڕەنگی تەمە بەپێی سیستەم
    updateThemeBasedOnSystem();
}

// نوێکردنەوەی ڕەنگی تەمە بەپێی سیستەم
function updateThemeBasedOnSystem() {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const currentTheme = document.body.getAttribute('data-theme');
    
    if(currentTheme === 'auto') {
        document.body.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
    }
}

// نوێکردنەوەی دوگمەی ڕەنگی تەمە
function updateThemeButton(theme) {
    const themeBtn = document.querySelector('.theme-btn');
    const lang = '<?php echo $language; ?>';
    
    if(themeBtn) {
        const isDark = theme === 'dark';
        themeBtn.innerHTML = `<i class="fas fa-${isDark ? 'sun' : 'moon'}"></i> ${translations[lang]?.[isDark ? 'lightMode' : 'darkMode'] || (isDark ? 'Light Mode' : 'Dark Mode')}`;
    }
}

// کات و ڕۆژ
function updateDateTime() {
    const now = new Date();
    const lang = '<?php echo $language; ?>';
    
    // ڕۆژ
    const dateOptions = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    
    let locale;
    switch(lang) {
        case 'ku': locale = 'ar-IQ'; break;
        case 'ar': locale = 'ar-SA'; break;
        case 'fa': locale = 'fa-IR'; break;
        case 'tr': locale = 'tr-TR'; break;
        default: locale = 'en-US';
    }
    
    document.getElementById('current-date').textContent = 
        now.toLocaleDateString(locale, dateOptions);
    
    // کات
    const timeOptions = { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit',
        hour12: lang !== 'ku' && lang !== 'tr' && lang !== 'en'
    };
    
    document.getElementById('current-time').textContent = 
        now.toLocaleTimeString(locale, timeOptions);
    
    // ناوچەی کاتی
    document.getElementById('current-timezone').textContent = 
        Intl.DateTimeFormat().resolvedOptions().timeZone;
}

// QR کۆد دروستکردن
function generateQRCode() {
    const canvas = document.getElementById('qrCanvas');
    const profileUrl = window.location.href;
    
    QRCode.toCanvas(canvas, profileUrl, {
        width: 180,
        margin: 1,
        color: {
            dark: '#022',
            light: '#FFFFFF'
        }
    }, function(error) {
        if(error) {
            console.error('QR Code generation error:', error);
            canvas.parentElement.innerHTML = '<p style="color: red;">QR Code generation failed</p>';
        }
    });
}

// نیشاندانی توانایەکان
function animateSkills() {
    const skillBars = document.querySelectorAll('.skill-progress');
    skillBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 300);
    });
}

// loading hide
window.addEventListener('load', function() {
    const loader = document.getElementById('loader');
    
    // 1.5 چرکە چاوەڕوان بکە بۆ نموونەیی
    setTimeout(() => {
        loader.style.opacity = '0';
        setTimeout(() => {
            loader.style.display = 'none';
            animateSkills();
            generateQRCode();
        }, 500);
    }, 1500);
    
    // سەرلاین نیشاندان
    updateOnlineStatus();
});

// سەرلاین نیشاندان
function updateOnlineStatus() {
    const statusElement = document.querySelector('.online-status');
    const lang = '<?php echo $language; ?>';
    
    if(navigator.onLine) {
        statusElement.style.background = '#4CAF50';
        statusElement.title = translations[lang]?.online || 'Online';
    } else {
        statusElement.style.background = '#f44336';
        statusElement.title = translations[lang]?.offline || 'Offline';
    }
}

// چاودێریکردنی پەیوەندی ئینتەرنێت
window.addEventListener('online', updateOnlineStatus);
window.addEventListener('offline', updateOnlineStatus);

// چاودێریکردنی گۆڕینی ڕەنگی تەمەی سیستەم
window.matchMedia('(prefers-color-scheme: dark)').addListener(updateThemeBasedOnSystem);

// نوێکردنەوەی کات
setInterval(updateDateTime, 1000);
updateDateTime();

// دەستکاری بەکارهێنەر بۆ فڕینی ناو
document.addEventListener('DOMContentLoaded', function() {
    const nameElement = document.querySelector('.profile-name');
    nameElement.style.opacity = '0';
    nameElement.style.transition = 'opacity 1s ease-in-out';
    
    setTimeout(() => {
        nameElement.style.opacity = '1';
    }, 500);
    
    // دەستکاری بۆ کلیک کردن لەسەر پەیوەندیکان
    document.querySelectorAll('a[href^="http"]').forEach(link => {
        link.setAttribute('target', '_blank');
        link.setAttribute('rel', 'noopener noreferrer');
    });
});

// Share API (ئەگەر پشتگیری بکرێت)
function shareProfile() {
    if(navigator.share) {
        navigator.share({
            title: '<?php echo htmlspecialchars($row["fname"] . " " . $row["lname"]); ?>',
            text: '<?php echo $language == "ku" ? "سەیری پرۆفایلی من بکە" : "Check out my profile"; ?>',
            url: window.location.href
        })
        .then(() => console.log('Successful share'))
        .catch(error => console.log('Error sharing:', error));
    }
}

// پشتیوانی کردن لە Service Worker بۆ PWA
if('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
        .then(registration => {
            console.log('ServiceWorker registered');
        })
        .catch(err => {
            console.log('ServiceWorker registration failed:', err);
        });
    });
}
</script>

<?php
} else {
    echo "<div style='text-align:center; padding:50px;'>No records found</div>";
}
mysqli_close($db);
?>
</body>
</html>