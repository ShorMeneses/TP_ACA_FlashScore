<?php

require_once './League.php';
require_once './FootDetail.php';
require_once './Game.php';

class GameInfo
{
    const BASE_URL = 'http://www.flashscore.mobi';
    const PROXY = 'dinosaur.luismeneses.pt:3128';
    const PROXY_AUTH = 'couves:couves';


    public function getLeaguesLinks($leagues)
    {
        for ($i = 0; $i < count($leagues); $i++) {

            for ($j = 0; $j < count($leagues[$i]->games); $j++) {
                //TODO isto ta a passear pelos jogos associar os footdetails a cada jogo
                if ($leagues[$i]->games[$j]->game_status != 'Scheduled' || $leagues[$i]->games[$j]->game_status != 'Postponed') {
                    $leagues[$i]->games[$j]->setGameInfo(self::getInfo(self::BASE_URL . $leagues[$i]->games[$j]->game_link));

                }
                //  var_dump($leagues[$i]->games[$j]);

            }
        }

    }


    public function init()
    {
        $href = "http://www.flashscore.mobi/match/neyqNuTm";

        self::getInfo($href);
    }

    public function getInfo($hrefOfGame)
    {

        $ch = curl_init();    //Start cURL

        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'BotToGetGames');  //Meter como const no amUtil dps
        curl_setopt($ch, CURLOPT_URL, $hrefOfGame);
        //   curl_setopt($ch, CURLOPT_PROXY, self::PROXY);
        //    curl_setopt($ch, CURLOPT_PROXYUSERPWD, self::PROXY_AUTH);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $htmlGameInfo = curl_exec($ch);


        return self::ScrapInfo($htmlGameInfo);

    }

    public function ScrapInfo($htmlGameInfo)
    {
        $res = array();

        try {


            $domDocument = new DOMDocument();
            @$domDocument->loadHTML($htmlGameInfo);

            $gameParts = $domDocument;//->getElementById("detail-tab-content");

            $goals1H = self::getGoalsFirstHalf($gameParts);
            //  $goals2H = self::getGoalsSecondHalf($gameParts);
            //  $details1H = self::getFirstHalfDetails($gameParts);
            //   $details2H = self::getSecondHalfDetails($gameParts);
        } catch (Exception $e) {

        }
        //array_push($res,$goals1H);
        //  array_push($res,$goals2H);
        //  array_push($res,$details1H);
//        array_push($res,$details2H);

        return $res;

    }


    private static function getGoalsFirstHalf($gameParts)
    {

        $detaiTab = $gameParts->getElementById("detail-tab-content");

        if ($detaiTab) {
            $detaiTabSon = $detaiTab->childNodes;

            foreach ($detaiTabSon as $someElement) {

                if ($someElement->tagName == "h4") {
                    //var_dump();
                    if ($someElement->firstChild->nodeValue == "1st Half: ") {
                        echo "\n  1 parte \n ";
                        if ($someElement->nextSibling != null && $someElement->nextSibling->getAttribute('class') == "detail") {
                            echo "  1 parte info";
                            self::gameIncidents($someElement->nextSibling->childNodes,"1");
                        }
                    }// 1st half
                    if ($someElement->firstChild->nodeValue == "2nd Half: ") {
                        echo "\n  2 parte \n";
                        if ($someElement->nextSibling != null && $someElement->nextSibling->getAttribute('class') == "detail") {
                            echo "  2 parte info";
                            self::gameIncidents($someElement->nextSibling->childNodes,"2");
                        }
                    }//2nd half

                }//if tag h4
            }//foreach
        }

    }//getGoalsFirstHalf


    private static function gameIncidents($details,$part){
        foreach ($details as $incidents) {
            if ($incidents) {
                if ($incidents->tagName != 'hr') {
                    echo "\n Incident ".$part." half " . $incidents->textContent;
                }
            }
        }
    }
}