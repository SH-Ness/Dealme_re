<style media="screen">
    .ftr{
        position: fixed;
        bottom: 70px;
        left: 0;
        /* right: 0; */
        /* background: #fff; */
        /* background: rgba(255,255,255,0.9); */
        z-index: 998;
        max-width: 500px;
        /* min-width: 500px; */
        margin: 0 auto;
    }
    .collapse.show{
        box-shadow: 0px -3px 4px -1px rgb(0 0 0 / 50%);
    }
    .ftr-btn{
        border: 0;
        border-radius: 0;
        /* padding: 10px; */
        margin: 0 !important;
        width: auto !important;
        padding: 0 10px !important;
    }
    /* .ftr-info-icon{
      width: 50px;
      height: 50px;
      background-color: #fff;
      box-shadow: 0 3px 6px 0 rgb(157 157 157 / 16%);
      border-radius: 100%;
    } */
    .ftr-btn img{
        max-height: 50px;
        max-width: 50px;
        min-width: 50px;
        background: #fff;
        border-radius: 100%;
        padding: 10px;
        box-shadow: 0 3px 6px 0 rgb(157 157 157 / 60%);
    }
    .btn-outline-secondary:hover{
        background: transparent;
        color: #555;
        /* box-shadow: 0px -3px 4px -1px rgb(0 0 0 / 50%); */
        border-top: 1px solid #ddd;
    }
    .ftr .card{
        background: transparent;
        box-shadow: none !important;
        -webkit-box-shadow:none !important;
    }
    .flex-wrap{
    .flex-wrap: wrap !important;
    }
    ul{
        list-style: none;
    }
    .ul-group{
        background: rgba(255,255,255,0.9);
    }
    .card .card-body{
        padding: 0 !important;
    }
    .ph-40{
        padding-top: 40px !important;
        padding-bottom: 40px !important;
    }
    .ph-0{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }
    .pt-0{
        padding-top: 0px !important;
    }
    .pa-20{
        padding: 20px !important;
    }
    li{
        line-height: 25px;
    }
    .ftr #collapseExample.collapse{overflow: scroll !important; max-height: 500px !important;}
</style>
<footer class="ftr">
    <div class="card ph-0 pt-0">
        <div class="card-body">
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    <div class="ul-group pa-20">
                        <img src="/assets/imgs/logo_en.svg" alt="영어로고" style="width: 100px; margin: 15px -5px;">
                        <ul class="flex-wrap" style="padding: 0; display:flex; cursor:pointer;">
                            <li><a target="_blank" data-i18n="lanstr.lcode_0108">서비스 소개</a> ㆍ </li>
                            <li><a href="/terms/terms_of_service" data-i18n="[html]lanstr.lcode_0103">이용약관</a> ㆍ </li>
                            <li><a href="/terms/protection_policy" data-i18n="lanstr.lcode_0104"></a></li>
                            <li><a href="/terms/location_info" data-i18n="[html]lanstr.lcode_0117"></a></li>
                            <li data-i18n="[html]lanstr.lcode_0105"></li>
                            <li><a href="mailto:support@dealightme.net" onclick="getEmail('mailto:support@dealightme.net')" target="_blank" data-i18n="[html]lanstr.lcode_0109"></a></li>
                        </ul>

                        <ul class="flex-wrap" style="padding: 0;">
                            <li data-i18n="lanstr.lcode_0110">상호: 주식회사 오픈베이</li>
                            <li data-i18n="[html]lanstr.lcode_0111">대표: 한태준 | 사업자등록번호: 106-86-82755</li>
                            <li data-i18n="[html]lanstr.lcode_0112">주소: 서울특별시 강남구 학동로 137-6</li>
                            <li onclick="gofooterPages('https://www.ftc.go.kr/bizCommPop.do?wrkr_no=1068682755')" data-i18n="[html]lanstr.lcode_0113">통신판매업: 2018-서울강남-00889</li>
                            <li data-i18n="lanstr.lcode_0114" onclick="getCall('02-3785-3670')">전화: 02-3785-3670</li>
                            <li data-i18n="lanstr.lcode_0115">이메일: <a href="mailto:support@dealightme.net"  class="text-blue">support@dealightme.net</a></li>
                        </ul>

                        <p class="text-secondary mt-3" data-i18n="lanstr.lcode_0116">
                            ㈜오픈베이는 통신판매중개자로서 통신판매의 당사자가 아니며
                            상품의 예약, 이용과 관련한 의무와 책임은 각 판매자에게 있습니다.<br>
                        </p>
                        <a href="#" class="text-secondary mt-3">ⒸOpenbay Co., Ltd., All Rights Reserved.</a>
                    </div>
                </div>
            </div>
            <div class="btn btn-sm mb-0 ftr-btn" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="width:100%; text-align: center;">
                <!-- DealightMe -->
                <a href="#!" class="ftr-info-icon">
                    <img src="/assets/imgs/ftr-info.svg" alt="푸터">
                </a>
            </div>
        </div>
    </div>
</footer>
<script src="/assets/theme/js/jquery-3.3.1.min.js"></script>
<script>
    // $(document).ready(function(){
    //
    //     setTimeout(function() {
    //       alert("test")
    //       link = $('#bizComm').attr('href');
    //       $('#bizComm').attr("onclick","gofooterPages("+link+")");
    //       $('#bizComm').attr("href","");
    //     }, 3000);
    //
    // });
    function getEmail(email){
        var isAndroid=(/android/i).test(navigator.userAgent); //현재기기가 안드인지 체크
        var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
        if(isAndroid){//안드가 맞다면
            window.androidBridge.call_email(email);
        }else if(varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 || varUA.indexOf("macintosh") > -1){
            console.log("IOS");
            window.webkit.messageHandlers.openMail.postMessage(email);
        }else{
            console.log('android 전용 기능');
        }
    }
    function gofooterPages(url){
        var isAndroid = (/android/i).test(navigator.userAgent); //현재기기가 안드인지 체크
        var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
        if(isAndroid){//안드가 맞다면
            window.androidBridge.goPages(url);
        }else if(varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 || varUA.indexOf("macintosh") > -1){
            console.log("IOS");
            window.webkit.messageHandlers.openUrl.postMessage(url);
        }else{
            location.href = url;
        }
    }
    function getCall(phone_num){
        var isAndroid = (/android/i).test(navigator.userAgent); //현재기기가 안드인지 체크
        var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
        if(isAndroid){//안드가 맞다면
            window.androidBridge.call_phone(phone_num);
        }else if(varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 || varUA.indexOf("macintosh") > -1){
            window.webkit.messageHandlers.openCall.postMessage(phone_num);
        }else{
            alert("지원되지 않는 기기입니다.");
        }
    }
</script>
