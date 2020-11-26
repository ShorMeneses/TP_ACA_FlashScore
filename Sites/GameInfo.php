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
        $href="http://www.flashscore.mobi/match/AZchMNjM/";
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

            $gameParts = $domDocument;//->getElementById("detail-tab-content");

            $goals1H = self::getGoalsFirstHalf($gameParts);
          //  $goals2H = self::getGoalsSecondHalf($gameParts);
          //  $details1H = self::getFirstHalfDetails($gameParts);
         //   $details2H = self::getSecondHalfDetails($gameParts);
        }catch (Exception $e){

        }
        //array_push($res,$goals1H);
      //  array_push($res,$goals2H);
      //  array_push($res,$details1H);
//        array_push($res,$details2H);

        return $res;

    }


    private static function getGoalsFirstHalf($gameParts){

        $as = $gameParts->getElementById("detail-tab-content");
       // echo $as->textContent;
        $as2 = $as->childNodes;
        $asDivs=[];
       // var_dump($as2);
        foreach ($as2 as $someAElement) {
            if ($someAElement->getAttribute('class')=='detail' ){
                if ($someAElement->previousSibling->tagName == 'h4' && strpos($someAElement->previousSibling->textContent,'1st') ==02   ){
                    echo '1parte';
                    echo strpos($someAElement->previousSibling->textContent,'1st');
                 //   $asDivs[0]=$someAElement->textContent;
                }
              /*  if ($someAElement->previousSibling->tagName == 'h4' && strpos($someAElement->previousSibling->textContent,'2nd') >=0  ){
                    echo '2parte';
                   // $asDivs[1]=$someAElement->textContent;
                }*/
            }
        }

//var_dump($asDivs);

       /* $as = $gameParts->getElementsByTagName('div');
        $asDivs=[];
var_dump($as);
        foreach ($as as $someAElement) {
            if ($someAElement->getAttribute('class')=='detail'  && $someAElement->parentNode->getAttribute('id') == 'detail-tab-content'){
$h4 = $someAElement->previousSibling;
                if ($someAElement->previousSibling->tagName == 'h4' && strpos($someAElement->previousSibling->textContent,'1st') >=0  && strpos($someAElement->previousSibling->textContent,'2nd')==null ){
                    $go=true;
                    do{
                        //echo 'got to 1st part';
                      //  echo $someAElement->previousSibling->textContent;
                        $asDivs[0]=$someAElement->textContent;
                        if ($someAElement->previousSibling->tagName != 'hr'  ){
                            $go=false;
                        }
                    }
                    while ($go);




                }
                if ($someAElement->previousSibling->tagName == 'h4' && strpos($someAElement->previousSibling->textContent,'2nd') >=0){
                    //2 parte
                    echo 'got to 2st part';
                    $asDivs[1]=$someAElement->textContent;
                }

                //array_push($asDivs,$someAElement->textContent);
            }
        }
        var_dump($asDivs);
*/


    }




















  /*  private static function getGoalsFirstHalf($gameParts){
        try {
            if(@$gameParts->firstChild->tagName=="h4"){
                if($gameParts->firstChild->firstChild->nextSibling->tagName=="b"){
                    $goals1H= $gameParts->firstChild->firstChild->nextSibling->nodeValue;
                    $goals1H = explode(":",$goals1H);
                        return $goals1H;
                }
            }

        }catch(Exception $e){
        }

    } */

    /* private static function getGoalsSecondHalf($gameParts){
        try {
            if(@$gameParts->firstChild->nextSibling->nextSibling->tagName!="h4"){
                @$gameParts=$gameParts->firstChild->nextSibling;
            }

            if(@$gameParts->firstChild->nextSibling->nextSibling->tagName=="h4"){
                if($gameParts->firstChild->nextSibling->nextSibling->firstChild->nextSibling->tagName=="b"){
                    $goals2H= $gameParts->firstChild->nextSibling->nextSibling->firstChild->nextSibling->nodeValue;
                    $goals2H = explode(":",$goals2H);
                    return $goals2H;
                }
            }

        }catch(Exception $e){
        }

    }*/


   /* private static function getFirstHalfDetails($gameParts){
        $resArray=array();
        $boolean=true;

       @$actualDiv=$gameParts->firstChild->nextSibling->firstChild; // equivalente a div incident soccer

        try {
           if(@$actualDiv->tagName=="div"){


                   while ($boolean) {

                       $footDetail= new FootDetail($actualDiv->firstChild->textContent,
                           $actualDiv->firstChild->nextSibling->nextSibling->textContent,
                           $actualDiv->firstChild->nextSibling->getAttribute("class"));//tempo descricao tipo

                       array_push($resArray,$footDetail);


                       if ($actualDiv->nextSibling->tagName == "hr") {
                           $boolean = false;
                       } else {
                           $actualDiv = $actualDiv->nextSibling;
                       }
                   }
           }
        }catch(Exception $e){
        }
        return $resArray;
    }
*/

   /* private static function getSecondHalfDetails($gameParts){
        $resArray=array();
        $boolean=true;

        @$actualDiv=$gameParts->firstChild->nextSibling->nextSibling->nextSibling->firstChild; // equivalente a div incident soccer
        if(@$actualDiv->tagName!="div"){
            @$actualDiv=$gameParts->firstChild->nextSibling->nextSibling->fistChild;
           // TODO caso n aconteça nada na 1 parte
            
        }
        try {
            if(@$actualDiv->tagName=="div"){


                while ($boolean) {
                    echo $actualDiv->firstChild->nextSibling->getAttribute("class");

                    $footDetail= new FootDetail($actualDiv->firstChild->textContent,
                        $actualDiv->firstChild->nextSibling->nextSibling->textContent,
                        $actualDiv->firstChild->nextSibling->getAttribute("class"));//tempo descricao tipo

                    array_push($resArray,$footDetail);


                    if ($actualDiv->nextSibling->tagName == "hr") {
                        $boolean = false;
                    } else {
                        $actualDiv = $actualDiv->nextSibling;
                    }
                }
            }
        }catch(Exception $e){
        }
        return $resArray;
    }*/


}