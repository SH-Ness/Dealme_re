<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends MY_Controller {
  protected $isCheckPrivilegeController = false;
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

  public function __construct()
  {
    parent::__construct();
    $this->load->model('common/Member_Model');
    $this->load->model('file/File_Model');

    if (isset($this->session->userdata('g_manager')->idx_user)) {
      $signItem = array('pw_reset','sign_up_language','sign_up_nationality','sign_up_region','sign_up_nickname','sign_up_register_picture','appLogin');
        if (!$this->input->is_ajax_request() && !in_array(basename($_SERVER["PHP_SELF"]),$signItem)) {
            redirect('search/main_contents');
        } else {
            // redirect('search/main_contents');
        }
    }
    $this->load->helper('date');
  }

  public function selLang($lng, $ns){
    $json = file_get_contents("assets/language/".$lng.".json");
    $obj = json_decode($json);
    $output_data = json_encode($obj, JSON_UNESCAPED_UNICODE);
    Header('Content-Type: application/json');
    print($output_data);
  }

  public function login_email()
	{
    //google_login
    include_once APPPATH . "libraries/google2/vendor/autoload.php";
    $google_client = new Google_Client();
    $google_client->setClientId('87075193321-rd2ghi8jmbgh01k7jcm30onksdqedud8.apps.googleusercontent.com'); //Define your ClientID
    $google_client->setClientSecret('lgAxjubNkv8NFs9M496-DfLh'); //Define your Client Secret Key
    $google_client->setRedirectUri(base_url().'sns_login/google'); //Define your Redirect Uri
    $google_client->addScope('email');
    $google_client->addScope('profile');
    $google_client->addScope('https://www.googleapis.com/auth/contacts');
    $google_client->addScope('https://www.googleapis.com/auth/plus.login');
    $google_client->addScope('https://www.googleapis.com/auth/plus.me');

    $login_button = '<a href="'.$google_client->createAuthUrl().'" class="btn btn-default rounded btn-block"><img src="'.SITE_ASSET_PATH.'/imgs/google-icon.svg" alt="google"></a>';
    $data['login_button'] = $login_button;

    //kakao_login
    $restAPIKey = "4ce424e94a71222b330439b3a383cc34"; //본인의 REST API KEY를 입력해주세요
    $callbacURI = urlencode(base_url().'/sns_login/kakao'); //본인의 Call Back URL을 입력해주세요
    $kakaoLoginUrl = "https://kauth.kakao.com/oauth/authorize?client_id=".$restAPIKey."&redirect_uri=".$callbacURI."&response_type=code";
    $login_button2 = '<a href="'.$kakaoLoginUrl.'" class="btn btn-default rounded btn-block"><img src="'.SITE_ASSET_PATH.'/imgs/kakao-icon.svg" ></a>';
    $data['login_button2'] = $login_button2;

    $restAPIKey2 = "1508728219324153"; //본인의 REST API KEY를 입력해주세요
    $callbacURI2 = urlencode(base_url().'sns_login/facebook'); //본인의 Call Back URL을 입력해주세요
    $facebookLoginUrl = "https://www.facebook.com/v9.0/dialog/oauth?client_id=".$restAPIKey2."&redirect_uri=".$callbacURI2."&state=code";
    // $login_button3 = '<a href="'.$facebookLoginUrl.'" class="btn btn-default rounded btn-block"><img src="'.SITE_ASSET_PATH.'/imgs/facebook-icon.svg" alt="facebook"></a>';
    $login_button3 = '<a href="" class="btn btn-default rounded btn-block"><img src="'.SITE_ASSET_PATH.'/imgs/facebook-icon.svg" alt="facebook"></a>';
    $data['login_button3'] = $login_button3;
    $this->load->view('common/login_email', $data);
	}

  public function appLogin(){
    $key = $this->input->get("key");
    if(empty($key)){
      //$this->session->unset_userdata('g_manager');
      //$this->session->sess_destroy();
      //$this->login_email();
          $arr = '{"tp_language": "kr"}';
          $this->session->set_userdata('g_manager', json_decode($arr));

      redirect('search/main_contents');
    }else{
      $decode_key = base64_decode($key);
      $arr = explode(',', $decode_key);
      $return_array = array();
      $this->db->select('*')
      ->from('dts_user')
      ->where('idx_user', $arr[0])
      ->where('email', $arr[1])
      ->where('is_del', 'N');
      $row = $this->db->get()->row();
      if(isset($row)){
        $this->session->set_userdata('g_manager', $row);
        redirect('search/main_contents');
      }else{ //계정이 없는 경우
        //$this->login_email();
            $arr = '{"tp_language": "kr"}';
            $this->session->set_userdata('g_manager', json_decode($arr));
        redirect('search/main_contents');
      }
    }
  }

  // public function login_pw()
  // {
  //   // $this->load->view('common/login_pw');
  // }
  public function personal_information_processing()
  {
    $ss_language = $this->session->userdata('language');
    $data['top_menu_title'] = $ss_language == 'en' ? 'Privacy Policy' : '개인정보 보호정책';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['ss_language'] = empty($ss_language) ? 'kr' : $ss_language;

    $this->db->select(' * ')
    ->where('code','0002')
    ->from('dtc_info');
    $info_item = $this->db->get()->row();
    $data['privacy'] = $info_item;

    $this->load->view('common/personal_information_processing', $data);
  }
  public function terms_of_use()
  {
    $ss_language = $this->session->userdata('language');
    $data['top_menu_title'] = $ss_language == 'en' ? 'Terms of use' : '이용약관';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['ss_language'] = $ss_language;
    $data['top_right2_btn_nm'] = '';

    $this->db->select(' * ')
    ->where('code','0001')
    ->from('dtc_info');
    $info_item = $this->db->get()->row();
    $data['terms_of_use'] = $info_item;

    $this->load->view('common/terms_of_use', $data);
  }

  public function pw_find_result()
	{
    $data['top_menu_title'] = 'Forgot password';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $this->load->view('common/pw_find_result', $data);
	}

  public function pw_find_email()
	{
    $data['top_menu_title'] = 'Forgot password';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $this->load->view('common/pw_find_email', $data);
	}
  public function pw_change_completed()
  {
    $data['top_menu_title'] = 'Reset password';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $this->load->view('common/pw_change_completed', $data);
  }
  public function pw_forgot()
  {
    // 2차에서 변경된 작업 TODO 나중에 주석 해제 하면 언어 선택 가능
    $ss_language = "kr";//$this->session->userdata('language');
    $data['top_menu_title'] = $ss_language == 'en' ? 'Forgot password' : '비밀번호 찾기';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['ss_language'] = $ss_language;
    $this->load->view('common/pw_forgot', $data);
  }
  public function sign_up()
  {
    $ss_language = $this->session->userdata('language');
    if($ss_language == ''){
      $this->session->set_userdata('language', "kr");
      // echo "<script>alert('잘못된 접근 방법입니다.');history.back();</script>";
      // 2차에서 변경된 작업 TODO 나중에 주석 해제 하면 언어 선택 가능
    }

    $data['top_menu_title'] = $ss_language == 'en' ? 'Sign Up' : "회원가입";
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['ss_language'] = empty($ss_language) ? 'kr' : $ss_language;
    $this->load->view('common/sign_up', $data);
  }
  public function uuidgen() {
     return sprintf('%08x-%04x-%04x-%04x-%04x%08x',
        mt_rand(0, 0xffffffff),
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff), mt_rand(0, 0xffffffff)
      );
  }
  public function pw_email_code()
  {
    $uuid = $this->uuidgen();
    $mode = $this->input->get('mode');
    $tp_language = $this->session->userdata('language');
    //joinning_idx :: insertMember 후 세션 생성
    switch ($mode) {
      case 'sign_up':
          $auth_email = $this->db->select(' * ')->from('dts_user')->where('idx_user',$this->session->userdata('joinning_idx'))->get()->row()->email;

          $this->load->config('email');
          $this->_send_email($auth_email,$tp_language == "kr" ? "이메일 인증을 완료해주세요" : "Please confirm your email address",$this->emailType($tp_language,$uuid));
          // echo $this->email->print_debugger();
        $insert_data = array(
            "uuid" => $uuid
          , "idx_user" => $this->session->userdata('joinning_idx')
          , "user_id" => $auth_email
          , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
        );
        $this->Member_Model->insertCode($insert_data);
        break;
      case 'pw_find':

        break;
      default:
        redirect('common/login_email');
        break;
    }
    $data['top_menu_title'] = $tp_language == 'kr' ? '이메일 인증' : 'Verify your Email';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['auth_email'] = $auth_email;
    $data['tp_language'] = $tp_language;

    $this->load->view('common/pw_email_code', $data);
  }
  public function sign_up_first()
  {
    $data['top_menu_title'] = 'Sign Up';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "common/sign_up_nationality";
    $data['top_right_btn_nm'] = '<h6 class="page-skip">SKIP</h6>';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $this->load->view('common/sign_up_first_language', $data);
  }
  public function sign_up_language()
  {
    /*
    if($this->session->userdata('joinning_idx') == ''){
      echo "<script>alert('잘못된 접근 방법입니다.');history.back();</script>";
    }
    */
    $data['top_menu_title'] = 'Sign Up';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "common/sign_up_nationality";
    $data['top_right_btn_nm'] = '<h6 class="page-skip">SKIP</h6>';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $this->load->view('common/sign_up_language', $data);
  }

  public function sign_up_language_go()
  {
    $language = $this->input->post('tp_language');
    if($language == "kr" || $language == "en" ){
      $data['rcode'] = 1;
      $this->session->set_userdata('language', $language);
    }else{
      $data['rcode'] = 0;
    }
    echo json_encode($data['rcode']);
  }
  public function sign_up_nationality()
  {
    if($this->session->userdata('joinning_idx') == ''){
      echo "<script>alert('잘못된 접근 방법입니다.');history.back();</script>";
    }
    $ss_language = $this->session->userdata('language');
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0100">회원가입</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "common/sign_up_region";
    $data['top_right_btn_nm'] = '<h6 class="page-skip">SKIP</h6>';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['nations'] = $this->Member_Model->selectNations();
    $data['ss_language'] = $ss_language;

    $this->load->view('common/sign_up_nationality', $data);
  }
  public function sign_up_region()
  {
    if($this->session->userdata('joinning_idx') == ''){
      echo "<script>alert('잘못된 접근 방법입니다.');history.back();</script>";
    }
    $ss_language = $this->session->userdata('language');

    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0100">회원가입</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "common/sign_up_nickname";
    $data['top_right_btn_nm'] = '<h6 class="page-skip">SKIP</h6>';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['ss_language'] = $ss_language;

    $data['regions'] = $this->Member_Model->selectRegions();

    $this->load->view('common/sign_up_region', $data);
  }
  public function sign_up_nickname()
  {
    if($this->session->userdata('joinning_idx') == ''){
      echo "<script>alert('잘못된 접근 방법입니다.');history.back();</script>";
    }
    $ss_language = $this->session->userdata('language');
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0100">회원가입</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "common/sign_up_register_picture";
    $data['top_right_btn_nm'] = '<h6 class="page-skip">SKIP</h6>';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['ss_language'] = $ss_language;

    $this->load->view('common/sign_up_nickname', $data);
  }
  public function sign_up_register_picture()
  {
    if($this->session->userdata('joinning_idx') == ''){
      echo "<script>alert('잘못된 접근 방법입니다.');history.back();</script>";
    }
    $ss_language = $this->session->userdata('language');
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0100">회원가입</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "common/card_registration";
    $data['top_right_btn_nm'] = '<h6 class="page-skip">SKIP</h6>';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['ss_language'] = $ss_language;

    $this->load->view('common/sign_up_register_picture', $data);
  }
  public function card_registration()
  {
    $data['top_menu_title'] = '내가 가진 카드를 등록하세요';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "common/select_card";
    $data['top_right_btn_nm'] = '<h6 class="page-skip">SKIP</h6>';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $this->load->view('common/card_registration', $data);
  }
  public function select_card()
  {
    $data['top_menu_title'] = '카드 등록';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $this->load->view('common/select_card', $data);
  }
  public function lotte_card()
  {
    $data['top_menu_title'] = '카드 등록';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $this->load->view('common/lotte_card', $data);
  }

  public function pw_reset()
  {
    $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$this->session->userdata('joinning_idx'))->get()->row()->tp_language;

    $data['top_menu_title'] = $tp_language == 'en' ? 'Reset password' : '비밀번호 재설정';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['tp_language'] = $tp_language;
    $this->load->view('common/pw_reset', $data);
  }

  public function Login(){
    $email = $this->input->post('email');
    $login_pw = $this->input->post("login_pw");
    $tp_language = $this->input->post("tp_language");
    echo json_encode($this->Member_Model->Login($email,$login_pw, $tp_language));
  }
  public function emailCheck(){
    echo json_encode($this->Member_Model->emailCheck());
  }

  public function pwCheck(){
    echo json_encode($this->Member_Model->pwCheck());
  }

  public function emailAuthCheck(){
    echo json_encode($this->Member_Model->EmailAuthCheck());
  }

  public function insertMember(){
    $user_pw = $this->input->post('login_pw');

    $salt = $this->config->item('__salt__');
    $encrypt = hash('sha256', $user_pw.$salt);
    $language = $this->session->userdata('language');

    $data = array(
        "email" => $this->input->post('email')
      , "login_pw" => $encrypt
      , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
      , "is_email" => "N"
      , "tp_language" => $language != "" ? $language : "kr"
    );
    echo json_encode($this->Member_Model->insertMember($data));
  }

  public function FindMember(){
    $email = $this->input->post('email');
    echo json_encode($this->Member_Model->FindMember($email));
  }

  public function auth_certification(){ //인증 체크
    $uuid = $this->input->get("code");
    $auth_email = $this->db->select(' * ')->from('dts_email_auth')->where('uuid',$uuid)->get()->result();
    $timestamp = strtotime("+1 days");
    if(!empty($auth_email)){
      $dt_check = false;
      foreach ($auth_email as $row)
      {
        $where = array(
          "idx_user" => $row->idx_user
        );
         $data = array(
          "uuid" => $row->uuid
         );
         $dt_insert = strtotime($row->dt_insert);
         $dt_check_out = strtotime('+1 day', $dt_insert);
         $now = strtotime("Now");
      }

      $success = array(
          "is_email" => "Y"
      );
      $this->Member_Model->completedCode($where);

      $language = $this->session->userdata('language');
      if($dt_check_out >= $now){
        $this->Member_Model->updateMember($success, $where);

        if($language == 'kr'){
          echo "<script>alert('이메일 인증이 완료되었습니다.');close();</script>";
        }else if($language == 'en'){
          echo "<script>alert('Congrats! You are verified!');close();</script>";
        }
      }else{
        echo "<script>alert('이메일 인증이 만료되었습니다.');close();</script>";
      }
    }else{
      echo "<script>alert('잘못된 접근 방법입니다.');close();</script>";
    }
    // $this->load->view('common/auth_certification', $data);
  }

  public function updateMember(){
    $idx_user = $this->session->userdata('joinning_idx');
    $email = $this->session->userdata('login_email');

    $data = array(
      "dt_update" => mdate('%Y-%m-%d %H:%i:%s', now())
    );
    if($this->input->post('tp_language') != ''){ $data['tp_language'] = $this->input->post('tp_language'); }
    if($this->input->post('tp_nationality') != ''){ $data['tp_nationality'] = $this->input->post('tp_nationality'); }
    if($this->input->post('tp_region') != ''){ $data['tp_region'] = $this->input->post('tp_region'); }
    if($this->input->post('nick_name') != ''){ $data['nick_name'] = $this->input->post('nick_name'); }
    if($this->input->post('login_pw') != ''){
      $user_pw = $this->input->post('login_pw');
      $salt = $this->config->item('__salt__');
      $encrypt = hash('sha256', $user_pw.$salt);
      $data['login_pw'] = $encrypt;
    }

    if(!empty($_FILES['profile_img']['name'])){
      $return_data = $this->File_Model->upload_dynamic("profile_img", 5, "profile");
      $data['profile_img'] = $return_data['file_location'];
    }
    $where = array();

    if(!empty($idx_user)){
      $where['idx_user'] = $idx_user;
    }else if(!empty($email)){
      $where['email'] = $email;
    }else{
      exit;
    }
    echo json_encode($this->Member_Model->updateMember($data, $where));
  }
  public function emailType($language,$uuid){
    $result = "";
    if($language == "kr"){
      $result = "<div id='readFrame'>"
      ."<xlink href='https://fonts.googleapis.com/css?family=Noto+Sans+KR' rel='stylesheet'>"
      ."<table style='width: 100%; height: 600px; text-align: center;'>"
      ."<tbody><tr><td align='center' width='400' style='background-color: #f5f5f5'>"
      ."<div style='width: 400px;height: 324px;border-radius: 12px;background-color: #ffffff;box-sizing: border-box;padding: 61px 52px 32px 52px;text-align: center;font-family: 'Noto Sans KR', sans-serif;font-size: 13px;color: rgba(0, 0, 0, 0.54);user-select: none;'>"
      ."<div style='width: 104px; height: 16px;left: 50%;margin: 0 auto;padding-bottom: 7.8px;background-image: url('logo_miricanvas.png');background-repeat: no-repeat;'></div>"
      ."<div style='height: 41px;font-size: 26px;font-weight: 300;line-height: 28px;padding-bottom: 24px;color: rgba(0, 0, 0, 0.82);margin: 0 auto;'>인증하기</div>"
      ."<div style='height: 19px;line-height: 1.62;padding-bottom: 12px;'>"
      ."</div>"
      ."<div style='height: 40px;padding-bottom: 32px;'>"
      ."안녕하세요, 딜라잇미입니다.<br/>아래의 버튼을 클릭하면<br/>이메일 인증이 완료됩니다."
      ."</div>"
      ."<div style='width: 298px;background-color: #806EFF;border: solid 1px #9c93ce;border-radius: 5px;display: inline-block;padding-top: 10px;padding-bottom: 10px;font-size: 14px;font-weight: 500;line-height: 1.43;text-align: center;color: #ffffff;text-decoration: none; cursor: pointer;'>"
      ."<a href='https://dealightme.net/common/auth_certification?code=".$uuid."' style=':text-align: center;color: #ffffff;text-decoration: none; display: inline-block;' rel='noreferrer noopener' target='_blank'>인증하기</a>"
      ."</div>"
      ."</div>"
      ."</td></tr>"
      ."</tbody></table>"
      ."</xlink></div>";
    }else if($language == "en"){
      $result = "<div id='readFrame'>"
      ."<xlink href='https://fonts.googleapis.com/css?family=Noto+Sans+KR' rel='stylesheet'>"
      ."<table style='width: 100%; height: 600px; text-align: center;'>"
      ."<tbody><tr><td align='center' width='400' style='background-color: #f5f5f5'>"
      ."<div style='width: 400px;height: 324px;border-radius: 12px;background-color: #ffffff;box-sizing: border-box;padding: 61px 52px 32px 52px;text-align: center;font-family: 'Noto Sans KR', sans-serif;font-size: 13px;color: rgba(0, 0, 0, 0.54);user-select: none;'>"
      ."<div style='width: 104px; height: 16px;left: 50%;margin: 0 auto;padding-bottom: 7.8px;background-image: url('logo_miricanvas.png');background-repeat: no-repeat;'></div>"
      ."<div style='height: 41px;font-size: 26px;font-weight: 300;line-height: 28px;padding-bottom: 24px;color: rgba(0, 0, 0, 0.82);margin: 0 auto;'>Confirm your Email</div>"
      ."<div style='height: 19px;line-height: 1.62;padding-bottom: 12px;'>"
      ."</div>"
      ."<div style='height: 40px;padding-bottom: 32px;'>"
      ."Thanks for joining DealightMe!<br/>Please click the button below<br/>to confirm your email address"
      ."</div>"
      ."<div style='width: 298px;background-color: #806EFF;border: solid 1px #9c93ce;border-radius: 5px;display: inline-block;padding-top: 10px;padding-bottom: 10px;font-size: 14px;font-weight: 500;line-height: 1.43;text-align: center;color: #ffffff;text-decoration: none; cursor: pointer;'>"
      ."<a href='https://dealightme.net/common/auth_certification?code=".$uuid."' style=':text-align: center;color: #ffffff;text-decoration: none; display: inline-block;' rel='noreferrer noopener' target='_blank'>Verify Now</a>"
      ."</div>"
      ."</div>"
      ."</td></tr>"
      ."</tbody></table>"
      ."</xlink></div>";
    }
    return $result;
  }

  public function setLang(){
    if($this->input->post('tp_language') != ''){
      $tp_language = $this->input->post('tp_language');
    }else{
      $tp_language = 'kr';
    }

    $this->session->set_userdata('language', $tp_language);

    $result_array['rcode'] = 1;
    $result_array['rmsg'] = "SUCCESS";
    echo json_encode($result_array);
  }
}
