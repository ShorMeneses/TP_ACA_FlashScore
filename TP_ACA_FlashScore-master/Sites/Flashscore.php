<?php

require_once './League.php';
require_once './Game.php';

    class Flashscore {

        const BASE_URL = 'http://www.flashscore.mobi/'; 
        const PROXY = 'dinosaur.luismeneses.pt:3128'; 
        const PROXY_AUTH = 'couves:couves';
        public $leagues;


        // Constructor
    public function __construct(){ 

    } 

    public static function getSite(){
        
        $url =self::BASE_URL;
      //$url =self::BASE_URL . $searchParam;     //Concatenated 2 strings
        echo $url;

        $ch = curl_init();    //Start cURL


        curl_setopt($ch,CURLOPT_HTTPGET,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
        curl_setopt($ch,CURLOPT_USERAGENT,'BotToGetGames');  //Meter como const no amUtil dps
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_PROXY, self::PROXY);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, self::PROXY_AUTH);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        $htmlRes = curl_exec($ch);

        self::scrapIt($htmlRes);
        
       // var_dump($htmlRes);

    }

    public static function scrapIt($htmlRes){
        //vai buscar a tabela

        $startPos =stripos($htmlRes, '<div id="score-data"');

        $end = stripos($htmlRes,'<p class="advert-bottom"',$offset=$startPos);

        $length = $end-$startPos;

        $html = substr($htmlRes, $startPos, $length);

        //echo $html;
        self::loadHtml($html);
    }

    public static function loadHtml($html){
        $domDocument=new DOMDocument();
        $domDocument->loadHTML($html);

        $leaguesHtml= $domDocument->getElementsByTagName('h4');
        $leagues = array();

        foreach ($leaguesHtml as $league) {
            $tempLeagues= new League();
            $tempLeagues->setLeagueName($league->nodeValue);
            array_push($leagues,$tempLeagues);

        }
        $actualLeague=0;
        $htmlGames= $domDocument->getElementsByTagName('span');

        self::scrapGames($html,count($leagues));

    }

    public static function scrapGames($html,$numberOfLeagues){
        echo $numberOfLeagues;
        $texts=array();
        $startSearching =0;
        for ($i=0;$i<$numberOfLeagues;$i++){
            $startPos =stripos($html, '<h4>',$startSearching);

        if($i == $numberOfLeagues-1){
            $end = stripos($html,'</div>');
        }else{
            $end = stripos($html,'<h4>',$offset=$startPos+1);
        }

            $length = $end-$startPos;
            array_push($texts,substr($html, $startPos, $length));

            $startSearching=$end;

        }
        var_dump($texts);

    }

    }