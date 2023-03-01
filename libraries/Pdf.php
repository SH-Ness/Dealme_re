<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/dompdf/dompdf_config.custom.inc.php";
require_once APPPATH."/third_party/dompdf/dompdf_config.inc.php";
require_once APPPATH."/third_party/dompdf/dompdf.php";

class Pdf {

    function __construct($params = array())
    {
      parent::__construct();

    }

}

/* End of file Html2pdf.php */
