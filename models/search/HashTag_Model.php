<?php
class HashTag_Model extends CI_Model {

    public function __construct()
    {
      $this->load->database();
    }
    public function getList()
    {
        $return_obj = array();

        $this->db->select()
        ->from('dtc_hashtag')
        ->order_by('order_no', 'asc');

        if(!empty($this->input->post('idx_category'))){
          $this->db->where('idx_category', $this->input->post('idx_category'));
        }

        if(!empty($this->input->post('hashtag'))){
          $this->db->like('hashtag', $this->input->post('hashtag'));
        }

        if(!empty($this->input->post('tp_table'))){
          $this->db->where("idx_category in (select idx_category from dtc_category where tp_table = '".$this->input->post('tp_table')."')");
        }
        $data = $this->db->get()->result_array();

        $return_obj["data"] = $data;
        return $return_obj;
    }

    public function getCategoryList($tp_table){
      $return_obj = array();

      $this->db->select()
      ->from('dtc_category')
      ->where('tp_table', $tp_table)
      ->order_by('order_no', 'asc');
      $data = $this->db->get()->result_array();
      return $data;
    }

    public function updateItem($data, $where){
      $return_obj = array();

      $this->db->update('dtc_hashtag', $data, $where);

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
