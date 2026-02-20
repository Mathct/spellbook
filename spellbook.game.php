<?php
 /**
  *------
  * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
  * spellbook implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
  * 
  * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
  * See http://en.boardgamearena.com/#!doc/Studio for more information.
  * -----
  * 
  * spellbook.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */

use Bga\GameFramework\Components\Deck;
use Bga\GameFramework\Table;
use Bga\GameFramework\UserException;
use Bga\GameFramework\VisibleSystemException;

include('modules/Pending.php');
include('modules/Card.php');


class spellbook extends Table
{
    public static $instance = null;

    public Deck $materia;
   
    public array $listecards;
    public array $listeaide;

	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
        
        self::initGameStateLabels( array( 
            "color" => 10, 
            "position" => 11, 
            "idclone" => 12,
            "selectautel" => 13,
            "testtransmutation" => 14, //pas utilisé pour le moment
            "matinperm" => 15,
            "matinos" => 16,
            "testdiscard32" => 17,
            "solo" => 18,
            "variable1" => 19, //test auto select
            "variable2" => 20, //pas utilisé pour le moment
            "game_mode" => 100,

        ) );  
        
        self::$instance = $this;

        $this->materia = $this->bga->deckFactory->createDeck( "materia" );
        $this->materia->autoreshuffle = true;

	}

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO `player` (`player_id`, `player_color`, `player_canal`, `player_name`, `player_avatar`) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";
        }
        $sql .= implode( ',', $values );
        self::DbQuery( $sql );
        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
/////////////////////////////////////////////////////////////////////////////////  
//       _____                        _____       _ _   _       _ _          _   _             
//      / ____|                      |_   _|     (_) | (_)     | (_)        | | (_)            
//     | |  __  __ _ _ __ ___   ___    | |  _ __  _| |_ _  __ _| |_ ______ _| |_ _  ___  _ __  
//     | | |_ |/ _` | '_ ` _ \ / _ \   | | | '_ \| | __| |/ _` | | |_  / _` | __| |/ _ \| '_ \ 
//     | |__| | (_| | | | | | |  __/  _| |_| | | | | |_| | (_| | | |/ / (_| | |_| | (_) | | | |
//      \_____|\__,_|_| |_| |_|\___| |_____|_| |_|_|\__|_|\__,_|_|_/___\__,_|\__|_|\___/|_| |_|
//                                                                                               
/////////////////////////////////////////////////////////////////////////////////    

        self::initStat( 'player', 'score_spell', 0 ); 
        self::initStat( 'player', 'score_familiar', 0 );

        $nbreplayers = count(self::getObjectListFromDB( "SELECT `player_id` FROM `player`", true ));

        if($nbreplayers == 1)
        {
        spellbook::$instance->setGameStateValue('solo', 1);
        }


        $materia = array();
        for ($i = 1; $i <= 7; $i++)
        {
            for ($j = 1; $j <=3; $j++)
            {
                $materia[] = array( 'type' => $i, 'type_arg' => $j, 'nbr' => 5);
            }
            
        }

        $this->materia->createCards( $materia, 'deck' );
        $this->materia->shuffle( 'deck' );
        if($nbreplayers != 1)
        {
            for ($m = 1; $m<=5; $m++)
            {
            $this->materia->pickCardForLocation( 'deck', 'materiaautel', $m );
            }
        }
        if($nbreplayers == 1)
        {
            for ($m = 1; $m<=7; $m++)
            {
            $this->materia->pickCardForLocation( 'deck', 'materiaautel', $m );
            }
        }
        foreach( $players as $player_id => $player )
        {
            for ($n = 1; $n<= 2; $n++)
            {
                $this->materia->pickCardForLocation( 'deck', 'materiareserve_'.$player_id , $n );

            }

        }


        $gamemode = $this->bga->tableOptions->get(100);
        if ($gamemode == 2)
        {
            $set = 1;
            $jourset_1 = [1,1,2,2,3,3,0];
            $type_1 = [[0,0,0,0,0,3,1],[0,0,0,0,0,0,1],[0,0,0,0,0,0,1]]; // p3/p4/p5
            $vp_1 = [[1,3,1,2,2,4,3],[2,4,2,4,4,4,5],[3,5,3,6,6,4,7]]; // p3/p4/p5
            

            foreach( $players as $player_id => $player )
            {
                for ($k = 1; $k<= 7; $k++)
                {
                    for ($p =3; $p<=5; $p++)
                    {
                        $indexjour = $k-1;
                        $jour = intval($jourset_1[$indexjour]);
                        $type = $type_1[$p-3][$k-1];
                        $vp = $vp_1[$p-3][$k-1];
                        self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES ({$set}, {$k}, {$player_id}, {$jour}, {$p}, {$type}, {$vp})" );
                    }

                }

            }
        }

        if ($gamemode == 3)
        {
            $set = 2;
            $jourset_1 = [1,1,2,3,3,0,0];
            $type_1 = [[0,0,0,0,3,1,4],[0,0,0,0,0,1,4],[0,0,0,0,0,2,4]]; // p3/p4/p5
            $vp_1 = [[2,4,3,3,4,3,0],[3,4,4,4,6,6,0],[4,4,5,5,8,0,0]]; // p3/p4/p5
            

            foreach( $players as $player_id => $player )
            {
                for ($k = 1; $k<= 7; $k++)
                {
                    for ($p =3; $p<=5; $p++)
                    {
                        $indexjour = $k-1;
                        $jour = intval($jourset_1[$indexjour]);
                        $type = $type_1[$p-3][$k-1];
                        $vp = $vp_1[$p-3][$k-1];
                        self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES ({$set}, {$k}, {$player_id}, {$jour}, {$p}, {$type}, {$vp})" );
                    }

                }

            }

        }

        if ($gamemode == 4)
        {
        $set = 3;
        $jourset_1 = [1,1,3,2,2,0,0];
        $type_1 = [[0,0,0,0,0,2,1],[0,0,0,0,0,2,4],[0,0,0,4,0,2,2]]; // p3/p4/p5
        $vp_1 = [[0,2,3,2,4,2,0],[2,3,4,2,5,3,0],[5,4,6,0,6,6,0]]; // p3/p4/p5
        

        foreach( $players as $player_id => $player )
        {
            for ($k = 1; $k<= 7; $k++)
            {
                for ($p =3; $p<=5; $p++)
                {
                    $indexjour = $k-1;
                    $jour = intval($jourset_1[$indexjour]);
                    $type = $type_1[$p-3][$k-1];
                    $vp = $vp_1[$p-3][$k-1];
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES ({$set}, {$k}, {$player_id}, {$jour}, {$p}, {$type}, {$vp})" );
                }

            }

        }

        }

        if ($gamemode == 1)
        {
            $randsetrouge = bga_rand(1,3);
            if($randsetrouge == 1)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 1, {$player_id}, 1, 3, 0, 1)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 1, {$player_id}, 1, 4, 0, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 1, {$player_id}, 1, 5, 0, 3)" );

                }

            }
            if($randsetrouge == 2)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 1, {$player_id}, 1, 3, 0, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 1, {$player_id}, 1, 4, 0, 3)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 1, {$player_id}, 1, 5, 0, 4)" );

                }
                
            }
            if($randsetrouge == 3)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 1, {$player_id}, 1, 3, 0, 0)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 1, {$player_id}, 1, 4, 0, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 1, {$player_id}, 1, 5, 0, 5)" );

                }
                
            }

            $randsetviolet = bga_rand(1,3);
            if($randsetviolet == 1)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 2, {$player_id}, 1, 3, 0, 3)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 2, {$player_id}, 1, 4, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 2, {$player_id}, 1, 5, 0, 5)" );

                }

            }
            if($randsetviolet == 2)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 2, {$player_id}, 1, 3, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 2, {$player_id}, 1, 4, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 2, {$player_id}, 1, 5, 0, 4)" );

                }
                
            }
            if($randsetviolet == 3)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 2, {$player_id}, 1, 3, 0, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 2, {$player_id}, 1, 4, 0, 3)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 2, {$player_id}, 1, 5, 0, 4)" );

                }
                
            }

            $randsetvert = bga_rand(1,3);
            if($randsetvert == 1)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 3, {$player_id}, 2, 3, 0, 1)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 3, {$player_id}, 2, 4, 0, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 3, {$player_id}, 2, 5, 0, 3)" );

                }

            }
            if($randsetvert == 2)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 3, {$player_id}, 2, 3, 0, 3)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 3, {$player_id}, 2, 4, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 3, {$player_id}, 2, 5, 0, 5)" );

                }
                
            }
            if($randsetvert == 3)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 3, {$player_id}, 3, 3, 0, 3)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 3, {$player_id}, 3, 4, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 3, {$player_id}, 3, 5, 0, 6)" );

                }
                
            }

            $randsetnoir = bga_rand(1,3);
            if($randsetnoir == 1)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 4, {$player_id}, 2, 3, 0, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 4, {$player_id}, 2, 4, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 4, {$player_id}, 2, 5, 0, 6)" );

                }

            }
            if($randsetnoir == 2)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 4, {$player_id}, 3, 3, 0, 3)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 4, {$player_id}, 3, 4, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 4, {$player_id}, 3, 5, 0, 5)" );

                }
                
            }
            if($randsetnoir == 3)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 4, {$player_id}, 2, 3, 0, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 4, {$player_id}, 2, 4, 0, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 4, {$player_id}, 2, 5, 4, 0)" );

                }
                
            }

            $randsetblanc = bga_rand(1,3);
            if($randsetblanc == 1)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 5, {$player_id}, 3, 3, 0, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 5, {$player_id}, 3, 4, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 5, {$player_id}, 3, 5, 0, 6)" );

                }

            }
            if($randsetblanc == 2)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 5, {$player_id}, 3, 3, 3, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 5, {$player_id}, 3, 4, 0, 6)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 5, {$player_id}, 3, 5, 0, 8)" );

                }
                
            }
            if($randsetblanc == 3)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 5, {$player_id}, 2, 3, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 5, {$player_id}, 2, 4, 0, 5)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 5, {$player_id}, 2, 5, 0, 6)" );

                }
                
            }

            $randsetbleu = bga_rand(1,3);
            if($randsetbleu == 1)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 6, {$player_id}, 3, 3, 3, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 6, {$player_id}, 3, 4, 0, 4)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 6, {$player_id}, 3, 5, 0, 4)" );

                }

            }
            if($randsetbleu == 2)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 6, {$player_id}, 0, 3, 1, 3)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 6, {$player_id}, 0, 4, 1, 6)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 6, {$player_id}, 0, 5, 2, 0)" );

                }
                
            }
            if($randsetbleu == 3)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 6, {$player_id}, 0, 3, 2, 2)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 6, {$player_id}, 0, 4, 2, 3)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 6, {$player_id}, 0, 5, 2, 6)" );

                }
                
            }

            $randsetjaune = bga_rand(1,3);
            if($randsetjaune == 1)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 7, {$player_id}, 0, 3, 1, 3)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 7, {$player_id}, 0, 4, 1, 5)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (1, 7, {$player_id}, 0, 5, 1, 7)" );

                }

            }
            if($randsetjaune == 2)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 7, {$player_id}, 0, 3, 4, 0)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 7, {$player_id}, 0, 4, 4, 0)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (2, 7, {$player_id}, 0, 5, 4, 0)" );

                }
                
            }
            if($randsetjaune == 3)
            {
                foreach( $players as $player_id => $player )
                {
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 7, {$player_id}, 0, 3, 1, 0)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 7, {$player_id}, 0, 4, 4, 0)" );
                    self::DbQuery( "INSERT INTO `cards` (`set_id`, `set_color`, `player_id`, `jour`, `power`, `type`, `vp`) VALUES (3, 7, {$player_id}, 0, 5, 2, 0)" );

                }
                
            }
        
        }




        








        /************ Init `Pending` *****/
        if($nbreplayers != 1)
        {
        foreach( $players as $player_id => $player )
        {
            $this->addPendingFirst($player_id, "Matin");
        }
        }

        if($nbreplayers == 1)
        {
        foreach( $players as $player_id => $player )
        {
            $this->addPendingFirst($player_id, "SoloInit");
        }
        }





        /************ End of the game initialization *****/
        return 2;
    }

   
/////////////////////////////////////////////////////////////////////////////////  
//               _            _ _ _____        _            
//              | |     /\   | | |  __ \      | |           
//     __ _  ___| |_   /  \  | | | |  | | __ _| |_ __ _ ___ 
//    / _` |/ _ \ __| / /\ \ | | | |  | |/ _` | __/ _` / __|
//   | (_| |  __/ |_ / ____ \| | | |__| | (_| | || (_| \__ \
//    \__, |\___|\__/_/    \_\_|_|_____/ \__,_|\__\__,_|___/
//     __/ |                                                
//    |___/                                                 
/////////////////////////////////////////////////////////////////////////////////  


    protected function getAllDatas()
    {
        $result = array();
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "`player`" table in "dbmodel.sql" if you need it.
        $sql = "SELECT `player_id` `id`, `player_score` score, `player_no` pos FROM `player` ";
        $result['players'] = self::getCollectionFromDb( $sql );
  
        // TODO: Gather all information about current game situation (visible by player $current_player_id).

        $result['nbreplayers'] = count ($result['players']);

        $result['cards'] = self::getObjectListFromDB( "SELECT `set_id` `id`, `set_color` color, `player_id` `player` FROM `cards` WHERE `power` = 3" );
        $result['familier'] = self::getObjectListFromDB( "SELECT `player_no` no, `player_id` `player` FROM `player`");
        $result['materia'] = self::getObjectListFromDB( "SELECT `card_id` `id`, `card_type` `type`, `card_type_arg` type_arg, `card_location` location, `card_location_arg` location_arg FROM `materia` WHERE `card_location` != 'deck' and `card_location` != 'discard'");
        
        $result['firstplayer'][] = self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no`=1");

        $result['listecards'] = $this->listecards;
        $result['listeaide'] = $this->listeaide;

        $result['final'][] = count(self::getObjectListFromDB( "SELECT `player_id` FROM `player` WHERE `final` = 1", true ));

        $listplayers = self::getObjectListFromDB("SELECT `player_id` `id` FROM `player`", true);
        foreach($listplayers as $player)
        {
            $familier = 'materiafamilier_'.$player;
            $result['nbrefamilier'][$player] = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
            $result['nbrespell'][$player] = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` = {$player} AND `typerune` != 0", true ));

        }
        
        $nbreopponent = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaopponent'", true ));
        $nbrereserveopponent = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent'", true ));
        if (($nbreopponent>=0)&&($nbreopponent<=3))
        {
            $scoreopponent = $nbrereserveopponent;
        }
        else
        {
            $multi = $nbreopponent-4;
            $scoreopponent = 14 + ($multi*2) + $nbrereserveopponent;
        }
        $result['scoresolo']=$scoreopponent;
          
        return $result; 
    }

   
/////////////////////////////////////////////////////////////////////////////////  
//     _____                      _____                                   _             
//    / ____|                    |  __ \                                 (_)            
//   | |  __  __ _ _ __ ___   ___| |__) | __ ___   __ _ _ __ ___  ___ ___ _  ___  _ __  
//   | | |_ |/ _` | '_ ` _ \ / _ \  ___/ '__/ _ \ / _` | '__/ _ \/ __/ __| |/ _ \| '_ \ 
//   | |__| | (_| | | | | | |  __/ |   | | | (_) | (_| | | |  __/\__ \__ \ | (_) | | | |
//    \_____|\__,_|_| |_| |_|\___|_|   |_|  \___/ \__, |_|  \___||___/___/_|\___/|_| |_|
//                                                 __/ |                                
//                                                |___/                                 
/////////////////////////////////////////////////////////////////////////////////    

    function getGameProgression()
    {
        // TODO: compute and return the game progression

        $listplayers = self::getObjectListFromDB("SELECT `player_id` `id` FROM `player`", true);
        $tableaufamiliar = array();
        $tableauspell = array();
        foreach($listplayers as $player)
        {
            $familier = 'materiafamilier_'.$player;
            $nbrefamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
            $nbrespell = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` = {$player} AND `typerune` != 0", true ));
            $tableaufamiliar[]=$nbrefamilier;
            $tableauspell[]=$nbrespell;
        }

        $maxfamiliar = max($tableaufamiliar);
        $maxspell = max($tableauspell);

        $a = floor($maxfamiliar*100/14);
        $b = floor($maxspell*100/7);

        if($a>=$b)
        {
            return $a;
        }

        if($a<$b)
        {
            return $b;
        }

        
    }


/////////////////////////////////////////////////////////////////////////////////  
//     _    _ _   _ _ _ _            __                  _   _                 
//    | |  | | | (_) (_) |          / _|                | | (_)                
//    | |  | | |_ _| |_| |_ _   _  | |_ _   _ _ __   ___| |_ _  ___  _ __  ___ 
//    | |  | | __| | | | __| | | | |  _| | | | '_ \ / __| __| |/ _ \| '_ \/ __|
//    | |__| | |_| | | | |_| |_| | | | | |_| | | | | (__| |_| | (_) | | | \__ \
//     \____/ \__|_|_|_|\__|\__, | |_|  \__,_|_| |_|\___|\__|_|\___/|_| |_|___/
//                           __/ |                                             
//                          |___/                                              
/////////////////////////////////////////////////////////////////////////////////  

function addPending($player_id, $function, $arg = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
    $sql = "INSERT INTO `pending` (`player_id`, `function`, `arg`, `arg2`, `arg3`, `arg4`) VALUES (".$player_id.", '".$function."', '".$arg."', '".$arg2."', '".$arg3."', '".$arg4."')";
    self::DbQuery( $sql );
}

function addPendingTarget($player_id, $function, $target, $arg = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
    $sql = "INSERT INTO `pending` (`player_id`, `function`, `target`, `arg`, `arg2`, `arg3`, `arg4`) VALUES (".$player_id.", '".$function."', '".$target."', '".$arg."', '".$arg2."', '".$arg3."', '".$arg4."')";
    self::DbQuery( $sql );
}

function addPendingFirst($player_id, $function, $arg = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
    $minid = self::getUniqueValueFromDB( "select min(`id`) from `pending`")-1;
    $sql = "INSERT INTO `pending` (`id`, `player_id`, `function`, `arg`, `arg2`) VALUES (".$minid.",".$player_id.", '".$function."', '".$arg."', '".$arg2."')";
    self::DbQuery( $sql );
}

function checkArgs($arg1)
    {
        $ret = self::argPlayerTurn();

        if(!in_array($arg1,$ret['selectable']) && !in_array($arg1,$ret['buttons']))
        {
            throw new UserException( "Not a valid selection");
        }
        
    }

function getPlayerRelativePositions()  // permet de mettre dans view.php les joueurs dans l'ordre de la base de données et de positionner le current player en haut avec les autres joueurs dans l'ordre du tour
    {
        $result = array();
        
        $players = self::loadPlayersBasicInfos();
        $nextPlayer = self::createNextPlayerTable(array_keys($players)); //met joueurs dans l'ordre du tour au niveau de l'affichage à droite
        
        $current_player = self::getCurrentPlayerId();
        
        if(!isset($nextPlayer[$current_player])) {
            // Spectator mode: prend la vue du premier joueur de la liste
            $player_id = $nextPlayer[0];
        }
        else {
            // Normal mode: current `player` est premier de la liste puis les autres dans l ordre de la base de données `player`
            $player_id = $current_player;
        }
        $result[] = $player_id;
        
        for($i=1; $i<count($players); $i++) {
            $player_id = $nextPlayer[$player_id];
            $result[] = $player_id;
        }
        return $result;
    }

function AutelReorganisation()

{

$list = self::getObjectListFromDB( "SELECT `card_id` `id`, `card_location_arg` location FROM `materia` WHERE `card_location` = 'materiaautel' ORDER BY `card_location_arg` ASC" );
$nbre = count($list);
    if($nbre >=1 )
    {
        for ( $i=1; $i<=$nbre; $i++)
        {
            if($list[$i-1]['location'] != $i )
            {
                spellbook::$instance->materia->moveCard( $list[$i-1]['id'], 'materiaautel', $i);
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$list[$i-1]['id'],
                    'parent' => 'materiaautel_'.$i,
                    
                    )
                    );
            }
            
        }

    }

}

function CalculPv()

{
    self::DbQuery( "UPDATE `player` set `player_score` = 0" ); // remise à zero des scores
    $players = self::getObjectListFromDB( "SELECT `player_id` FROM `player`", true );
    //$activeplayer_id = $this->getActivePlayerId();

    


    // Calcul poour chaque `player`
    
    foreach ($players as $playerid)
    {
        /// `VP` card27 
    
    $lvl = self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =7 AND `set_id` =2");

    if ($lvl != NULL)
    {
       $nbreotherspell = count(self::getObjectListFromDB( "SELECT `set_id` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` != 7", true ));
       $nbreotherspell45 = count(self::getObjectListFromDB( "SELECT `set_id` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` != 7 AND `power` != 3", true ));
       $nbreotherspell3 = count(self::getObjectListFromDB( "SELECT `set_id` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` != 7 AND `power` = 3", true ));
    }

    if ($lvl == 3)
    {
        
        $score = $nbreotherspell;
        self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$score} WHERE `player_id` = {$playerid}" );
    }

    if ($lvl == 4)
    {
        $score = ($nbreotherspell45*2)+$nbreotherspell3;
        self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$score} WHERE `player_id` = {$playerid}" );
    }

    if ($lvl == 5)
    {
        $score = $nbreotherspell*2;
        self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$score} WHERE `player_id` = {$playerid}" );
    }

    /// `VP` card34 

    $lvl34 = self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =4 AND `set_id` =3");

    if ($lvl34 == 5)
    {
        $familier = 'materiafamilier_'.$playerid;

        $rouge = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 1", true ));
        $violet = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 2", true ));
        $vert = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 3", true ));
        $noir = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 4", true ));
        $blanc = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 5", true ));
        $bleu = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 6", true ));
        $jaune = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 7", true ));

        $compteur34 = 0;

        if ($rouge > 0)
        {
            $compteur34 = $compteur34 +1;
        }
        if ($violet > 0)
        {
            $compteur34 = $compteur34 +1;
        }
        if ($vert > 0)
        {
            $compteur34 = $compteur34 +1;
        }
        if ($noir > 0)
        {
            $compteur34 = $compteur34 +1;
        }
        if ($blanc> 0)
        {
            $compteur34 = $compteur34 +1;
        }
        if ($bleu > 0)
        {
            $compteur34 = $compteur34 +1;
        }
        if ($jaune > 0)
        {
            $compteur34 = $compteur34 +1;
        }

        self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$compteur34} WHERE `player_id` = {$playerid}" );
    }


    /// `VP` card37

    $lvl37 = self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =7 AND `set_id` =3");

    if ($lvl37 == 4)
    {
        
        $rune = self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =7 AND `set_id` =3");
        $familier = 'materiafamilier_'.$playerid;

        $compteur37 = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type_arg` = '{$rune}'", true ));
        

        self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$compteur37} WHERE `player_id` = {$playerid}" );
    }

        // calcul pour familier

        $familier = 'materiafamilier_'.$playerid;
        $nbrefamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$familier}'", true ));
        $vptotalfamilier = 0;

        if(($nbrefamilier >=1)&&($nbrefamilier <=5))
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$nbrefamilier} WHERE `player_id` = {$playerid}" );
            $vptotalfamilier = $nbrefamilier;
        }
        if($nbrefamilier == 6)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + 7 WHERE `player_id` = {$playerid}" );
            $vptotalfamilier = 7;
        }
        if(($nbrefamilier >=7)&&($nbrefamilier <=8))
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$nbrefamilier} +1 WHERE `player_id` = {$playerid}" );
            $vptotalfamilier = $nbrefamilier +1;
        }
        if(($nbrefamilier >=9)&&($nbrefamilier <=11))
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$nbrefamilier} +2 WHERE `player_id` = {$playerid}" );
            $vptotalfamilier = $nbrefamilier +2;
        }
        if($nbrefamilier == 12)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + 15 WHERE `player_id` = {$playerid}" );
            $vptotalfamilier = 15;
        }
        if($nbrefamilier == 13)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + 16 WHERE `player_id` = {$playerid}" );
            $vptotalfamilier = 16;
        }
        if($nbrefamilier == 14)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + 18 WHERE `player_id` = {$playerid}" );
            $vptotalfamilier = 18;
        }

        // calcul pour les sorts

        
        $vp1 = self::getUniqueValueFromDB("SELECT `vp` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =1");
        if ($vp1 != NULL)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$vp1} WHERE `player_id` = {$playerid}" );
        }
        $vp2 = self::getUniqueValueFromDB("SELECT `vp` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =2");
        if ($vp2 != NULL)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$vp2} WHERE `player_id` = {$playerid}" );
        }
        $vp3 = self::getUniqueValueFromDB("SELECT `vp` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =3");
        if ($vp3 != NULL)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$vp3} WHERE `player_id` = {$playerid}" );
        }
        $vp4 = self::getUniqueValueFromDB("SELECT `vp` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =4");
        if ($vp4 != NULL)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$vp4} WHERE `player_id` = {$playerid}" );
        }
        $vp5 = self::getUniqueValueFromDB("SELECT `vp` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =5");
        if ($vp5 != NULL)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$vp5} WHERE `player_id` = {$playerid}" );
        }
        $vp6 = self::getUniqueValueFromDB("SELECT `vp` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =6");
        if ($vp6 != NULL)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$vp6} WHERE `player_id` = {$playerid}" );
        }
        $vp7 = self::getUniqueValueFromDB("SELECT `vp` FROM `cards` WHERE `player_id`={$playerid} AND `typerune` !=0 AND `set_color` =7");
        if ($vp7 != NULL)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = `player_score` + {$vp7} WHERE `player_id` = {$playerid}" );
        }
        

        //// mettre ici le score en negatif si joueur solo 





        
        // mise à `jour` du score
        $score = self::getUniqueValueFromDB("SELECT `player_score` FROM `player` WHERE `player_id`={$playerid}");
        //mise a `jour` du score du joueur
        spellbook::$instance->notifyAllPlayers('score','', array(
            'player' =>  $playerid,
            'score' => $score,
            
            )
            );
        
            
        $this->setStat( $vptotalfamilier, 'score_familiar', $playerid );
        $vptotalspell = $score - $vptotalfamilier;
        $this->setStat( $vptotalspell, 'score_spell', $playerid );


        $familier = 'materiafamilier_'.$playerid;
        $nbrefamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
        $nbrespell = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` = {$playerid} AND `typerune` != 0", true )); 

        spellbook::$instance->notifyAllPlayers('pannel','', array(
            'id' =>  $playerid,
            'familier' => $nbrefamilier,
            'spell' => $nbrespell,
            
            )
            );


    }

    if (spellbook::$instance->getGameStateValue('solo')==1)
    {
        $nbreopponent = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaopponent'", true ));
        $nbrereserveopponent = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent'", true ));
        if (($nbreopponent>=0)&&($nbreopponent<=3))
        {
            $scoreopponent = $nbrereserveopponent;
        }
        else
        {
            $multi = $nbreopponent-4;
            $scoreopponent = 14 + ($multi*2) + $nbrereserveopponent;
        }

        spellbook::$instance->notifyAllPlayers('pannelsolo','', array(
            'score' =>  $scoreopponent,
            
            
            )
            );
    }

    
}

function EndGame($player)

{
    if (spellbook::$instance->getGameStateValue('solo')==0)
    {
    //$player_id = $this->getActivePlayerId();
    $player_id = $player;
    
    
    $nbrejoueurs = count(self::getObjectListFromDB( "SELECT `player_id` FROM `player`", true ));
    $final = count(self::getObjectListFromDB( "SELECT `player_id` FROM `player` WHERE `final` = 1", true ));
    $numerojoueur = intval(self::getUniqueValueFromDB("SELECT `player_no` FROM `player` WHERE `player_id`={$player_id}"));

    if(($final>=1)&&($numerojoueur == $nbrejoueurs))
    {
        spellbook::$instance->CalculPv();

        // tie breaker
        $players = self::getObjectListFromDB( "SELECT `player_id` FROM `player`", true );
        foreach ($players as $playerid)
        {
            $countspell = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` = {$playerid} AND `typerune` != 0", true ));
            $reserve = 'materiareserve_'.$playerid;
            $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));
            $scoreaux = ($countspell*10)+$countreserve;
            self::DbQuery( "UPDATE `player` set player_score_aux = {$scoreaux} WHERE `player_id` = {$playerid}" );

        }


        $this->gamestate->nextState('end'); 
    }

    $familier = 'materiafamilier_'.$player_id;
    $nbrefamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
    $nbrespell = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` = {$player_id} AND `typerune` != 0", true ));

    
    if (($nbrefamilier == 14)||($nbrespell == 7))
    {
        self::DbQuery( "UPDATE `player` set `final` = 1 WHERE `player_id` = {$player_id}" );
        $final = count(self::getObjectListFromDB( "SELECT `player_id` FROM `player` WHERE `final` = 1", true ));
        if($final == 1)
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( 'The end of the game has just been triggered'), array());
            spellbook::$instance->notifyAllPlayers('alerte','', array());
        }
    }

    $final = count(self::getObjectListFromDB( "SELECT `player_id` FROM `player` WHERE `final` = 1", true ));
    
    if(($final>=1)&&($numerojoueur == $nbrejoueurs))
    {
        spellbook::$instance->CalculPv();

        // tie breaker
        $players = self::getObjectListFromDB( "SELECT `player_id` FROM `player`", true );
        foreach ($players as $playerid)
        {
            $countspell = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` = {$playerid} AND `typerune` != 0", true ));
            $reserve = 'materiareserve_'.$playerid;
            $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));
            $scoreaux = ($countspell*10)+$countreserve;
            self::DbQuery( "UPDATE `player` set player_score_aux = {$scoreaux} WHERE `player_id` = {$playerid}" );
            

        }

        $this->gamestate->nextState('end'); 
    }
    }

    if (spellbook::$instance->getGameStateValue('solo')==1)
    {
        $player_id = $this->getActivePlayerId();
        $familier = 'materiafamilier_'.$player_id;
        $nbrefamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
        $nbrespell = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` = {$player_id} AND `typerune` != 0", true ));
        $nbreopponent = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaopponent'", true ));
        $nbrereserveopponent = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent'", true ));

    if (($nbrefamilier == 14)||($nbrespell == 7)||($nbreopponent == 17))
    {
        spellbook::$instance->CalculPv();
        $scoreplayer = intval(self::getUniqueValueFromDB("SELECT `player_score` FROM `player` WHERE `player_id`={$player_id}"));
        if (($nbreopponent>=0)&&($nbreopponent<=3))
        {
            $scoreopponent = $nbrereserveopponent;
        }
        else
        {
            $multi = $nbreopponent-4;
            $scoreopponent = 14 + ($multi*2) + $nbrereserveopponent;
        }

        $compar = $scoreplayer - $scoreopponent;
        if ($compar == 0)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = 0 WHERE `player_id` = {$player_id}" );
        }
        if ($compar < 0)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = -1 WHERE `player_id` = {$player_id}" );
        }
        if ($compar > 0)
        {
            self::DbQuery( "UPDATE `player` set `player_score` = 1 WHERE `player_id` = {$player_id}" );
        }


        
        $this->gamestate->nextState('end'); 
    }

    }
    
   
    

}



function Permanent36($take)

{
    
    

    $player_id = $this->getActivePlayerId();
    $draw = intval(self::getUniqueValueFromDB("SELECT `p36` FROM `player` WHERE `player_id` = {$player_id}"));

    $reserve = 'materiareserve_'.$player_id;
    $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));
    $libre = 9- $countreserve;

    if(($draw != 0)&&($libre !=0))
    {
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id` = {$player_id} AND `set_color` = 6 AND `typerune` != 0"));
        $explode = explode("_", $take);
        $tableau = array_map('intval', $explode); //pour transformer les string du tableau en entier et recreer un nouveau tableau

        /// enlever les zero du tableau
        $tableausanszero = array_filter($tableau, function($valeur) {
            return $valeur != 0;
        });

        // Réindexer le tableau pour réorganiser les clés
        $tableausanszero = array_values($tableausanszero);

        
        $compteur36 = 0;
        foreach($tableausanszero as $id)
        {
            $runetake = intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id` = {$id}"));
            if($runetake == $rune)
            {
                $compteur36 = $compteur36 +1;
            }

        }

        $nbredraw = $draw * $compteur36;

        
        
        if($nbredraw !=0)
        {
            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$player_id;
            $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacement);

            if($nbredraw<=$libre)
            {
                for($i = 1; $i <= $nbredraw; $i++)
                {
                    $emplacementlibre = array_slice($diff, 0, $i);
                    $premieremplacementlibre = $emplacementlibre[$i-1];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $premieremplacementlibre);
                $idmateria = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                $colormateria = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                $runemateria = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $premieremplacementlibre,
                    
                    )
                    );
                    
                }

            }

            if($nbredraw>$libre)
            {
                for($i = 1; $i <= $libre; $i++)
                {
                    $emplacementlibre = array_slice($diff, 0, $i);
                    $premieremplacementlibre = $emplacementlibre[$i-1];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $premieremplacementlibre);
                $idmateria = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                $colormateria = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                $runemateria = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $premieremplacementlibre,
                    
                    )
                    );

                }

            }
            
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "MIRAGE"'), array(
                'player_name' => self::getUniqueValueFromDB("SELECT `player_name` FROM `player` WHERE `player_id`={$player_id}"),
                                
                )
                );

        }



    }

    

}

function getLogsType( $type ) 
{
    if($type == 11)
    {return "<div class='maticone11' title=''></div>";}
    if($type == 12)
    {return "<div class='maticone12' title=''></div>";}
    if($type == 13)
    {return "<div class='maticone13' title=''></div>";}
    if($type == 21)
    {return "<div class='maticone21' title=''></div>";}
    if($type == 22)
    {return "<div class='maticone22' title=''></div>";}
    if($type == 23)
    {return "<div class='maticone23' title=''></div>";}
    if($type == 31)
    {return "<div class='maticone31' title=''></div>";}
    if($type == 32)
    {return "<div class='maticone32' title=''></div>";}
    if($type == 33)
    {return "<div class='maticone33' title=''></div>";}
    if($type == 41)
    {return "<div class='maticone41' title=''></div>";}
    if($type == 42)
    {return "<div class='maticone42' title=''></div>";}
    if($type == 43)
    {return "<div class='maticone43' title=''></div>";}
    if($type == 51)
    {return "<div class='maticone51' title=''></div>";}
    if($type == 52)
    {return "<div class='maticone52' title=''></div>";}
    if($type == 53)
    {return "<div class='maticone53' title=''></div>";}
    if($type == 61)
    {return "<div class='maticone61' title=''></div>";}
    if($type == 62)
    {return "<div class='maticone62' title=''></div>";}
    if($type == 63)
    {return "<div class='maticone63' title=''></div>";}
    if($type == 71)
    {return "<div class='maticone71' title=''></div>";}
    if($type == 72)
    {return "<div class='maticone72' title=''></div>";}
    if($type == 73)
    {return "<div class='maticone73' title=''></div>";}
        

}




///////////////////////////////////////////////////////////////////////////////// 
//     _____  _                                    _   _                 
//    |  __ \| |                                  | | (_)                
//    | |__) | | __ _ _   _  ___ _ __    __ _  ___| |_ _  ___  _ __  ___ 
//    |  ___/| |/ _` | | | |/ _ \ '__|  / _` |/ __| __| |/ _ \| '_ \/ __|
//    | |    | | (_| | |_| |  __/ |    | (_| | (__| |_| | (_) | | | \__ \
//    |_|    |_|\__,_|\__, |\___|_|     \__,_|\___|\__|_|\___/|_| |_|___/
//                     __/ |                                             
//                    |___/                                              
/////////////////////////////////////////////////////////////////////////////////    


function actSelect($arg1)
{
   
    self::checkAction( 'actSelect' );
    self::checkArgs($arg1);        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    //$this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actButton($arg1)
{
   
    self::checkAction( 'actSelect' );
    self::checkArgs($arg1);        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    //$this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actValidateselectionsoir( $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9 )
{
   
    self::checkAction( 'actSelect' );
    for ($i=1; $i<=9; $i++)
    {
        $arg = 'arg'.$i;
        
        if($$arg !== '0')
        {
            $explode = explode('_', $$arg);
            $$arg = intval($explode[1]);
            
            
        }
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3.'_'.$arg4.'_'.$arg5.'_'.$arg6.'_'.$arg7.'_'.$arg8.'_'.$arg9;
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPending ($pending['player_id'], "SoirLearn4", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card35", "SoirLearn4", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidateselectionsoir2( $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9 )
{
   
    self::checkAction( 'actSelect' );
    for ($i=1; $i<=9; $i++)
    {
        $arg = 'arg'.$i;
        
        if($$arg !== '0')
        {
            $explode = explode('_', $$arg);
            $$arg = intval($explode[1]);
            
            
        }
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3.'_'.$arg4.'_'.$arg5.'_'.$arg6.'_'.$arg7.'_'.$arg8.'_'.$arg9;
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card16", "Controle", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard16", "Controle", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidateselectioncard12( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card12", "Validation", $arg1, $arg2);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard12", "Validation", $arg1, $arg2);
    }

    $this->gamestate->nextState( 'next');
        
    
}

function actValidateswap2autel( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    spellbook::$instance->setGameStateValue('selectautel', 1);

    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card13", "Swap2", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard13", "Swap2", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidateswap2reserve( $arg1, $arg2, $arg3, $arg4)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=4; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection1 = $arg1.'_'.$arg2;
    $selection2 = $arg3.'_'.$arg4;
        
  
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card13", "Swap2Confirm", $selection1, $selection2);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard13", "Swap2Confirm", $selection1, $selection2);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidateswap3autel( $arg1, $arg2, $arg3)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=3; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3;
        
    spellbook::$instance->setGameStateValue('selectautel', 1);

    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card13", "Swap3", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard13", "Swap3", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidateswap3reserve( $arg1, $arg2, $arg3, $arg4, $arg5, $arg6)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=6; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection1 = $arg1.'_'.$arg2.'_'.$arg3;
    $selection2 = $arg4.'_'.$arg5.'_'.$arg6;
        
  
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card13", "Swap3Confirm", $selection1, $selection2);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard13", "Swap3Confirm", $selection1, $selection2);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidatestore2( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card14", "Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard14", "Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidatestore3( $arg1, $arg2, $arg3)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=3; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card14", "Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard14", "Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidatestore4( $arg1, $arg2, $arg3, $arg4)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=4; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3.'_'.$arg4;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card14", "Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard14", "Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
    
    
}

function actValidate22take2( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card22", "Confirm2", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard22", "Confirm2", $selection);
    }

    $this->gamestate->nextState( 'next');
        
    
}

function actValidate22take3( $arg1, $arg2, $arg3)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=3; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card22", "Confirm2", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard22", "Confirm2", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate23discard2( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card23", "Confirm2", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard23", "Confirm2", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate23discard3( $arg1, $arg2, $arg3)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=3; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card23", "Confirm3", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard23", "Confirm3", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate24store2( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card24", "Power4Store2Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard24", "Power4Store2Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate24take2( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card24", "Power5Take2Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard24", "Power5Take2Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate24store3( $arg1, $arg2, $arg3)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=3; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card24", "Power5Store3Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard24", "Power5Store3Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate25selectautel( $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10 )
{
   
    self::checkAction( 'actSelect' );
    for ($i=1; $i<=10; $i++)
    {
        $arg = 'arg'.$i;
        
        if($$arg !== '0')
        {
            $explode = explode('_', $$arg);
            $$arg = intval($explode[1]);
            
            
        }
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3.'_'.$arg4.'_'.$arg5.'_'.$arg6.'_'.$arg7.'_'.$arg8.'_'.$arg9.'_'.$arg10;
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card25", "ConfirmReplace", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard25", "ConfirmReplace", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate25take3( $arg1, $arg2, $arg3)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=3; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card25", "Take3Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard25", "Take3Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate25take2( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card25", "Take2Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard25", "Take2Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate32take2( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
        $this->addPendingTarget ($pending['player_id'], "Card32", "Take2Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
        $this->addPendingTarget ($pending['player_id'], "Clonecard32", "Take2Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate33store2( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card33", "AltarStore2Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard33", "AltarStore2Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate33store3( $arg1, $arg2, $arg3)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=3; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card33", "AltarStore3Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard33", "AltarStore3Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidatebonuslearn( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPending ($pending['player_id'], "ConfirmBonusLearn", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card35", "ConfirmBonusLearn", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate16bonuslearn( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card16", "ConfirmBonusLearn", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard16", "ConfirmBonusLearn", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate37store3( $arg1, $arg2, $arg3)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=3; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2.'_'.$arg3;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card37", "Power3Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard37", "Power3Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}

function actValidate37store2( $arg1, $arg2)
{
   
    self::checkAction( 'actSelect' );

    for ($i=1; $i<=2; $i++)
    {
        $arg = 'arg'.$i;
              
        $explode = explode('_', $$arg);
        $$arg = intval($explode[1]);
            
            
        
    }
    $selection = $arg1.'_'.$arg2;
        
    
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    self::DbQuery("delete from `pending` where `id`=".$pending['id']);
    if(spellbook::$instance->getGameStateValue('idclone') == 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Card37", "Power3Confirm", $selection);
    }
    if(spellbook::$instance->getGameStateValue('idclone') != 0)
    {
    $this->addPendingTarget ($pending['player_id'], "Clonecard37", "Power3Confirm", $selection);
    }
    $this->gamestate->nextState( 'next');
        
    
}



///////////////////////////////////////////////////////////////////////////////// 
//     _____                             _        _                                                    _       
//    / ____|                           | |      | |                                                  | |      
//    | |  __  __ _ _ __ ___   ___   ___| |_ __ _| |_ ___    __ _ _ __ __ _ _   _ _ __ ___   ___ _ __ | |_ ___ 
//    | | |_ |/ _` | '_ ` _ \ / _ \ / __| __/ _` | __/ _ \  / _` | '__/ _` | | | | '_ ` _ \ / _ \ '_ \| __/ __|
//    | |__| | (_| | | | | | |  __/ \__ \ || (_| | ||  __/ | (_| | | | (_| | |_| | | | | | |  __/ | | | |_\__ \
//     \_____|\__,_|_| |_| |_|\___| |___/\__\__,_|\__\___|  \__,_|_|  \__, |\__,_|_| |_| |_|\___|_| |_|\__|___/
//                                                                    __/ |                                   
//                                                                   |___/                                    
///////////////////////////////////////////////////////////////////////////////// 

function argPlayerTurn()
{
    $pending =  self::getObjectFromDB( "SELECT* FROM `pending` order by `id` desc limit 1");
    $arg = $this->callPending($pending, false);
    
    return $arg;
}

///////////////////////////////////////////////////////////////////////////////// 
//      _____                            _        _                    _   _                 
//     / ____|                          | |      | |                  | | (_)                
//    | |  __  __ _ _ __ ___   ___   ___| |_ __ _| |_ ___    __ _  ___| |_ _  ___  _ __  ___ 
//    | | |_ |/ _` | '_ ` _ \ / _ \ / __| __/ _` | __/ _ \  / _` |/ __| __| |/ _ \| '_ \/ __|
//    | |__| | (_| | | | | | |  __/ \__ \ || (_| | ||  __/ | (_| | (__| |_| | (_) | | | \__ \
//     \_____|\__,_|_| |_| |_|\___| |___/\__\__,_|\__\___|  \__,_|\___|\__|_|\___/|_| |_|___/
//                                                                                       
/////////////////////////////////////////////////////////////////////////////////                                                                                       

 
function callPending($pending, $execute, $arg1 = null, $arg2 = null)
{
    // ici on va aller voir si une class du nom 'target' existe et lancer la fonction demandée (utilisé pour lancer une fonction dans un card.php)
    if(class_exists($pending['function'])){
        $obj = new $pending['function']();
        $obj->player_id = $this->getActivePlayerId();
        if($pending['player_id'] != null)
        {
            $obj->player_id = $pending['player_id'];
        }
        $obj->player = new Pending($obj->player_id);
        
        $method = "";
        if($pending['target'] != null)
        {
            $method = $pending['target'];
        }
        if(!$execute)
        {
            $name = "arg".$method;
        }
        else
        {
            $name = $method;
        }
        $ret = $obj->$name($pending['arg'], $pending['arg2'], $arg1, $arg2);
    }
    /// sinon on va chercher la fonction en question dans le pending.php
    else
    {
        $obj = $this;
        if($pending['player_id'] != null)
        {
            $obj = new Pending($pending['player_id']);
        }
        
        $fname ="";
        if(!$execute)
        {
            $fname .= "arg";
        }
        $fname .= $pending['function'];
        
        $ret = null;
        if(method_exists($obj, $fname))
        {
            $ret = $obj->$fname($pending['arg'], $pending['arg2'], $arg1, $arg2);
        }
    }
    return $ret;
}


function stPending() {
   
   $pending =  self::getObjectFromDB( "SELECT * FROM `pending` order by `id` desc limit 1");
   if($pending == null)
   {
        // ici la partie prend fin si jamais la tablea `pending` est completement vide
        $this->gamestate->nextState( 'end' ); 
   }
   else
   {
       $args = $this->callPending($pending, false);
              
       if($args == null || (count($args['selectable']) == 0 && count($args['buttons']) == 0))
       {
           // ici si je n'ai aucun selectable, ni bouton, la fonction arg est sauté et on passe directement à la fonction associée
           $this->callPending($pending, true);
           self::DbQuery("delete from `pending` where `id`=".$pending['id']);
           $this->gamestate->nextState( 'same' );  
       }
       /*else if(count($args['selectable']) + count($args['buttons']) == 1)  // je supprime cette partie qui permet de faire une selection automatique si jamais il n'y qu'une seule possibilité possible
       {
           //AUTO PLAY IF ONLY ONE CHOICE
           foreach($args['selectable'] as $arg1 => $argnul)
           {
               $this->callPending($pending, true, $arg1);
           }
           foreach($args['buttons'] as $arg1 => $argnul)
           {
               $this->callPending($pending, true, $arg1);
           }
           self::DbQuery("delete from `pending` where `id`=".$pending['id']);
           $this->gamestate->nextState( 'same' );  
       }*/
       else
       {
          //// pour donner la main au joueur de la pile
           $this->gamestate->changeActivePlayer( $pending['player_id']);
                      
           $this->gamestate->nextState( 'player' ); 
       }            
   }
   
}


/////////////////////////////////////////////////////////////////////////////////
//    ______               _     _      
//   |___  /              | |   (_)     
//      / / ___  _ __ ___ | |__  _  ___ 
//     / / / _ \| '_ ` _ \| '_ \| |/ _ \
//    / /_| (_) | | | | | | |_) | |  __/
//   /_____\___/|_| |_| |_|_.__/|_|\___|
//                                   
/////////////////////////////////////////////////////////////////////////////////                                   


    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $player_id = $this->getActivePlayerId();
    	            self::DbQuery("delete from `pending` where `player_id` = {$player_id}");  //// ici on supprime toutes les lignes du joueur zombie de la pile pending... il ne pourra plus revenir en tant que joueur
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new VisibleSystemException( "Zombie mode not supported at this game state: ".$statename );
    }
   
///////////////////////////////////////////////////////////////////////////////// 
//     _____  ____                                    _      
//    |  __ \|  _ \                                  | |     
//    | |  | | |_) |  _   _ _ __   __ _ _ __ __ _  __| | ___ 
//    | |  | |  _ <  | | | | '_ \ / _` | '__/ _` |/ _` |/ _ \
//    | |__| | |_) | | |_| | |_) | (_| | | | (_| | (_| |  __/
//    |_____/|____/   \__,_| .__/ \__, |_|  \__,_|\__,_|\___|
//                         | |     __/ |                     
//                         |_|    |___/                      
/////////////////////////////////////////////////////////////////////////////////    

    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
