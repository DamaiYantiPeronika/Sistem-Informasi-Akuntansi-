<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        $CI = get_instance();
        $email = $CI->session->userdata('email');
        if ($email) {
            return $CI->db->get_where('user', ['email' => $email])->row_array();
        }
        return null;
    }
}

