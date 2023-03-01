<?php
class Search_Model extends CI_Model {

    public function __construct()
    {
      $this->load->database();
    }

    public function selectBanners($where, $limit = 0){
      $tp_language = $this->session->userdata('g_manager')->tp_language;

      $this->db->select('a.*, b.*')
      ->select("replace(a.text_middle, '\n', '</br>') text_middle")
      ->from('dts_banner a')
      ->join('dts_banner_case b', 'a.tp_case = b.idx_banner_case', 'left outer')
      ->where($where)
      ->where('tp_language', $tp_language)
      ->limit($limit)
      ->order_by("a.order_no", "asc");

      $data = $this->db->get()->result_array();
      return $data;
    }

    public function selectBannerCase(){
      $this->db->select('a.*')
      ->from('dts_banner_case a')
      ->order_by("idx_banner_case", "asc");

      $data = $this->db->get()->result_array();
      return $data;
    }

    public function selectCountry(){
      $this->db->select('a.*')
      ->from('dts_country a')
      ->order_by("area_code", "asc");

      $data = $this->db->get()->result_array();
      return $data;
    }

    public function getCountryItem($where){
      $this->db->select('a.*')
      ->from('dts_country a')
      ->where('a.area_code', $where);
      $data = $this->db->get()->result_array();
      if(empty($data)){
        return null;
      }

      $this->db->select('a.*')
      ->from('dts_best_offer a')
      ->where('is_main','N')
      ->where('a.tp_area', $where);
      $data[0]['best_offer'] = $this->db->get()->result_array();

      return $data[0];
    }

    public function getBestOfferItem($where){
      $this->db->select('a.*')
      ->from('dts_best_offer a')
      ->where("idx_best_offer",$where);
      $data = $this->db->get()->row();
      return $data;
    }
}
