<?php



require_once './lib/php-parser/simple_html_dom.php';

    class Rarbg {

        const BASE_URL = 'https://rarbgproxied.org/torrents.php?search='; 
        const PROXY = 'dinosaur.luismeneses.pt:3128'; 
        const PROXY_AUTH = 'couves:couves';
          // Constructor 
    public function __construct(){ 
      
    } 

    public static function getSite($searchParam){

        $searchParam = self::replaceSpace($searchParam);
        

        $url =self::BASE_URL . $searchParam . '&order=seeders&by=DESC';     //Concatenated 2 strings
        echo $url;

        $ch = curl_init();    //Start cURL

        

        curl_setopt($ch,CURLOPT_HTTPGET,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
        curl_setopt($ch,CURLOPT_USERAGENT,'BotToGetRarbg');  //Meter como const no amUtil dps
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_PROXY, self::PROXY);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, self::PROXY_AUTH);

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        $htmlRes = curl_exec($ch);

        self::scrapIt($htmlRes);
        
        var_dump($htmlRes);

    }

    public static function scrapIt($htmlRes){

        $html = str_get_html($htmlRes);

        $table = $html->find('.lista2');
        var_dump($table);

    }












    public static function replaceSpace($searchParam){                  //Meter na amUtil
        $searchParam = str_replace(' ', '+',$searchParam);

        return $searchParam;

    }


    }