<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {
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
    $this->load->model('search/Search_Model');
    $this->load->model('search/HashTag_Model');
    $this->load->model('search/Merchant_Model');
    $this->load->model('search/Offer_Model');
    $this->load->model('payment/Payment_Model');
    $this->load->model('code/Code_Model');
    $this->load->model('qna/Qna_Model');
    $this->load->model('common/Member_Model');
    $this->load->model("community/Community_Model");
    $this->load->model('card/Card_Model');
    $this->load->model('gbtapi/Gbtapi_Model');
    $this->load->model('search/Country_Model');
    $this->load->model('file/File_Model');

    $this->load->helper('string');
    $this->load->helper('date');
  }

  public function main_contents()
  {
    $data['bottom_search_btn_use'] = true;
    $data['bottom_search_img1_src'] = false;
    $data['bottom_search_img2_src'] = true;
    $data['bottom_community_btn_use'] = true;
    $data['bottom_community_img1_src'] = true;
    $data['bottom_community_img2_src'] = false;
    $data['bottom_ticket_btn_use'] = true;
    $data['bottom_ticket_img1_src'] = true;
    $data['bottom_ticket_img2_src'] = false;
    $data['bottom_card_btn_use'] = true;
    $data['bottom_card_img1_src'] = true;
    $data['bottom_card_img2_src'] = false;
    $data['bottom_myprofile_btn_use'] = true;
    $data['bottom_myprofile_img1_src'] = true;
    $data['bottom_myprofile_img2_src'] = false;

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    } else {
        $tp_language = $this->session->userdata('g_manager')->tp_language;
    }

    $data['country'] = $this->Search_Model->selectCountry();

    $banner_where = array(
      "tp_banner" => 1
    );
    $data['banners'] = $this->Search_Model->selectBanners($banner_where);
    $banner_where = array(
      "tp_banner" => 2
    );
    $data['banners2'] = $this->Search_Model->selectBanners($banner_where);

    $this->db->select('a.*')
    ->from('dts_banner_case a')
    ->where('is_hide','N')
    ->order_by("order_no", "asc");
    $banner_bottoms = $this->db->get()->result_array();
    $banner_bottoms2 = array();
    foreach ($banner_bottoms as $bbitem) {
      $this->db->select('a.*')
      ->select("replace(a.text_middle, '\n', '</br>') text_middle")
      ->from('dts_banner a')
      ->join('dts_banner_case b', 'a.tp_case = b.idx_banner_case', 'left outer')
      ->where('b.is_main','Y')
      ->where('tp_banner','3')
      ->where('b.idx_banner_case',$bbitem["idx_banner_case"])
      ->where('tp_language',$tp_language)
      ->order_by("b.idx_banner_case", "asc");
      $banners = $this->db->get()->result_array();
      $bbitem["banners"] = $banners;
      $banner_bottoms2[] = $bbitem;
    }
    sort($banner_bottoms2); //정렬 처리


    $data['language'] = $tp_language;
    $data['banner_bottoms'] = $banner_bottoms2;
    $data['search_text'] = $this->input->get("search_text");
    $data['categorys'] = $this->HashTag_Model->getCategoryList("merchant");

    $this->load->view('search/main_contents', $data);
  }

  public function search_screen()
  {
    $data['search_text'] = $this->input->get('search_text');

    $data['categorys'] = $this->HashTag_Model->getCategoryList("merchant");
    $this->load->view('search/search_screen', $data);
  }
  public function search_screen_input()
  {
    $data['search_text'] = $this->input->get('search_text');

    $this->load->view('search/search_screen_input', $data);
  }
  public function content_details()
  {
    $data['top_menu_title'] = '';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "common/login_email";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right3_btn_use'] = false;

    $data['data'] = $this->Merchant_Model->getItem($this->input->get('idx_merchant'));

    $this->db->select(' * ')
    ->where('code','0005')
    ->from('dtc_info');
    $involved = $this->db->get()->result_array();
    $data['involved'] = $involved;

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    } else {
        $tp_language = $this->session->userdata('g_manager')->tp_language;
    }

    $data['language'] = $tp_language;
    $data['data_fran'] = $this->Merchant_Model->getItemFran($this->input->get('idx_merchant'));
    $data['data_image'] = $this->Merchant_Model->getItemImage($this->input->get('idx_merchant'));
    $data['data_tag'] = $this->Merchant_Model->getItemTag($this->input->get('idx_merchant'));
    $data['data_offer'] = $this->Offer_Model->getList();
    $data['data_offer_count'] = count($this->Offer_Model->getList());
    $this->load->view('search/content_details', $data);
  }
  public function franchisee()
  {
    $tp_language = $this->session->userdata('g_manager')->tp_language;
    $name = $this->Merchant_Model->getItem($this->input->get('idx_merchant'));
    $data['top_menu_title'] = "<h5 style='font-weight: bold;' id='head_merchant' idx=".$name['idx_merchant'].">".($tp_language == 'kr' ? $name['name_kor'] : $name['name_eng'])."</h5>";
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = false;
    $data['top_right2_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';

    $data['data'] = $this->Offer_Model->getFranList($this->input->get('idx_merchant'));

    $this->load->view('search/franchisee', $data);
  }
  public function getFransList(){
    echo json_encode($this->Offer_Model->getCouponFranList($this->input->post('idx_merchant')));
  }
  public function getMyCardBenefitsList(){
    echo json_encode($this->Offer_Model->getCardBenefits($this->input->post('merchant_list')));
  }
  public function purchase_a_ticket()
  {
    //lcode_0195
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0195">티켓</p>';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right3_btn_use'] = false;

    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    $data['language'] = $tp_language;
    $idx_merchant = $this->db->select(' * ')->from('dts_offer')->where('offer_serial_num',$this->input->get('offer_serial_num'))->get()->row()->idx_merchant;
    $data['fran'] = $this->db->select(' * ')->from('dts_franchiss')->where('idx_merchant', $idx_merchant)->get()->result();
    $data['data'] = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));
    $this->load->view('search/purchase_a_ticket', $data);
  }
  public function after_the_sale_period()
  {
    $data['top_menu_title'] = '티켓 구입';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right3_btn_use'] = false;
    $this->load->view('search/after_the_sale_period', $data);
  }
  public function expiration_date()
  {
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0171">쿠폰</p>';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right3_btn_use'] = false;
    $this->load->view('search/expiration_date', $data);
  }
  public function buy_tickets()
  {
    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $data['top_menu_title'] = '';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right3_btn_use'] = false;

    $data['user'] = $this->Member_Model->getItem($idx_user);
    $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    $data['language'] = $tp_language;
    $data['data'] = $this->Offer_Model->getItem($this->input->post('offer_serial_num'));
    $data['myCards'] = $this->Card_Model->getMyList();
    $data['shopCredit'] = $this->Gbtapi_Model->getCredit();
    $this->load->view('search/buy_tickets', $data);
  }
  public function ticket_completed(){ //결제 최종 단계
    $this->Gbtapi_Model->import_getToken(); //아임포트 엑세스 토큰
    $access_token = $this->session->userdata('importapi');
    $curl = curl_init();

    //m_redirect_url
    $imp_uid = $this->input->get('imp_uid'); // imp uid
    $merchant_uid = $this->input->get('merchant_uid'); //상점 uid
    $imp_success = $this->input->get('imp_success'); // 정상 종료 여부
    $error_msg = $this->input->get('error_msg'); //결제 에러 메세지

    //결제 취소나 실패시 이동하기 위한 offer_serial_num
    $offer_serial_num = $this->input->get('offer_serial_num');

    //query m_redirect_url 부분에서 전송 해주는 것
    if(empty($imp_uid)){
      echo "<script>alert('잘못된 접근입니다.');history.back();</script>";
      return;
    }

    if($imp_success == "false"){ //결제 취소 했을 경우
      echo "<script>alert('".$error_msg."');window.location.href='https://dealightme.net/search/purchase_a_ticket?offer_serial_num=".$offer_serial_num."'</script>";
      return;
    }

    //import 에서 데이터 검증
    $url = "https://api.iamport.kr/payments/".$imp_uid;

    $ch = curl_init(); // 리소스 초기화
     // post 형태로 데이터를 전송
     $postdata = array(
         'imp_uid' => $imp_uid,
     );

     // 옵션 설정
     curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));
     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: '.$access_token));

     $output = curl_exec($ch); // 데이터 요청 후 수신

     $json = json_decode($output, true);

     $output = curl_exec($ch); // 데이터 요청 후 수신
     // echo $output;
     // print_r($json);
    $obj = json_decode($output);
    curl_close($ch);  // 리소스 해제

     // 결제 검증
     if($obj->response->status == "paid"){
      //  echo "<br/><br/>imp_uid:".$obj->response->imp_uid;
      //  echo "<br/>머천트 uid:".$obj->response->merchant_uid;
      //  echo "<br/>구매자 이메일:".$obj->response->buyer_email; // 구매자 이메일
      //  echo "<br/>결제금액:".$obj->response->amount;
      //  echo "<br/>카드번호:".$obj->response->apply_num;
      //  echo "<br/>개수:".json_decode($obj->response->custom_data)->cnt_order;
      //  echo "<br/>gbt_api_key:".json_decode($obj->response->custom_data)->api_key;
      // echo "<br/>idx_user:".json_decode($obj->response->custom_data)->idx_user;
      // echo "<br/>idx_merchant:".json_decode($obj->response->custom_data)->idx_merchant;
      $idx_user = json_decode($obj->response->custom_data)->idx_user;
      $idx_merchant = json_decode($obj->response->custom_data)->idx_merchant;
      $cnt_order = json_decode($obj->response->custom_data)->cnt_order;

      //$offer_serial_num 없으면 url 주소로 접근 한것
      $payment_check = $this->Payment_Model->getItem($obj->response->imp_uid);

      if(empty($payment_check)){ //결제 체크 부분 중복 발급 방지
        $payment = array(
            "payment_key" => random_string('alnum', 16)
          , "imp_uid" => $obj->response->imp_uid
          , "idx_user" => $idx_user
          , "dt_payment" => mdate('%Y-%m-%d %H:%i:%s', now())
          , "idx_merchant" => $idx_merchant
          , "offer_serial_num" => $offer_serial_num
          , "is_issuance" => "N" //발급여부
          , "is_cancel" => "Y"
          , "cnt_order" => $cnt_order
          , "price_order" => $obj->response->amount
        );

        $user_offer = array(
            "user_offer_key" => random_string('alnum', 16)
          , "idx_user" => $idx_user
          , "offer_serial_num" => $offer_serial_num
          , "dt_purchase" => mdate('%Y-%m-%d %H:%i:%s', now())
          , "cnt_offer" => $cnt_order
        );

        $this->Payment_Model->insertItem2($user_offer);
        $result = $this->Payment_Model->insertItem($payment);

        if($result['rcode'] == "1"){
          $this->Gbtapi_Model->addTransaction(json_decode($obj->response->custom_data)->api_key,$obj->response->buyer_email,$cnt_order,$idx_user);
        }
        echo "<script>alert('결제가 완료되었습니다.\n이메일로 티켓이 전송이 되었습니다.');</script>";
      }else{
        echo "<script>alert('이미 결제한 사용자입니다.');</script>";
      }
       $data['top_menu_title'] = '결제 완료';
       $data['top_left_btn_use'] = true;
       $data['top_left2_btn_use'] = false;
       $data['top_right_btn_use'] = true;
       $data['top_right_btn_url'] = "";
       $data['top_right_btn_nm'] = '';
       $data['top_right2_btn_use'] = false;
       $data['top_right3_btn_use'] = false;
       $this->load->view('search/ticket_completed',$data);
     }else{
       echo "<script>alert('".$error_msg."');window.location.href='https://dealightme.net/search/purchase_a_ticket?offer_serial_num=".$offer_serial_num."'</script>";
     }
  }
  public function purchased_tickets()
  {
    $data['top_menu_title'] = '티켓';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right3_btn_use'] = false;

    $data['data'] = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));

    $this->load->view('search/purchased_tickets', $data);
  }
  public function get_coupon()
  {
    if(!isset($this->session->userdata('g_manager')->idx_user)) {
        redirect('common/login_email');
    }

    //'<p data-i18n="lanstr.lcode_0171">쿠폰</p>';
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;

    $name = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));

    $title = $tp_language == 'kr' ? $name['name_kor'] : $name['name_eng'];
    $data['top_menu_title'] = "<h5 style='font-weight: bold; margin-bottom:0;' id='head_merchant' idx=".$name['idx_merchant'].">".$title."</h5>";
    $data['top_left_btn_use'] = false;
    $data['top_left2_btn_use'] = true;

    $data['data'] = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));
    $data['fran_data'] = $this->Offer_Model->getFranList($name['idx_merchant']);

    if($data['data']['is_use'] == 'Y'){
      // echo "<script>alert('사용완료한 쿠폰입니다.');history.back();</script>";
      redirect('search/completed_coupon?offer_serial_num='.$this->input->get('offer_serial_num'));
      // redirect('search/coupon_usage_completed?offer_serial_num='.$this->input->get('offer_serial_num'));
      return;
    }

    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    $data['tp_language'] = $tp_language;
    $this->load->view('search/get_coupon', $data);
  }

  public function add_coupon(){
    echo json_encode($this->Offer_Model->getItem($this->input->post('offer_serial_num')));
  }

  public function getBestOfferItem(){
    echo json_encode($this->Search_Model->getBestOfferItem($this->input->post('idx')));
  }

  public function coupons_held()
  {
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0171">쿠폰</p>';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right3_btn_use'] = false;
    $this->load->view('search/coupons_held', $data);
  }
  public function getScoreList()
  {
    $offer_serial_num = $this->input->post('offer_serial_num');
    echo json_encode($this->Offer_Model->getScoreList($offer_serial_num));
  }

  // 2차에서 사용안함
  // public function coupon_usage_completed()
  // {
  //   $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0171">쿠폰</p>';
  //   $data['top_left_btn_use'] = true;
  //   $data['top_left2_btn_use'] = false;
  //   $data['top_right_btn_use'] = false;
  //   $data['top_right_btn_url'] = "";
  //   $data['top_right_btn_nm'] = '';
  //   $data['top_right2_btn_use'] = false;
  //   $data['top_right3_btn_use'] = false;
  //
  //   $data['data'] = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));
  //   $idx_user = $this->session->userdata('g_manager')->idx_user;
  //   $offer_serial_num = $this->input->get('offer_serial_num');
  //
  //
  //   $idx_merchant = $this->db->select(' * ')->from('dts_offer')->where('offer_serial_num',$offer_serial_num)->get()->row()->idx_merchant;
  //   $data['data_fran'] = $this->Merchant_Model->getItemFran($idx_merchant);
  //   $this->db->select(' * ')->from('dts_offer_grade')->where('idx_user',$idx_user)->where('offer_serial_num',$offer_serial_num);
  //   $data['score_item'] = $this->db->get()->result_array();
  //
  //   $data['grade1'] = $this->db->query('select count(grade) num1 FROM dts_offer_grade WHERE grade <= 1.0 AND grade >= 0.5 AND offer_serial_num ='.$offer_serial_num)->result();
  //   $data['grade2'] = $this->db->query('select count(grade) num2 FROM dts_offer_grade WHERE grade <= 2.0 AND grade >= 1.5 AND offer_serial_num ='.$offer_serial_num)->result();
  //   $data['grade3'] = $this->db->query('select count(grade) num3 FROM dts_offer_grade WHERE grade <= 3.0 AND grade >= 2.5 AND offer_serial_num ='.$offer_serial_num)->result();
  //   $data['grade4'] = $this->db->query('select count(grade) num4 FROM dts_offer_grade WHERE grade <= 4.0 AND grade >= 3.5 AND offer_serial_num ='.$offer_serial_num)->result();
  //   $data['grade5'] = $this->db->query('select count(grade) num5 FROM dts_offer_grade WHERE grade <= 5.0 AND grade >= 4.5 AND offer_serial_num ='.$offer_serial_num)->result();
  //
  //   if($data['data']['is_use'] == 'N'){
  //     redirect('search/get_coupon?offer_serial_num='.$this->input->get('offer_serial_num'));
  //     return;
  //   }
  //   $this->load->view('search/coupon_usage_completed', $data);
  // }

  public function completed_coupon(){
    if(!isset($this->session->userdata('g_manager')->idx_user)) {
      redirect('common/login_email');
    }

    $name = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));

    $tp_language = $this->session->userdata('g_manager')->tp_language;
    $data['top_menu_title'] = "<h5 style='font-weight: bold;' id='head_merchant' idx=".$name['idx_merchant'].">".($tp_language == 'kr' ? $name['name_kor'] : $name['name_eng'])."</h5>";
    $data['top_left_btn_use'] = false;
    $data['top_left2_btn_use'] = true;

    $data['data'] = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));
    $data['fran_data'] = $this->Offer_Model->getFranList($name['idx_merchant']);
    $data['tp_language'] = $tp_language;

    if($data['data']['is_use'] == 'N'){
      redirect('search/get_coupon?offer_serial_num='.$this->input->get('offer_serial_num'));
      return;
    }
    $this->load->view('search/completed_coupon', $data);
  }

  public function search_results()
  {
    $data['search_text'] = $this->input->get('search_text');

    $search_text = $this->input->get('search_text');

    if(!empty($search_text)){
        $this->Community_Model->incrementCnt2($search_text);
    }

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    } else {
        $tp_language = $this->session->userdata('g_manager')->tp_language;
    }

    $data['myCard'] = (isset($this->session->userdata('g_manager')->idx_user)) ? $this->Card_Model->getMyCardList() : array();
    $data['areas'] = $this->session->userdata('g_manager')->tp_language == 'kr' ? $this->Code_Model->selectList('area') : $this->Code_Model->selectList('area_en');
    $data['divisions'] = $this->Code_Model->selectList('division');
    $data['language'] = $tp_language;
    $this->load->view('search/search_results', $data);
  }
  public function application_problem()
  {
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0142">이용 문제 제출</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right3_btn_use'] = false;

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
        $data['data'] = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));
    } else {
        $tp_language = $this->session->userdata('g_manager')->tp_language;
    }

    $data['tp_language'] = $tp_language;
    $data['qna_types'] = $this->Code_Model->selectList('type_qna');
    $this->load->view('search/application_problem', $data);
  }

  public function product_notes()
  {
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0140">유의사항</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $data['data'] = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));
    $this->load->view('search/product_notes', $data);
  }
  public function product_use()
  {
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0141">이용약관</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $data['data'] = $this->Offer_Model->getItem($this->input->get('offer_serial_num'));
    $this->load->view('search/product_use', $data);
  }

  public function getHashTagList(){
    echo json_encode($this->HashTag_Model->getList());
  }

  public function getMerchantList(){
    echo json_encode($this->Merchant_Model->getList());
  }

  public function getOfferList(){
    echo json_encode($this->Offer_Model->getList());
  }

  public function updateFavorite(){
    echo json_encode($this->Offer_Model->updateFavorite());
  }

  public function insertPayment(){
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $data = array(
        "payment_key" => random_string('alnum', 16)
      , "idx_user" => $idx_user
      , "dt_payment" => mdate('%Y-%m-%d %H:%i:%s', now())
      , "idx_merchant" => $this->input->post('idx_merchant')
      , "offer_serial_num" => $this->input->post('offer_serial_num')
      , "cnt_order" => $this->input->post('cnt_order')
      , "price_order" => $this->input->post('price_order')
    );

    if($this->input->post('imp_uid') != null){
      $data['imp_uid'] = $this->input->post('imp_uid');
    }

    $data2 = array(
        "user_offer_key" => random_string('alnum', 16)
      , "idx_user" => $idx_user
      , "offer_serial_num" => $this->input->post('offer_serial_num')
      , "dt_purchase" => mdate('%Y-%m-%d %H:%i:%s', now())
      , "cnt_offer" => $this->input->post('cnt_order')
    );
    $this->Payment_Model->insertItem2($data2);
    echo json_encode($this->Payment_Model->insertItem($data));
  }

  public function updatePayment(){
    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $data = array(
      "is_use" => $this->input->post('is_use')
    );

    $where = array(
      "user_offer_key" => $this->input->post('user_offer_key')
    );

    echo json_encode($this->Payment_Model->updateItem2($data, $where));
  }

  public function insertQnaItem(){
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $data = array(
        "type_qna" => $this->input->post('type_qna')
      , "idx_merchant" => $this->input->post('merchant')
      , "insert_user" => $idx_user
      , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
      , "answer_email" => $this->input->post('answer_email')
      , "content" => $this->input->post('content')
    );

    echo json_encode($this->Qna_Model->insertItem($data));
  }

  public function updatefranchisee(){
    $data = array(
      "latitude" => $this->input->post('lat')
    , "longitude" => $this->input->post('lng')
    );

    $where = array(
      "idx_franchiss" => $this->input->post('idx_franchiss'),
    );
    echo json_encode($this->Merchant_Model->updateFran($data, $where));
  }
  public function terms_of_use()
  {
    $data['top_menu_title'] = 'Terms of use';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $this->db->select(' * ')
    ->where('code','0001')
    ->from('dtc_info');
    $info_item = $this->db->get()->row();
    $data['terms_of_use'] = $info_item;

    $this->load->view('common/terms_of_use', $data);
  }
  public function personal_information_processing()
  {
    $data['top_menu_title'] = 'Privacy Policy';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $this->db->select(' * ')
    ->where('code','0002')
    ->from('dtc_info');
    $info_item = $this->db->get()->row();
    $data['privacy'] = $info_item;

    $this->load->view('common/personal_information_processing', $data);
  }
  public function insertGrade()
  {
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $score = $this->input->post('score');
    $offer_serial_num = $this->input->post('offer_serial_num');

    echo json_encode($this->Offer_Model->insertGradeItem($idx_user,$offer_serial_num,$score));

  }

  public function main_search_screen()
  {
    $data['bottom_search_btn_use'] = true;
    $data['bottom_search_img1_src'] = true;
    $data['bottom_search_img2_src'] = false;
    $data['bottom_community_btn_use'] = true;
    $data['bottom_community_img1_src'] = false;
    $data['bottom_community_img2_src'] = true;
    $data['bottom_ticket_btn_use'] = true;
    $data['bottom_ticket_img1_src'] = true;
    $data['bottom_ticket_img2_src'] = false;
    $data['bottom_card_btn_use'] = true;
    $data['bottom_card_img1_src'] = true;
    $data['bottom_card_img2_src'] = false;
    $data['bottom_myprofile_btn_use'] = true;
    $data['bottom_myprofile_img1_src'] = true;
    $data['bottom_myprofile_img2_src'] = false;
    $data['search_text'] = $this->input->get('search_text');

    $data['categorys'] = $this->HashTag_Model->getCategoryList("merchant");

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $data['language'] = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    } else {
        $data['language'] = $this->session->userdata('g_manager')->tp_language;
    }

    $this->load->view('search/main_search_screen', $data);
  }

  public function getBestOfferList()
  {
    echo json_encode($this->Country_Model->getBestOfferList());
  }

  public function getReviewList()
  {
    echo json_encode($this->Merchant_Model->getReviewList());
  }

  public function insertReviewComment()
  {
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $data = array(
      'idx_merchant' => $this->input->post('idx_merchant')
      ,'idx_user' => $idx_user
      ,'grade' => $this->input->post('score')
      ,'contents' => $this->input->post('contents')
      ,'dt_insert' => mdate('%Y-%m-%d %H:%i:%s', now())
      ,'is_blind' => 'N'
    );

    if(!empty($_FILES['thumb_file']['name'])){
      $return_data = $this->File_Model->review_upload("thumb_file", 5, "review");
      // echo json_encode($return_data);
      $data['img_thumbnail'] = $return_data['file_location'];
    }
    echo json_encode($this->Merchant_Model->insertReview($data));
  }

  public function getGrade(){
    echo json_encode($this->Merchant_Model->getGrade());
  }

  public function deleteReview(){
    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $where = array(
      "idx_merchant" => $this->input->post('idx_merchant'),
      "idx_user" =>   $idx_user
    );

    echo json_encode($this->Merchant_Model->deleteReview($where));
  }

  public function updateReview(){
    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $where = array(
      "idx_merchant" => $this->input->post('idx_merchant'),
      "idx_user" =>  $idx_user
    );

    $data = array(
      'grade' => $this->input->post('grade'),
      'contents' => $this->input->post('contents')
    );

    echo json_encode($this->Merchant_Model->updateReview($data,$where));
  }
}
