<?php

    require_once './League.php';
    require_once './FootDetail.php';
    require_once './Game.php';

class GameInfo
{
    const BASE_URL = 'http://www.flashscore.mobi';
    const PROXY = 'dinosaur.luismeneses.pt:3128';
    const PROXY_AUTH = 'couves:couves';


    public function getLeaguesLinks($leagues){
        for ($i=0;$i<count($leagues);$i++){

            for ($j=0;$j<count($leagues[$i]->games);$j++){
                //TODO isto ta a passear pelos jogos associar os footdetails a cada jogo
                if ( $leagues[$i]->games[$j]->game_status != 'Scheduled' ||  $leagues[$i]->games[$j]->game_status != 'Postponed' ){
                    $leagues[$i]->games[$j]->setGameInfo(self::getInfo(self::BASE_URL.$leagues[$i]->games[$j]->game_link));

                }
                var_dump($leagues[$i]->games[$j]);

            }
        }

}



    public function init(){
        $href="http://www.flashscore.mobi/match/WdxtNRJ4/";
        self::getInfo($href);
    }

    public function getInfo($hrefOfGame){

        $ch = curl_init();    //Start cURL

        curl_setopt($ch,CURLOPT_HTTPGET,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
        curl_setopt($ch,CURLOPT_USERAGENT,'BotToGetGames');  //Meter como const no amUtil dps
        curl_setopt($ch, CURLOPT_URL,$hrefOfGame);
    //   curl_setopt($ch, CURLOPT_PROXY, self::PROXY);
    //    curl_setopt($ch, CURLOPT_PROXYUSERPWD, self::PROXY_AUTH);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $htmlGameInfo = curl_exec($ch);


       return self::ScrapInfo($htmlGameInfo);

    }

    public function ScrapInfo($htmlGameInfo)
    {
        $res=array();

        try {


            $domDocument = new DOMDocument();
            @$domDocument->loadHTML($htmlGameInfo);

            $gameParts = $domDocument;
            $goals1H = self::getGoalsFirstHalf($gameParts);

        }catch (Exception $e){

        }


        return $res;

    }


    private static function getGoalsFirstHalf($gameParts)
    {

        $as = $gameParts->getElementById("detail-tab-content");

    if ($as){
        $as2 = $as->childNodes;


        foreach ($as2 as $someAElement) {

            if ($someAElement->tagName == 'h4' && self::contains($someAElement->textContent, '1st') == 'true') {
                echo "\nFirst h4 " . $someAElement->textContent;

                if ($someAElement->nextSibling) {
                    if (count($someAElement->nextSibling->childNodes) > 1) {
                        if ($someAElement->nextSibling->tagName != 'h4') {
                            if ($someAElement->nextSibling->childNodes[0]->tagName != 'h4') {
                                foreach ($someAElement->nextSibling->childNodes as $incidents) {
                                  if ($incidents) {
                                       if ($incidents->tagName != 'hr') {
                                            echo "\n Incident first half " . $incidents->textContent;
                                    }
                                }
                            }
                        }
                    }
                    }
                }
            }

            if ($someAElement->tagName == 'h4' && self::contains($someAElement->textContent, '2nd') == 'true') {
                echo "\n\nSecond h4 " . $someAElement->textContent;

                if($someAElement->nextSibling) {
                    if (count($someAElement->nextSibling->childNodes) > 1) {
                        if ($someAElement->nextSibling->tagName != 'h4') {
                            if ($someAElement->nextSibling->childNodes[0]->tagName != 'h4') {
                                foreach ($someAElement->nextSibling->childNodes as $incidents) {
                                    if ($incidents) {
                                        if ($incidents->tagName != 'hr') {
                                            echo "\n Incident second half " . $incidents->textContent;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }


        }

    }
    }






    private static function contains($string,$needle){
        if(preg_match("/{$needle}/i", $string)) {
            return 'true';
        }else{
            return 'false';
        }
    }



}