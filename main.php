<?php
require_once './Sites/Flashscore.php';

$flashschore = new Flashscore();  //Create PirateBay instance


while (true){
$flashschore -> getSite();
sleep(strtotime('tomorrow') - time()+60);
}