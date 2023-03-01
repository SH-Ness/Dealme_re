<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
|  Google API Configuration
| -------------------------------------------------------------------
|
| To get API details you have to create a Google Project
| at Google API Console (https://console.developers.google.com)
|
|  client_id         string   Your Google API Client ID.
|  client_secret     string   Your Google API Client secret.
|  redirect_uri      string   URL to redirect back to after login.
|  application_name  string   Your Google application name.
|  api_key           string   Developer key.
|  scopes            string   Specify scopes
*/
$config['google_login']['client_id']        = '87075193321-rd2ghi8jmbgh01k7jcm30onksdqedud8.apps.googleusercontent.com';
$config['google_login']['client_secret']    = 'lgAxjubNkv8NFs9M496-DfLh';
$config['google_login']['redirect_uri']     = base_url().'sns_login/google';
$config['google_login']['authorize_url']    = "https://accounts.google.com/o/oauth2/auth";
$config['google_login']['token_url']        = "https://accounts.google.com/o/oauth2/token";
$config['google_login']['info_url']         = "https://www.googleapis.com/userinfo/v2/me";
$config['google_login']['token_request_post'] = TRUE;

// $config['naver_login']['client_id']         = "네아로 클라이언트 ID";
// $config['naver_login']['client_secret']     = "네아로 클라이언트 secret";
// $config['naver_login']['redirect_uri']  = "네아로 Redirect URI";
// $config['naver_login']['authorize_url'] = "https://nid.naver.com/oauth2.0/authorize";
// $config['naver_login']['token_url']     = "https://nid.naver.com/oauth2.0/token";
// $config['naver_login']['info_url']      = "https://openapi.naver.com/v1/nid/me";
// $config['naver_login']['token_request_post'] = FALSE;


$config['facebook_login']['client_id']  = "1508728219324153";      // 페이스북 앱 ID 입력
$config['facebook_login']['client_secret']= "b2d297a8c465839e750a1cf718693b82";   // 페이스북 앱 시크릿 코드
$config['facebook_login']['redirect_uri']   = base_url().'sns_login/facebook';
$config['facebook_login']['authorize_url']= "https://www.facebook.com/dialog/oauth";
$config['facebook_login']['token_url']  = "https://graph.facebook.com/v9.0/oauth/access_token";
$config['facebook_login']['info_url']       = "https://graph.facebook.com/v9.0/me";
$config['facebook_login']['token_request_post'] = FALSE;

$config['kakao_login']['client_id']     = "4ce424e94a71222b330439b3a383cc34";   // REST API 키를 입력
$config['kakao_login']['client_secret'] = "";   // 카카오는 Client Secret을 사용하지 않습니다. 공백으로 지정
$config['kakao_login']['redirect_uri']  = base_url().'/sns_login/kakao';
$config['kakao_login']['logout_redirect_uri']  = base_url().'/mypage/logout';
$config['kakao_login']['authorize_url'] = "https://kauth.kakao.com/oauth/authorize";
$config['kakao_login']['token_url']     = "https://kauth.kakao.com/oauth/token";
$config['kakao_login']['info_url']      = "https://kapi.kakao.com/v2/user/me";
$config['kakao_login']['token_request_post'] = FALSE;

?>
