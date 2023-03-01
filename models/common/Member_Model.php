<?php
class Member_Model extends CI_Model {

    public function __construct()
    {
      $this->load->database();
    }

    public function Login($email,$login_pw, $tp_language){
      $return_array = array();
      $this->db->select('*')
      ->from('dts_user')
      ->where('email', $email)
      ->where('is_del', 'N');

      $row = $this->db->get()->row();
      if(isset($row)){
        $salt = $this->config->item('__salt__');
        $encrypt = hash('sha256', $login_pw.$salt);
        if($row->login_pw == $encrypt){
          if($row->is_email == "N"){
            $return_array['rcode'] = 3;
            if($row->tp_language == 'kr'){
            $return_array['rmsg'] = '로그인 에러 (이메일 미인증)';
            }else if($row->tp_language == 'en'){
            $return_array['rmsg'] = 'This email has not been verified yet.';
            }
          }else{
            $return_array['rcode'] = 1;
            $return_array['rdata'] = $row;

            if(isset($tp_language)){
              $row->tp_language = $tp_language;
            }
            $this->session->set_userdata('g_manager', $row);
          }
        }else{
          $return_array['rcode'] = 2;
          $return_array['rmsg'] = '로그인 에러 (비밀번호 불일치)';
        }
      }else{
        $return_array['rcode'] = 0;
        $return_array['rmsg'] = '로그인 에러 (존재하지 않는 아이디)';
      }
      return $return_array;
    }

    public function emailCheck()
    {
      $email = $this->input->post('email');

      $this->db->select('')
      ->from('dts_user')
      ->where('email', $email)
      ->where('is_del', 'N');

      $row = $this->db->get()->row();

      $return_array = array();
      if(!isset($row)){
        $return_array['rcode'] = 0;
        $return_array['rmsg'] = '존재하지 않는 계정입니다.';
      }else{
        $return_array['rcode'] = 1;
        $this->session->set_userdata('login_email', $row->email);
      }

      return $return_array;
    }

    public function pwCheck()
    {
      $email = $this->session->userdata('login_email');
      $login_pw = $this->input->post('login_pw');

      $salt = $this->config->item('__salt__');
      $encrypt = hash('sha256', $login_pw.$salt);

      $this->db->select('')
      ->from('dts_user')
      ->where('email', $email)
      ->where('login_pw', $encrypt)
      ->where('is_del', 'N');

      $row = $this->db->get()->row();

      $return_array = array();
      if(!isset($row)){
        $return_array['rcode'] = 0;
        $return_array['rmsg'] = '비밀번호가 틀렸습니다.';
      }else{
        if($row->is_email == "N"){
          $return_array['rcode'] = 0;
          if($row->tp_language == 'kr'){
          $return_array['rmsg'] = '이메일이 인증되지 않은 계정입니다.';
          }else if($row->tp_language == 'en'){
          $return_array['rmsg'] = 'This email has not been verified yet.';
          }
        }else{
          $return_array['rcode'] = 1;
          $this->session->set_userdata('g_manager', $row);
        }
      }

      return $return_array;
    }

    public function EmailAuthCheck(){
      $email = $this->input->post('email');
      $this->db->select('')
      ->from('dts_user')
      ->where('email', $email);

      $row = $this->db->get()->row();

      $return_array = array();
      if($row->is_email == "N"){
          $return_array['rcode'] = 0;
          $return_array['rmsg'] = $row->tp_language == 'kr' ? '이메일이 인증되지 않은 계정입니다.' : 'This email has not been verified yet.';
        }else{
          $return_array['rcode'] = 1;
          $return_array['rmsg'] = '이메일 인증완료';
        }
        return $return_array;
    }

    public function getItem($where){
      $this->db->select()
      ->from('dts_user')
      ->where('idx_user', $where);

      $data = $this->db->get()->result_array();

      if(empty($data)){
        return null;
      }

      return $data[0];
    }

    public function FindMember($where){
      $this->db->select()
      ->from('dts_user')
      ->where('email', $where);

      $data = $this->db->get()->result_array();

      if(empty($data)){
        return null;
      }

      return $data[0];
    }

    public function getItem2($where){
      $this->db->select()
      ->from('dts_user')
      ->where('email', $where);

      $data = $this->db->get()->row();

      if(empty($data)){
        return null;
      }

      return $data;
    }

    public function selectNations(){
      $this->db->select()
      ->from('dts_nations');

      $data = $this->db->get()->result_array();
      return $data;
    }

    public function selectRegions(){
      $this->db->select()
      ->from('dts_regions');

      $data = $this->db->get()->result_array();
      return $data;
    }

    public function insertMember($data){
      $result_array = array();
      $exist_cnt = $this->db->select('count(*) cnt')->from('dts_user')->where('email', $data['email'])->get()->row()->cnt;
      if($exist_cnt > 0){
        $result_array = array(
            "rcode" => 0
          , "rmsg" => "exist"
        );
        return $result_array;
      }
      if($this->db->insert('dts_user', $data)){
        $result_array = array(
            "rcode" => 1
          , "rmsg" => "email_cert"
        );

        $this->session->set_userdata('joinning_idx', $this->db->insert_id());
      }else{
        $result_array = array(
            "rcode" => 0
          , "rmsg" => "fail"
        );
      }
      return $result_array;
    }

    public function insertCode($data){
      $return_obj = array();

      $this->db->insert("dts_email_auth", $data);

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

    public function completedCode($data){
      $this->db->delete("dts_email_auth",$data);
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

    public function updateMember($data, $where){
      $result_array = array();
      if($this->db->update('dts_user', $data, $where)){
        $result_array = array(
            "rcode" => 1
          , "rmsg" => ""
        );

        $this->db->select('')
        ->from('dts_user')
        ->where($where);

        $row = $this->db->get()->row();
        $this->session->set_userdata('g_manager', $row);
      }else{
        $result_array = array(
            "rcode" => 0
          , "rmsg" => "fail"
        );
      }
      return $result_array;
    }
}
