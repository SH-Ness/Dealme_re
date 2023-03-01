<?php
class Card_Model extends CI_Model {
  public function __construct()
  {
    $this->load->database();
  }

  public function getCardCompanyList()
  {
    $return_obj = array();

    $this->db->select('a.*')
    ->from('dtc_cardcom a');
    $data = $this->db->get()->result_array();

    return $data;
  }

  public function getCardList($where)
  {
    $return_obj = array();

    $this->db->select('a.*')
    ->select('(select cardcom_logo from dtc_cardcom where cd_cardcom = a.cd_cardcom) cardcom_logo')
    ->select('(select cardcom_nm from dtc_cardcom where cd_cardcom = a.cd_cardcom) cardcom_nm')
    ->from('dts_card a')
    ->where('cd_cardcom', $where)
    ->where('is_del', 'N');
    $data = $this->db->get()->result_array();

    return $data;
  }

  public function getMyList()
  {
    $return_obj = array();

    $this->db->select('a.*')
    ->select('(select cardcom_logo from dtc_cardcom where cd_cardcom = a.cd_cardcom) cardcom_logo')
    ->select('(select cardcom_nm from dtc_cardcom where cd_cardcom = a.cd_cardcom) cardcom_nm')
    ->from('dts_card a')
    ->join('dts_user_card b', 'a.idx_card = b.idx_card')
    ->where('a.is_del', 'N')
    ->where('b.idx_user', $this->session->userdata('g_manager')->idx_user);
    $data = $this->db->get()->result_array();

    return $data;
  }
  public function deleteCard($where)
  {
    $return_obj = array();

    $this->db->delete('dts_user_card',$where);

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

  public function selectCard($where)
  {
    $return_obj = array();
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $query = "SELECT * FROM dts_user_card where idx_card in (select idx_card from dts_card where bin_num = '$where') and idx_user = '$idx_user'";
    $data2 = $this->db->query($query)->result_array();
    if(count($data2)>0){
      $return_obj["rcode"] = "998";
      $return_obj["rmsg"] = "Exist!!";
      return $return_obj;
    }

    $this->db->select('a.*')
    ->select('(select cardcom_logo from dtc_cardcom where cd_cardcom = a.cd_cardcom) cardcom_logo')
    ->select('(select cardcom_nm from dtc_cardcom where cd_cardcom = a.cd_cardcom) cardcom_nm')
    ->from('dts_card a')
    ->where('is_del', 'N')
    ->where('bin_num', $where);
    $data = $this->db->get()->result_array();

    if(count($data)>0){
      $return_obj["data"] = $data;
      return $return_obj;
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "No card";
      return $return_obj;
    }



  }

  public function insertItem($data){
    $return_obj = array();

    $this->db->insert('dts_user_card', $data);

    $resultCount = $this->db->affected_rows(); // insert 및 update를 할때에는  $객체 =  $this->db->affected_rows();
    if($resultCount > 0){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }
    return $return_obj;
  }

  public function getMyCardList()
  {
    $return_obj = array();

    $this->db->select('a.idx_card')
    ->from('dts_card a')
    ->join('dts_user_card b', 'a.idx_card = b.idx_card')
    ->where('a.is_del', 'N')
    ->where('b.idx_user', $this->session->userdata('g_manager')->idx_user);
    $data = $this->db->get()->result_array();

    return $data;
  }
}
