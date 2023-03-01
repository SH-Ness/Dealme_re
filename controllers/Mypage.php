<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mypage extends MY_Controller {
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
     $this->load->model('community/Community_Model');
     $this->load->helper('date');
   }
   public function logout(){
     $this->session->unset_userdata('g_manager');
     $this->session->sess_destroy();
     redirect('/');
   }

   function kakao_logout(){
   // ?client_id={REST_API_KEY}&logout_redirect_uri={LOGOUT_REDIRECT_URI}
   //
   $this->load->config('social_login');
   $data = array(
       'client_id' => $this->config->item('kakao_login')['client_id']
       ,'logout_redirect_uri' => $this->config->item('kakao_login')['logout_redirect_uri']
   );

   $url = "https://kauth.kakao.com/oauth/logout"."?" . http_build_query($data);
   $this->session->unset_userdata('g_manager');
   $this->session->sess_destroy();
   redirect($url);

   // $ch = curl_init();                                 //curl 초기화
   // curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
   // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
   // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초
   // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
   //
   // $response = curl_exec($ch);
   // curl_close($ch);
   // echo $url;
 }
   public function my_page_main()
   {
     if(isset($this->session->userdata('g_manager')->idx_user)) {
         $idx_user = $this->session->userdata('g_manager')->idx_user;
         $data['data'] = $this->Member_Model->getItem($idx_user);
     }

     $data['bottom_search_btn_use'] = true;
     $data['bottom_search_img1_src'] = true;
     $data['bottom_search_img2_src'] = false;
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
     $data['bottom_myprofile_img1_src'] = false;
     $data['bottom_myprofile_img2_src'] = true;

     $this->load->view('mypage/my_page_main', $data);
   }
   public function profile_settings()
   {
     $idx_user = $this->session->userdata('g_manager')->idx_user;

     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0082"></span>'; //프로필 설정
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;

     $data['data'] = $this->Member_Model->getItem($idx_user);

     $this->load->view('mypage/profile_settings', $data);
   }
   public function my_notice()
   {
     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0107"></span>'; //공지사항';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;
     $data['notice'] = $this->Community_Model->getNoticeList();
     $this->load->view('mypage/my_notice', $data);
   }
   public function notice_detail()
   {
     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0107"></span>';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;
     $data['data'] = $this->Community_Model->getNoticeItem($this->input->get('idx'));
     $page_change = $this->db->query('SELECT idx_board FROM dtc_board'
 .' WHERE idx_board IN ((SELECT idx_board FROM dtc_board WHERE idx_board_mng = 2 AND idx_board < '.$this->input->get('idx').' ORDER BY idx_board DESC LIMIT 1),'
 .' (SELECT idx_board FROM dtc_board WHERE idx_board_mng = 2 AND idx_board > '.$this->input->get('idx').' ORDER BY idx_board LIMIT 1));')->result();
     $data['page_change'] = $page_change;
     $this->load->view('mypage/notice_detail', $data);
   }
   public function purchase_history()
   {
     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0089"></span>'; //구매 내역';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;

     $idx_user = $this->session->userdata('g_manager')->idx_user;
     $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
     $data['tp_language'] = $tp_language;

     $this->load->view('mypage/purchase_history', $data);
   }
   public function my_posts_list()
   {
     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0088"></span>'; //내가 쓴 글';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;

     $idx_user = $this->session->userdata('g_manager')->idx_user;
     $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
     $data['tp_language'] = $tp_language;

     $this->load->view('mypage/my_posts_list', $data);
   }
   public function my_page_etc()
   {
     if(isset($this->session->userdata('g_manager')->idx_user)) {
         $idx_user = $this->session->userdata('g_manager')->idx_user;
         $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
         $data['data'] = $this->Member_Model->getItem($idx_user);
     } else {
         $tp_language = $this->session->userdata('g_manager')->tp_language;
     }

     $data['tp_language'] = $tp_language;
     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0093"></span>'; // 설정
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;

     $this->load->view('mypage/my_page_etc', $data);
   }

   public function pw_reset()
   {
     $idx_user = $this->session->userdata('g_manager')->idx_user;

     $data['top_menu_title'] = '비밀번호 재설정';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_url'] = "";
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;
     $data['top_right2_btn_url'] = "";
     $data['top_right2_btn_nm'] = '';

     $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
     $data['tp_language'] = $tp_language;

     $this->load->view('mypage/pw_reset', $data);
   }

   public function updateMember(){
     $idx_user = $this->session->userdata('g_manager')->idx_user;

     $data = array(
       "dt_update" => mdate('%Y-%m-%d %H:%i:%s', now())
     );

     if($this->input->post('tp_language') != ''){ $data['tp_language'] = $this->input->post('tp_language'); }
     if($this->input->post('tp_nationality') != ''){ $data['tp_nationality'] = $this->input->post('tp_nationality'); }
     if($this->input->post('tp_region') != ''){ $data['tp_region'] = $this->input->post('tp_region'); }
     if($this->input->post('nick_name') != ''){ $data['nick_name'] = $this->input->post('nick_name'); }
     if($this->input->post('is_push') != ''){ $data['is_push'] = $this->input->post('is_push'); }
     if($this->input->post('is_email') != ''){ $data['is_email'] = $this->input->post('is_email'); $data['dt_email_check'] = mdate('%Y-%m-%d %H:%i:%s', now());}
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

     $where = array(
       "idx_user" => $idx_user
     );
     echo json_encode($this->Member_Model->updateMember($data, $where));
   }
   // 설정(언어, 국정, 지역)
   public function set()
   {
     $idx_user = $this->session->userdata('g_manager')->idx_user;

     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0184"></span>'; //설정
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_url'] = "";
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;
     $data['top_right2_btn_url'] = "";
     $data['top_right2_btn_nm'] = '';

     $data['data'] = $this->Member_Model->getItem($idx_user);

     $this->load->view('mypage/set', $data);
   }
   public function set_region()
   {
     // if($this->session->userdata('joinning_idx') == ''){
     //   echo "<script>alert('잘못된 접근 방법입니다.');history.back();</script>";
     // }
     $idx_user = $this->session->userdata('g_manager')->idx_user;
     $tp_region = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_region;
     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0178"></span>'; //지역 설정';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = true;
     $data['top_right_btn_url'] = "";
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;
     $data['top_right2_btn_url'] = "";
     $data['top_right2_btn_nm'] = '';

     $data['myregion'] = $tp_region;
     $data['regions'] = $this->Member_Model->selectRegions();

     $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
     $data['tp_language'] = $tp_language;

     $this->load->view('mypage/set_mypage_region', $data);
   }
   public function set_nationality()
   {
     // if($this->session->userdata('joinning_idx') == ''){
     //   echo "<script>alert('잘못된 접근 방법입니다.');history.back();</script>";
     // }
     $idx_user = $this->session->userdata('g_manager')->idx_user;
     $tp_nationality = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_nationality;
     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0177"></span>'; //국적 설정';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = true;
     $data['top_right_btn_url'] = "";
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;
     $data['top_right2_btn_url'] = "";
     $data['top_right2_btn_nm'] = '';
     $data['mynation'] = $tp_nationality;
     $data['nations'] = $this->Member_Model->selectNations();

     $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
     $data['tp_language'] = $tp_language;

     $this->load->view('mypage/set_mypage_nationality', $data);
   }

   // FAQ
   public function faq()
   {
     $data['top_menu_title'] = '<span>FAQ</span>';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;

     $this->load->view('mypage/faq', $data);
   }

   // advertisement
   public function advertisement()
   {
     $data['top_menu_title'] = '<span>광고/제휴 문의</span>';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;

     $this->load->view('mypage/advertisement', $data);
   }

   public function changeLanguage(){
      $tp_language = $this->input->post('tp_language');

      // $this->session->unset_userdata('g_manager');
      $arr = '{"tp_language": "'.$tp_language.'"}';
      $this->session->set_userdata('g_manager', json_decode($arr));

      $result_array = array();
      $result_array['rcode'] = 1;
      $result_array['language'] = $tp_language;
      echo json_encode($result_array);
   }
}
