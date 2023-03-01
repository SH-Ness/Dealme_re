<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sns_login extends CI_Controller {
  public function __construct()
  {
     parent::__construct();
     $this->load->model('common/Member_Model');
     $this->load->helper('date');
  }

  public function naver()
  {
      $this->load->library("naver_login");
      $result = $this->naver_login->get_profile();

      print_r($result);
  }

  public function facebook()
  {
      $this->load->library("facebook_login");
      $result = $this->facebook_login->get_profile();
      echo json_encode($result);
      $current_datetime = date('Y-m-d H:i:s');
      $data =  $result;

      $user_data = array(
       'login_oauth_uid' => $data['id'],
       'first_name' => $data['name'],
       'last_name'  => $data['name'],
       'email_address' => $data['email']
      );

      $this->session->set_userdata('login_email', $data['email']);
      $this->session->set_userdata('user_data', $user_data);
      $user_row = $this->Member_Model->getItem2($data['email']);
      if(!isset($user_row)){
        $data2 = array(
            "email" => $data['email']
          , "nick_name" => $data['name']
          , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
          , "tp_login" => "facebook"
        );
        $this->Member_Model->insertMember($data2);
        if($this->db->insert_id() > 0){
          redirect('common/sign_up_language');
        }
      }else{
        $this->session->set_userdata('g_manager', $user_row);
        redirect('/');
      }
  }

  public function kakao()
  {
      $this->load->library("kakao_login");
      $result = $this->kakao_login->get_profile();

      // print_r($result);
      // echo json_encode($result);
      $current_datetime = date('Y-m-d H:i:s');
      $data =  $result;

      $user_data = array(
       'login_oauth_uid' => $data['id'],
       'first_name' => $data['kakao_account']['profile']['nickname'],
       'last_name'  => $data['kakao_account']['profile']['nickname'],
       'email_address' => $data['kakao_account']['email']
      );

      // echo json_encode($data,JSON_UNESCAPED_UNICODE);
      // 2021.10.20 로그인 시 email 데이터를 못가져왔을때 처리
      if(empty($data['kakao_account']['email'])){
        echo "<script>alert('카카오톡에서 등록된 이메일을 찾을 수 없습니다.');history.back();</script>";
        // redirect('/');
      }

      $this->session->set_userdata('login_email', $data['kakao_account']['email']);
      $this->session->set_userdata('user_data', $user_data);
      $user_row = $this->Member_Model->getItem2($data['kakao_account']['email']);
      if(!isset($user_row)){
        $data2 = array(
            "email" => $data['kakao_account']['email']
          , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
          , "tp_login" => "kakao"
        );
        $this->Member_Model->insertMember($data2);
        if($this->db->insert_id() > 0){
          redirect('common/sign_up_nickname');
        }
      }else{
        $this->session->set_userdata('g_manager', $user_row);
        if($user_row->tp_login != "kakao"){
          $login_data = array(
              "tp_login" => 'kakao'
          );
          $where = array(
              "idx_user" => $user_row->idx_user
          );
          $this->Member_Model->updateMember($login_data,$where);
        }
        if($user_row->agree_email == "N"){
          $data3 = array(
              "agree_email" => 'Y'
          );
          $where = array(
              "idx_user" => $user_row->idx_user
          );
          $this->Member_Model->updateMember($data3,$where);
          redirect('common/sign_up_nickname');
        }else{
          redirect('/');
        }
      }
  }

  public function kakao_webview(){
    $return_obj = array();
    $user_data = array(
     'login_oauth_uid' => $this->input->post('id'),
     'first_name' => $this->input->post('nickname'),
     'last_name'  => $this->input->post('nickname'),
     'email_address' => $this->input->post('email')
    );

    $this->session->set_userdata('login_email', $this->input->post('email'));
    $this->session->set_userdata('user_data', $user_data);
    $user_row = $this->Member_Model->getItem2($this->input->post('email'));
    if(!isset($user_row)){
      $data2 = array(
          "email" => $this->input->post('email')
        , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
        , "tp_login" => "kakao"
        , "nick_name" => $this->input->post('nickname')
        , "agree_email" => 'N'
      );
      echo json_encode($this->Member_Model->insertMember($data2));
    }else{
      $this->session->set_userdata('g_manager', $user_row);
      echo json_encode($result_array = array("rcode" => 1, "rmsg" => "emailCheck"));
    }
  }

  public function google()
  {
      $this->load->library("google_login");
      $result = $this->google_login->get_profile();

      // print_r($result);
      $current_datetime = date('Y-m-d H:i:s');
      $data =  json_decode($result);

      $user_data = array(
       'login_oauth_uid' => $data->id,
       'first_name' => $data->given_name,
       'last_name'  => $data->family_name,
       'email_address' => $data->email,
       'profile_picture'=> $data->picture,
       'updated_at' => $current_datetime
      );

      $this->session->set_userdata('login_email', $data->email);
      $this->session->set_userdata('user_data', $user_data);
      $user_row = $this->Member_Model->getItem2($data->email);
      if(!isset($user_row)){
        $data2 = array(
            "email" => $data->email
          , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
          , "tp_login" => "google"
        );
        $this->Member_Model->insertMember($data2);
        if($this->db->insert_id() > 0){
          redirect('common/sign_up_language');
        }
      }else{
        $this->session->set_userdata('g_manager', $user_row);

        redirect('/');
      }
  }
}
?>
