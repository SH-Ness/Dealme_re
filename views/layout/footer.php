<!-- Required jquery and libraries -->
<script src="/assets/theme/js/jquery-3.3.1.min.js"></script>
<!-- <script src="/assets/theme/js/popper.min.js"></script> -->
<script src="/assets/theme/vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- cookie js -->
<script src="/assets/theme/js/jquery.cookie.js"></script>
<!-- Swiper slider  js-->
<script src="/assets/theme/vendor/swiper/js/swiper.js"></script>
<!-- Customized jquery file  -->
<script src="/assets/theme/js/main.js"></script>
<script src="/assets/theme/js/color-scheme-demo.js"></script>
<!-- PWA app service registration and works -->
<!-- <script src="/assets/theme/js/pwa-services.js"></script> -->
<!-- page level custom script -->
<script src="/assets/theme/js/app.js"></script>
<script src="/assets/plugins/jquery.form.min.js"></script>
<script src="/assets/plugins/raty/jquery.raty.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- Required jquery and libraries --></body>

<!-- Facebook Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '885922318652842');
    fbq('track', 'PageView');

</script>
<!-- End Facebook Pixel Code -->
<script>
    $(function(){

    });

    function imgError(image){
        image.onerror = "";
        image.src = "/assets/imgs/noimg.png";
        console.log($(image).css("object-fit","none"));
        return true;
    }

    function formatDate(date, format) {
        const map = {
            mm: date.getMonth()+1 < 10 ? "0"+(date.getMonth()+1) : date.getMonth() + 1,
            dd: date.getDate(),
            // yy: date.getFullYear().toString().slice(-2),
            yyyy: date.getFullYear(),
            hh: date.getHours(),
            ii: date.getMinutes(),
            ss: date.getSeconds(),
        }
        return format.replace(/mm|dd|yyyy|hh|ii|ss/gi, matched => map[matched])
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/i18next-xhr-backend/3.2.2/i18nextXHRBackend.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/i18next/8.1.0/i18next.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-i18next/1.2.0/jquery-i18next.min.js" ></script>
<script>
    //https://dealightme.net/assets
    //'<?=base_url('common/selLang/{{lng}}/{{ns}}');?>'
    //'/assets/language/{{lng}}.json'
    // 71번째줄 $this->session->userdata('g_manager')->tp_language;



    i18next
        .use(i18nextXHRBackend)
        .init({
            lng: lan_type,
            backend: {
                loadPath: '<?=base_url('common/selLang/{{lng}}/{{ns}}');?>'
            }
        }, function(err, t) {
            jqueryI18next.init(i18next, $, {
                i18nName: 'i18next'
                ,fallbackLng: ["en", "kr"]
            });

            $(document).localize();

            $('.lang-select').click(function() {
                // debugger;
                console.info(this.innerHTML);
                i18next.changeLanguage(this.innerHTML, function(){
                    $(document).localize();
                });
            });
        });

</script>
</html>
