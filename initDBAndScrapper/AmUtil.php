<?php

class AmUtil{

    const BOT_SIGNATURE = "For educational tests only";
    const PROXY = 'dinosaur.luismeneses.pt:3128';
    const PROXY_AUTH = 'couves:couves';
    const BASE_URL_F = 'http://www.flashscore.mobi';
    const BASE_URL_B = 'http://www.flashscore.mobi/basketball/';

    public static function askCurl($sport,$href,$proxy){
        if($sport=="Foot"){
            $url = self::BASE_URL_F.$href;
        }elseif ($sport="Bask"){
            $url = self::BASE_URL_B.$href;
        }
        $ch = curl_init();    //Start cURL


        curl_setopt($ch,CURLOPT_HTTPGET,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
        curl_setopt($ch,CURLOPT_USERAGENT,self::BOT_SIGNATURE);
        curl_setopt($ch, CURLOPT_URL,$url);
        if ($proxy){
             curl_setopt($ch, CURLOPT_PROXY, self::PROXY);
             curl_setopt($ch, CURLOPT_PROXYUSERPWD, self::PROXY_AUTH);
        }
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $htmlRes = curl_exec($ch);

        return $htmlRes;
    }






}//AmUtil