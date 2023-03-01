<?php
class File_Model  extends CI_Model {
  public function __construct()
  {
    $this->load->helper('date');
  }
  public function upload($filename, $tp_file, $idx_board = 0){
    $config['upload_path'] = 'assets/upload/community/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $config['max_size'] = '20480';
    $config['encrypt_name'] = TRUE;

    $this->load->library('upload', $config);

    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $cnt_field = count($_FILES[$filename]['name']);
    $file_data = array();
    if($cnt_field > 0) {
      $multi_files = $_FILES;
      for($i=0;$i<$cnt_field;$i++){
        //$this->Model_upload->multi_upload($field_name,'upload/images');
        // $this->Model_upload->multi_upload($filename,'upload/images',$multi_files,$i);
        $_FILES[$filename]['name']= $multi_files[$filename]['name'][$i];
        $_FILES[$filename]['type']= $multi_files[$filename]['type'][$i];
        $_FILES[$filename]['tmp_name']= $multi_files[$filename]['tmp_name'][$i];
        $_FILES[$filename]['error']= $multi_files[$filename]['error'][$i];
        $_FILES[$filename]['size']= $multi_files[$filename]['size'][$i];
        $upload_ok = $this->upload->do_upload($filename);
        $data = $this->upload->data();
        $file_data[] = array(
           "tp_file" => $tp_file
          ,"file_nm" => $data['orig_name']
          ,"save_nm" => $data['file_name']
          ,"insert_user" => $idx_user
          ,"dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
          ,"idx_board" => $idx_board
        );
      }
    }
    // echo json_encode($file_data); exit;
    $result = $this->db->insert_batch('dtc_board_file', $file_data);
    if($result){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }
    return $return_obj;
  }

  public function upload2($filename, $filepath, $tp_table, $idx_item = 0){
    $config['upload_path'] = "assets/upload/$filepath/";
    // git,jpg,png 파일만 업로드를 허용한다.
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    // 허용되는 파일의 최대 사이즈
    $config['max_size'] = '40000';
    $config['encrypt_name']   = TRUE;
    // 이미지인 경우 허용되는 최대 폭
    // $config['max_width']  = '1280';
    // 이미지인 경우 허용되는 최대 높이
    // $config['max_height']  = '1280';
    $this->load->library('upload', $config);

    if(empty($_FILES[$filename]['name'])){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "file isn't";
      return $return_obj;
    }


    $cnt_field = count($_FILES[$filename]['name']);
    $file_data = array();

    if($cnt_field > 0) {
      $multi_files = $_FILES;
      for($i=0;$i<$cnt_field;$i++){
        //$this->Model_upload->multi_upload($field_name,'upload/images');
        // $this->Model_upload->multi_upload($filename,'upload/images',$multi_files,$i);
        $_FILES[$filename]['name']= $multi_files[$filename]['name'][$i];
        $_FILES[$filename]['type']= $multi_files[$filename]['type'][$i];
        $_FILES[$filename]['tmp_name']= $multi_files[$filename]['tmp_name'][$i];
        $_FILES[$filename]['error']= $multi_files[$filename]['error'][$i];
        $_FILES[$filename]['size']= $multi_files[$filename]['size'][$i];

        $upload_ok = $this->upload->do_upload($filename);
        $data = $this->upload->data();

        $file_data[] = array(
           "tp_table" => $tp_table
          ,"item_no" => ($i+1)
          ,"img_name" => $data['orig_name']
          ,"img_save" => $data['file_name']
          ,"img_thumbnail" => $data['orig_name']
          ,"directory_name" => $filepath
          ,"img_type" => $data['file_type']
          ,"dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
          ,"idx_item" => $idx_item
        );
      }
    }
    // echo json_encode($file_data); exit;
    $result = $this->db->insert_batch('dtc_image', $file_data);
    if($result){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }
    return $return_obj;
  }

  public function upload_dynamic($filename, $file_type, $file_path, $file_desc = ''){
    $config['upload_path'] = 'assets/upload/'.$file_path.'/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $config['max_size'] = '40000';
    $config['encrypt_name']   = TRUE;
    $this->load->library('upload', $config);
    if(!$this->upload->do_upload($filename))
    {
      $return_obj = array();

      $return_obj["message"] = "업로드에 실패 했습니다." . $this->upload->display_errors('','');
      $return_obj["uploaded"] = 0;
      $return_obj["idx_file"] = 0;
      $return_obj["file_location"] = "";
      return $return_obj;
    }
    else
    {
      $data = $this->upload->data();
      $return_obj = array();
      $return_obj["uploaded"] = 1;

      $file_data = array(
         "file_type" => $file_type
        ,"file_nm" => $data['orig_name']
        ,"file_desc" => $file_desc
        ,"file_size" => $data['file_size']
        ,"file_ext" => $data['file_ext']
        ,"file_location" => $data['file_name']
      );

      return $file_data;
    }
  }
  public function upload_update($filename, $tp_file, $idx_board = 0){
    $config['upload_path'] = 'assets/upload/community/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $config['max_size'] = '40000';
    $config['encrypt_name']   = TRUE;

    $this->load->library('upload', $config);


    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $cnt_field = count($_FILES[$filename]['name']);
    $file_data = array();

    if($cnt_field > 0) {
      $multi_files = $_FILES;
      for($i=0;$i<$cnt_field;$i++){
        //$this->Model_upload->multi_upload($field_name,'upload/images');
        // $this->Model_upload->multi_upload($filename,'upload/images',$multi_files,$i);
        $_FILES[$filename]['name']= $multi_files[$filename]['name'][$i];
        $_FILES[$filename]['type']= $multi_files[$filename]['type'][$i];
        $_FILES[$filename]['tmp_name']= $multi_files[$filename]['tmp_name'][$i];
        $_FILES[$filename]['error']= $multi_files[$filename]['error'][$i];
        $_FILES[$filename]['size']= $multi_files[$filename]['size'][$i];

        $upload_ok = $this->upload->do_upload($filename);
        $data = $this->upload->data();
        $file_data[] = array(
           "tp_file" => $tp_file
          ,"file_nm" => $data['orig_name']
          ,"save_nm" => $data['file_name']
          ,"insert_user" => $idx_user
          ,"dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
          ,"idx_board" => $idx_board
        );
      }
    }
    // echo json_encode($file_data); exit;
    $result = $this->db->update('dtc_board_file', $file_data, $idx_board);
    if($result){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }
    return $return_obj;
  }

  public function review_upload($filename, $file_type, $file_path, $file_desc = ''){
    // 사용자가 업로드 한 파일을 /static/user/ 디렉토리에 저장한다.
    $config['upload_path'] = 'assets/upload/'.$file_path.'/';
    // git,jpg,png 파일만 업로드를 허용한다.
    $config['allowed_types'] = 'gif|jpg|png|jpeg|svg';
    // 허용되는 파일의 최대 사이즈
    $config['max_size'] = '10000';
    $config['encrypt_name']   = TRUE;
    // 이미지인 경우 허용되는 최대 폭
    // $config['max_width']  = '1280';
    // 이미지인 경우 허용되는 최대 높이
    // $config['max_height']  = '1280';
    if($file_type == "excel"){
      $config['allowed_types'] = 'xls|xlsx';
    }
    $this->load->library('upload', $config);

    if(!$this->upload->do_upload($filename))
    {
      $return_obj = array();

      $return_obj["message"] = "업로드에 실패 했습니다." . $this->upload->display_errors('','');
      $return_obj["uploaded"] = 0;
      $return_obj["idx_file"] = 0;
      $return_obj["file_ext"] = "";
      $return_obj["file_location"] = "";
      return $return_obj;
    }
    else
    {
      $data = $this->upload->data();
      $return_obj = array();
      // $return_obj["fileName"] = $data['file_name'];
      // $return_obj["origName"] = $data['orig_name'];
      $file_data["uploaded"] = 1;
      // $return_obj["url"] = '/upload/imgs/'.$data['file_name'];

      $file_data = array(
         "file_type" => $file_type
        ,"file_nm" => $data['orig_name']
        ,"file_desc" => $file_desc
        ,"file_size" => $data['file_size']
        ,"file_ext" => $data['file_ext']
        ,"file_location" => $data['file_name']
        ,"uploaded" => 1
      );
      // $result = $this->db->insert('dta_file', $file_data);
      // if($result){
        // $return_obj["idx_file"] = $this->db->insert_id();
      //   $return_obj['obj'] = $file_data;
      // }else{
        // $return_obj["idx_file"] = 0;
      // }
      return $file_data;
    }
  }
}
