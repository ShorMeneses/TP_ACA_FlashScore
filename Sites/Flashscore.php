<?php

require_once './Liga.php';
require_once './Jogo.php';

    class Flashscore {

        const BASE_URL = 'http://www.flashscore.mobi/'; 
        const PROXY = 'dinosaur.luismeneses.pt:3128'; 
        const PROXY_AUTH = 'couves:couves';
        public $ligas;
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

        $lenght = $end-$startPos;

        $html = substr($htmlRes, $startPos, $lenght);

        //echo $html;
        self::loadHtml($html);
    }

    public static function loadHtml($html){
        $domDocument=new DOMDocument();
        $domDocument->loadHTML($html);

        $ligasHtml= $domDocument->getElementsByTagName('h4');
        $ligas = array();

        foreach ($ligasHtml as $liga) {
            $ligaTemp= new Liga();
            $ligaTemp->setLigaN($liga->nodeValue);
            array_push($ligas,$ligaTemp);

        }
        $ligaAtual=0;
        $jogosHtml= $domDocument->getElementsByTagName('span');

        self::scrapGames($html,count($ligas));

    }

    public static function scrapGames($html,$a){
        echo $a;
        $texts=array();
        $startSearching =0;
        for ($i=0;$i<$a;$i++){
            $startPos =stripos($html, '<h4>',$startSearching);

        if($i == $a-1){
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