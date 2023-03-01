<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends MY_Controller {
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

    $this->load->helper('string');
    $this->load->helper('date');
  }

  public function country_contents()
  {
    $data['top_menu_title'] = '';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right3_btn_use'] = false;
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
    
    $area_code = $this->input->get('code');
    $data['country'] = $this->Search_Model->getCountryItem($area_code);
    $data['myCard'] = (isset($this->session->userdata('g_manager')->idx_user)) ? $this->Card_Model->getMyCardList() : array();

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
      ->where('tp_banner','4')
      ->where('b.is_main','N')
      ->where('b.tp_area',$area_code)
      ->where('b.idx_banner_case',$bbitem["idx_banner_case"])
      ->where('tp_language',$tp_language)
      ->order_by("b.idx_banner_case", "asc");
      $banners = $this->db->get()->result_array();
      $bbitem["banners"] = $banners;
      $banner_bottoms2[] = $bbitem;
    }
    sort($banner_bottoms2); //정렬 처리

    $data['ctr_best_offer'] = $this->Country_Model->getCountryBestOfferList($area_code);
    $data['banner_bottoms'] = $banner_bottoms2;
    $data['language'] = $tp_language;
    $data['search_text'] = $this->input->get("search_text");
    $data['categorys'] = $this->HashTag_Model->getCategoryList("merchant");
    $data['areas'] = $this->Search_Model->selectCountry();//$this->Code_Model->selectList('area');
    $data['division'] = $this->Code_Model->selectList('division');
    $data['area_code'] = $area_code;

    $this->load->view('country/country_contents', $data);
  }
}
