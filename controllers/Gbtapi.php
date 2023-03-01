<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gbtapi extends MY_Controller {
  protected $isCheckPrivilegeController = false;
  // protected $models = ['User', 'Verify', 'Basic', 'Userjob', 'LoginRecord', 'Payment', 'CardQna', 'Alarm', 'Mentoring', 'Card', 'ImitOral', 'Vita', 'PointVoucher', 'Point', 'Coupon'];
  public function __construct()
  {
    parent::__construct();
    $this->load->model("gbtapi/Gbtapi_Model");
    $this->load->model('payment/Payment_Model');

    $this->load->helper('string');
    $this->load->helper('date');
  }
  // 글로벌 틱스 API 로그인
  public function gbt_login(){
    $curl = curl_init();

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
    echo "엑세스토큰값: ".$obj->data->access_token; //엑세스 토큰 값
    $this->session->set_userdata('gbtapi', $obj->data->access_token);
    // echo $response;
  }
  //예약생성 트랜잭션 생성
  //https://sg-api.globaltix.com/api/transaction/create
  public function addTransaction($api_key,$buyer_email,$cnt_order,$idx_user){
    $token = $this->session->userdata('gbtapi'); //글로벌틱스 토큰
    // $api_key = $this->input->post('api_key'); //api key
    // $buyer_email = $this->input->post('buyer_email'); //구매자 이름
    // $cnt_order = $this->input->post('cnt_order'); // 개수
    // $idx_user = $this->session->userdata('g_manager')->idx_user;
    $curl = curl_init();
    if($this->session->has_userdata('gbtapi')){ //access_token 토큰 값이 있는지 체크
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sg-api.globaltix.com/api/transaction/create',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "ticketTypes": [{
          "index": 0,
          "id": '.$api_key.',
          "fromResellerId": null,
          "quantity": '.$cnt_order.'
        }],
        "customerName": "DealightMe Test",
        "email": '.$buyer_email.',
        "paymentMethod": "CREDIT"
      }',
        CURLOPT_HTTPHEADER => array(
          'Accept-Version: 1.0',
          'Authorization: Bearer '.$token,
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      $return_obj = json_decode($response);
      echo json_encode($return_obj);
    }else{
      $this->Gbtapi_Model->getApiLogin(); //api 키 없으면 로그인
      addTransaction();
    }
  }
  //예약생성 트랜잭션 생성
  //https://sg-api.globaltix.com/api/transaction/create
  public function addPayment(){
    $token = $this->session->userdata('gbtapi'); //글로벌틱스 토큰
    $api_key = $this->input->post('api_key'); //api key
    $buyer_email = $this->input->post('buyer_email'); //구매자 이름
    $cnt_order = $this->input->post('cnt_order'); // 개수
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $curl = curl_init();
    if($this->session->has_userdata('gbtapi')){ //access_token 토큰 값이 있는지 체크
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sg-api.globaltix.com/api/transaction/create',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
      	"ticketTypes": [{
      		"index": 0,
      		"id": '.$api_key.',
      		"fromResellerId": null,
      		"quantity": '.$cnt_order.'
      	}],
      	"customerName": "DealightMe Test",
      	"email": '.$buyer_email.',
      	"paymentMethod": "CREDIT"
      }',
        CURLOPT_HTTPHEADER => array(
          'Accept-Version: 1.0',
          'Authorization: Bearer '.$token,
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      $return_obj = json_decode($response);
      echo json_encode($return_obj);
    }else{
      $this->Gbtapi_Model->getApiLogin(); //api 키 없으면 로그인
      addPayment();
    }
  }
}
