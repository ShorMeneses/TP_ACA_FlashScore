<?php
require_once './Sites/Rarbg.php';


$searchParam = readline('Choose a query parameter: '); ;  //String to search on websites


$rarbg = new Rarbg();  //Create PirateBay instance

$rarbg -> getSite($searchParam);