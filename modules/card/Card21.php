<?php 

class Card21 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        

        $reserve = 'materiareserve_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));

        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 1 AND `typerune` !=0")); //////ATTENTION set_color

        //$ret["selected"][] = $parg1;
        $explode = explode("_", $parg1);
        $card = intval($explode[1]);
        $joueur = intval($explode[2]);
        

        if (($level >= 3)&&($countreserve<4))
        {
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
        }

        if (($level >= 4)&&($countreserve<5))
        {
            //$ret['buttons'][] = 'level4';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
        }

        if (($level == 5)&&($countreserve<6))
        {
            //$ret['buttons'][] = 'level5';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_3';
        }

        $countselectable = count($ret["selectable"]);
        
        if ($countselectable >= 2)
        {
            $ret['titleyou'] = clienttranslate('${you} must select the level to trigger');
            $ret['buttons'][]='cancel'; 
        }

        if ($ret["selectable"] == NULL)
        {
            $ret['titleyou'] = clienttranslate('${you} cannot trigger this spell');
            $ret['buttons'][]='cancel'; 
        }

        if ($countselectable == 1)
        {
            $autoexplode = explode("_", $ret["selectable"][0]);
            $auto = intval($autoexplode[3]);
            spellbook::$instance->setGameStateValue('variable1', $auto);
            $ret["selectable"] = array();
        }



               
        return $ret;
     }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            if((spellbook::$instance->getGameStateValue('matinperm')==1)||(spellbook::$instance->getGameStateValue('matinos')==1))
            {
                spellbook::$instance->addPending($this->player_id, "MatinSupp");
            }
            else
            {
                spellbook::$instance->addPending($this->player_id, "Matin");
            }
        }

        else
        {

            if($varg1 == NULL)
            {
                $level = spellbook::$instance->getGameStateValue('variable1');
                if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card21", "Confirm", 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card21", "Confirm", 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card21", "Confirm", 5);
        }
            }
            else
            {
        $explode = explode("_", $varg1);
        $level = intval($explode[3]);

        if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card21", "Confirm", 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card21", "Confirm", 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card21", "Confirm", 5);
        }
    }
    }

    }

    public function argConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Draw Materia)');

        if ($parg1 == "3")
        {
            $ret["selected"][] = 'materiacard_1_'.$this->player_id.'_1';
        }
        if ($parg1 == "4")
        {
            $ret["selected"][] = 'materiacard_1_'.$this->player_id.'_2';
        }
        if ($parg1 == "5")
        {
            $ret["selected"][] = 'materiacard_1_'.$this->player_id.'_3';
        }
        

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/
           
        return $ret;
     }
    
    public function Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            if((spellbook::$instance->getGameStateValue('matinperm')==1)||(spellbook::$instance->getGameStateValue('matinos')==1))
            {
                spellbook::$instance->addPending($this->player_id, "MatinSupp");
            }
            else
            {
                spellbook::$instance->addPending($this->player_id, "Matin");
            }
        }

        if ($varg1 == "confirm")
        {*/
            
            $nbreajout = 0;         
            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true ));
            $emplacementmateria = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacementmateria);

            $tableaucolor = array();
            $tableaurune = array();

        if ($parg1 == "3")
        {
            $nbreajout = 4 - $countreserve;
            $emplacementlibre = array_slice($diff, 0, $nbreajout);
            for ($i=0 ; $i<=($nbreajout-1); $i++)
            {
                $emplacement = $emplacementlibre[$i];
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

                $tableaucolor[] = intval($colormateria);
                $tableaurune[] = intval($runemateria);
            }

            if ($nbreajout == 1)
            {
                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                                    
                    )
                    );


            }
            if ($nbreajout == 2)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                                    
                    )
                    );
                
            }
            if ($nbreajout == 3)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($tableaucolor[2]);
                $signe3= intval($tableaurune[2]);
                $log3 = ($col3*10)+$signe3;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2} ${log3}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                                    
                    )
                    );
                
            }
            if ($nbreajout == 4)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($tableaucolor[2]);
                $signe3= intval($tableaurune[2]);
                $log3 = ($col3*10)+$signe3;
                $col4= intval($tableaucolor[3]);
                $signe4= intval($tableaurune[3]);
                $log4 = ($col4*10)+$signe4;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2} ${log3} ${log4}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                    'log4' => spellbook::$instance->getLogsType($log4),
                                    
                    )
                    );
                
            }
        }

        if ($parg1 == "4")
        {
            $nbreajout = 5 - $countreserve;
            $emplacementlibre = array_slice($diff, 0, $nbreajout);
            for ($i=0 ; $i<=($nbreajout-1); $i++)
            {
                $emplacement = $emplacementlibre[$i];
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

                $tableaucolor[] = intval($colormateria);
                $tableaurune[] = intval($runemateria);
            }

            if ($nbreajout == 1)
            {
                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                                    
                    )
                    );


            }
            if ($nbreajout == 2)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                                    
                    )
                    );
                
            }
            if ($nbreajout == 3)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($tableaucolor[2]);
                $signe3= intval($tableaurune[2]);
                $log3 = ($col3*10)+$signe3;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2} ${log3}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                                    
                    )
                    );
                
            }
            if ($nbreajout == 4)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($tableaucolor[2]);
                $signe3= intval($tableaurune[2]);
                $log3 = ($col3*10)+$signe3;
                $col4= intval($tableaucolor[3]);
                $signe4= intval($tableaurune[3]);
                $log4 = ($col4*10)+$signe4;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2} ${log3} ${log4}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                    'log4' => spellbook::$instance->getLogsType($log4),
                                    
                    )
                    );
                
            }

            if ($nbreajout == 5)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($tableaucolor[2]);
                $signe3= intval($tableaurune[2]);
                $log3 = ($col3*10)+$signe3;
                $col4= intval($tableaucolor[3]);
                $signe4= intval($tableaurune[3]);
                $log4 = ($col4*10)+$signe4;
                $col5= intval($tableaucolor[4]);
                $signe5= intval($tableaurune[4]);
                $log5 = ($col5*10)+$signe5;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2} ${log3} ${log4} ${log5}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                    'log4' => spellbook::$instance->getLogsType($log4),
                    'log5' => spellbook::$instance->getLogsType($log5),
                                    
                    )
                    );
                
            }

        }

        if ($parg1 == "5")
        {
            $nbreajout = 6 - $countreserve;
            $emplacementlibre = array_slice($diff, 0, $nbreajout);
            for ($i=0 ; $i<=($nbreajout-1); $i++)
            {
                $emplacement = $emplacementlibre[$i];
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

                    $tableaucolor[] = intval($colormateria);
                    $tableaurune[] = intval($runemateria);
            }

            if ($nbreajout == 1)
            {
                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                                    
                    )
                    );


            }
            if ($nbreajout == 2)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                                    
                    )
                    );
                
            }
            if ($nbreajout == 3)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($tableaucolor[2]);
                $signe3= intval($tableaurune[2]);
                $log3 = ($col3*10)+$signe3;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2} ${log3}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                                    
                    )
                    );
                
            }
            if ($nbreajout == 4)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($tableaucolor[2]);
                $signe3= intval($tableaurune[2]);
                $log3 = ($col3*10)+$signe3;
                $col4= intval($tableaucolor[3]);
                $signe4= intval($tableaurune[3]);
                $log4 = ($col4*10)+$signe4;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2} ${log3} ${log4}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                    'log4' => spellbook::$instance->getLogsType($log4),
                                    
                    )
                    );
                
            }

            if ($nbreajout == 5)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($tableaucolor[2]);
                $signe3= intval($tableaurune[2]);
                $log3 = ($col3*10)+$signe3;
                $col4= intval($tableaucolor[3]);
                $signe4= intval($tableaurune[3]);
                $log4 = ($col4*10)+$signe4;
                $col5= intval($tableaucolor[4]);
                $signe5= intval($tableaurune[4]);
                $log5 = ($col5*10)+$signe5;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2} ${log3} ${log4} ${log5}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                    'log4' => spellbook::$instance->getLogsType($log4),
                    'log5' => spellbook::$instance->getLogsType($log5),
                                    
                    )
                    );
                
            }

            if ($nbreajout == 6)
            {

                $col1= intval($tableaucolor[0]);
                $signe1= intval($tableaurune[0]);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($tableaucolor[1]);
                $signe2= intval($tableaurune[1]);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($tableaucolor[2]);
                $signe3= intval($tableaurune[2]);
                $log3 = ($col3*10)+$signe3;
                $col4= intval($tableaucolor[3]);
                $signe4= intval($tableaurune[3]);
                $log4 = ($col4*10)+$signe4;
                $col5= intval($tableaucolor[4]);
                $signe5= intval($tableaurune[4]);
                $log5 = ($col5*10)+$signe5;
                $col6= intval($tableaucolor[5]);
                $signe6= intval($tableaurune[5]);
                $log6 = ($col6*10)+$signe6;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ERUPTION" and draws ${log1} ${log2} ${log3} ${log4} ${log5} ${log6}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                    'log4' => spellbook::$instance->getLogsType($log4),
                    'log5' => spellbook::$instance->getLogsType($log5),
                    'log6' => spellbook::$instance->getLogsType($log6),
                                    
                    )
                    );
                
            }

        }

        

        
            if(($this->player_p26 == 0)&&(spellbook::$instance->getGameStateValue('matinperm')==0)&&(spellbook::$instance->getGameStateValue('matinos')==0))
            {
                spellbook::$instance->addPending($this->player_id, "Midi");
            }
            if($this->player_p26 == 1)
            {
                if(spellbook::$instance->getGameStateValue('matinperm')==0)
                {
                    spellbook::$instance->setGameStateValue('matinperm', 1);
                    spellbook::$instance->addPending($this->player_id, "MatinSupp");
                }
                else
                {
                    spellbook::$instance->setGameStateValue('matinperm', 0);
                    spellbook::$instance->addPending($this->player_id, "Midi");
                }

            }
            
            if(spellbook::$instance->getGameStateValue('matinos')==1)
            {
                spellbook::$instance->setGameStateValue('matinos', 0);
                spellbook::$instance->addPending($this->player_id, "Autel");
            }



        //}

        
    }



}