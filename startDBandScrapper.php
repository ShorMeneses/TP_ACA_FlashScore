<?php
require_once 'initDBAndScrapper/Flashscore.php';

<<<<<<< Updated upstream
$flashschore = new Flashscore();  //Create Flashscore instance
echo "\n Api will be available at [IP adress]/get/getFutGames.php";

while (true){
$flashschore -> getSite();
=======
$promptMSG ="0 - Both \n1 - Soccer \n2 - Basketball \n";
echo $promptMSG;
$typeOfGame = readline();
echo "\n Api will be available at [IP adress]/get/getFutGames.php";
$DBcreate = new DBCreate($typeOfGame);

while (true){
$flashschore = new Flashscore($typeOfGame);
$flashschore ->handlerGetSite();
>>>>>>> Stashed changes
sleep(strtotime('tomorrow') - time()+60);
}