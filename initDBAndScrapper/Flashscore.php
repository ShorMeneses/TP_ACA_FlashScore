<?php

require_once 'DataTypes/League.php';
require_once 'DataTypes/Game.php';
require_once 'GameInfo.php';
require_once 'AmUtil.php';
require_once 'DBCreate.php';
require_once 'DBInsert.php';



    class Flashscore {
        private $leagues;
        private $typeOfGame;

        // Constructor
    public function __construct($typeOfGame){
       $this->typeOfGame = $typeOfGame;
    }

    public function handlerGetSite(){
        switch ($this->typeOfGame) {
          /*  case 0:
                $this->getSite('');
                $this->getSite('basketball/');
                break;*/
            case 1:
                $this->getSite('');
                break;
            case 2:
                $this->getSite('basketball/');
                break;
        }
    }

    public function getSite($typeOfGameHref){

        // TODO on href add various types of game
        echo "\n Started Scrapping".$this->typeOfGame;
        $htmlRes = AmUtil::askCurl('/'.$typeOfGameHref,false);

        self::scrapIt($htmlRes);

    }

    public  function scrapIt($htmlRes){

        $startPos =stripos($htmlRes, '<div id="score-data"');

        $end = stripos($htmlRes,'<p class="advert-bottom"',$offset=$startPos);

        $length = $end-$startPos;

        $html = substr($htmlRes, $startPos, $length);

        self::loadHtml($html);
    }

    public function loadHtml($html){
        $this->leagues = array();
        $domDocument=new DOMDocument();
        @$domDocument->loadHTML($html);

        $leaguesHtml= $domDocument->getElementsByTagName('h4');


        foreach ($leaguesHtml as $league) {
            $tempLeagues= new League();
            $tempName=$league->nodeValue;
            $tempName = str_replace("'","",$tempName);
            $tempLeagues->setLeagueName($tempName);

            array_push($this->leagues,$tempLeagues);
        }

        self::scrapGames($html,count($this->leagues));

    }

    public function scrapGames($html,$numberOfLeagues){
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

    }

    public function getInfoFromGamesHTML($leaguesFromHTML){


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


            for ($j=0;$j<count($gamesNames[0]);$j++) {


                $matchTime = self::cleanMatchTime($gamesTime[0][$j]);
                $matchUrl = self::cleanMatchUrl($gamesUrls[0][$j]);
                $matchScore = self::cleanMatchScore($gamesScores[0][$j]);
                $gamesStatus =self::cleanMatchStatus($matchTime,$matchScore);
                $gameNames=self::cleanGameNames($gamesNames[0][$j]);

                $words = explode("-",$gameNames);
                $goals = explode(":",$matchScore);

                @$tempGame = new Game($matchTime, $words[0],$words[1], $matchUrl,$goals[0],$goals[1],$gamesStatus);

                $this->leagues[$i]->pushJogos($tempGame);

            }
        }

        $gameInfo = new GameInfo();
        $DBcreate = new DBCreate();

        $allInfo = $gameInfo ->getLeaguesLinks($this->leagues);
        $DBInsert = new DBInsert($allInfo,$this->typeOfGame);
       
        
    }

        public function cleanMatchTime($matchTime){
            $pos=strpos($matchTime,"</s");
            if($pos>1){
                $matchTime = str_replace("</s","", $matchTime);
                $matchTime = str_replace(">","", $matchTime);
                $matchTime = str_replace("'","",$matchTime);
            }
            return $matchTime;
        }

        public function cleanMatchUrl($matchUrl){
            $matchUrl = str_replace('href="',"", $matchUrl);
            $matchUrl = str_replace('"',"", $matchUrl);
            $matchUrl = str_replace("'","",$matchUrl);

            return $matchUrl;
        }

        public function cleanMatchScore($matchScore){
            $matchScore = str_replace("</a","", $matchScore);
            $matchScore = str_replace("'","",$matchScore);

            return $matchScore;
        }

        public function cleanGameNames($gameNames){
            $gameNames = str_replace("'","",$gameNames);

            return $gameNames;
        }

        public function cleanMatchStatus($matchTime,$matchScore){
            $gamesStatus ='Error';

            if($matchTime=="Half Time"){
                $gamesStatus="Half Time";

                }elseif($matchTime=="Postponed"){
                    $gamesStatus="Postponed";

                    }elseif (strpos($matchScore,"-:-")!==false){
                        $gamesStatus="Scheduled";

                        }elseif (strlen($matchTime) <4 ) {
                            $gamesStatus = "Live";

                            }elseif(strpos($matchTime,":") ){
                                $gamesStatus = "Finished";
                            }

            $gamesStatus = str_replace("'","",$gamesStatus);
            return $gamesStatus;
        }



    }