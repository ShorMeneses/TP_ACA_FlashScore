<?php

require_once 'DataTypes/League.php';
require_once 'DataTypes/FootDetail.php';
require_once 'DataTypes/BaskDetails.php';
require_once 'DataTypes/Game.php';

class GameInfo
{
    private $type;
    private $typeOfGame;

    public function getLeaguesLinks($leagues)
    {
        for ($i = 0; $i < count($leagues); $i++) {
            for ($j = 0; $j < count($leagues[$i]->games); $j++) {
                //Iterate through Leagues and Games
                //If the game is finished, on Half Time or Live try to search for details otherwise there will be no details so there will be no need to go check
                if ($this->contains($leagues[$i]->games[$j]->game_status, 'Finished') == 'true' || $this->contains($leagues[$i]->games[$j]->game_status, 'Half Time') == 'true' || $this->contains($leagues[$i]->games[$j]->game_status, 'Live') == 'true') {
                    $this->type = "link";
                    //Set the events of the game 
                    $leagues[$i]->games[$j]->setFutGameInfo($this->getInfo($leagues[$i]->games[$j]->game_link));
                    $this->type = "lineUp";
                    //Set the lineup link of the game
                    $leagues[$i]->games[$j]->setFutGameLineUp($this->getInfo($leagues[$i]->games[$j]->game_link));
                } else {
                    // Not Checked
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
        //Ask cURL to make a request to the game link
        $htmlGameInfo = AmUtil::askCurl($hrefOfGame, false);

        return $this->ScrapInfo($htmlGameInfo);

    }

    public function ScrapInfo($htmlGameInfo)
    {

        try {
            //Load the HTML into a DOMDocument
            $domDocument = new DOMDocument();
            @$domDocument->loadHTML($htmlGameInfo);

            $gameParts = $domDocument;

            //Decide what to get, first if it's Soccer or Basketball and then Game events or lineup
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

        }

        return $res;

    }

    private static function getFLineUp($gameParts)
    {
        $res = "";

        //Try to get href attribute of <a> element that has the lineup link
        $detaiTabs = $gameParts->getElementById("detail-tabs");
        if ($detaiTabs) {
            $res = $detaiTabs->firstChild->nextSibling->nextSibling->getAttribute('href');
        }
        return $res;

    }

    private static function getFOccurences($gameParts)
    {

        $res = array();
        $detailArray1H = array();
        $detailArray2H = array();

        //Get the element that has all the events
        $detaiTab = $gameParts->getElementById("detail-tab-content");

        if ($detaiTab) {
            $detaiTabSon = $detaiTab->childNodes;
            foreach ($detaiTabSon as $someElement) {
                //Iterating over <h4> and then deciding if it's 1st or 2nd half
                if ($someElement->tagName == "h4") {
                    if ($someElement->firstChild->nodeValue == "1st Half: ") {
                        if ($someElement->nextSibling != null && $someElement->nextSibling->getAttribute('class') == "detail") {
                            $detailArray1H = self::gameIncidents($someElement->nextSibling->childNodes);    //Send only the HTML with the events regarding to the 1st part
                            $res[0] = $detailArray1H;

                        }
                    } // 1st half

                    if ($someElement->firstChild->nodeValue == "2nd Half: ") {
                        if ($someElement->nextSibling != null && $someElement->nextSibling->getAttribute('class') == "detail") {
                            $detailArray2H = self::gameIncidents($someElement->nextSibling->childNodes);    //Send only the HTML with the events regarding to the 2nd part
                            $res[1] = $detailArray2H;
                        }
                    } //2nd half

                } //if tag h4
            } //foreach
        }
        return $res;
    } //getFOccurences

    private static function gameIncidents($details)
    {
        $detailArray = array();
        foreach ($details as $incidents) {
            if ($incidents) {
                if ($incidents->tagName != 'hr') {                                          //<hr> tag ends all the events of the part
                    $footDetail = new FootDetail(
                        $incidents->firstChild->textContent,
                        $incidents->firstChild->nextSibling->nextSibling->textContent,
                        $incidents->firstChild->nextSibling->getAttribute("class"));        //Type Description Time

                    array_push($detailArray, $footDetail);
                }
            }
        }
        return $detailArray;
    }

    private static function getBLineUp($gameParts)
    {
        $res = "";

         //Try to get href attribute of <a> element that has the lineup link
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
                //Since basketball games don't have events only the parts when only get the parts details
                if ($someElement->tagName == "h4") {
                    @$part = $someElement->firstChild->textContent;
                    @$score = $someElement->firstChild->nextSibling->textContent;
                    $baskDetail = new BaskDetails($part, $score);
                    array_push($res, $baskDetail);
                }
            }
        }

        return $res;
    } //getBOccurences

    public function contains($haystack, $needle)
    {
        //Contains function, if there is the needle in the haystack it will return a string 'true' in case there is 'false' in case there isn't
        if (preg_match("/{$needle}/i", $haystack)) {
            return 'true';
        } else {
            return 'false';
        }
    }

}
