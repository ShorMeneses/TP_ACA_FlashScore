<?php


class DBInsert{

    public $servername = "localhost";
    public $username = "root";
    public $password = "";
    public $conn;
    public $leagues;
    public $typeOfGame;

    public function __construct($leagues,$typeOfGame){
        $this->typeOfGame = $typeOfGame;
        $this->leagues = $leagues;
        $this->conn = new mysqli($this->servername, $this->username, $this->password);
        $this->conn ->select_db('FlashscoreDB');
        $this->bdHandler();
        $this->conn->close();

    }

    function bdHandler(){
        switch ($this->typeOfGame) {
            case 0:
                $this->bdHandlerSoccer();
                $this->bdHandlerBasket();
                break;
            case 1:
                $this->bdHandlerSoccer();
                break;
            case 2:
                $this->bdHandlerBasket();
                break;
        }
    }

    function bdHandlerSoccer(){
        $this->deleteAllSoccer($this->conn);
        $this->insertLeaguesSoccer($this->conn);
        $this->insertGamesSoccer($this->conn);
    }

    function bdHandlerBasket(){
        $this->deleteAllBasket($this->conn);
        $this->insertLeaguesBasket($this->conn);
        $this->insertGamesBasket($this->conn);
    }



    function insertLeaguesSoccer($conn){


        if ($conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        echo "\n Adding leagues";

        for ($i=0;$i<count($this->leagues);$i++){
        $leagueName =$this->leagues[$i]->name;

            $sql = "INSERT INTO FootLeagues (nome) values ('$leagueName')";

            if ($conn->query($sql) === TRUE) {
                
            } else {
                echo "\n Error adding league: " . $this->conn->error;
            }


        }
        echo "\n Leagues added";


    }


    //------------------------------------


    function insertGamesSoccer($conn){
        echo "\n Adding games";
        if ($conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        for ($i=0;$i<count($this->leagues);$i++){
            foreach ($this->leagues[$i]->games as $game){

                $gameIAmAt = $game;

                $league_id = $i+1;
                $game_time =$gameIAmAt->game_time;


                $home_team =$gameIAmAt->home_team;


                $away_team =$gameIAmAt->away_team;



                $game_status =$gameIAmAt->game_status;


                $hGoals =$gameIAmAt->hGoals;

                $aGoals =$gameIAmAt->aGoals;


                $game_link =$gameIAmAt->game_link;


                $game_info =json_encode($gameIAmAt->game_info);


                $sql = "INSERT INTO FootGames (league_id,
                        game_time,
                        home_team,
                        away_team,
                        game_status,
                        hGoals,
                        aGoals,
                        game_link,
                        game_info) values ('$league_id',
                        '$game_time',
                        '$home_team',
                        '$away_team',
                        '$game_status',
                        '$hGoals',
                        '$aGoals',
                        '$game_link',
                        '$game_info')";


                if ($conn->query($sql) === TRUE) {
                   // echo "\n Game added successfully";
                } else {
                    echo "\n Error adding game: " . $this->conn->error;
                }
            }
        }
        echo "\n Added games on ".count($this->leagues). " leagues" ;

    }


    function deleteAllBasket(mysqli $conn){


        $sql = "DELETE FROM basketgames ";


        if ($conn->query($sql) === TRUE) {
            echo "\n Table BasketGames deleted successfully";
        } else {
            echo "\n Error on deleting table: " . $this->conn->error;
        }

        $sql="ALTER TABLE basketgames AUTO_INCREMENT = 1";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table BasketGames auto increment reset";
        } else {
            echo "\n Error on suto increment reset" . $this->conn->error;
        }


        $sql = "DELETE FROM basketleagues ";


        if ($conn->query($sql) === TRUE) {
            echo "\n Table BasketLeagues deleted successfully";
        } else {
            echo "\n Error on deleting table: " . $this->conn->error;
        }


        $sql="ALTER TABLE basketleagues AUTO_INCREMENT = 1";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table BasketLeagues auto increment reset";
        } else {
            echo "\n Error on suto increment reset" . $this->conn->error;
        }

    }

    function deleteAllSoccer(mysqli $conn){


        $sql = "DELETE FROM footgames ";


        if ($conn->query($sql) === TRUE) {
            echo "\n Table FootGames deleted successfully";
        } else {
            echo "\n Error on deleting table: " . $this->conn->error;
        }

        $sql="ALTER TABLE footgames AUTO_INCREMENT = 1";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table FootGames auto increment reset";
        } else {
            echo "\n Error on suto increment reset" . $this->conn->error;
        }


        $sql = "DELETE FROM footleagues ";


        if ($conn->query($sql) === TRUE) {
            echo "\n Table footLeagues deleted successfully";
        } else {
            echo "\n Error on deleting table: " . $this->conn->error;
        }


        $sql="ALTER TABLE footleagues AUTO_INCREMENT = 1";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table FootLeagues auto increment reset";
        } else {
            echo "\n Error on suto increment reset" . $this->conn->error;
        }

    }


    function insertLeaguesBasket($conn){

        echo "\n Adding Basketball leagues";

        for ($i=0;$i<count($this->leagues);$i++){
        $leagueName =$this->leagues[$i]->name;

            $sql = "INSERT INTO BasketLeagues (nome) values ('$leagueName')";

            if ($conn->query($sql) === TRUE) {
                
            } else {
                echo "\n Error adding league: " . $this->conn->error;
            }


        }
        echo "\n Basketball Leagues added";


    }


 function insertGamesBasket($conn){
        echo "\n Adding Basketball games";
        if ($conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        for ($i=0;$i<count($this->leagues);$i++){
            foreach ($this->leagues[$i]->games as $game){

                $gameIAmAt = $game;

                $league_id = $i+1;
                $game_time =$gameIAmAt->game_time;


                $home_team =$gameIAmAt->home_team;


                $away_team =$gameIAmAt->away_team;



                $game_status =$gameIAmAt->game_status;


                $hGoals =$gameIAmAt->hGoals;

                $aGoals =$gameIAmAt->aGoals;


                $game_link =$gameIAmAt->game_link;


                $game_info =json_encode($gameIAmAt->game_info);


                $sql = "INSERT INTO BasketGames (league_id,
                        game_time,
                        home_team,
                        away_team,
                        game_status,
                        hGoals,
                        aGoals,
                        game_link,
                        game_info) values ('$league_id',
                        '$game_time',
                        '$home_team',
                        '$away_team',
                        '$game_status',
                        '$hGoals',
                        '$aGoals',
                        '$game_link',
                        '$game_info')";


                if ($conn->query($sql) === TRUE) {
                   // echo "\n Game added successfully";
                } else {
                    echo "\n Error adding game: " . $this->conn->error;
                }
            }
        }
        echo "\n Added Basketball games on ".count($this->leagues). " leagues" ;

    }




}