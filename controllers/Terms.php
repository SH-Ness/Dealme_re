<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terms extends CI_Controller {

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
  }
	public function terms_of_service()
	{
    // $data['top_menu_title'] = 'Terms of service';
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0141">이용약관</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $data['language'] = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    } else {
        $data['language'] = $this->session->userdata('g_manager')->tp_language;
    }

    $this->db->select(' * ')
    ->where('code','0001')
    ->from('dtc_info');
    $info_item = $this->db->get()->row();
    $data['terms_of_use'] = $info_item;

    $this->load->view('terms/terms_of_service', $data);
	}
  public function protection_policy()
	{
    // $data['top_menu_title'] = 'Privacy Policy';
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0104">개인정보보호정책</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $data['language'] = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    } else {
        $data['language'] = $this->session->userdata('g_manager')->tp_language;
    }

    $this->db->select(' * ')
    ->where('code','0002')
    ->from('dtc_info');
    $info_item = $this->db->get()->row();
    $data['privacy'] = $info_item;

    $this->load->view('terms/protection_policy', $data);
	}
  public function location_info()
	{
    // $data['top_menu_title'] = 'Location Information';
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0198">위치정보이용약관</p>';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $data['language'] = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    } else {
        $data['language'] = $this->session->userdata('g_manager')->tp_language;
    }

    $this->db->select(' * ')
    ->where('code','0003')
    ->from('dtc_info');
    $info_item = $this->db->get()->row();
    $data['location'] = $info_item;

    $this->load->view('terms/location_info', $data);
	}
}
