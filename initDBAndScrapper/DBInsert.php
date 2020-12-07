<?php


class DBInsert{

    public $servername = "localhost";
    public $username = "root";
    public $password = "";
    public $conn;
    public $leagues;

    public function __construct($leagues){

        $this->leagues = $leagues;
        $this->conn = new mysqli($this->servername, $this->username, $this->password);
        $this->conn ->select_db('FlashscoreDB');
        self::deleteAll($this->conn);
        $this->insertLeagues($this->conn);
        $this->insertGames($this->conn);
        $this->conn->close();

    }


    function insertLeagues($conn){


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


    function insertGames($conn){
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

    function deleteAll(mysqli $conn){


        $sql = "DELETE FROM footgames ";


        if ($conn->query($sql) === TRUE) {
            echo "\n Table FootGames deleted successfully";
        } else {
            echo "\n Error on deleting table: " . $this->conn->error;
        }
        $sql="ALTER TABLE footgames AUTO_INCREMENT = 1";

        if ($conn->query($sql) === TRUE) {
            echo "\n Table auto increment reset";
        } else {
            echo "\n Error on suto increment reset" . $this->conn->error;
        }
    }


}