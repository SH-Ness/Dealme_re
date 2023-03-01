<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "libraries/Social_login.php";

class Google_login extends Social_login {

    protected function _get_authorize_param() {
        $scope_array = array(
            "https://www.googleapis.com/auth/plus.login",
            "https://www.googleapis.com/auth/contacts",
            "https://www.googleapis.com/auth/userinfo.email",
            "https://www.googleapis.com/auth/userinfo.profile <a href='https://www.googleapis.com/auth/plus.me' target='_blank'>https://www.googleapis.com/auth/plus.me</a>");

        $param = parent::_get_authorize_param();
        $param['access_type'] = "offline";
        $param['scope'] = implode(" ", $scope_array);

        return $param;
    }

}
