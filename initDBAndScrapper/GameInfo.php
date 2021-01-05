<?php

require_once 'DataTypes/League.php';
require_once 'DataTypes/FootDetail.php';
require_once 'DataTypes/BaskDetails.php';
require_once 'DataTypes/Game.php';

class GameInfo
{
<<<<<<< Updated upstream

    public function getLeaguesLinks($leagues){
        for ($i = 0; $i < count($leagues); $i++) {
            for ($j = 0; $j < count($leagues[$i]->games); $j++) {
             
                if ($this->contains($leagues[$i]->games[$j]->game_status,'Finished') == 'true' || $this->contains($leagues[$i]->games[$j]->game_status,'Half Time') == 'true'  || $this->contains($leagues[$i]->games[$j]->game_status,'Live') == 'true'   ) {
                    $leagues[$i]->games[$j]->setFutGameInfo($this->getInfo($leagues[$i]->games[$j]->game_link));
                }else{
                    //echo 'not checked';
=======
    private $type;
    private $typeOfGame;

    public function getLeaguesLinks($leagues)
    {
        for ($i = 0; $i < count($leagues); $i++) {
            for ($j = 0; $j < count($leagues[$i]->games); $j++) {

                if ($this->contains($leagues[$i]->games[$j]->game_status, 'Finished') == 'true' || $this->contains($leagues[$i]->games[$j]->game_status, 'Half Time') == 'true' || $this->contains($leagues[$i]->games[$j]->game_status, 'Live') == 'true') {
                    $this->type = "link";
                    $leagues[$i]->games[$j]->setFutGameInfo($this->getInfo($leagues[$i]->games[$j]->game_link));
                    $this->type = "lineUp";
                    $leagues[$i]->games[$j]->setFutGameLineUp($this->getInfo($leagues[$i]->games[$j]->game_link));
                } else {
                  // Not Checked
>>>>>>> Stashed changes
                }
            }
        }
        return $leagues;
    }

    public function __construct($typeOfGame)
    {
        $this->typeOfGame = $typeOfGame;
    }

    public function getInfo($hrefOfGame)
    {

<<<<<<< Updated upstream
    public function getInfo($hrefOfGame){

        $htmlGameInfo = AmUtil::askCurl($hrefOfGame,false);
=======
        $htmlGameInfo = AmUtil::askCurl($hrefOfGame, false);
>>>>>>> Stashed changes

        return $this->ScrapInfo($htmlGameInfo);

    }

    public function ScrapInfo($htmlGameInfo)
    {

        try {
            $domDocument = new DOMDocument();
            @$domDocument->loadHTML($htmlGameInfo);

            $gameParts = $domDocument;
<<<<<<< Updated upstream
            $res = self::getOccurences($gameParts);

        }catch (Exception $e){
=======

            if ($this->typeOfGame == 1) {
                if ($this->type == "link") {
                    $res = self::getFOccurences($gameParts);
                } else {
                    $res = self::getFLineUp($gameParts);
                }
            } else if ($this->typeOfGame == 2) {
                if ($this->type == "link") {
                    $res = self::getBOccurences($gameParts);
                } else {
                    $res = self::getBLineUp($gameParts);
                }
            }

        } catch (Exception $e) {

>>>>>>> Stashed changes
        }

        return $res;

    }

<<<<<<< Updated upstream

    private static function getOccurences($gameParts){
=======
    private static function getFLineUp($gameParts)
    {
        $res = "";

        $detaiTabs = $gameParts->getElementById("detail-tabs");
        if ($detaiTabs) {
            $res = $detaiTabs->firstChild->nextSibling->nextSibling->getAttribute('href');
        }
        return $res;

    }

    private static function getFOccurences($gameParts)
    {
>>>>>>> Stashed changes

        $res = array();
        $detailArray1H = array();
        $detailArray2H = array();

        $detaiTab = $gameParts->getElementById("detail-tab-content");

        if ($detaiTab) {
            $detaiTabSon = $detaiTab->childNodes;
            foreach ($detaiTabSon as $someElement) {
                if ($someElement->tagName == "h4") {
                    if ($someElement->firstChild->nodeValue == "1st Half: ") {
                        if ($someElement->nextSibling != null && $someElement->nextSibling->getAttribute('class') == "detail") {
                            $detailArray1H = self::gameIncidents($someElement->nextSibling->childNodes);
                            $res[0] = $detailArray1H;

                        }
                    } // 1st half

                    if ($someElement->firstChild->nodeValue == "2nd Half: ") {
                        if ($someElement->nextSibling != null && $someElement->nextSibling->getAttribute('class') == "detail") {
                            $detailArray2H = self::gameIncidents($someElement->nextSibling->childNodes);
                            $res[1] = $detailArray2H;
                        }
                    } //2nd half

                } //if tag h4
            } //foreach
        }
<<<<<<< Updated upstream
    return $res;
    }//getGoalsFirstHalf


    private static function gameIncidents($details){
        $detailArray=array();
            foreach ($details as $incidents) {
                if ($incidents) {
                    if ($incidents->tagName != 'hr') {
                        $footDetail= new FootDetail(
                            $incidents->firstChild->textContent,
                            $incidents->firstChild->nextSibling->nextSibling->textContent,
                            $incidents->firstChild->nextSibling->getAttribute("class"));//tempo descricao tipo

                        array_push($detailArray,$footDetail);
                    }
=======
        return $res;
    } //getFOccurences

    private static function gameIncidents($details)
    {
        $detailArray = array();
        foreach ($details as $incidents) {
            if ($incidents) {
                if ($incidents->tagName != 'hr') {
                    $footDetail = new FootDetail(
                        $incidents->firstChild->textContent,
                        $incidents->firstChild->nextSibling->nextSibling->textContent,
                        $incidents->firstChild->nextSibling->getAttribute("class")); //tempo descricao tipo

                    array_push($detailArray, $footDetail);
>>>>>>> Stashed changes
                }
            }
        }
        return $detailArray;
    }


    private static function getBLineUp($gameParts)
    {
        $res = "";

        $detaiTabs = $gameParts->getElementById("detail-tabs");
        if ($detaiTabs) {
            $res = $detaiTabs->firstChild->nextSibling->nextSibling->getAttribute('href');
        }
        return $res;

    }

    private static function getBOccurences($gameParts)
    {

        $res = array();

        $detaiTab = $gameParts->getElementById("detail-tab-content");
        
        if ($detaiTab) {
            $detaiTabSon = $detaiTab->childNodes;
            foreach ($detaiTabSon as $someElement) {
                if ($someElement->tagName == "h4") {
                    @$part = $someElement->firstChild->textContent;
                    @$score = $someElement->firstChild->nextSibling->textContent;
                    $baskDetail = new BaskDetails($part,$score);
                    array_push($res,$baskDetail);
                }
            }
        }
       
        return $res;
    } //getBOccurences

    public function contains($haystack, $needle)
    {
        if (preg_match("/{$needle}/i", $haystack)) {
            return 'true';
        } else {
            return 'false';
        }
    }

}
