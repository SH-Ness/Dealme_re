<?php
class Code_Model extends CI_Model {

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

        $this->db->select()
        ->from('dts_code')
        ->order_by('code_group', 'asc')
        ->order_by('order_no', 'asc')
        ->limit($length, $start);
        $data = $this->db->get()->result_array();

        $this->db->select('count(*) cnt')
        ->from('dts_code');
        $data_count = $this->db->get()->row();

        $return_obj["data"] = $data;
        $return_obj["rcode"] = "1";
        $return_obj["rmsg"] = "";
        $return_obj["draw"] = $draw;
        $return_obj["recordsTotal"] = $data_count->cnt;
        $return_obj["recordsFiltered"] = $data_count->cnt;

        return $return_obj;
    }

    public function getItem($where, $where1){
      $this->db->select()
      ->from('dts_code')
      ->where('code_group', $where)
      ->where('code_value', $where1);
      $data = $this->db->get()->result_array();

      if(empty($data)){
        return null;
      }

      return $data[0];
    }

    public function insertItem($data){
      $return_obj = array();

      $code_value = $data['code_value'];
      $code_group = $data['code_group'];

      $query = "SELECT * FROM dts_code where code_value = '$code_value' and code_group = '$code_group'";
      $data2 = $this->db->query($query)->result_array();
      if(count($data2)>0){
          $return_obj["rcode"] = "998";
          $return_obj["rmsg"] = "Exist!!";
        return $return_obj;
      }

      $this->db->insert('dts_code', $data);

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

    public function updateItem($data, $where){
      $return_obj = array();

      $this->db->update('dts_code', $data, $where);

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

    public function deleteItem($where){
      $return_obj = array();

      $this->db->update('dts_code', array('is_use'=>'N','is_view'=>'N'), $where);

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

    public function selectList($code_group)
    {
      // data query
      $this->db->select("*")
      ->from("dts_code")
      ->where('code_group', $code_group)
      ->where('is_use', 'Y')
      ->where('is_view', 'Y')
      ->order_by("order_no", "ASC");

      $data = $this->db->get()->result_array();

      return $data;
    }

    public function selectAddrList(){
      $this->db->distinct()
      ->select("sido")
      ->from("dta_addr");

      return $this->db->get()->result_array();
    }

    public function selectAddrList2($sido){
      $this->db->distinct("gugun")
      ->from("dta_addr")
      ->where("sido", $sido);

      return $this->db->get()->result_array();
    }

    public function uuidgen() {
       return sprintf('%08x%04x%04x%04x%04x%08x',
          mt_rand(0, 0xffffffff),
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0xffff), mt_rand(0, 0xffffffff)
        );
    }

}
