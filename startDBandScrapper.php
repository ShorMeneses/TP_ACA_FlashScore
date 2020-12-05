<?php
require_once 'initDBAndScrapper/Flashscore.php';

$flashschore = new Flashscore();  //Create Flashscore instance
echo "\n Api will be available at [IP adress]/get/getFutGames.php";

while (true){
$flashschore -> getSite();
sleep(strtotime('tomorrow') - time()+60);
}