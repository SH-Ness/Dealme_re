<?php

/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2017-10-26
 * Time: 오후 7:18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    /**
     * 해당 페이지가 로그인을 요구할지를 결정합니다.
     * false 이면 로그인이 필요치 않은 페이지입니다.
     */
    protected $isCheckPrivilegeController = true;

    /**
     * 현재 로그인한 유저의 정보를 보관합니다.
     */
    protected $g_manager = null; //success,warning,error, ...
    protected $g_manager_privilege = null;

    /**
     * A list of helpers to be auto-loaded
     */
    protected $helpers = ['url'];
    /**
     * A list of libraries to be auto-loaded
     */
    protected $libraries = ['session'];

    /**
     * A list of models to be autoloaded
     */
    protected $models = [];

    /**
     * A formatting string for the model autoloading feature.
     * The percent symbol (%) will be replaced with the model name.
     */
    protected $model_string = 'M%';

    /**
     * Page id
     */
    protected $page_id = '';

    /*
     * Page Title
     * */
    protected $page_title = '';

    /*
     * Layout Style
     *  - LAYOUT_STYLE_BEFORE_LOGIN
     *  - LAYOUT_STYLE_HAS_NAVBAR
     * */
    protected $is_template = false;

    public function __construct()
    {
        parent::__construct();

        /* 2022-05-09 추가 */
        if(!$this->session->has_userdata('g_manager')) {
            $arr = '{"tp_language": "kr"}';
            $this->session->set_userdata('g_manager', json_decode($arr));
        }

        // $this->_load_models();
        // $this->_load_helpers();
        // $this->_load_libraries();

        if (!$this->isCheckPrivilegeController)
            return;

        $this->load->library('session');
        if (!$this->session->has_userdata('g_manager')) {
            if (!$this->input->is_ajax_request()) {
                redirect('common/login_email');
            } else {
                redirect('common/login_email');
            }
        }

        $this->g_manager = $this->session->userdata('g_manager');
    }

    /* --------------------------------------------------------------
     * HELPER LOADING
     * ------------------------------------------------------------ */

    /**
     * Load helpers based on the $this->helpers array
     */
    private function _load_helpers()
    {
        foreach ($this->helpers as $helper) {
            $this->load->helper($helper);
        }
    }

    protected function _show_err_msg($error_msg = '', $error_class = 'success', $error_title = '알림')
    {
        $this->error_flag = true;
        $this->error_msg = $error_msg;
        $this->error_class = $error_class;
        $this->error_title = $error_title;

        $error = array(
            'error_flag' => $this->error_flag,
            'error_msg' => $this->error_msg,
            'error_class' => $this->error_class,
            'error_title' => $this->error_title,
        );

        $this->session->set_tempdata('error', $error, 30); //30초동안 오류메세지 보관.
    }

    /* --------------------------------------------------------------
        * MODEL LOADING
        * ------------------------------------------------------------ */

    /**
     * Load models based on the $this->models array
     */
    private function _load_models()
    {
        foreach ($this->models as $model) {
            $this->load->model($this->_model_name($model));
        }
    }

    /**
     * Returns the loadable model name based on
     * the model formatting string
     */
    protected function _model_name($model)
    {
        return str_replace('%', $model, $this->model_string);
    }

    /* --------------------------------------------------------------
     * LIBRARY LOADING
     * ------------------------------------------------------------ */

    /**
     * Load libraries based on the $this->libraries array
     */
    private function _load_libraries()
    {
        foreach ($this->libraries as $library) {
            $this->load->library($library);
        }
    }

    /**
     * Load view with layout
     * */
    public function load_view($path, $vars = array())
    {
        $html = $this->load->view($path, $vars, true);

        $data = array(
            'html_body' => $html,
            'ls_style' => $this->layout_style,
            'page_id' => $this->page_id,
            'page_title' => $this->page_title,
            'is_template' => $this->is_template
        );

        $this->load->view('layout/layout', $data);
    }

    /**
     * 파일 업로드 메서드
     * 요청으로 올라온 파일을  목적폴더에 저장한다.
     * 저장이 성공하면 파일명을 귀환한다.
     *
     * @param string $dir_path 업로드 폴더 경로
     * @param string $file_name 업로드 파일명
     * @param boolean $should_redirect 업로드실패시 redirect로 귀환할것인가 결정 // false : api 요청에 대한 응답으로 json으로 리턴
     * @param string $redirect_url 업로드에 실패할 경우 리턴할 URL
     * @param string $file_type 업로드 파일타입
     * @return string
     */

    protected function _file_upload($dir_path = 'temp', $file_name, $should_redirect = true, $redirect_url = '', $file_type = '*')
    {
        // $file_name 으로 올라온 파일이 없다면 빈 문짜열 귀환
        if (!isset($_FILES[$file_name]) || empty($_FILES[$file_name]['tmp_name'])) {
            return '';
        }

        $this->load->library('upload');

        $config['upload_path'] = _make_dir($dir_path);
        $config['allowed_types'] = $file_type;
        $config['file_name'] = _unique_string();
        $this->upload->initialize($config);

        if ($this->upload->do_upload($file_name)) {
            $dir_path = str_replace('-',"/", $dir_path);
            return $dir_path . '/' . $this->upload->data('file_name');
        } else {
            // 파일업로드 실패라면 에러메시지귀환, 실행중지
            if ($should_redirect) {
                $this->_show_res_msg($this->upload->display_errors('', ''), 'error', '오류');
                redirect($redirect_url == '' ? base_url('member/index') : $redirect_url);
            } else {
                die (json_encode([
                    'code' => 1,
                    'msg' => $this->upload->display_errors('', '')
                ]));
            }
        }

        return '';
    }

    /**
     * 다중파일업로드 메서드
     * 파라미터설명은 위메서드와 같다.
     *
     * @param string $dir_path
     * @param $file_name
     * @param bool $should_redirect
     * @param string $redirect_url
     * @param string $file_type
     * @return array
     */
    protected function _multi_file_upload($dir_path = 'temp', $file_name, $should_redirect = true, $redirect_url = '', $file_type = 'jpg|jpeg|png|mp4|mp3')
    {
        $files = [];

        // $file_name 으로 올라온 파일이 없다면 빈 문짜열 귀환
        if (empty($_FILES[$file_name]) || count($_FILES[$file_name]['name']) < 1) {
            return $files;
        }

        $this->load->library('upload');

        $config['upload_path'] = _make_dir($dir_path);
        $config['allowed_types'] = $file_type;

        for ($nInd = 0; $nInd < count($_FILES[$file_name]['name']); $nInd++) {
            if (!empty($_FILES[$file_name]['name'][$nInd])) {
                $config['file_name'] = _unique_string();
                $this->upload->initialize($config);

                $_FILES['server_upload_file']['name'] = $_FILES[$file_name]['name'][$nInd];
                $_FILES['server_upload_file']['type'] = $_FILES[$file_name]['type'][$nInd];
                $_FILES['server_upload_file']['tmp_name'] = $_FILES[$file_name]['tmp_name'][$nInd];
                $_FILES['server_upload_file']['error'] = $_FILES[$file_name]['error'][$nInd];
                $_FILES['server_upload_file']['size'] = $_FILES[$file_name]['size'][$nInd];

                if ($this->upload->do_upload('server_upload_file')) {
                    $dir_path = str_replace('-',"/", $dir_path);
                    array_push($files, $dir_path . "/" . $this->upload->data('file_name'));
                } else {
                    // 파일업로드 실패라면 에러메시지귀환, 실행중지
                    if ($should_redirect) {
                        $this->_show_res_msg($this->upload->display_errors('', ''), 'error', '오류');
                        redirect($redirect_url == '' ? base_url('member/index') : $redirect_url);
                    } else {
                        die (json_encode([
                            'code' => 1,
                            'msg' => $this->upload->display_errors('', '')
                        ]));
                    }
                }
            }
        }
        return $files;
    }

    protected function _set_temp_value($value)
    {
        $this->session->set_flashdata('wiz_temp', $value);
    }

    protected function _get_temp_value()
    {
        return $this->session->flashdata('wiz_temp');
    }

    public function _send_email($to, $subject, $message)
    {
        $this->load->library('email');
        $this->email->clear();

        $this->email->from(EMAIL_ADMIN_EMAIL);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $ret = $this->email->send();

        return $ret;
    }

    public function _send_push($device, $tokens, $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields['to'] = $tokens;
        if ($device == 1) {
            $f_key = 'data';
        } else {
            $f_key = 'notification';
        }
        $fields[$f_key] = array(
            'title' => $data['title'],
            'body' => $data['message'],
            'type' => $data['type'],
            'sound' => 'default',
            'badge' => 0,
            'content_available' => true
        );

        if ($data != null) {
            $fields[$f_key] = array_merge($fields[$f_key], $data);
        }

        $this->curl_request_async($url, $fields);

        return $fields;
    }

    function curl_request_async($url, $params, $type = 'POST')
    {
        $google_api_key = "AAAAoSjbVdo:APA91bHPULxoXuipAwoArqcIxg1WTeQ2qa6T6mxXSP7tAvjtnlpfyIgmD5KS1ivH39aOo9UVLMd_fQ19IA0qcPIpoCiqEykwJQ8EhXG-DJvo2t-eJU_komBnEfVkKJHQdfw8BvAA1cYa";
        $post_string = json_encode($params);

        $parts = parse_url($url);
        if ($parts['scheme'] == 'http') {
            $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
        } else if ($parts['scheme'] == 'https') {
            $fp = fsockopen("ssl://" . $parts['host'], isset($parts['port']) ? $parts['port'] : 443, $errno, $errstr, 30);
        }

        // Data goes in the path for a GET request
        if ('GET' == $type)
            $parts['path'] .= '?' . $post_string;

        $out = "$type " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Authorization: key=" . $google_api_key . "\r\n";
        $out .= "Content-Type: application/json\r\n";
        $out .= "Content-Length: " . strlen($post_string) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        // Data goes in the request body for a POST request
        if ('POST' == $type && isset($post_string))
            $out .= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }

    function _send_sms($phone, $msg)
    {
        $sms_url = "https://apis.aligo.in/send/"; // 전송요청 URL
        $sms['user_id'] = SMS_USER_ID; // SMS 아이디
        $sms['key'] = SMS_API_KEY;//인증키

        $_POST['msg'] = $msg; // 메세지 내용 : euc-kr로 치환이 가능한 문자렬만 사용하실 수 있습니다. (이모지 사용불가능)
        $_POST['receiver'] = $phone; // 수신번호
        $_POST['sender'] = SMS_SENDER_PHONE_NUMBER; // 발신번호
        $_POST['rdate'] = ''; // 예약일자 - 20161004 : 2016-10-04일기준
        $_POST['rtime'] = ''; // 예약시간 - 1930 : 오후 7시30분
        $_POST['testmode_yn'] = ''; // Y 인경우 실제문자 전송X , 자동취소(환불) 처리
        $_POST['subject'] = ''; //  LMS, MMS 제목 (미입력시 본문중 44Byte 또는 엔터 구분자 첫라인)
        $_POST['msg_type'] = 'SMS'; //  SMS, LMS, MMS등 메세지 타입을 지정
        $_POST['image'] = '';   // image_url

        $sms['msg'] = stripslashes($_POST['msg']);
        $sms['receiver'] = $_POST['receiver'];
        $sms['sender'] = $_POST['sender'];
        $sms['rdate'] = $_POST['rdate'];
        $sms['rtime'] = $_POST['rtime'];
        $sms['testmode_yn'] = empty($_POST['testmode_yn']) ? '' : $_POST['testmode_yn'];
        $sms['title'] = $_POST['subject'];
        $sms['msg_type'] = $_POST['msg_type'];

        $oCurl = curl_init();

        // 이미지 전송 설정
        if(!empty($_POST['image'])) {
            if(file_exists($_POST['image'])) {
                $tmpFile = explode('/',$_POST['image']);
                $str_filename = $tmpFile[sizeof($tmpFile)-1];
                $tmp_filetype = mime_content_type($_POST['image']);
                if ((version_compare(PHP_VERSION, '5.5') >= 0)) { // PHP 5.5 Version 이상부터 적용
                    $sms['image'] = new CURLFile($_POST['image'], $tmp_filetype, $str_filename);
                    curl_setopt($oCurl, CURLOPT_SAFE_UPLOAD, true);
                } else {
                    $sms['image'] = '@'.$_POST['image'].';filename='.$str_filename. ';type='.$tmp_filetype;
                }
            }
        }

        $host_info = explode("/", $sms_url);
        $port = $host_info[0] == 'https:' ? 443 : 80;

        curl_setopt($oCurl, CURLOPT_PORT, $port);
        curl_setopt($oCurl, CURLOPT_URL, $sms_url);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $ret = curl_exec($oCurl);
        curl_close($oCurl);

//        echo $ret;
        $retArr = json_decode($ret); // 결과배열
        if ($retArr->result_code == 1)
            return true;
        else
            return false;
//        print_r($retArr); // Response 출력 (연동작업시 확인용)
    }

    public function param_get($param)
    {
        return $this->quote_smart($this->input->get($param));
    }

    public function param_post($param)
    {
        return $this->quote_smart($this->input->post($param));
    }

    public function quote_smart($value)
    {
        // remove stripsladhes(/).
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        // Quote if not integer:
        $value = htmlspecialchars($value, ENT_QUOTES);
        $value = stripslashes($value);
        if (!is_numeric($value)) {
            //$value=mysql_real_escape_string($value);
        }

        return $value;
    }
    /**
     * returns ajax response data
     *      status  - EXIT_SUCCESS or EXIT_ERROR
     *      code    - if status is EXIT_ERROR, it will returns error code
     *      data    - any
     * */
    public function ajax_response($status = EXIT_SUCCESS, $code = RESULT_CODE_SUCCESS, $data = null)
    {
        return json_encode(array(
            'status' => $status,
            'code' => $code,
            'data' => $data
        ));
    }
}
