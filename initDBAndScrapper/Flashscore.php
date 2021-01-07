<?php

require_once 'DataTypes/League.php';
require_once 'DataTypes/Game.php';
require_once 'GameInfo.php';
require_once 'AmUtil.php';
require_once 'DBCreate.php';
require_once 'DBInsert.php';

class Flashscore
{
    private $leagues;
    private $typeOfGame;
    private $whichHtmlDoIHave;

    // Constructor
    public function __construct($typeOfGame)
    {
        $this->typeOfGame = $typeOfGame;
    }

    public function handlerGetSite()
    {
        //It will choose which type of game to go to
        //After doint the getSite function it will do everything again, to update games info
        switch ($this->typeOfGame) {
            case 0:
                $this->getSite('');
                $this->getSite('basketball/');
                sleep(10);
                $this->handlerGetSite();
                break;
            case 1:
                $this->getSite('');
                sleep(5);
                $this->handlerGetSite();
                break;
            case 2:
                $this->getSite('basketball/');
                sleep(5);
                $this->handlerGetSite();
                break;
        }

    }

    public function getSite($typeOfGameHref)
    {

        //Make the cURL request
        echo "\n Started Scrapping";
        $htmlRes = AmUtil::askCurl('/' . $typeOfGameHref, false);

        //It allows to know if the Html it got from cURL is from soccer or basket, in order to later know in which tables to add  (Only needed because Option 0 - Both)
        if ($typeOfGameHref == 'basketball/') {
            $this->whichHtmlDoIHave = 'basket';
        }

        self::scrapIt($htmlRes);

    }

    public function scrapIt($htmlRes)
    {
        //Split all unnecessary parts of HTML to try to improve performance
        $startPos = stripos($htmlRes, '<div id="score-data"');
        $end = stripos($htmlRes, '<p class="advert-bottom"', $offset = $startPos);
        $length = $end - $startPos;
        $html = substr($htmlRes, $startPos, $length);

        self::loadHtml($html);
    }

    public function loadHtml($html)
    {
        //Load cleaned html and create a DOMDocument Object
        $this->leagues = array();
        $domDocument = new DOMDocument();
        @$domDocument->loadHTML($html);

        $leaguesHtml = $domDocument->getElementsByTagName('h4');

        //Iterate over all <h4> elements since they are the ones that express the leagues
        foreach ($leaguesHtml as $league) {
            //Create a new League object and associate the variables
            $tempLeagues = new League();
            $tempName = $league->nodeValue;
            $tempName = str_replace("'", "", $tempName);        //In order to prevent Database problems where remove the quote
            $tempLeagues->setLeagueName($tempName);

            array_push($this->leagues, $tempLeagues);           //Add all Leagues object to the Leagues array
        }

        self::scrapGames($html, count($this->leagues));

    }

    public function scrapGames($html, $numberOfLeagues)
    {
        //Split the HTML for Leagues
        //This HTML after splitted will have all the HTML of all the games in a League
        $texts = array();
        $startSearching = 0;
        for ($i = 0; $i < $numberOfLeagues; $i++) {
            $startPos = stripos($html, '<h4>', $startSearching);

            if ($i == $numberOfLeagues - 1) {
                $end = stripos($html, '</div>');                            //In case it's the last League of the list
            } else {
                $end = stripos($html, '<h4>', $offset = $startPos + 1);
            }

            $length = $end - $startPos;
            array_push($texts, substr($html, $startPos, $length));

            $startSearching = $end;

        }

        self::getInfoFromGamesHTML($texts);

    }

    public function getInfoFromGamesHTML($leaguesFromHTML)
    {

        for ($i = 0; $i < count($leaguesFromHTML); $i++) {
            $gamesNames = array();
            $gamesUrls = array();
            $gamesTime = array();
            $gamesScores = array();
            $gamesStatus = "";

            //Using Regular Expression to get the relevant information of every game
            preg_match_all('/(?<=<\/span>)[^<]+/', $leaguesFromHTML[$i], $gamesNames, PREG_PATTERN_ORDER); //Game Names

            preg_match_all('/href="[^>]+" /', $leaguesFromHTML[$i], $gamesUrls, PREG_PATTERN_ORDER); //URL's games

            preg_match_all('/>[^>]+<\/s/', $leaguesFromHTML[$i], $gamesTime, PREG_PATTERN_ORDER); //Times games

            preg_match_all('/[^>]+<\/a/', $leaguesFromHTML[$i], $gamesScores, PREG_PATTERN_ORDER); //Scores games

            for ($j = 0; $j < count($gamesNames[0]); $j++) {
                //After having the information clean the string that comes
                $matchTime = self::cleanMatchTime($gamesTime[0][$j]);
                $matchUrl = self::cleanMatchUrl($gamesUrls[0][$j]);
                $matchScore = self::cleanMatchScore($gamesScores[0][$j]);
                $gamesStatus = self::cleanMatchStatus($matchTime, $matchScore);
                $gameNames = self::cleanGameNames($gamesNames[0][$j]);

                //Split the teams and score in arrays so that pos[0] equals home team and pos[1] away team
                $words = explode("-", $gameNames);
                $goals = explode(":", $matchScore);

                //Create a Game Object
                @$tempGame = new Game($matchTime, $words[0], $words[1], $matchUrl, $goals[0], $goals[1], $gamesStatus);

                //Put the games in the League
                $this->leagues[$i]->pushJogos($tempGame);

            }
        }

        //Because Soccer and Basket games have different details when need to check which the HTML
        //we have in order to get the right details from HTML, this responsability is given to the GameInfo Class
        //This class will also receive the Type Of Game to know which HTML elements to search for
        if ($this->typeOfGame == 0) {
            if ($this->whichHtmlDoIHave == 'basket') {
                $gameInfo = new GameInfo(2);
            } else {
                $gameInfo = new GameInfo(1);
            }
        } else {
            $gameInfo = new GameInfo($this->typeOfGame);
        }

        $allInfo = $gameInfo->getLeaguesLinks($this->leagues);

        if ($this->typeOfGame == 0) {
            if ($this->whichHtmlDoIHave == 'basket') {
                $DBInsert = new DBInsert($allInfo, 2);
            } else {
                $DBInsert = new DBInsert($allInfo, 1);
            }
        } else {
            $DBInsert = new DBInsert($allInfo, $this->typeOfGame);
        }

    }

    public function cleanMatchTime($matchTime)
    {
        $pos = strpos($matchTime, "</s");
        if ($pos > 1) {
            $matchTime = str_replace("</s", "", $matchTime);
            $matchTime = str_replace(">", "", $matchTime);
            $matchTime = str_replace("'", "", $matchTime);
        }
        return $matchTime;
    }

    public function cleanMatchUrl($matchUrl)
    {
        $matchUrl = str_replace('href="', "", $matchUrl);
        $matchUrl = str_replace('"', "", $matchUrl);
        $matchUrl = str_replace("'", "", $matchUrl);

        return $matchUrl;
    }

    public function cleanMatchScore($matchScore)
    {
        $matchScore = str_replace("</a", "", $matchScore);
        $matchScore = str_replace("'", "", $matchScore);

        return $matchScore;
    }

    public function cleanGameNames($gameNames)
    {
        $gameNames = str_replace("'", "", $gameNames);

        return $gameNames;
    }

    public function cleanMatchStatus($matchTime, $matchScore)
    {
        $gamesStatus = 'Error';

        if ($matchTime == "Half Time") {
            $gamesStatus = "Half Time";

        } elseif ($matchTime == "Postponed") {
            $gamesStatus = "Postponed";

        } elseif (strpos($matchScore, "-:-") !== false) {
            $gamesStatus = "Scheduled";

        } elseif (strlen($matchTime) < 4) {
            $gamesStatus = "Live";

        } elseif (strpos($matchTime, ":")) {
            $gamesStatus = "Finished";
        }

        $gamesStatus = str_replace("'", "", $gamesStatus);
        return $gamesStatus;
    }

}
