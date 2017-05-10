<?php

global $mailget_user;
$mailget_select = '';

class mailget_curl {

    function mailget_curl($mailget_api) {
        global $mailget_user;
        $url = "http://www.formget.com/mailget/mailget_api/user_validation";
        $data = array(
            'api_key' => $mailget_api
        );
        $result = $this->curl_call($url, $data);
        $result = json_decode($result);
        if (empty($result)) {
            $mailget_user = 'Invalid API Key';
        } elseif ($result->status == 'success') {
            $mailget_user = $result->api_key;
        } else {
            $mailget_user = 'Invalid API Key';
        }
    }

    function mailget_select_list($mailget_api) {
        global $mailget_user;
        $url = "http://www.formget.com/mailget/mailget_api/get_select_list";
        $data = array(
            'api_key' => $mailget_api
        );

        $result = $this->curl_call($url, $data);
        $result = json_decode($result);
        if ($result->status == 'success') {
            $mailget_select = $result->select;
        } else {
            $mailget_select = 'Invalid API Key.';
        }
        return $mailget_select;
    }

    function get_list_in_json($mailget_api) {
        global $mailget_user;
        $url = "http://www.formget.com/mailget/mailget_api/get_list_in_json";
        $data = array(
            'api_key' => $mailget_api
        );

        $result = $this->curl_call($url, $data);
        $result = json_decode($result);
        if (empty($result)) {
            $mailget_select = 'Invalid API Key';
        } elseif ($result->status == 'success') {
            $mailget_select = $result->contact_list;
        } else {
            $mailget_select = 'Invalid API Key';
        }
        return $mailget_select;
    }

    /* fUNCTION CURL Data */

    public function curl_data($arr, $list_id, $send_val = 'multiple') {
        global $mailget_user;
        $main_contact_arr = array();
        if ($mailget_user != 'Invalid API Key') {
            if (!empty($arr)) {
                $url = "http://www.formget.com/mailget/mailget_api/save_data";
                foreach ($arr as $arr_row) {
                    if (isset($arr_row['name']) && isset($arr_row['email']) && isset($arr_row['get_date']) && isset($arr_row['ip']) && filter_var(trim($arr_row['email']), FILTER_VALIDATE_EMAIL)) {
                        $contact_arr['name'] = $arr_row['name'];
                        $contact_arr['email'] = $arr_row['email'];
                        $contact_arr['date'] = $arr_row['get_date'];
                        $contact_arr['ip'] = $arr_row['ip'];
                        $main_contact_arr[$arr_row['email']] = $contact_arr;
                    }
                }
                if (!empty($main_contact_arr)) {
                    $main_data = json_encode($main_contact_arr);
                    $data = array(
                        'json_arr' => $main_data,
                        'list_id_enc' => $list_id,
                        'send_val' => $send_val
                    );

                    $result = $this->curl_call($url, $data);
                    return $result;
                }
            }
        } else {
            return 'Invalid API Key';
        }
    }

    /* fUNCTION TO CALL cURL */

    public function curl_call($url, $data) {
        $args = array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'body' => $data,
            'cookies' => array()
        );
        $result = wp_remote_post($url, $args);
        $message = wp_remote_retrieve_body($result);
        return $message;
    }

}

?>