<?php
/**
 * Created by PhpStorm.
 * User: nagata
 * Date: 14/09/05
 * Time: 15:14
 */

class splitMailText {

    public function __construct() {

    }

    public function main() {
        $text = $this->readMailText();
        $mail_parts_arr = $this->mailSplitArray($text);

        return $mail_parts_arr;
    }

    private function readMailText() {

        $mail_string = '';

        $fp = fopen('./mail_test.txt', 'r');
        while(!feof($fp)) {
            $mail_string .= fgets($fp);
        }
        fclose($fp);

        return $mail_string;
    }

    private function mailSplitArray($mail_text) {

        $protocol_parts = array();

        $protocol_split_start = '/--------------------- [a-zA-Z ()-]* Begin ------------------------/';
        $protocol_split_end = '/---------------------- [a-zA-Z ()-]* End -------------------------/';

        $temp_str = $mail_text;

        $i=0;
        while(true) {

            preg_match($protocol_split_start, $temp_str, $matchs);
            if(!empty($matchs[0])) {
                $protocol_type = str_replace('--------------------- ', '', $matchs[0]);
                $protocol_type = str_replace(' Begin ------------------------', '', $protocol_type);
            } else {
                $protocol_type = 'unknown';
            }
            $protocol_parts[$i]['type'] = $protocol_type;

            $str_parts1 = preg_split($protocol_split_start, $temp_str, 2);
            $str_parts2 = preg_split($protocol_split_end, $str_parts1[1], 2);
            $protocol_parts[$i]['param'] = $str_parts2[0];
            $i++;
            if(preg_match($protocol_split_start, $str_parts2[1]) !== 1) break;
            $temp_str = $str_parts2[1];
        }

        return $protocol_parts;
    }
}

$classObj = new splitMailText();
$parts = $classObj->main();
var_dump($parts);
