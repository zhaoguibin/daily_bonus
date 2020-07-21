<?php
/**
 * Created by SuChuang.
 * User: ZHaoGuiBin
 * Date: 2020/7/21
 * Time: 15:09
 */

require_once('Tools.php');

class MixDailyBonus
{
    use Tools;

    public function dailyBonus()
    {
        $R5nb_c8f5_auth = 'R5nb_c8f5_auth=4fd2Tj5rfAR%2FSK2nS8f9uh1EQar0EtWs%2BqWjcRYo4AjK%2B4pxKa7Dh04XpvJcDdqCdtLYdtHzB6%2F04MOucqYbeXYsUg';
        $saltkey = 'R5nb_c8f5_saltkey=u737lstS';
        $header = [
            'Cookie: ' . $R5nb_c8f5_auth . ';' . $saltkey
        ];

        $header_1 = Tools::curlGets('http://www.mixrnb.com/plugin.php?id=dsu_paulsign:sign', $header);

        $sessionVerify = Tools::getSessionVerify($header_1['header']);
        if ($sessionVerify) {
            $header[0] .= ';' . $sessionVerify;
            $header_2 = Tools::curlGets('http://www.mixrnb.com/plugin.php?security_verify_data=313932302c31303830', $header);
        }

        $midVerify = Tools::getMidVerify($header_2['header']);

        if ($midVerify) {
            $header[0] .= ';' . $midVerify;
            $header_3 = Tools::curlGet('http://www.mixrnb.com/plugin.php?id=dsu_paulsign:sign&&security_verify_data=313932302c31303830', [], $header);
        }

        $formHash = Tools::getFormHash($header_3['data']);
        //签到
        $qmdk = [
            'formhash' => $formHash[1][0],
            'qdxq' => 'kx'
        ];

        $header[] = 'Content-Type: application/x-www-form-urlencoded';
        $header[] = 'accept: text/csv;charset=gbk,*/*';
        $res = Tools::curlPost('http://www.mixrnb.com/plugin.php?id=dsu_paulsign:sign&operation=qiandao&infloat=1&inajax=1', $qmdk, $header);

        var_dump($res);
        exit;
    }
}

$mix = new MixDailyBonus();
$mix->dailyBonus();