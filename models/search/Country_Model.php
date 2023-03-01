<?php
class Country_Model extends CI_Model {

  public function __construct()
  {
    $this->load->database();
  }

  public function getBestOfferList()
  {

    $return_obj = array();

    $is_main = $this->input->post('is_main');
    $tp_area = $this->input->post('tp_area');
    $tp_language = $this->input->post('tp_language');

    $this->db->select('a.*')
    ->from('dts_best_offer a')
    ->where('tp_area', $tp_area)
    ->where('tp_language', $tp_language)
    ->where('is_main', $is_main)
    ->limit(5)
    ->order_by('order_no', 'asc');

    $data['data'] = $this->db->get()->result_array();
    return $data;
  }

  public function getCountryBestOfferList($tp_area)
  {
    $return_obj = array();
    $tp_language = $this->session->userdata('g_manager')->tp_language;

    $this->db->select('a.*')
    ->from('dts_best_offer a')
    ->where('tp_area', $tp_area)
    ->where('tp_language', $tp_language)
    ->where('is_main', "N")
    ->order_by('order_no', 'asc');

    $data = $this->db->get()->result_array();
    $result = array_chunk($data, 3);
    return $result;
  }
}
?>
