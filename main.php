<?php
require_once './Sites/Flashscore.php';


$searchParam = readline('Choose a query parameter: '); ;  //String to search on websites


$flashschore = new Flashscore();  //Create PirateBay instance

$flashschore -> getSite($searchParam);