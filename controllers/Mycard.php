<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mycard extends MY_Controller {
    protected $isCheckPrivilegeController = true;

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
     $this->load->model('card/Card_Model');
     $this->load->helper('date');
   }
   public function mycard_main()
   {
     $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0239">카드함</span>'; //카드함';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;
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
     $data['bottom_card_img1_src'] = false;
     $data['bottom_card_img2_src'] = true;
     $data['bottom_myprofile_btn_use'] = true;
     $data['bottom_myprofile_img1_src'] = true;
     $data['bottom_myprofile_img2_src'] = false;

     if(isset($this->session->userdata('g_manager')->idx_user)) {
         $idx_user = $this->session->userdata('g_manager')->idx_user;
         $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
         $data['myCards'] = $this->Card_Model->getMyList();
     } else {
         $tp_language = $this->session->userdata('g_manager')->tp_language;
     }

     $data['tp_language'] = $tp_language;
     $this->load->view('mycard/mycard_main', $data);
   }
   public function mycard_list()
   {
     echo json_encode($this->Card_Model->getMyList());
   }
   public function mycard_add()
   {
     $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0176">카드 등록</p>';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;
     $this->load->view('mycard/mycard_add', $data);
   }
   public function mycard_del()
   {
     $where = array(
       'idx_card' => $this->input->post('idx_card')
      ,'idx_user' => $idx_user = $this->session->userdata('g_manager')->idx_user
    );
      echo json_encode($this->Card_Model->deleteCard($where));
   }
   public function mycard_select_card()
   {
     if(!isset($this->session->userdata('g_manager')->idx_user)) {
         redirect('common/login_email');
     }

     $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0176">카드 등록</p>';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;

     $data['card_companys'] = $this->Card_Model->getCardCompanyList();
     $this->load->view('mycard/mycard_select_card', $data);
   }
   public function mycard_lotte_card()
   {
     if(!isset($this->session->userdata('g_manager')->idx_user)) {
         redirect('common/login_email');
     }

     $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0176">카드 등록</p>';
     $data['top_left_btn_use'] = true;
     $data['top_right_btn_use'] = false;
     $data['top_right_btn_nm'] = '';
     $data['top_right2_btn_use'] = false;

     $idx_user = $this->session->userdata('g_manager')->idx_user;
     $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
     $data['tp_language'] = $tp_language;

     $data['cards'] = $this->Card_Model->getCardList($this->input->get('cd_cardcom'));
     $this->load->view('mycard/mycard_lotte_card', $data);
   }

   public function selectCard(){
     echo json_encode($this->Card_Model->selectCard($this->input->post('bin_num')));
   }

   public function insertItem(){
     $idx_user = $this->session->userdata('g_manager')->idx_user;

     $data = array(
        'idx_user' => $idx_user
       ,'idx_card' => $this->input->post('idx_card')
       ,'dt_insert' => mdate('%Y-%m-%d %H:%i:%s', now())
     );
     echo json_encode($this->Card_Model->insertItem($data));
   }
}
