<style>
    /* #main_contents{height: calc(100% - 87px) !important;} */
    #main_contents .container:nth-of-type(n+4) {background: rgba(156, 147, 206, 0.14);padding-top: 20px;}
    #main_contents .container:nth-of-type(n+5) {background: rgba(156, 147, 206, 0.14);}
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
    /* 입력한 검색어 삭제버튼 */
    .search_del{font-size: 20px;width: 20px;height: 20px;background: #806eff;color: #fff;display: block;line-height: 18px;text-align: center;border-radius: 100%;}
    /* 언어 선택 토글 */
    .toggle-div {display: flex;}
    .toggle-div input {position: absolute !important; clip: rect(0, 0, 0, 0); height: 1px; width: 1px;}
    .toggle-div label {border-radius: 7px; font-weight: bold; text-align: center; padding: 0.5em 2em; background-color: #eff2f5;}
    .toggle-div label:hover {cursor: pointer;}
    .toggle-div input#r-en:checked+label, .toggle-div input#r-kr:checked+label {border-radius: 7px; background-color: #806eff; color: white;}

</style>
<header>
    <div class="row top_menu" style="width:100%; max-width:640px; position:fixed; top:0px; background:#ffffff; padding:15px; z-index:999; align-items:center;height: 60px;">
        <div class="col align-self-center text-left">
        <span class="icon text-left">
<img src="/assets/imgs/logo_en.svg" alt="영어로고" style="max-height: 23px;">
        </span>
        </div>

    <div class="toggle-div">
        <input type="radio" id="r-en" name="lang-toggle" value="en">
        <label for="r-en">EN</label>
        <input type="radio" id="r-kr" name="lang-toggle" value="kr">
        <label for="r-en">KR</label>
    </div>
    </div>


</header>
<main class="main-container2" id = "main_contents" style="background: #fff;">
    <div class="container">
        <!-- area -->
        <div class="swiper-container swiper-users text-center swiper-container-horizontal" id = "first_swiper" style="height:180px;">
            <div class="swiper-wrapper area text-center">
                <?php foreach($banners as $row){ ?>
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
                <?php } ?>
            </div>
            <div class="swiper-pagination" style="bottom:10px !important;"></div>
        </div>
    </div>
