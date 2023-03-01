<?php
class Qna_Model extends CI_Model {

    public function __construct()
    {
      $this->load->database();
    }
    public function getList()
    {
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $draw = $this->input->post('draw');

        $search_text = $this->input->post('search_text');
        $is_complete = $this->input->post('is_complete');

        $return_obj = array();

        $this->db->select('a.*')
        ->select('(select user_nm from dts_user where idx_user = a.insert_user) user_nm')
        ->from('dts_qna a')
        ->limit($length, $start);

        if($is_complete != ''){
          $this->db->where('is_complete', $is_complete);
        }

        if($search_text != ''){
          $this->db->where("(insert_user in (select idx_user from dts_user where user_nm like '%$search_text%') or answer_email like '%$search_text%' or content like '%$search_text%')");
        }

        $data = $this->db->get()->result_array();

        $this->db->select('count(*) cnt')
        ->from('dts_qna');

        if($is_complete != ''){
          $this->db->where('is_complete', $is_complete);
        }

        if($search_text != ''){
          $this->db->where("(insert_user in (select idx_user from dts_user where user_nm like '%$search_text%') or answer_email like '%$search_text%' or content like '%$search_text%')");
        }

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
      $this->db->select()
      ->from('dts_qna')
      ->where('idx_qna', $where);
      $data = $this->db->get()->result_array();

      if(empty($data)){
        return null;
      }

      return $data[0];
    }

    public function insertItem($data){
      $return_obj = array();

      $this->db->insert('dts_qna', $data);

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

      $this->db->update('dts_qna', $data, $where);

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
