<?php
require_once 'initDBAndScrapper/Flashscore.php';

//Message to describe the options to the user
$promptMSG = "0 - Both \n1 - Soccer \n2 - Basketball \n";
echo $promptMSG;

//Read user response
$typeOfGame = readline();
echo "\n Api will be available at [IP adress]/get/getFutGames.php";

//Create the DataBase Structure, it has the TypeOfGame to know which tables it needs to work on
$DBcreate = new DBCreate($typeOfGame);

//Everyday at 00:00 it will reload Leagues and Games
while (true) {
    $flashschore = new Flashscore($typeOfGame);
    $flashschore->handlerGetSite();                 //Calling the main function in Flashscore class
    sleep(strtotime('tomorrow') - time() + 60);
}
