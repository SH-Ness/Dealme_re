<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends CI_Controller {

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
	public function index()
	{
    $data['top_menu_title'] = '쪽지함';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $this->load->view('inbox/index', $data);
	}

  public function detail()
	{
    $data['top_menu_title'] = '받은 쪽지';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $this->load->view('inbox/detail', $data);
	}

  public function add_message()
	{
    $data['top_menu_title'] = '쪽지 쓰기';
    $data['top_left_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $this->load->view('inbox/add_message', $data);
	}
}
