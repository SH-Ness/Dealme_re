<style media="screen">
    *{
        font-family: Pretendard;
    }
    h5{
        color:#111;
    }
    .header{
        /* padding: 0 0 calc(constant(safe-area-inset-top) + 5px);
        padding: 0 0 calc(env(safe-area-inset-top) + 5px); */
        width:100%;
        max-width:640px;
        position:fixed;
        top:0px;
        background:#ffffff;
        color: #000 !important;
        padding:15px;
        z-index:999;
        align-items:center;
        height: 60px;
        /* margin-top:40px; */
    }
</style>
<header class="header">
    <div class="row">
        <div class="text-left col align-self-center">
            <button class="btn" type="button" style="padding: 0 !important;">
                <a href="javascript:dmhistoryback();" style="margin-right: 10px;">
                    <img src="/assets/imgs/arrow-black.svg" alt="">
                </a>
                <a class="navbar-brand" href="<?=base_url('/')?>">
                    <h4 class="page-title mb-0" style="font-size:20px;"><?=$top_menu_title;?></h4>
                </a>
            </button>
        </div>
    </div>
</header>
<script>
    function dmhistoryback(){
        history.back();
        //alert(history.length);
        /*
        if(e.persisted) {

        }else{
          history.back();
        }
        */

    }
</script>
