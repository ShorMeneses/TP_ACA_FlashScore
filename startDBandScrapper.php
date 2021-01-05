<?php
require_once 'initDBAndScrapper/Flashscore.php';

$promptMSG =" 1 - Soccer \n 2 - Basketball \n";
echo $promptMSG;
$typeOfGame = readline();



$flashschore = new Flashscore($typeOfGame);  //Create Flashscore instance
echo "\n Api will be available at [IP adress]/get/getFutGames.php";



while (true){
$flashschore -> handlerGetSite();
sleep(strtotime('tomorrow') - time()+60);
}