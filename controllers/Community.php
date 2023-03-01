<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Community extends MY_Controller {
  public function __construct()
  {
     parent::__construct();
    $this->load->model("community/Community_Model");
    $this->load->helper('date');
    $this->load->model("file/File_Model");
    $this->load->model("common/Member_Model");
    $this->load->model('search/HashTag_Model');
    $this->load->model('code/Code_Model');
  }
  public function magazine()
  {
    $data['top_menu_title'] = '';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = true;
    $data['top_right_btn_use'] = true;
    $data['top_right2_btn_use'] = true;
    $data['top_right_btn_url'] = "community/community_message";
    $data['top_right2_btn_url'] = "community/community_search_screen";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_nm'] = '';
    $data['bottom_search_btn_use'] = true;
    $data['bottom_search_img1_src'] = true;
    $data['bottom_search_img2_src'] = false;
    $data['bottom_community_btn_use'] = true;
    $data['bottom_community_img1_src'] = false;
    $data['bottom_community_img2_src'] = true;
    $data['bottom_ticket_btn_use'] = true;
    $data['bottom_ticket_img1_src'] = true;
    $data['bottom_ticket_img2_src'] = false;
    $data['bottom_card_btn_use'] = true;
    $data['bottom_card_img1_src'] = true;
    $data['bottom_card_img2_src'] = false;
    $data['bottom_myprofile_btn_use'] = true;
    $data['bottom_myprofile_img1_src'] = true;
    $data['bottom_myprofile_img2_src'] = false;
    $this->load->view('community/magazine', $data);
  }
  public function community_magazine_contents()
  {
    $data['top_menu_title'] = '';
    $data['top_left_btn_use'] = false;
    $data['top_left2_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';
    $data['top_right3_btn_use'] = false;

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;
        $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    } else {
        $tp_language = $this->session->userdata('g_manager')->tp_language;
    }

    $data['tp_language'] = $tp_language;

    $data['data'] = $this->Community_Model->getMagazineItem($this->input->get('idx_magazine'));
    // $data['comments'] = $this->Community_Model->getMagazineComments($this->input->get('idx_magazine'));

    $this->load->view('community/community_magazine_contents', $data);
  }
  public function community_community()
  {
    $data['top_menu_title'] = '';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = true;
    $data['top_right_btn_use'] = true;
    $data['top_right2_btn_use'] = true;
    $data['top_right_btn_url'] = "community/community_message";
    $data['top_right2_btn_url'] = "community/community_search_screen";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_nm'] = '';
    $data['bottom_search_btn_use'] = true;
    $data['bottom_search_img1_src'] = true;
    $data['bottom_search_img2_src'] = false;
    $data['bottom_community_btn_use'] = true;
    $data['bottom_community_img1_src'] = false;
    $data['bottom_community_img2_src'] = true;
    $data['bottom_ticket_btn_use'] = true;
    $data['bottom_ticket_img1_src'] = true;
    $data['bottom_ticket_img2_src'] = false;
    $data['bottom_card_btn_use'] = true;
    $data['bottom_card_img1_src'] = true;
    $data['bottom_card_img2_src'] = false;
    $data['bottom_myprofile_btn_use'] = true;
    $data['bottom_myprofile_img1_src'] = true;
    $data['bottom_myprofile_img2_src'] = false;


    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $my_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    $data['my_language'] = $my_language;
    $this->load->view('community/community_community', $data);
  }
  public function add_comment()
  {
    $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0071"></span>'; //글 작성
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = true;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "community/community_community";
    $data['top_right_btn_nm'] = '<span data-i18n="lanstr.lcode_0075">등록</span>';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $my_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    $data['my_language'] = $my_language;
    $data['tp_language'] = $this->input->get("tp_language");
    $this->load->view('community/add_comment', $data);
  }
  public function community_message()
  {
    $data['top_menu_title'] = '<span data-i18n="lanstr.lcode_0183"></span>'; //메시지
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "community/community_community";
    $data['top_right2_btn_nm'] = '';


    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $data['list'] = $this->Community_Model->getChatRoomList($idx_user);

//    $data['list'] = [];
    $this->load->view('community/community_message', $data);
  }
  public function personal_chat()
  {
    $data['top_menu_title'] = '채팅';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = true;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = "";
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = true;
    $data['top_right2_btn_url'] = "community/community_community";
    $data['top_right2_btn_nm'] = '';

    $target_idx = $this->input->get("target_idx");
    $room = $this->input->get("room");

    $target_member = $this->Member_Model->getItem($target_idx);
    if(!empty($target_member["nick_name"])){
        $data['top_menu_title'] = $target_member["nick_name"];
    }else if(!empty($target_member["email"])){
        $data['top_menu_title'] = $target_member["email"];
    }

    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $chat_message = array();
    if($room == "0"){ //룸 생성
      $room = $this->Code_Model->uuidgen();
      $this->Community_Model->createRoom($room, $idx_user, $target_idx);
      $this->load->helper('url');
      redirect('/community/personal_chat?room='.$room.'&target_idx='.$target_idx);

    }else{ // 기존 룸 가져오기
      $chat_message = $this->Community_Model->getChatRoomMessageList($room, $idx_user);
    }

    $data['chat_message'] = $chat_message;
    $data['target_idx'] = $target_idx;
    $data['current_idx'] = $idx_user;
    $data['room'] = $room;

    $this->load->view('community/personal_chat', $data);
  }
  public function opponent_profile()
  {
    $data['top_menu_title'] = '';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = false;
    $data['top_right_btn_use'] = false;
    $data['top_right_btn_url'] = '';
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = '';
    $data['top_right2_btn_nm'] = '';
    $data['top_right3_btn_use'] = false;
    $data['top_right3_btn_url'] = "community/community_community";
    $data['top_right3_btn_nm'] = '';

    if(isset($this->session->userdata('g_manager')->idx_user)) {
        $idx_user = $this->session->userdata('g_manager')->idx_user;

        if($this->input->get("idx_user") == $idx_user){
          $data['is_my'] = true;
          $data['chatData'] = null;
        }else{
          $data['is_my'] = false;
          $rdata = $this->Community_Model->getChatRoomInfo($idx_user, $this->input->get("idx_user"));
          if(empty($rdata)){
              $data['chatData'] = "0";
          }else{
            $data['chatData'] = $rdata->room_key;
          }
        }
    } else {
        $data['is_my'] = false;
        $data['chatData'] = "0";
    }

    $data['target_idx'] = $this->input->get("idx_user");
    $data['data'] = $this->Member_Model->getItem($this->input->get("idx_user"));

    $this->load->view('community/opponent_profile', $data);
  }
  public function article_details()
  {
    $data['top_menu_title'] = '';
    $data['top_left_btn_use'] = true;
    $data['top_left_btn_url'] = '';
    $data['top_left_btn_nm'] = '';
    $data['top_right_btn_use'] = false; //공유하기
    $data['top_right_btn_url'] = '';
    $data['top_right_btn_nm'] = '';
    $data['top_right2_btn_use'] = true;
    $data['top_right2_btn_url'] = '';
    $data['top_right2_btn_nm'] = '';

    $data['data'] = $this->Community_Model->getItem($this->input->get("idx_board"));
    $data['data_img'] = $this->Community_Model->getItemFiles($this->input->get("idx_board"));
    // $data['comments'] = $this->Community_Model->getComments($this->input->get('idx_board'));
    $data['data_tag'] = $this->Community_Model->getItemTags($this->input->get("idx_board"));

    $this->Community_Model->incrementCnt($this->input->get("idx_board"));

    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    $data['tp_language'] = $tp_language;

    $this->load->view('community/article_details', $data);
  }
  public function community_search_results()
  {
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $tp_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    $data['tp_language'] = $tp_language;
    $data['search_text'] = $this->input->get('search_text');

    $this->Community_Model->incrementCnt2($this->input->get('search_text'));
    $this->load->view('community/community_search_results', $data);
  }
  public function community_search_screen()
  {
    $data['search_text'] = $this->input->get('search_text');

    $data['categorys'] = $this->HashTag_Model->getCategoryList("board");
    $this->load->view('community/community_search_screen', $data);
  }
  public function community_search_screen_input()
  {
    $data['search_text'] = $this->input->get('search_text');
    $this->load->view('community/community_search_screen_input', $data);
  }

  public function getList(){
    echo json_encode($this->Community_Model->getList());
  }

  public function getCommentList(){
    echo json_encode($this->Community_Model->getComments($this->input->post('idx_board')));
  }

  public function getMyComment(){
    echo json_encode($this->Community_Model->getMyComments($this->input->post('comment_key')));
  }

  public function getMagazineCommentList(){
    echo json_encode($this->Community_Model->getMagazineComments($this->input->post('idx_magazine')));
  }

  public function getMagazineList(){
    echo json_encode($this->Community_Model->getMagazineList());
  }

  public function insertCommunity(){
    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $tag_nm = $this->input->post('tag_nm');

    $data = array(
        "idx_board_mng" => $this->input->post("idx_board_mng")
      , "title" => $this->input->post("title")
      , "contents" => $this->input->post("contents")
      , "insert_user" => $idx_user
      , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
      , "tp_language" => $this->input->post("tp_language")
    );
    $idx_board = $this->Community_Model->insertItem($data);

    if(!empty($tag_nm)){
      $data_tag = array();
      foreach($tag_nm as $key => $idx){
        $data_tag[] = array(
            "tp_table" => "board"
          , "idx_item" => $idx_board
          , "order_no" => $key+1
          , "tag_nm" => $idx
        );
      }
      $this->Community_Model->insertTag($data_tag);
    }
    if(!empty($_FILES['thumb_file']['name'])){
      $this->File_Model->upload("thumb_file", 2, $idx_board);
    }

    $return_obj = array();
    if($idx_board != 0){
      $return_obj["rcode"] = "1";
      $return_obj["rmsg"] = "";
    }else{
      $return_obj["rcode"] = "0";
      $return_obj["rmsg"] = "Error!!";
    }

    echo json_encode($return_obj);
  }

  public function insertComment(){
    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $data = array(
        "idx_board" => $this->input->post("idx_board")
      , "title" => $this->input->post("title")
      , "contents" => $this->input->post("contents")
      , "insert_user" => $idx_user
      , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
    );
    $comment_key = $this->Community_Model->insertComment($data);
    echo json_encode($this->File_Model->upload2("thumb_file", "community", "board_comment", $comment_key));
  }

  public function insertMagazineComment(){
    $idx_user = $this->session->userdata('g_manager')->idx_user;

    $data = array(
        "idx_magazine" => $this->input->post("idx_magazine")
      , "title" => $this->input->post("title")
      , "contents" => $this->input->post("contents")
      , "insert_user" => $idx_user
      , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
    );
    $comment_key = $this->Community_Model->insertMagazineComment($data);
    echo json_encode($this->File_Model->upload2("thumb_file", "community", "magazine_comment", $comment_key));
  }

  public function getHashTagList(){
    echo json_encode($this->HashTag_Model->getList());
  }
  public function deleteBoard(){
    $data = array(
      "is_del" => "Y"
    );

    $where = array(
      "idx_board" => $this->input->post("idx_board")
    );

    $this->db->select('a.*')
    ->select('(select nick_name from dts_user where idx_user = a.insert_user) nick_name')
    ->select('(select profile_img from dts_user where idx_user = a.insert_user) profile_img')
    ->select("(select img_name from dtc_image where tp_table = 'board_comment' and idx_item = a.comment_key) img_name")
    ->select("(select img_save from dtc_image where tp_table = 'board_comment' and idx_item = a.comment_key) img_save")
    ->from('dts_board_comment a')
    ->where('idx_board', $this->input->post("idx_board"))
    ->where('cd_status', '1')
    ->order_by("dt_insert", "desc");

      $comment_data = $this->db->get()->result_array();
      foreach ($comment_data as $key => $value) {
      $data2 = array(
        "cd_status" => "0"
      );
      $where2 = array (
       "comment_key" => $value["comment_key"]
      );
      $this->Community_Model->updateCommentItem($data2,$where2);
      }

      echo json_encode($this->Community_Model->updateItem($data, $where));
  }

  public function article_modify(){
    $data['top_menu_title'] = '<p data-i18n="lanstr.lcode_0175">글 수정</p>';
    $data['top_left_btn_use'] = true;
    $data['top_left2_btn_use'] = true;
    $data['top_right_btn_use'] = true;
    $data['top_right_btn_url'] = "community/article_details?idx_board=".$this->input->get("idx_board");;
    $data['top_right_btn_nm'] = '<span data-i18n="lanstr.lcode_0081">수정</span>';
    $data['top_right2_btn_use'] = false;
    $data['top_right2_btn_url'] = "";
    $data['top_right2_btn_nm'] = '';

    $idx_user = $this->session->userdata('g_manager')->idx_user;
    $my_language = $this->db->select(' * ')->from('dts_user')->where('idx_user',$idx_user)->get()->row()->tp_language;
    $data['my_language'] = $my_language;

    $this->db->select(' * ')
    ->from('dtc_board')
    ->where('idx_board',$this->input->get("idx_board"));
    $bitem = $this->db->get()->row();

    $this->db->select(' * ')
    ->from('dtc_board_file')
    ->where('idx_board',$this->input->get("idx_board"));
    $data['img_list'] = $this->db->get()->result();

    $btag = $this->Community_Model->getItemTags($this->input->get("idx_board"));
    if(empty($bitem)){
      $data['board_item'] = null;
    }else{
        $data['board_item'] = $bitem;
        if(!empty($btag)){
        $data['board_tag'] = $btag;
        }
      $this->load->view('community/article_modify', $data);
    }
  }
  public function updateCommunity(){
    $tag_nm = $this->input->post('tag_nm');
    $where = array(
      "idx_board" => $this->input->post("idx_board")
    );

    $data = array(
      "idx_board_mng" => $this->input->post("idx_board_mng")
    , "title" => $this->input->post("title")
    , "contents" => $this->input->post("contents")
    , "insert_user" => $this->session->userdata('g_manager')->idx_user
    , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
    , "tp_language" => $this->input->post("tp_language")
    );

    if(!empty($_FILES['thumb_file']['name'])){
      $this->File_Model->upload("thumb_file", 2, $this->input->post("idx_board"));
    }

    if(!empty($tag_nm)){
      $tag_data = array(
        "tp_table" => "board"
      , "idx_item" => $this->input->post("idx_board")
      );
      $this->Community_Model->deleteTag($tag_data);
      $data_tag = array();
      foreach($tag_nm as $key => $idx){
        $data_tag[] = array(
            "tp_table" => "board"
          , "idx_item" => $this->input->post("idx_board")
          , "order_no" => $key+1
          , "tag_nm" => $idx
        );
      }
      $this->Community_Model->insertTag($data_tag);
    }else{
      $tag_data = array(
        "tp_table" => "board"
      , "idx_item" => $this->input->post("idx_board")
      );
      $this->Community_Model->deleteTag($tag_data);
    }

    echo json_encode($this->Community_Model->updateItem($data,$where));
  }

  public function deleteBoardComment(){
    $where = array(
      "comment_key" => $this->input->post("comment_key")
    );
    echo json_encode($this->Community_Model->deleteBoardComment($where));
  }

  public function deleteMagazineComment(){
    $where = array(
      "comment_key" => $this->input->post("comment_key")
    );
    echo json_encode($this->Community_Model->deleteMagazineComment($where));
  }

  public function UpdateMagazineComment(){
    $where = array(
      "comment_key" => $this->input->post("comment_key")
    );

    $data = array(
      "contents" => $this->input->post("contents")
    , "dt_insert" => mdate('%Y-%m-%d %H:%i:%s', now())
    );

    echo json_encode($this->Community_Model->updateCommentItem($data, $where));
  }

  public function setAddMessage(){
    $idx_user = $this->session->userdata('g_manager')->idx_user;


    $room_key = $this->input->post("room");
    $target_idx = $this->input->post("target_idx");
    $current_idx = $this->input->post("current_idx");
    $message = $this->input->post("message");
    $msg_key = $this->Code_Model->uuidgen();

    $data = array(
        "msg_key" => $msg_key
      , "room_key" =>$room_key
      , "idx_user" => $idx_user
      , "is_read" => "N"
      , "message" => $message
      , "dt_send" => mdate('%Y-%m-%d %H:%i:%s', now())
      , "dt_apply" => mdate('%Y-%m-%d %H:%i:%s', now())
    );
    $rData = $this->Community_Model->addChatMessage($data);
    echo json_encode($rData);
  }
  // public function deleteImg(){ 2차 사업
  //   $file_key = $this->input->post("file_key");
  //   $save_nm = $this->db->select(' * ')->from('dtc_board_file')->where('file_key',$file_key)->get()->row()->save_nm;
  //   unlink('assets/upload/community/'.$save_nm);
  //   $where = array(
  //   'file_key' => $file_key
  //   );
  //   echo json_encode($this->Community_Model->deleteImgItem($where));
  // }
}
