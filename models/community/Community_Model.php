<?php
class Community_Model extends CI_Model {

    public function __construct()
    {
      $this->load->database();
    }

    public function getList()
    {
        $idx_board_mng = $this->input->post('idx_board_mng');
        $tp_language = $this->input->post('tp_language');
        $page = $this->input->post('page');
        $pageCnt = $this->input->post('pageCnt');

        $hashtag = $this->input->post('hashtag');

        $insert_user = $this->input->post('insert_user');
        $return_obj = array();

        $this->db->select('a.*')
        ->select('(select count(*) from dts_board_comment where idx_board = a.idx_board) cnt_comment')
        ->select('(select nick_name from dts_user where idx_user = a.insert_user) nick_name')
        ->select('(select profile_img from dts_user where idx_user = a.insert_user) profile_img')
        ->select('(select count(*) from dtc_board_file where idx_board = a.idx_board) cnt_file')
        ->select("replace(a.contents, '\n', '</br>') contents")
        ->from('dtc_board a')
        ->where('idx_board_mng', $idx_board_mng)
        ->where('tp_language', $tp_language)
        ->where('cd_status', '1')
        ->where('is_del', 'N')
        ->order_by('a.dt_insert', 'desc');

        if($insert_user != ''){
          $this->db->where('insert_user', $insert_user);
        }

        if(!empty($hashtag)){
          $this->db->where("idx_board in (select idx_item from dts_tag where tp_table = 'board' and tag_nm like '%".$hashtag."%')");
        }

        if(!empty($page) && !empty($pageCnt)){
          $this->db->limit($pageCnt, ($page-1)*$pageCnt);
        }

        $data = $this->db->get()->result_array();

        $this->db->select('count(*) cnt')
        ->from('dtc_board a')
        ->where('idx_board_mng', $idx_board_mng)
        ->where('tp_language', $tp_language)
        ->where('cd_status', '1');

        if($insert_user != ''){
          $this->db->where('insert_user', $insert_user);
        }

        if(!empty($hashtag)){
          $this->db->where("idx_board in (select idx_item from dts_tag where tp_table = 'board' and tag_nm like '%".$hashtag."%')");
        }

        $data_count = $this->db->get()->row();

        $this->db->select('a.*')
        ->from('dtc_board_file a')
        ->where("idx_board in (select idx_board from dtc_board where idx_board_mng = '$idx_board_mng' and tp_language = '$tp_language' and cd_status = '1')");
        if($insert_user != ''){
          $this->db->where('insert_user', $insert_user);
        }
        $data_img = $this->db->get()->result_array();

        $return_obj["data"] = $data;
        $return_obj["data_img"] = $data_img;
        $return_obj["rcode"] = "1";
        $return_obj["rmsg"] = "";
        $return_obj["recordsTotal"] = $data_count->cnt;
        $return_obj["recordsFiltered"] = $data_count->cnt;

        return $return_obj;
    }
    public function getNoticeList()
    {
      $return_obj = array();
      $tp_language = $this->session->userdata('g_manager')->tp_language;

      $this->db->select('*')
      ->from('dtc_board')
      ->where('idx_board_mng', '2')
      ->where('tp_language',$tp_language)
      ->where('is_del', 'N');
      $data = $this->db->get()->result_array();

      return $data;
    }
    public function getMagazineList()
    {
        $tp_language = $this->input->post('tp_language');
        $page = $this->input->post('page');
        $pageCnt = $this->input->post('pageCnt');
        $hashtag = $this->input->post('hashtag');

        $insert_user = $this->input->post('insert_user');
        $return_obj = array();

        $this->db->select('a.idx_magazine, a.title,  a.tp_language,  a.img_thumbnail,  a.dt_insert,  a.is_del  ')

        ->select('(select name from dta_admin where idx_admin = a.insert_user) nick_name')
        ->select("(SELECT img_save FROM dtc_image WHERE tp_table = 'magazine' and idx_item = a.idx_magazine order by item_no limit 1) images")
        // ->select("replace(a.contents, '\n', '</br>') contents")
        ->from('dts_magazine a')
        ->where('tp_language', $tp_language)
        ->where('is_del', 'N')
        ->order_by('a.dt_insert', 'desc');

        // if($insert_user != ''){
        //   $this->db->where('insert_user', $insert_user);
        // }

        if(!empty($hashtag)){
          $this->db->where("idx_magazine in (select idx_item from dts_tag where tp_table = 'magazine' and tag_nm like '%".$hashtag."%')");
        }

        if(!empty($page) && !empty($pageCnt)){
          $this->db->limit($pageCnt, ($page-1)*$pageCnt);
        }

        $data = $this->db->get()->result_array();

        $this->db->select('count(*) cnt')
        ->from('dts_magazine a')
        ->where('tp_language', $tp_language)
        ->where('is_del', 'N');

        if($insert_user != ''){
          $this->db->where('insert_user', $insert_user);
        }

        if(!empty($hashtag)){
          $this->db->where("idx_magazine in (select idx_item from dts_tag where tp_table = 'magazine' and tag_nm like '%".$hashtag."%')");
        }

        $data_count = $this->db->get()->row();

        // $this->db->select('a.*')
        // ->from('dtc_board_file a')
        // ->where("idx_board in (select idx_board from dtc_board where idx_board_mng = '$idx_board_mng' and tp_language = '$tp_language' and cd_status = '1')");
        // if($insert_user != ''){
        //   $this->db->where('insert_user', $insert_user);
        // }
        // $data_img = $this->db->get()->result_array();

        $return_obj["data"] = $data;
        // $return_obj["data_img"] = $data_img;
        $return_obj["rcode"] = "1";
        $return_obj["rmsg"] = "";
        $return_obj["recordsTotal"] = $data_count->cnt;
        $return_obj["recordsFiltered"] = $data_count->cnt;

        return $return_obj;
    }

    public function getComments($where){
      $return_obj = array();

      $this->db->select('a.*')
      ->select('(select nick_name from dts_user where idx_user = a.insert_user) nick_name')
      ->select('(select profile_img from dts_user where idx_user = a.insert_user) profile_img')
      ->select("(select img_name from dtc_image where tp_table = 'board_comment' and idx_item = a.comment_key) img_name")
      ->select("(select img_save from dtc_image where tp_table = 'board_comment' and idx_item = a.comment_key) img_save")
      ->from('dts_board_comment a')
      ->where('idx_board', $where)
      ->where('cd_status', '1')
      ->order_by("dt_insert", "desc");

      $data = $this->db->get()->result_array();

      $return_obj['data'] = $data;

      return $return_obj;
    }

    public function getMyComments($where){
      $return_obj = array();
	    $this->db->select(' * ')
  	  ->from('dts_board_comment')
      ->where('comment_key',$where);

      $data = $this->db->get()->row();

      $return_obj['data'] = $data;

      return $return_obj;
    }

    public function getMagazineComments($where){
      $return_obj = array();

      $this->db->select('a.*')
      ->select('(select nick_name from dts_user where idx_user = a.insert_user) nick_name')
      ->select('(select profile_img from dts_user where idx_user = a.insert_user) profile_img')
      ->select("(select img_name from dtc_image where tp_table = 'magazine_comment' and idx_item = a.comment_key) img_name")
      ->select("(select img_save from dtc_image where tp_table = 'magazine_comment' and idx_item = a.comment_key) img_save")
      ->from('dts_magazine_comment a')
      ->where('idx_magazine', $where)
      ->where('cd_status', '1')
      ->order_by("dt_insert", "desc");

      $data = $this->db->get()->result_array();

      $return_obj['data'] = $data;

      return $return_obj;
    }

    public function getItem($where){
      $this->db->select('a.*')
      ->select('(select count(*) from dts_board_comment where idx_board = a.idx_board) cnt_comment')
      ->select('(select nick_name from dts_user where idx_user = a.insert_user) nick_name')
      ->select('(select profile_img from dts_user where idx_user = a.insert_user) profile_img')
      ->select("replace(a.contents, '\n', '<br>') contents")

      ->from('dtc_board a')
      ->where('idx_board', $where);
      $data = $this->db->get()->result_array();
      // echo $this->db->last_query(); exit;
      if(empty($data)){
        return null;
      }

      return $data[0];
    }

    public function getNoticeItem($where){
      $this->db->select('a.*')
      ->from('dtc_board a')
      ->where('idx_board_mng', '2')
      ->where('idx_board',$where)
      ->where('is_del', 'N')
      ->select('(select user_nm from dts_user where idx_user = a.insert_user) user_nm');
      $data = $this->db->get()->result_array();

      if(empty($data)){
        return null;
      }

      return $data[0];
    }

    public function getMagazineItem($where){
      $this->db->select('a.*')
      ->select("replace(a.contents, '\n', '<br>') contents")

      ->from('dts_magazine a')
      ->where('idx_magazine', $where);
      $data = $this->db->get()->result_array();
      if(empty($data)){
        return null;
      }

      return $data[0];
    }

    public function getItemFiles($where){
      $this->db->select('a.*')
      ->from('dtc_board_file a')
      ->where('idx_board', $where);
      $data = $this->db->get()->result_array();

      if(empty($data)){
        return null;
      }

      return $data;
    }

    public function getItemTags($where){
      $this->db->select('a.*')
      ->from('dts_tag a')
      ->where('tp_table', "board")
      ->where('idx_item', $where)
      ->order_by("order_no");
      $data = $this->db->get()->result_array();

      // if(empty($data)){
      //   return null;
      // }

      return $data;
    }

    // 게시글 등록
    public function insertItem($data){
      $return_obj = array();

      $this->db->insert("dtc_board", $data);

      $resultCount = $this->db->affected_rows();
      if($resultCount > 0){
        $return_obj["rcode"] = "1";
        $return_obj["rmsg"] = "";
      }else{
        $return_obj["rcode"] = "0";
        $return_obj["rmsg"] = "Error!!";
      }
      // return $return_obj;
      return $this->db->insert_id();
    }

    // 댓글 등록
    public function insertComment($data){
      $return_obj = array();

      $this->db->insert("dts_board_comment", $data);

      $resultCount = $this->db->affected_rows();
      if($resultCount > 0){
        $return_obj["rcode"] = "1";
        $return_obj["rmsg"] = "";
      }else{
        $return_obj["rcode"] = "0";
        $return_obj["rmsg"] = "Error!!";
      }
      // return $return_obj;
      return $this->db->insert_id();
    }

    public function insertTag($data){
      $return_obj = array();

      $this->db->insert_batch("dts_tag", $data);

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
    public function deleteTag($data){
      $return_obj = array();
      $this->db->delete("dts_tag", $data);

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

    public function deleteImgItem($data){
      $return_obj = array();
      $this->db->delete("dtc_board_file", $data);

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

    // 커뮤니티 댓글 삭제
    public function deleteBoardComment($where){
      $return_obj = array();
      $this->db->delete("dts_board_comment", $where);

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

    // 매거진 댓글 등록
    public function insertMagazineComment($data){
      $return_obj = array();

      $this->db->insert("dts_magazine_comment", $data);

      $resultCount = $this->db->affected_rows();
      if($resultCount > 0){
        $return_obj["rcode"] = "1";
        $return_obj["rmsg"] = "";
      }else{
        $return_obj["rcode"] = "0";
        $return_obj["rmsg"] = "Error!!";
      }
      // return $return_obj;
      return $this->db->insert_id();
    }

    public function deleteMagazineComment($where){
      $return_obj = array();

      $this->db->delete("dts_magazine_comment", $where);

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

      $this->db->update("dtc_board", $data, $where);

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

    public function updateCommentItem($data, $where){
      $return_obj = array();

      $this->db->update("dts_board_comment", $data, $where);

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

    public function incrementCnt($where){
      $return_obj = array();

      $cnt_view = $this->db->select('cnt_view')->from('dtc_board')->where('idx_board', $where)->get()->row()->cnt_view+1;

      $this->db->update("dtc_board", array('cnt_view'=>$cnt_view), array('idx_board'=>$where));

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

    public function incrementCnt2($where){
      $return_obj = array();
      $cnt_search = 0;

      $search_row = $this->db->select('cnt_search')->from('dtc_hashtag')->where('hashtag', $where)->get()->row();


      if(!empty($search_row)){
          $cnt_search = $search_row->cnt_search+1;
          $this->db->update("dtc_hashtag", array('cnt_search'=>$cnt_search), array('hashtag'=>$where));
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



// --- 채팅 관련
    public function getChatRoomList($pUseridx)
    {
      //  $return_obj = array();

        $query_room = "SELECT * ";
        $query_room .= " , (	SELECT mu2.idx_user FROM dts_message_user mu2 WHERE mu2.room_key = mr.room_key AND mu2.idx_user != ".$pUseridx." ) AS target_idx ";
        $query_room .= ", (	SELECT u.nick_name FROM dts_message_user mu2 inner join dts_user u ON u.idx_user = mu2.idx_user WHERE mu2.room_key = mr.room_key AND mu2.idx_user != ".$pUseridx." ) AS user_nm ";
        $query_room .= ", (	SELECT u.profile_img FROM dts_message_user mu2 inner join dts_user u ON u.idx_user = mu2.idx_user WHERE mu2.room_key = mr.room_key AND mu2.idx_user != ".$pUseridx." ) AS profile_img ";
        $query_room .= "FROM dts_message_room mr ";
        $query_room .= "WHERE mr.room_key IN ( ";
      	$query_room .= " SELECT mu.room_key FROM dts_message_user mu WHERE mu.idx_user =  " . $pUseridx;
        $query_room .= " ) ";
        $query_room .= "ORDER BY mr.dt_last desc ";
        $query_room .= "";

        $query = $this->db->query($query_room);
        $data = $query->result_array();

        //$return_obj["data"] = $data;

        return $data;
    }

    public function getChatRoomInfo($idx_user, $target_idx_user)
    {
      //  $return_obj = array();

        $query_room = " SELECT mu1.room_key FROM dts_message_user mu1  ";
        $query_room .= " INNER JOIN dts_message_user mu2 ON mu1.room_key = mu2.room_key AND mu2.idx_user =  " . $idx_user;
        $query_room .= " WHERE  mu1.idx_user =  " . $target_idx_user;

        $query = $this->db->query($query_room);
        $data = $query->row();

        //$return_obj["data"] = $data;

        return $data;
    }

    public function createRoom($uuid_code, $idx_user, $target_idx_user)
    {

        $now_date = mdate('%Y-%m-%d %H:%i:%s', now());
        $data_room = array();
        $data_room["room_key"] = $uuid_code;
        $data_room["dt_insert"] = $now_date;
        $data_room["dt_last"] = $now_date;
        $data_room["last_message"] = "";

        $this->db->insert("dts_message_room", $data_room);

        $data_room_user = array();
        $data_room_user["room_key"] = $uuid_code;
        $data_room_user["idx_user"] = $idx_user;
        $data_room_user["cd_status"] = "1";
        $data_room_user["dt_last"] =  $now_date;

        $this->db->insert("dts_message_user", $data_room_user);


        $data_room_user = array();
        $data_room_user["room_key"] = $uuid_code;
        $data_room_user["idx_user"] = $target_idx_user;
        $data_room_user["cd_status"] = "1";
        $data_room_user["dt_last"] =  $now_date;

        $this->db->insert("dts_message_user", $data_room_user);

        //$return_obj["data"] = $data;

       //  return $data;
    }

    public function addChatMessage($messagedata)
    {
      $return_obj = array();

      $this->db->insert("dts_message", $messagedata);

      $where_q = array();
      $where_q["room_key"] =  $messagedata["room_key"];

      $updateData = array();
      $updateData["last_message"] =  $messagedata["message"];
      $updateData["dt_last"] =  $messagedata["dt_send"];
      $this->db->update("dts_message_room", $updateData, $where_q );

      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
      return $return_obj;
    }

    public function getChatRoomMessageList($room_key, $idx_user)
    {
      //  $return_obj = array();

        $query_room = "SELECT * ";
        $query_room .= " , ( case when idx_user = ".$idx_user." then 1 else 0 end) AS is_me ";
        $query_room .= ", (	SELECT u.nick_name FROM dts_user u where u.idx_user = m.idx_user  ) AS user_nm ";
        $query_room .= "FROM dts_message m where room_key = '".$room_key."' ";
        $query_room .= "ORDER BY m.dt_send asc ";

        $query = $this->db->query($query_room);
        $data = $query->result_array();

        //$return_obj["data"] = $data;

        return $data;
    }


}
