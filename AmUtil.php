<?php

class AmUtil{

    const BOT_SIGNATURE = "For educational tests only";
    const PROXY = 'dinosaur.luismeneses.pt:3128';
    const PROXY_AUTH = 'couves:couves';
    const BASE_URL = 'http://www.flashscore.mobi';


    public static function askCurl($href,$proxy){
        $url = self::BASE_URL.$href;

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

//while(1){

//metodo a chamar
//sleep(60);//1 minute pause
//}




}//AmUtil