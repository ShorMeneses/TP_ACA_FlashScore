<?php
    require_once '../League.php';
    require_once '../Game.php';

    $fut= new getFutGames();


 class getFutGames{
    public $servername = "localhost";
    public $username = "root";
    public $password = "";
    public $conn;
    public $leagues;


    public function __construct(){
        $this->leagues=array();
        $this->conn = new mysqli($this->servername, $this->username, $this->password);
        $this->conn->select_db('FlashscoreDB');
        $this->getGames($this->conn);
        $this->conn->close();
    }

    public function getGames($conn){


        $sql="SELECT * FROM FootLeagues";

        $leaguesDB= $conn->query($sql);

        if($leaguesDB->num_rows>0){
         while ($leagueDB=$leaguesDB->fetch_assoc()){
             $leagueTemp = new League();
             $leagueTemp->setLeagueName($leagueDB["nome"]);
             $l=$leagueDB["id"];

             $sql="SELECT * FROM footgames WHERE league_id ='$l' ";

             $gamesOfLeagues=$conn->query($sql);

             $gamesTemp=array();
             while ($gameOfLeague=$gamesOfLeagues->fetch_assoc()){

                 $game = new Game($gameOfLeague["game_time"],
                     $gameOfLeague["home_team"],
                     $gameOfLeague["away_team"],
                     $gameOfLeague["game_link"],
                     $gameOfLeague["hGoals"],
                     $gameOfLeague["aGoals"],
                     $gameOfLeague["game_status"]
                 );

                 $arr = (array) json_decode($gameOfLeague["game_info"],true);

                $game->setGameInfo($arr);
                 array_push($gamesTemp,$game);

             }

             $leagueTemp->pushJogos($gamesTemp);
             array_push($this->leagues,$leagueTemp);
         }


            echo json_encode($this->leagues);
        }else{
            echo"nada";
        }


    }

}