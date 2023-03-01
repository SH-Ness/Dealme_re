<style media="screen">
    .bottom_menu{
        max-width: 500px;
        margin: 0 auto;
        right: 0;
        left: 0;
        width:100%;
        position:fixed;
        bottom:0;
        background: #ffffff;
        padding:10px 15px;
        z-index:999;
        border-top: 1px solid #ddd;
        height: 60px;
        /* padding: 10px 20px 60px 20px; */
        /* padding: 10px 20px calc(constant(safe-area-inset-bottom) + 40px);
        padding: 10px 20px calc(env(safe-area-inset-bottom) + 40px); */
    }
    .text-click{
        color: #806eff;
        font-weight: bold;
    }
</style>
<div class="bottom_menu">
    <ul class="nav" style="display:flex; justify-content:space-between;">
        <?php if (!empty($bottom_search_btn_use)) {
            if($bottom_search_btn_use){ ?>
                <li class="nav-item">
                    <a href="<?=base_url('/search/main_contents')?>" data-transition="slide">
                        <?php if($bottom_search_img1_src){ ?>
                            <span class="icon text-center" style="display:grid;">
                <img src="/assets/imgs/home-icon-g.svg" alt="홈" style="width: 24px; margin: auto;">
                                <!-- <span class="text-click" style="color:#ddd;" data-i18n="lanstr.lcode_0200"></span> -->
                <span class="text-click" style="color:#ddd;" data-i18n="lanstr.lcode_0200">
                    <?= $this->session->userdata('g_manager')->tp_language == 'kr' ? '홈' : 'Home' ?>
                </span>
              </span>
                        <?php } ?>
                        <?php if($bottom_search_img2_src){ ?>
                            <span class="icon text-center" style="display:grid;">
                <img src="/assets/imgs/home-icon-p.svg" alt="홈" style="width: 24px; margin: auto;">
                                <!-- <span class="text-click" data-i18n="lanstr.lcode_0200"></span> -->
                <span class="text-click">
                    <?= $this->session->userdata('g_manager')->tp_language == 'kr' ? '홈' : 'Home' ?>
                </span>
              </span>
                        <?php } ?>
                    </a>
                </li>
            <?php }
        } ?>
        <?php if($bottom_community_btn_use){ ?>
            <li class="nav-item page-ani">
                <a href="<?=base_url('search/main_search_screen')?>" data-transition="slide">
                    <?php if($bottom_community_img1_src){ ?>
                        <span class="icon text-center" style="display:grid;">
          <img src="/assets/imgs/search-icon-g.svg" alt="검색" style="margin: auto; width:24px;">
                            <!-- <span class="text-click" style="color:#ddd;" data-i18n="lanstr.lcode_0201"></span> -->
          <span class="text-click" style="color:#ddd;">
              <?= $this->session->userdata('g_manager')->tp_language == 'kr' ? '검색' : 'Search' ?>
          </span>
        </span>
                    <?php } ?>
                    <?php if($bottom_community_img2_src){ ?>
                        <span class="icon text-center" style="display:grid;">
          <img src="/assets/imgs/search-icon-p.svg" alt="검색" style="margin: auto; width:24px;">
                            <!-- <span class="text-click" data-i18n="lanstr.lcode_0201"></span> -->
          <span class="text-click">
              <?= $this->session->userdata('g_manager')->tp_language == 'kr' ? '검색' : 'Search' ?>
          </span>
        </span>
                    <?php } ?>
                </a>
            </li>
        <?php } ?>
        <!-- <?php if($bottom_community_btn_use){ ?>
    <li class="nav-item" style="padding:10px;">
      <a href="<?=base_url('community/magazine')?>">
        <?php if($bottom_community_img1_src){ ?>
        <span class="icon">
          <img src="/assets/imgs/speech bubble.svg" alt="커뮤니티">
        </span>
        <?php } ?>
        <?php if($bottom_community_img2_src){ ?>
        <span class="icon">
          <img src="/assets/imgs/speech-bubble-active.svg" alt="커뮤니티">
        </span>
        <?php } ?>
      </a>
    </li>
    <?php } ?> -->
        <?php if($bottom_ticket_btn_use){ ?>
            <li class="nav-item page-ani">
                <a href="<?=base_url('ticket/coupon_box')?>" data-transition="slide">
                    <?php if($bottom_ticket_img1_src){ ?>
                        <span class="icon text-center" style="display:grid;">
          <img src="/assets/imgs/coupon-icon-g.svg" alt="쿠폰" style="margin: auto; width:24px;">
                            <!-- <span class="text-click" style="color:#ddd;" data-i18n="lanstr.lcode_0202"></span> -->
          <span class="text-click" style="color:#ddd;">
              <?= $this->session->userdata('g_manager')->tp_language == 'kr' ? '쿠폰' : 'Coupon' ?>
          </span>
        </span>
                    <?php } ?>
                    <?php if($bottom_ticket_img2_src){ ?>
                        <span class="icon text-center" style="display:grid;">
          <img src="<?php echo SITE_ASSET_URL;?>/imgs/coupon-icon-p.svg" alt="쿠폰" style="margin: auto; width:24px;">
                            <!-- <span class="text-click" data-i18n="lanstr.lcode_0202"></span> -->
          <span class="text-click">
              <?= $this->session->userdata('g_manager')->tp_language == 'kr' ? '쿠폰' : 'Coupon' ?>
          </span>
        </span>
                    <?php } ?>
                </a>
            </li>
        <?php } ?>
        <!-- <?php if($bottom_card_btn_use){ ?>
    <li class="nav-item" style="padding:10px;">
      <a href="<?=base_url('mycard/mycard_main')?>">
        <?php if($bottom_card_img1_src){ ?>
        <span class="icon">
          <img src="<?php echo SITE_ASSET_URL;?>/imgs/Payment2.png" alt="카드">
        </span>
        <?php } ?>
        <?php if($bottom_card_img2_src){ ?>
        <span class="icon">
          <img src="<?php echo SITE_ASSET_URL;?>/imgs/Payment.png" alt="카드">
        </span>
        <?php } ?>
      </a>
    </li>
    <?php } ?> -->
        <?php if($bottom_myprofile_btn_use){ ?>
            <li class="nav-item page-ani">
                <a href="<?=base_url('mypage/my_page_main')?>" data-transition="slide">
                    <?php if($bottom_myprofile_img1_src){ ?>
                        <span class="icon text-center" style="display:grid;">
          <img src="<?php echo SITE_ASSET_URL;?>/imgs/mypage-icon-g.svg" alt="마이페이지" style=" width:24px; margin:auto;">
                            <!-- <span class="text-click" style="color:#ddd;" data-i18n="lanstr.lcode_0203"></span> -->
          <span class="text-click" style="color:#ddd;">
              <?= $this->session->userdata('g_manager')->tp_language == 'kr' ? '마이페이지' : 'My page' ?>
          </span>
        </span>
                    <?php } ?>
                    <?php if($bottom_myprofile_img2_src){ ?>
                        <span class="icon text-center" style="display:grid;">
          <img src="<?php echo SITE_ASSET_URL;?>/imgs/mypage-icon-p.svg" alt="마이페이지" style=" width:24px; margin:auto;">
                            <!-- <span class="text-click" data-i18n="lanstr.lcode_0203"></span> -->
          <span class="text-click">
              <?= $this->session->userdata('g_manager')->tp_language == 'kr' ? '마이페이지' : 'My page' ?>
          </span>
        </span>
                    <?php } ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>

<script type="text/javascript">
    $('a').hover(
        function () {
            $(this).stop().animate({opacity : '.5'}, 500); },
        function () { $(this).stop().animate({opacity : '1'}, 500);
        });
</script>
