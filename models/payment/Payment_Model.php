<?php
class Payment_Model extends CI_Model {

  public function __construct()
  {
    $this->load->database();
  }


  public function getList()
  {
      $start = $this->input->post('start');
      $length = $this->input->post('length');
      $draw = $this->input->post('draw');
      $return_obj = array();

      $this->db->select('a.*')
      ->select("(select name_kor from dts_merchant where idx_merchant = a.idx_merchant) idx_merchant")
      ->select("(select offer_title from dts_offer where offer_serial_num = a.offer_serial_num) offer_title")
      ->from('dts_payment a')
      ->limit($length, $start);
      $data = $this->db->get()->result_array();

      $this->db->select('count(*) cnt')
      ->from('dts_payment');
      $data_count = $this->db->get()->row();

      $return_obj["data"] = $data;
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
      $return_obj["draw"] = $draw;
      $return_obj["recordsTotal"] = $data_count->cnt;
      $return_obj["recordsFiltered"] = $data_count->cnt;

      return $return_obj;
  }

  public function getItem($where){
    $this->db->select('*')
    ->from('dts_payment')
    ->where('imp_uid', $where);
    $data = $this->db->get()->result_array();

    if(empty($data)){
      return null;
    }
    return $data[0];
  }

  public function insertItem($data){
    $return_obj = array();

    $this->db->insert('dts_payment', $data);

    $resultCount = $this->db->affected_rows();
    if($resultCount > 0){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }
    return $return_obj;
  }

  public function insertItem2($data){
    $return_obj = array();

    $this->db->insert('dts_user_offer', $data);

    $resultCount = $this->db->affected_rows();
    if($resultCount > 0){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }
    return $return_obj;
  }

  public function updateItem($data, $where){
    $return_obj = array();

    $this->db->update('dts_payment', $data, $where);

    $resultCount = $this->db->affected_rows();
    if($resultCount > 0){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }
    return $return_obj;
  }

  public function updateItem2($data, $where){
    $return_obj = array();

    $this->db->update('dts_user_offer', $data, $where);

    $resultCount = $this->db->affected_rows();
    if($resultCount > 0){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }
    return $return_obj;
  }
}
