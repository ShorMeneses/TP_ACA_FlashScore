<?php

require_once 'DataTypes/League.php';
require_once 'DataTypes/FootDetail.php';
require_once 'DataTypes/Game.php';

class GameInfo
{



    public function getLeaguesLinks($sport, $leagues){
        for ($i = 0; $i < count($leagues); $i++) {
            for ($j = 0; $j < count($leagues[$i]->games); $j++) {
             
                if ($this->contains($leagues[$i]->games[$j]->game_status,'Finished') == 'true' || $this->contains($leagues[$i]->games[$j]->game_status,'Half Time') == 'true'  || $this->contains($leagues[$i]->games[$j]->game_status,'Live') == 'true'   ) {
                    $leagues[$i]->games[$j]->setFutGameInfo($this->getInfo($sport,$leagues[$i]->games[$j]->game_link));
                }else{
                    //echo 'not checked';
                }
            } 
        }
        return $leagues;
    }



    public function getInfo($sport,$hrefOfGame){

        $htmlGameInfo = AmUtil::askCurl($sport,$hrefOfGame,false);

        return $this->ScrapInfo($sport,$htmlGameInfo);

    }

    public function ScrapInfo($sport,$htmlGameInfo){


        try {
            $domDocument = new DOMDocument();
            @$domDocument->loadHTML($htmlGameInfo);

            $gameParts = $domDocument;

            if($sport=="Foot"){
                $res = self::getFOccurences($gameParts);
            }elseif ($sport="Bask"){
                $res = self::getBOccurences($gameParts);
            }

        }catch (Exception $e){
        }

        return $res;

    }

    private static function getBOccurences(DOMDocument $gameParts){
        return "";

    }

    private static function getFOccurences($gameParts){

        $res = array();
        $detailArray1H=array();
        $detailArray2H=array();

        $detaiTab = $gameParts->getElementById("detail-tab-content");

        if ($detaiTab) {
            $detaiTabSon = $detaiTab->childNodes;
            foreach ($detaiTabSon as $someElement) {
                if ($someElement->tagName == "h4") {
                    if ($someElement->firstChild->nodeValue == "1st Half: ") {
                        if ($someElement->nextSibling != null && $someElement->nextSibling->getAttribute('class') == "detail") {
                                $detailArray1H=self::gameIncidents($someElement->nextSibling->childNodes);
                                $res[0]=$detailArray1H;

                        }
                    }// 1st half


                    if ($someElement->firstChild->nodeValue == "2nd Half: ") {
                        if ($someElement->nextSibling != null && $someElement->nextSibling->getAttribute('class') == "detail") {
                            $detailArray2H=self::gameIncidents($someElement->nextSibling->childNodes);
                            $res[1]=$detailArray2H;
                        }
                    }//2nd half

                }//if tag h4
            }//foreach
        }
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
                }
            }
        return $detailArray;
    }


    public function contains($haystack,$needle){
        if(preg_match("/{$needle}/i", $haystack)) {
            return 'true';
        }else{
            return 'false';
        }
    }

}