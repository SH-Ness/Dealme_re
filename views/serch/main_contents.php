<style>
    *{font-family: Pretendard;}
    img{width:100%; height:auto;}
    body{background-color: #fff;padding-top: 60px;overflow: hidden;}
    header.header{
        /* right:0; right:constant(safe-area-inset-right); right:env(safe-area-inset-right); */
        /* height:40px !important; */
        background:#ffffff;
        max-width: 640px;
        position: relative !important;
        top: 0;
        z-index: 100;
        background-color: transparent;
        padding: 0px 0;
        margin: 0;
    }
    .header.active{background-color: #fff;}
    .row{align-items: center;z-index: 1;width: 95%;margin: auto;}
    .row2{height: 60px;width: 100%;position: absolute;top: 0;z-index: 2;background-color: transparent;}
    .search_box>.form-group{padding:0px; margin:0;}
    .is-invalid{height:40px;}
    .country_box {max-width: 100%;display: flex;height: 28px;padding: 5px 10px;border-radius: 14px;background-color: #eff2f5;}
    .country_profile {width: 20px;height: 20px;margin: 0 6px 0 0;}
    input#search_text {background-image: url(<?php echo SITE_ASSET_URL;?>/imgs/search.svg);background-position: 10px 10px;background-repeat: no-repeat;text-indent: 20px;border-radius: 5px;border: 0;background-color: #f3f3f3;border-bottom: 1px solid #ddd;border-radius: 0;}
    div._keyword{cursor : pointer;}
    .main-container2 {overflow-y: auto !important; overflow-x: hidden !important; padding-bottom: 80px;}
    .form-group{margin-bottom: 0;}
    #exampleModalScrollable .modal-content{background-color: rgba(255,255,255,1);}
    #search_screen_input .relation{padding-left:0;}
    .form-group img{width:20px;height:20px;margin-right: 0;}
    input#search_text{background-color: transparent;}
    .text-blue{color: blue;}
    ::-webkit-scrollbar-thumb{background:transparent;}
    /* #main_contents{height: calc(100% - 87px) !important;} */
    #main_contents .container:nth-of-type(n+4) {background: rgba(156, 147, 206, 0.14);padding-top: 20px;}
    #main_contents .container:nth-of-type(n+5) {background: rgba(156, 147, 206, 0.14);}
    /* #main_contents .container:last-child{padding-bottom: 80px !important;} */
    /* 2021-05-06 추가요청건 */
    #main_contents .sub_menu img{max-height: 75px;width: auto;}
    #main_contents .country_menu {display: flex;justify-content: space-between;flex-wrap: wrap;padding: 15px;}
    #main_contents .country_menu img{max-height: 75px;}
    #main_contents .country_card{display: flex;background-color: white;border-width: 0.5px;height: 80px;margin: 10px 0 0px;padding: 10px;position: relative;}
    #main_contents .country_card_title{width: 100% !important;white-space: pre-wrap;overflow: hidden;text-overflow: ellipsis;word-break: break-all;}
    #main_contents .country_desc{/* margin: auto 0; */}
    #main_contents .country_btns{height: 40px;justify-content: space-between;background-color: white;border-width: 0.5px;word-break: break-all;line-height: 40px;text-align: center;border: solid 1px #eee;border-radius: 10px;margin: 10px 0;font-size: 14px;font-weight: bold;}
    #main_contents .country_plus{width: 50px;text-align: center;background: #d3d3d3;color: white;font-size:40px;line-height: 100px;}
    #main_contents .country_card h2{width: 32px;height: 20px;margin: 20px 0 20px 0;font-family: Pretendard;font-size: 16px;font-weight: bold;font-stretch: normal;font-style: normal;line-height: 1.25;letter-spacing: normal;text-align: leftcolor: #111;}
    #main_contents .country_card img{width: 60px;height: 60px;margin-right: 16px;border-radius: 10px;}
    #main_contents .country_card .next_btn{position: absolute;right: 0;top: 0;bottom: 0;width: 20px;height: 20px;margin: auto;}
    #main_contents .country_card_title{width: 90%;margin: 12px 0px 0 0;font-family: Pretendard;font-size: 16px;font-weight: 600;  font-stretch: normal;font-style: normal;line-height: 1.25;letter-spacing: normal;color: #111;text-align: left;}
    #main_contents .country_card_desc{width: 137px;height: 16px;font-family: Pretendard;font-size: 12px;font-weight: normal;font-stretch: normal;font-style: normal;line-height: 1.33;letter-spacing: normal;color: #999;text-align: left;}
    #main_contents .country_right{width: 20pxheight: 20px;margin: auto 0 auto auto;object-fit: contain;}
    #main_contents .country_name{height: 14px;margin: 3px 0 3px 6px;font-family: Pretendard;font-size: 12px;font-weight: bold;font-stretch: normal;font-style: normal;line-height: 1.17;letter-spacing: normal;color: #111;word-break: keep-all;cursor: pointer;}

    #country_swiper .swiper-slide.swiper-slide-active:first-child{margin-left: 0;}
    #conutry_swiper .swiper-slide {width: auto !important;}
    #couponModal .close{font-size: 30px;font-weight: 400;}
    #couponModal .modal-header{border: 0;flex-direction: column;}
    #couponModal .modal-header p.modal-title{text-align: center;margin: 0 auto;}
    #couponModal .modal-content {position: absolute !important;background-color: #fefefe;margin: auto;width: 90% !important;right: 0;left: 0;top: 50%;height: 100%;box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);animation:none;}
    #couponModal .ticket-evaluation{text-align: center;}
    #couponModal .modal-footer{border: 0;}
    #couponModal .modal-title{font-weight: bold;text-align: center;}
    #couponModal .body-text{margin-top: 20px;font-weight: bold;}
    #couponModal .body-text a{color: #c5c5c5;}
    #couponModal .grade{color: #66629b;font-size: 46px;font-weight: bold;}
    #couponModal .imgGroup{margin-top: 30px;}
    .imgGroup img {width: 25px;}
    .review_img img{width: 25px;}
    #couponModal .modal-footer{display: flex;}
    #couponModal .check-button{background: #9c93ce;height: 60px;border: 0;width: 45%;}
    #couponModal .check-button:nth-of-type(1){background: #c5c5c5;}
    .swiper-pagination-fraction{color: #fff;background: rgba(0,0,0,0.4);width: auto !important;height: 24px;padding: 2px 15px;border-radius: 15px !important;right: 10px;margin: auto;left: auto !important;font-size: 14px;}
    span.swiper-pagination-total{opacity: 0.7;}
    .country-title{float:left;margin-right:5px;font-size: 20px;font-weight:bold;padding: 10px 0;}
    #main_contents .banner-header{padding: 10px 0;}
    .banner2 #last_swiper .swiper-slide:nth-child(1n){width: 45% !important;}
    #main_contents .banner2 .card-footer{height: auto;min-height: 40px;max-height: 114px;background-color: transparent;border-radius: 10px;}
    #main_contents .banner2 .card-footer h4{text-overflow: ellipsis;overflow: hidden;white-space: break-spaces;max-height: 100px;max-width: 100px;line-height: normal;margin: 0;}
    #main_contents .banner #last_swiper .swiper-slide:nth-child(1n) {width: 46% !important;padding-bottom: 0;margin-bottom: 10px;margin-right: 10px !important;}
    #main_contents .banner2 #last_swiper .swiper-slide:nth-child(1n) {margin-right: 10px !important;}

    /* 입력한 검색어 삭제버튼 */
    .search_del{font-size: 20px;width: 20px;height: 20px;background: #806eff;color: #fff;display: block;line-height: 18px;text-align: center;border-radius: 100%;}

    /* 언어 선택 토글 */
    .toggle-div {display: flex;}
    .toggle-div input {position: absolute !important; clip: rect(0, 0, 0, 0); height: 1px; width: 1px;}
    .toggle-div label {border-radius: 7px; font-weight: bold; text-align: center; padding: 0.5em 2em; background-color: #eff2f5;}
    .toggle-div label:hover {cursor: pointer;}
    .toggle-div input#r-en:checked+label, .toggle-div input#r-kr:checked+label {border-radius: 7px; background-color: #806eff; color: white;}
</style>

<header class="header">
    <div class="row top_menu" style="width:100%; max-width:640px; position:fixed; top:0px; background:#ffffff; padding:15px; z-index:999; align-items:center;height: 60px;">
        <div class="col align-self-center text-left">
            <a href="<?=base_url('/')?>" class="is-invalid text-left">
        <span class="icon text-left">
          <!-- <img src="/assets//imgs/logo_ko.svg" alt="한글로고" style="max-height:23px;"> -->
          <img src="/assets/imgs/logo_en.svg" alt="영어로고" style="max-height:24px;">
        </span>
            </a>
        </div>
        <div style="position:absolute; top:10px; right:10px;">
            <!-- 2022-04-20 : 언어 선택 토글 추가 -->
            <div class="toggle-div">
                <input type="radio" id="r-en" name="lang-toggle" value="en" <?= $this->session->userdata('g_manager')->tp_language == 'en' ? 'checked' : '' ?> />
                <label for="r-en">EN</label>
                <input type="radio" id="r-kr" name="lang-toggle" value="kr" <?= $this->session->userdata('g_manager')->tp_language == 'kr' ? 'checked' : '' ?> />
                <label for="r-kr">KR</label>
            </div>
</header>

<main class="main-container2" id = "main_contents" style="background: #fff;">
    <div class="container">
        <!-- area -->
        <div class="swiper-container swiper-users text-center swiper-container-horizontal" id = "first_swiper" style="height:180px;">
            <div class="swiper-wrapper area text-center">
                <?php if (!empty($banners)) {
                    foreach($banners as $row){ ?>
                        <div class="swiper-slide">
                            <div class="card" style="height:180px;">
                                <div class="card-body" style="cursor:pointer;">
                                    <a onclick="pagechange('<?=$row['text_bottom']?>','<?=$row['url_type']?>')" class="avatar avatar-60 rounded bg-default-light">
                                        <h4><?=$row['text_top']?></h4>
                                        <h6><?=str_replace('\n', '</br>', $row['text_middle'])?></h6>
                                        <img src="<?= SITE_ASSET_URL;?>/upload/banner/<?=$row['img_banner']?>" alt="" style="object-fit: cover;">
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
            <div class="swiper-pagination" style="bottom:10px !important;"></div>
        </div>
    </div>