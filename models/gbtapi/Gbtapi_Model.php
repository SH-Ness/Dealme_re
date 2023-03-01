<?php
class Gbtapi_Model extends CI_Model {
  public function __construct()
  {
    $this->load->database();
  }

  public function getApiLogin()
  {
    $curl = curl_init();
    // https://uat-api.globaltix.com/api/auth/login 기존 변경전
    // ID: reseller@globaltix.com
    // PW: 12345
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://sg-api.globaltix.com/api/auth/login',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
      "username": "R0056334PGAPI@globaltix.com",
      "password": "iH@%iPNR9uVQr8mo"
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $obj = json_decode($response);
    $this->session->set_userdata('gbtapi', $obj->data->access_token);
    if(!empty($param)){
      echo "<script>alert('엑세스 토큰을 가져왔습니다.');window.location.href = '$param';</script>";
    }
  }
  public function getCredit(){
    $token = $this->session->userdata('gbtapi');
    $curl = curl_init();
    if($this->session->has_userdata('gbtapi')){ //access_token 토큰 값이 있는지 체크
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sg-api.globaltix.com/api/credit/getCreditByReseller',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Accept-Version: 1.0',
          'Authorization: Bearer '.$token
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      $return_obj = json_decode($response);
      return $return_obj;
      // echo json_encode($return_obj);
    }else{
      $this->getApiLogin(); //api 키 없으면 로그인
    }
  }
  public function import_getToken(){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.iamport.kr/users/getToken',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
      "imp_key": "1836922725323585",
      "imp_secret": "XpUexqIEk5igkvZz6lRqVKZk9auz2wt0VnesOW782ppmVoW1KNrFyrZHBuhD0KDV8SoWOv4RVtdRS9TD"
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $obj = json_decode($response);
    // echo "엑세스토큰값: ".$obj->response->access_token; //엑세스 토큰 값
    $this->session->set_userdata('importapi', $obj->response->access_token);
    // echo $response;
  }
}
