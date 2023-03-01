<?php
class Offer_Model extends CI_Model {

  public function __construct()
  {
    $this->load->database();
    $this->load->helper('date');
  }

  public function getList()
  {
    $page = $this->input->post('page');
    $pageCnt = $this->input->post('pageCnt');

    $search_text = $this->input->post('search_text');
    $return_obj = array();

    $tp_offer = $this->input->post('tp_offer');
    // $tp_offer = "2";

    $idx_merchant = $this->input->get('idx_merchant');

    $is_buy = $this->input->post('is_buy');

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
    }

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $this->db->select('a.*')
        ->select('date_format(a.dt_validity, "%Y년 %m월 %d일까지") as text_validity')
        ->select('date_format(a.dt_validity, "%b %e, %Y") as text_validity_eng')
        ->select('(select cardcom_logo from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_logo')
        ->select('(select cardcom_nm from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_nm')
        ->select("(select count(*) from dts_user_offer_favorite where offer_serial_num = a.offer_serial_num and idx_user = ".$idx_user.") is_heart")
        ->from('dts_offer a')
        ->join('dts_card b', 'a.idx_card = b.idx_card')
        ->where('is_apply', 'Y');
    } else {
        $this->db->select('a.*')
        ->select('date_format(a.dt_validity, "%Y년 %m월 %d일까지") as text_validity')
        ->select('date_format(a.dt_validity, "%b %e, %Y") as text_validity_eng')
        ->select('(select cardcom_logo from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_logo')
        ->select('(select cardcom_nm from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_nm')
        ->from('dts_offer a')
        ->join('dts_card b', 'a.idx_card = b.idx_card')
        ->where('is_apply', 'Y');
    }

    if(!empty($tp_offer)){
      $this->db->where('tp_offer', $tp_offer);
    }
    if(!empty($search_text)){
      $this->db->where('offer_title', $search_text);
    }

    if(!empty($is_buy)){
      $this->db->select('c.*, d.*')
      ->join('dts_user_offer c', "a.offer_serial_num = c.offer_serial_num and c.idx_user = '$idx_user'")
      ->join('dts_payment d', "a.offer_serial_num = d.offer_serial_num and d.idx_user = '$idx_user'")
      ->where('c.is_use','N') // 2021-10-14 쿠폰 작업 분리 추가
      ->where('a.dt_validity >= ', mdate('%Y-%m-%d %H:%i:%s', now())); // 2021-10-14 쿠폰 작업 분리 추가
    }

    if(!empty($idx_merchant) && $idx_merchant > 0){
      $this->db->where('idx_merchant', $idx_merchant);
    }

    if(!empty($page) && !empty($pageCnt)){
      $this->db->limit($pageCnt, ($page-1)*$pageCnt);
    }

    $data = $this->db->get()->result_array();

    // echo $this->db->last_query(); exit;
    return $data;
  }

  public function getList2(){
    $page = $this->input->post('page');
    $pageCnt = $this->input->post('pageCnt');

    $return_obj = array();
    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $this->db->select('a.*')
    ->select('(select cardcom_logo from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_logo')
    ->select('(select cardcom_nm from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_nm')
    ->select("(select count(*) from dts_user_offer_favorite where offer_serial_num = a.offer_serial_num and idx_user = ".$idx_user.") is_heart")
    ->from('dts_offer a')
    ->join('dts_card b', 'a.idx_card = b.idx_card')
    ->join('dts_user_offer_favorite c', 'a.offer_serial_num = c.offer_serial_num')
    ->where('c.idx_user', $idx_user);

    if(!empty($page) && !empty($pageCnt)){
      $this->db->limit($pageCnt, ($page-1)*$pageCnt);
    }

    $data = $this->db->get()->result_array();

    // echo $this->db->last_query(); exit;
    return $data;
  }

  public function getHistoryList(){
    $page = $this->input->post('page');
    $pageCnt = $this->input->post('pageCnt');

    $return_obj = array();
    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $this->db->select('a.*, c.*')
    ->select('date_format(a.dt_validity, "%Y년 %m월 %d일까지") as text_validity')
    ->select('date_format(a.dt_validity, "%M %d,%Y") as text_validity_eng')
    ->select('(select cardcom_logo from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_logo')
    ->select('(select cardcom_nm from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_nm')
    ->select("(select count(*) from dts_user_offer_favorite where offer_serial_num = a.offer_serial_num and idx_user = ".$idx_user.") is_heart")
    ->from('dts_offer a')
    ->join('dts_card b', 'a.idx_card = b.idx_card')
    ->join('dts_user_offer c', "a.offer_serial_num = c.offer_serial_num and c.idx_user = '$idx_user'")
    ->join('dts_payment d', "a.offer_serial_num = d.offer_serial_num and d.idx_user = '$idx_user'")
    ->where('is_apply', 'Y')
    ->where('c.is_use','Y')
    ->or_where('a.dt_validity < ', mdate('%Y-%m-%d %H:%i:%s', now()));


    if(!empty($page) && !empty($pageCnt)){
      $this->db->limit($pageCnt, ($page-1)*$pageCnt);
    }

    $data = $this->db->get()->result_array();

    // echo $this->db->last_query(); exit;
    return $data;
  }

  public function getFranList($idx_merchant)
  {
    $start = $this->input->post('start');
    $length = $this->input->post('length');
    $draw = $this->input->post('draw');

    $return_obj = array();

    // $idx_merchant = $this->input->get('idx_merchant');
    // $idx_user = $this->session->userdata('g_manager')->idx_user;
    $this->db->select('a.*')
    ->from('dts_franchiss a')
    ->where('idx_merchant', $idx_merchant)
    ->limit($length, $start);

    $data = $this->db->get()->result_array();
    return $data;
  }

  public function getCouponFranList($idx_merchant)
  {
    $start = $this->input->post('start');
    $length = $this->input->post('length');
    $draw = $this->input->post('draw');

    $return_obj = array();

    $idx_merchant = $this->input->post('idx_merchant');
    // $idx_user = $this->session->userdata('g_manager')->idx_user;
    $this->db->select('a.*')
    ->from('dts_franchiss a')
    ->where('idx_merchant', $idx_merchant)
    ->limit($length, $start);

    $data = $this->db->get()->result_array();
    return $data;
  }

  public function getItem($where){
    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $this->db->select('a.*')
    ->select('c.user_offer_key, c.is_use')
    ->select('(select name_kor from dts_merchant where idx_merchant = a.idx_merchant) name_kor')
    ->select('(select name_eng from dts_merchant where idx_merchant = a.idx_merchant) name_eng')
    ->select('(select cardcom_logo from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_logo')
    ->select('(select cardcom_nm from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_nm')
    ->select('(select cardcom_nm_eng from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_nm_eng')
    ->select('(select desc_kor from dts_merchant where idx_merchant = a.idx_merchant) desc_kor')
    ->select('(select desc_eng from dts_merchant where idx_merchant = a.idx_merchant) desc_eng')
    ->select("(select count(*) from dts_user_offer_favorite where offer_serial_num = a.offer_serial_num and idx_user = ".$idx_user.") is_heart")
    ->from('dts_offer a')
    ->join('dts_card b', 'a.idx_card = b.idx_card')
    ->join('dts_user_offer c', "a.offer_serial_num = c.offer_serial_num and c.idx_user = '$idx_user'", 'left outer')
    ->where('a.offer_serial_num', $where);
    $data = $this->db->get()->result_array();

    if(empty($data)){
      return null;
    }

    return $data[0];
  }

  public function updateFavorite(){
    $offer_serial_num = $this->input->post('offer_serial_num');
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $return_obj = array();
    $cnt_result = $this->db->from('dts_user_offer_favorite')->where('offer_serial_num', $offer_serial_num)->where('idx_user', $idx_user)->count_all_results();
    if($cnt_result == 0){
      $data = array(
         "offer_serial_num" => $offer_serial_num
        ,"idx_user" => $idx_user
      );
      $this->db->insert('dts_user_offer_favorite', $data);
      $return_obj['status'] = '1';
    }else{
      $where = array(
         "offer_serial_num" => $offer_serial_num
        ,"idx_user" => $idx_user
      );
      $this->db->delete('dts_user_offer_favorite', $where);
      $return_obj['status'] = '0';
    }

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
  public function insertGradeItem($idx_user,$offer_serial_num,$score)
  {

    $sql = $this->db->query("insert into dts_offer_grade (offer_serial_num, idx_user, grade) VALUES ('".$offer_serial_num."',".$idx_user.",".$score.") ON DUPLICATE KEY UPDATE grade = ".$score);


    //평균 업데이트
    $grade = $this->db->select('a.*')
    ->select('(SELECT avg(grade) FROM dts_offer_grade WHERE offer_serial_num = a.offer_serial_num) grade')
    ->where('offer_serial_num',$offer_serial_num)
    ->from('dts_offer a')
    ->get()->row()->grade;

    $data = array(
      "grade_avg" => $grade
    );

    $where = array(
      "offer_serial_num" => $offer_serial_num
    );

    $this->db->update('dts_offer', $data, $where);
    //평균 업데이트 끝


    $resultCount = $this->db->affected_rows(); // insert 및 update를 할때에는  $객체 =  $this->db->affected_rows();
    if($resultCount > 0){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = $grade;
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }
    return $return_obj;
  }

  public function getScoreList($offer_serial_num)
  {
    $serial_num = $offer_serial_num;
    $this->db->select(' * ')->from('dts_offer_grade')->where('offer_serial_num',$serial_num);
    $this->db->query('select count(grade) num1 FROM dts_offer_grade WHERE offer_serial_num ='.$serial_num)->result();
    $data = $this->db->get()->result_array();
    return $data;
  }
  public function getCardBenefits($merchant_list)
  {

    $return_obj = array();

    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $this->db->select('a.offer_serial_num, a.idx_card, a.idx_merchant')
    // ->select('(select cardcom_logo from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_logo')
    ->select('(select cardcom_nm from dtc_cardcom where cd_cardcom = b.cd_cardcom) cardcom_nm')
    ->select("(select count(*) from dts_user_offer_favorite where offer_serial_num = a.offer_serial_num and idx_user = ".$idx_user.") is_heart")
    ->from('dts_offer a')
    ->join('dts_card b', 'a.idx_card = b.idx_card')
    ->where('is_apply', 'Y');


    if(!empty($merchant_list) && $merchant_list > 0){
      $this->db->where_in("idx_merchant",$merchant_list);
    }

    $data = $this->db->get()->result_array();

    // echo $this->db->last_query(); exit;
    return $data;
  }

  public function deleteItem($where){
    $return_obj = array();
    $this->db->delete("dts_user_offer", $where);

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
