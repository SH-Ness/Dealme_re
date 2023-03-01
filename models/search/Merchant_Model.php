<?php
class Merchant_Model extends CI_Model {

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
    $search_type = $this->input->post('search_type');
    $search_area = $this->input->post('search_area');
    $search_cate = $this->input->post('search_cate');

    $return_obj = array();

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    } else {
        $tp_language = $this->session->userdata('g_manager')->tp_language;
    }

    $this->db->select('a.*')
    ->select("(SELECT sale_info FROM dts_offer WHERE idx_merchant = a.idx_merchant order by sale_info desc limit 1) max_sale_info")
    ->select("(SELECT img_thumbnail FROM dts_offer WHERE idx_merchant = a.idx_merchant order by sale_info desc limit 1) img_thumbnail")
    ->select("(SELECT img_save FROM dtc_image WHERE tp_table = 'merchant' and idx_item = a.idx_merchant order by item_no limit 1) img_save")
    ->select("(SELECT latitude FROM dts_franchiss WHERE idx_merchant = a.idx_merchant order by idx_franchiss limit 1) latitude")
    ->select("(SELECT longitude FROM dts_franchiss WHERE idx_merchant = a.idx_merchant order by idx_franchiss limit 1) longitude")
    // ->select("(SELECT grade_avg FROM dts_offer WHERE offer_serial_num = (SELECT offer_serial_num FROM dts_offer WHERE idx_merchant = a.idx_merchant order by offer_serial_num LIMIT 1) order by offer_serial_num LIMIT 1) grade_avg")
    ->from('dts_merchant a')
    ->where('cd_status','Y');
    // ->limit($length, $start);

    $like_query = $tp_language == "kr" ? "name_kor" : "name_eng"; //한국어 일때는 name_kor 영어일때는 name_eng로 검색
    if(!empty($search_text)){
      $this->db->where('idx_merchant in (select idx_item from dts_tag where tag_nm = "'.$search_text.'") OR'.
      '(SELECT code_name FROM dts_code WHERE code_group = "division" AND code_value = a.cd_division) = "'.$search_text.'" OR '.$like_query.' LIKE "%'.$search_text.'%"');
    }

    if(!empty($search_type)){
      switch ($search_type) {
        case '인기순':
          if(empty($search_area) && empty($search_cate)){
            $this->db->order_by('priority','DESC'); //인기순
          }else{
            $this->sort_tab($search_area, $search_cate,"priority","DESC");
          }
          break;
        case '거리순':
          $lat = $this->input->post('lat');
          $lng = $this->input->post('lng');
          $this->db->select("(SELECT IF(latitude != '' OR longitude != '',(6371*acos(cos(RADIANS(".$lat."))* cos(radians(latitude))*cos(radians(longitude) - RADIANS(".$lng."))+sin(radians(".$lat."))*sin(radians(latitude)))),99999) AS distance FROM dts_franchiss WHERE idx_merchant = a.idx_merchant ORDER BY distance ASC LIMIT 1) AS val_distance");

          if(empty($search_area) && empty($search_cate)){
            $this->db->order_by('val_distance','ASC');
          }else{
            $this->sort_tab($search_area, $search_cate,"val_distance","ASC");
          }
          break;
        case '평점순':
          if(empty($search_area) && empty($search_cate)){
            $this->db->order_by('grade','DESC'); //평점순
          }else{
            $this->sort_tab($search_area, $search_cate,"grade","DESC");
          }
          break;
      }
    }else{ //검색결과 없을 때 정렬
      if($tp_language == "kr"){
        if(empty($search_area) && empty($search_cate)){
          // 가중치 우선 후 한국어 정렬
          $this->db->order_by('priority DESC, name_kor ASC');
        }else{
          // $this->sort_tab($search_area, $search_cate,"priority","ASC");
       $this->sort_tab($search_area, $search_cate,"priority","DESC");
        }
      }else{
        if(empty($search_area) && empty($search_cate)){
          $this->db->order_by('name_eng','ASC');
        }else{
          $this->sort_tab($search_area, $search_cate,"name_eng","ASC");
        }
      }
    }

  // echo $this->output->enable_profiler(true);

    $data = $this->db->get()->result_array();

    // $return_obj["query1"] = $this->db->last_query();
    $this->db->select('count(*) cnt')
    ->from('dts_merchant');
    $data_count = $this->db->get()->row();
    $return_obj["data"] = $data;
    $return_obj["rcode"] = "1";
    $return_obj["rmsg"] = "";
    $return_obj["draw"] = $draw;
    $return_obj["recordsTotal"] = $data_count->cnt;
    $return_obj["recordsFiltered"] = $data_count->cnt;

    return $return_obj;
  }

  public function sort_tab($search_area, $search_cate, $column, $order){ //닉네임 정렬 함수 (지역,카테고리 다음으로 정렬할 컬럼)
    if(!empty($search_area) && empty($search_cate)){ //지역검색 조건만 있을
      $this->db->where("cd_area",$search_area);
      return $this->db->order_by("CASE WHEN cd_area IN (".$search_area.") THEN 0 ELSE 1 END ASC, ".$column. " ".$order);
    }else if(empty($search_area) && !empty($search_cate)){
     return $this->db->order_by("CASE WHEN cd_division IN (".$search_cate.") THEN 0 ELSE 1 END ASC, ".$column. " ".$order);
    }else{
       $this->db->where("cd_area",$search_area);
       $this->db->where("cd_division",$search_cate);
       $this->db->order_by("CASE WHEN cd_division IN (".$search_cate.") AND cd_area IN (".$search_area.") THEN 0 ELSE 1 END ASC, cd_area IN (".$search_area.") DESC");
       $this->db->order_by($column,$order); //평점순
       // return $this->output->enable_profiler(true);
    }
  }

  public function getReviewList(){
      $return_obj = array();
      // $idx_user = $this->session->userdata('g_manager')->idx_user;
      $idx_merchant = $this->input->post('idx_merchant');

      $this->db->select('a.*')
      ->select('date_format(a.dt_insert, "%Y.%m.%d") as text_insert')
      ->select('(select nick_name from dts_user where idx_user = a.idx_user) nick_name')
      ->select('(select profile_img from dts_user where idx_user = a.idx_user) profile_img')
      ->from('dts_merchant_review a')
      ->where("is_blind","N")
      ->where('idx_merchant', $idx_merchant)
      ->order_by("dt_insert","desc");
      $data = $this->db->get()->result_array();

      // echo $this->db->last_query(); exit;
      $return_obj["data"] = $data;

      return $return_obj;
  }

  public function getItem($where){
    $this->db->select()
    ->from('dts_merchant')
    ->where('idx_merchant', $where);
    $data = $this->db->get()->result_array();

    if(empty($data)){
      return null;
    }

    return $data[0];
  }

  public function getItemFran($where){
    $this->db->select()
    ->from('dts_franchiss')
    ->where('idx_merchant', $where);
    $data = $this->db->get()->result_array();

    return $data;
  }
  public function insertFran($data){
    $return_obj = array();

    $this->db->insert("dts_franchiss", $data);

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
  public function updateFran($data, $where){
    $return_obj = array();

    $this->db->update("dts_franchiss", $data, $where);

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

  public function updateReview($data, $where){
    $return_obj = array();

    $this->db->update("dts_merchant_review", $data, $where);

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

  public function getItemImage($where){
    $this->db->select()
    ->from('dtc_image')
    ->where('tp_table', 'merchant')
    ->where('idx_item', $where)
    ->order_by('item_no');
    $data = $this->db->get()->result_array();

    return $data;
  }

  public function getItemTag($where){
    $this->db->select()
    ->from('dts_tag')
    ->where('tp_table', 'merchant')
    ->where('idx_item', $where)
    ->order_by('order_no');
    $data = $this->db->get()->result_array();

    return $data;
  }

  public function insertReview($data){

    $return_obj = array();

    $this->db->insert("dts_merchant_review", $data);

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

  public function getGrade(){
    $idx_merchant = $this->input->post("idx_merchant");
    $return_obj = array();
    $return_obj['cnt'] = array(
     'grade1' => $this->db->query('select count(grade) num1 FROM dts_merchant_review WHERE grade <= 1.0 AND grade >= 0.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num1
    ,'grade2' =>  $this->db->query('select count(grade) num2 FROM dts_merchant_review WHERE grade <= 2.0 AND grade >= 1.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num2
    ,'grade3' =>  $this->db->query('select count(grade) num3 FROM dts_merchant_review WHERE grade <= 3.0 AND grade >= 2.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num3
    ,'grade4' =>  $this->db->query('select count(grade) num4 FROM dts_merchant_review WHERE grade <= 4.0 AND grade >= 3.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num4
    ,'grade5' =>  $this->db->query('select count(grade) num5 FROM dts_merchant_review WHERE grade <= 5.0 AND grade >= 4.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num5
    );

    $return_obj['sum'] = array(
     'grade1' => $this->db->query('select sum(ROUND(grade)) num1 FROM dts_merchant_review WHERE grade <= 1.0 AND grade >= 0.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num1
    ,'grade2' =>  $this->db->query('select sum(ROUND(grade)) num2 FROM dts_merchant_review WHERE grade <= 2.0 AND grade >= 1.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num2
    ,'grade3' =>  $this->db->query('select sum(ROUND(grade)) num3 FROM dts_merchant_review WHERE grade <= 3.0 AND grade >= 2.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num3
    ,'grade4' =>  $this->db->query('select sum(ROUND(grade)) num4 FROM dts_merchant_review WHERE grade <= 4.0 AND grade >= 3.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num4
    ,'grade5' =>  $this->db->query('select sum(ROUND(grade)) num5 FROM dts_merchant_review WHERE grade <= 5.0 AND grade >= 4.5 AND idx_merchant ='.$idx_merchant.' AND is_blind = "N"')->row()->num5
    );

    $return_obj['total_sum'] = array_sum($return_obj['sum']);
    $return_obj['total_cnt'] = array_sum($return_obj['cnt']);
    if(!empty($return_obj['total_sum']) && !empty($return_obj['total_cnt'])){
    $return_obj['total_avg'] = ($return_obj['total_sum'] / $return_obj['total_cnt']);
    }

    if(!empty($return_obj['total_avg'])){
    $data = array(
      'grade' => $return_obj['total_avg']
    );
    $where = array(
      'idx_merchant' => $idx_merchant
    );
      $this->db->update("dts_merchant", $data, $where);
    }else{
      $data = array(
        'grade' => 0.0
      );
      $where = array(
        'idx_merchant' => $idx_merchant
      );
      $this->db->update("dts_merchant", $data, $where);
    }

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

  public function deleteReview($where){
    $return_obj = array();
    $this->db->delete("dts_merchant_review", $where);

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
?>
