<?php

require_once './League.php';
require_once './Game.php';
require_once 'GameInfo.php';



    class Flashscore {
        private $leagues;

        const BASE_URL = 'http://www.flashscore.mobi/'; 
        const PROXY = 'dinosaur.luismeneses.pt:3128';
        const PROXY_AUTH = 'couves:couves';



        // Constructor
    public function __construct(){

    }

    public function getSite(){
        
        $url =self::BASE_URL;
      //$url =self::BASE_URL . $searchParam;     //Concatenated 2 strings
       // echo $url;

        $ch = curl_init();    //Start cURL


        curl_setopt($ch,CURLOPT_HTTPGET,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
        curl_setopt($ch,CURLOPT_USERAGENT,'BotToGetGames');  //Meter como const no amUtil dps
        curl_setopt($ch, CURLOPT_URL,$url);
       // curl_setopt($ch, CURLOPT_PROXY, self::PROXY);
       // curl_setopt($ch, CURLOPT_PROXYUSERPWD, self::PROXY_AUTH);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        $htmlRes = curl_exec($ch);

        self::scrapIt($htmlRes);
        
       // var_dump($htmlRes);

    }

    public  function scrapIt($htmlRes){
        //vai buscar a tabela

        $startPos =stripos($htmlRes, '<div id="score-data"');

        $end = stripos($htmlRes,'<p class="advert-bottom"',$offset=$startPos);

        $length = $end-$startPos;

        $html = substr($htmlRes, $startPos, $length);

        //echo $html;
        self::loadHtml($html);
    }

    public function loadHtml($html){
        $this->leagues = array();
        $domDocument=new DOMDocument();
        $domDocument->loadHTML($html);

        $leaguesHtml= $domDocument->getElementsByTagName('h4');
      // $leagues = array();


        foreach ($leaguesHtml as $league) {
            $tempLeagues= new League();
            $tempLeagues->setLeagueName($league->nodeValue);
            array_push($this->leagues,$tempLeagues);

        }
        $actualLeague=0;
        $htmlGames= $domDocument->getElementsByTagName('span');

        self::scrapGames($html,count($this->leagues));
//var_dump($html);
    }

    public  function scrapGames($html,$numberOfLeagues){
       // echo $numberOfLeagues;
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

        self::getInfoFromGamesHTML($texts);
       // var_dump($texts);

    }

    public  function getInfoFromGamesHTML($leaguesFromHTML){


        for ($i=0;$i<count($leaguesFromHTML);$i++){
            $gamesNames=array();
            $gamesUrls=array();
            $gamesTime=array();
            $gamesScores=array();
            $gamesStatus="";

            preg_match_all('/(?<=<\/span>)[^<]+/',$leaguesFromHTML[$i],$gamesNames,PREG_PATTERN_ORDER);  //Game Names

            preg_match_all('/href="[^>]+" /',$leaguesFromHTML[$i],$gamesUrls,PREG_PATTERN_ORDER);  //URL's games

            preg_match_all('/>[^>]+<\/s/',$leaguesFromHTML[$i],$gamesTime,PREG_PATTERN_ORDER);  //Times games

            preg_match_all('/[^>]+<\/a/',$leaguesFromHTML[$i],$gamesScores,PREG_PATTERN_ORDER);  //Scores games

           // var_dump($gamesTime);
            //echo json_encode(count($gamesTime[0]));

            for ($j=0;$j<count($gamesNames[0]);$j++) {


             $pos=   strpos($gamesTime[0][$j],"</s");                                       //clean match time
                if($pos>1){
                    $gamesTime[0][$j] = str_replace("</s","", $gamesTime[0][$j]);
                    $gamesTime[0][$j] = str_replace(">","", $gamesTime[0][$j]);
                }

                $gamesUrls[0][$j] = str_replace('href="',"", $gamesUrls[0][$j]);
                $gamesUrls[0][$j] = str_replace('"',"", $gamesUrls[0][$j]);


                $gamesScores[0][$j] = str_replace("</a","", $gamesScores[0][$j]);   //clean score of game

                if($gamesTime[0][$j]=="Half Time"){
                    $gamesStatus="Half Time";
                }elseif($gamesTime[0][$j]=="Postponed"){
                    $gamesStatus="Postponed";
                }elseif (strpos($gamesScores[0][$j],"-:-")!==false){
                    $gamesStatus="Scheduled";
                }elseif (strlen($gamesTime[0][$j]) <4 ) {
                    $gamesStatus = "Live";
                }elseif(strpos($gamesTime[0][$j],":") ){
                     $gamesStatus = "Finished";
                }

                $words = explode("-",$gamesNames[0][$j]);
                $goals = explode(":",$gamesScores[0][$j]);


                @$tempGame = new Game($gamesTime[0][$j], $words[0],$words[1], $gamesUrls[0][$j],$goals[0],$goals[1],$gamesStatus);

                $this->leagues[$i]->pushJogos($tempGame);

            }
        }

        $gameInfo = new GameInfo();

        $gameInfo -> getLeaguesLinks($this->leagues);




    }


    }