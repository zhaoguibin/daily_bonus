<?php

trait Tools {
    static function curlGets($sUrl,$header){
        $oCurl = curl_init();
// 设置请求头, 有时候需要,有时候不用,看请求网址是否有对应的要求

        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER,$header);
// 返回 response_header, 该选项非常重要,如果不为 true, 只会获得响应的正文
        curl_setopt($oCurl, CURLOPT_HEADER, true);
// 是否不需要响应的正文,为了节省带宽及时间,在只需要响应头的情况下可以不要正文
//        curl_setopt($oCurl, CURLOPT_NOBODY, true);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );

// 不用 POST 方式请求, 意思就是通过 GET 请求
        curl_setopt($oCurl, CURLOPT_POST, false);

        $sContent = curl_exec($oCurl);
// 获得响应结果里的：头大小
        $headerSize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
// 根据头大小去获取头信息内容
        $header = substr($sContent, 0, $headerSize);
        curl_close($oCurl);
        return [
            'header'=>$header,
            'sContent'=>$sContent,
        ];
    }

    static function getSessionVerify($header){
        $re = '/security_session_verify=(\w*)/';

        preg_match($re, $header, $matches, PREG_OFFSET_CAPTURE, 0);

        if($matches){
            return $matches[0][0];
        }

        return null;
    }

    static function getMidVerify($header){
        $re = '/security_session_mid_verify=(\w*)/';

        preg_match($re, $header, $matches, PREG_OFFSET_CAPTURE, 0);

        if($matches){
            return $matches[0][0];
        }

        return null;
    }

    static public function CurlGet($url, $data,$header)
    {
//        $url = $url . '?' . http_build_query($data);

//            $header = array(
//                'Accept: application/json',
//            );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_ACCEPT_ENCODING, "gzip,deflate");
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 超时设置,以秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        // 超时设置，以毫秒为单位
        // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
        $data = curl_exec($curl);
        $data=mb_convert_encoding($data, 'UTF-8','GBK');

        // 显示错误信息
        if (curl_error($curl)) {
            $data = [
                'code' => -1,
                'data' => curl_error($curl)
            ];
        } else {
            curl_close($curl);
            $data = [
                'code' => 1,
                'data' => $data
            ];
        }

        return $data;
    }

    static function getFormHash($html) {
        $re = '/<input type="hidden" name="formhash" value="(\w*)" \/>/mi';

        preg_match($re, $html, $matches, PREG_OFFSET_CAPTURE, 0);

// Print the entire match result
        return $matches;
    }

    public static function curlPost($url, $data = [],$header)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
//            $data  = json_encode($data);
            $data = http_build_query($data);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        $output_1=mb_convert_encoding($output, 'UTF-8','GBK');

        return $output;

    }
}