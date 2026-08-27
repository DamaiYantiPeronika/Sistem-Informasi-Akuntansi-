<?php
require_once APPPATH.'libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class Dompdf_gen {
    public function __construct() {
        $pdf = new Dompdf(); 
        $CI = & get_instance();
        $CI->dompdf = $pdf;
    }
}
