<?php

require_once 'DataTypes/League.php';
require_once 'DataTypes/FootDetail.php';
require_once 'DataTypes/Game.php';

class GameInfo
{
    private $type="";


    public function getLeaguesLinks($leagues){
        for ($i = 0; $i < count($leagues); $i++) {
            for ($j = 0; $j < count($leagues[$i]->games); $j++) {
             
                if ($this->contains($leagues[$i]->games[$j]->game_status,'Finished') == 'true' || $this->contains($leagues[$i]->games[$j]->game_status,'Half Time') == 'true'  || $this->contains($leagues[$i]->games[$j]->game_status,'Live') == 'true'   ) {
                    $this->type="link";
                    $leagues[$i]->games[$j]->setFutGameInfo($this->getInfo($leagues[$i]->games[$j]->game_link));
                    $this->type="lineUp";
                    $leagues[$i]->games[$j]->setFutGameLineUp($this->getInfo($leagues[$i]->games[$j]->game_link));
                }else{
                    //echo 'not checked';
                }
            } 
        }
        return $leagues;
    }



    public function getInfo($hrefOfGame){

        $htmlGameInfo = AmUtil::askCurl($hrefOfGame,false);


        return $this->ScrapInfo($htmlGameInfo);

    }

    public function ScrapInfo($htmlGameInfo){


        try {
            $domDocument = new DOMDocument();
            @$domDocument->loadHTML($htmlGameInfo);

            $gameParts = $domDocument;


            if($this->type=="link"){
                $res = self::getFOccurences($gameParts);
            }else {
                $res = self::getFLineUp($gameParts);
            }



        }catch (Exception $e){
        }

        return $res;

    }

    private static function getFLineUp($gameParts){
        $res ="";

        $detaiTabs = $gameParts->getElementById("detail-tabs");
        if ($detaiTabs) {
            $res = $detaiTabs->firstChild->nextSibling->nextSibling->getAttribute('href');
        }
        return $res;

    }

    private function cleanMatchUrl($matchUrl){
        $matchUrl = str_replace('href="',"", $matchUrl);
        $matchUrl = str_replace('"',"", $matchUrl);
        $matchUrl = str_replace("'","",$matchUrl);

        return $matchUrl;
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
    }//getFOccurences


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