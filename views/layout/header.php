<html lang = "kr">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KJ7PSM9');</script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-L8L6BM7Z0H"></script>
    <!-- End Google Tag Manager -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-L8L6BM7Z0H');
    </script>


    <meta charset="utf-8">
    <meta content="minimum-scale=1.0, width=device-width, maximum-scale=1, user-scalable=no, viewport-fit=contain, initial-scale=1" name="viewport" />
    <meta name="description" content="DealightMe">
    <meta name="author" content="DealightMe">
    <meta name="generator" content="DealightMe">
    <!-- naver meta -->
    <meta name="google-site-verification" content="aJrCunRpuN1BWVc4M-EienUjgI1eg9K3_c0kogtUEo4" />
    <meta name="naver-site-verification" content="2743b2479810cd609b90b62d98d0d58482916e1c" />
    <title>DealightMe</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="/assets/manifest.json" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/assets/imgs/dealight_logo01.png" sizes="180x180">
    <link rel="icon" href="/assets/imgs/dealight_logo01.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/assets/imgs/dealight_logo01.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" />

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="/assets/theme/vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!-- <link href="/assets/theme/css/style.css" rel="stylesheet" id="style"> -->


    <link rel="stylesheet" href="/assets/theme/css/style.css">
    <link rel="stylesheet" href="/assets/css/site.css">
    <link rel="stylesheet" href="/assets/css/site2.css">
    <link rel="stylesheet" href="/assets/css/common.css">

    <style>
        html,body{
            min-height: 100vh;
            max-width: 640px;
            margin: auto;
            /* 모바일 선택동작 제거 */
            -webkit-touch-callout: none;
            -webkit-user-select:none;
            background: #ffffff !important;
        }
        .main-container{
            height: 100% !important;
            border-radius: 0 !important;
            background: #fff !important;
        }
        @media (prefers-color-scheme: dark) {
            body:not(div){
                background: #000 !important;
                color: #FFF !important;
            }
            body:not(.main-container){
                color: #000 !important;
            }
            body:not(main){
                color: #000 !important;
            }
        }
    </style>
    <script>
        var lan_type = "<?= $this->session->userdata('language')?>";
        //TODO :: 비회원일 경우는 별도의 세션을 두어 처리 되게끔 해야함. 따라서 기본적으로 유저 데이터가 아닌 별도의 세션으로 변경하여 처리 필요.

        if('<?= $this->session->userdata('g_manager') != null && $this->session->userdata('g_manager')->tp_language != null;?>' != ''){
            lan_type = "<?= $this->session->userdata('g_manager') != null ? $this->session->userdata('g_manager')->tp_language : "kr"?>";
        }
    </script>
</head>

<body class="body-scroll d-flex flex-column h-100" data-page="homepage">
