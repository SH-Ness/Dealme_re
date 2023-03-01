<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ticket extends MY_Controller {

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
     $this->load->model('search/Offer_Model');
   }
   public function ticket_box()
   {
     $data['top_left_btn_use'] = true;
     $data['top_left2_btn_use'] = true;
     $data['top_left3_btn_use'] = true;
     $data['top_left4_btn_use'] = false;
     $data['bottom_search_btn_use'] = true;
     $data['bottom_search_img1_src'] = true;
     $data['bottom_search_img2_src'] = false;
     $data['bottom_community_btn_use'] = true;
     $data['bottom_community_img1_src'] = true;
     $data['bottom_community_img2_src'] = false;
     $data['bottom_ticket_btn_use'] = true;
     $data['bottom_ticket_img1_src'] = false;
     $data['bottom_ticket_img2_src'] = true;
     $data['bottom_card_btn_use'] = true;
     $data['bottom_card_img1_src'] = true;
     $data['bottom_card_img2_src'] = false;
     $data['bottom_myprofile_btn_use'] = true;
     $data['bottom_myprofile_img1_src'] = true;
     $data['bottom_myprofile_img2_src'] = false;

     $this->load->view('ticket/ticket_box', $data);
   }
   public function coupon_box()
   {
     $data['top_left_btn_use'] = true;
     $data['top_left2_btn_use'] = true;
     $data['top_left3_btn_use'] = true;
     $data['top_left4_btn_use'] = false;
     $data['bottom_search_btn_use'] = true;
     $data['bottom_search_img1_src'] = true;
     $data['bottom_search_img2_src'] = false;
     $data['bottom_community_btn_use'] = true;
     $data['bottom_community_img1_src'] = true;
     $data['bottom_community_img2_src'] = false;
     $data['bottom_ticket_btn_use'] = true;
     $data['bottom_ticket_img1_src'] = false;
     $data['bottom_ticket_img2_src'] = true;
     $data['bottom_card_btn_use'] = true;
     $data['bottom_card_img1_src'] = true;
     $data['bottom_card_img2_src'] = false;
     $data['bottom_myprofile_btn_use'] = true;
     $data['bottom_myprofile_img1_src'] = true;
     $data['bottom_myprofile_img2_src'] = false;
     $this->load->view('ticket/coupon_box', $data);
   }
   public function history()
   {
     $data['top_left_btn_use'] = true;
     $data['top_left2_btn_use'] = true;
     $data['top_left3_btn_use'] = false;
     $data['top_left4_btn_use'] = true;
     $data['bottom_search_btn_use'] = true;
     $data['bottom_search_img1_src'] = true;
     $data['bottom_search_img2_src'] = false;
     $data['bottom_community_btn_use'] = true;
     $data['bottom_community_img1_src'] = true;
     $data['bottom_community_img2_src'] = false;
     $data['bottom_ticket_btn_use'] = true;
     $data['bottom_ticket_img1_src'] = false;
     $data['bottom_ticket_img2_src'] = true;
     $data['bottom_card_btn_use'] = true;
     $data['bottom_card_img1_src'] = true;
     $data['bottom_card_img2_src'] = false;
     $data['bottom_myprofile_btn_use'] = true;
     $data['bottom_myprofile_img1_src'] = true;
     $data['bottom_myprofile_img2_src'] = false;
     $this->load->view('ticket/history', $data);
   }

   public function getList(){
     echo json_encode($this->Offer_Model->getList());
   }

   public function getList2(){
     echo json_encode($this->Offer_Model->getList2());
   }

   public function getHistoryList(){
     echo json_encode($this->Offer_Model->getHistoryList());
   }

   public function deleteItem(){
     $idx_user = $this->session->userdata('g_manager')->idx_user;
     $where = array(
       "user_offer_key" => $this->input->post('user_offer_key'),
       "idx_user" => $idx_user
     );

     $where2 = array(
       "payment_key" => $this->input->post('payment_key'),
       "idx_user" => $idx_user
     );

     if($this->db->delete("dts_payment", $where2)){
       echo json_encode($this->Offer_Model->deleteItem($where));
     }
   }

   public function deleteHistoryItem(){
     $idx_user = $this->session->userdata('g_manager')->idx_user;
     $where = array(
       "user_offer_key" => $this->input->post('user_offer_key'),
       "idx_user" => $idx_user
     );

     $where2 = array(
       "offer_serial_num" => $this->input->post('offer_serial_num'),
       "idx_user" => $idx_user
     );

     if($this->db->delete("dts_payment", $where2)){
       echo json_encode($this->Offer_Model->deleteItem($where));
     }
   }
}
