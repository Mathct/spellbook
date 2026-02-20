<?php 

class Card11 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        

        $reserve = 'materiareserve_'.$this->player_id;

        $nombretriangle = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = 1", true ));
        $nombrecarre = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = 2", true ));
        $nombrerond = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = 3", true ));

        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 1 AND `typerune` !=0"));

        //$ret["selected"][] = $parg1;
        $explode = explode("_", $parg1);
        $card = intval($explode[1]);
        $joueur = intval($explode[2]);
              

        if (($level >= 3)&&($nombrerond>=1))
        {
            //$ret["selected"][] = 'materiacard_1_'.$this->player_id.'_1';
            $id1 = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = 3", true );
            foreach($id1 as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }

        if (($level >= 4)&&($nombretriangle>=1))
        {
            //$ret["selected"][] = 'materiacard_1_'.$this->player_id.'_2';
            $id2 = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = 1", true );
            foreach($id2 as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }

        if (($level == 5)&&($nombrecarre>=1))
        {
            //$ret["selected"][] = 'materiacard_1_'.$this->player_id.'_3';
            $id3 = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = 2", true );
            foreach($id3 as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }

        

        if ($ret["selectable"] != NULL)
        {
            $ret['titleyou'] = clienttranslate('${you} must select the Materia to discard');
        }

        if ($ret["selectable"] == NULL)
        {
            $ret['titleyou'] = clienttranslate('${you} cannot trigger this spell');
        }




        $ret['buttons'][]='cancel';


        
        
        
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

            spellbook::$instance->addPendingTarget($this->player_id, "Card11", "Confirm", $varg1);

        }
        


    }

    public function argStep2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select the Materia to discard');
        
        
        $reserve = 'materiareserve_'.$this->player_id;

        

        if ($parg2 == "3")
        {
            $ret["selected"][] = 'materiacard_1_'.$this->player_id.'_1';
            $id = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = 3", true );
            foreach($id as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }

        if ($parg2 == "4")
        {
            $ret["selected"][] = 'materiacard_1_'.$this->player_id.'_2';
            $id = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = 1", true );
            foreach($id as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }

        if ($parg2 == "5")
        {
            $ret["selected"][] = 'materiacard_1_'.$this->player_id.'_3';
            $id = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = 2", true );
            foreach($id as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }


        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Step2($parg1, $parg2, $varg1, $varg2)
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
            spellbook::$instance->addPendingTarget($this->player_id, "Card11", "Confirm", $varg1);
        }


    }

    public function argConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected3"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Discard and Draw Materia)');
        
        $ret["selected3"][] = $parg1;

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';  */    
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

        if($varg1 == "confirm")
        {*/
            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);

            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $logdiscard = ($col*10)+$signe;


            spellbook::$instance->materia->moveCard( $idmateria, 'discard');
            spellbook::$instance->notifyAllPlayers('discard','', array(
                'mobile' =>  $idmateria,
                )
                );
            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );
            
            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true ));
            $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacement);
            $reste = 9 - $countreserve;
            if($reste == 1)
            {
                $emplacementlibre = array_slice($diff, 0, 1);
                $premier = $emplacementlibre[0];
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $premier);

                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");

                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  $location,
                    'emplacement' => $premier,
                    'player_name' => $this->player_name,
                    )
                    );

                $col1= intval($colormateria1);
                $signe1= intval($runemateria1);
                $log1 = ($col1*10)+$signe1;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "SACRIFICE", discard ${discard} and draws ${log1}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'discard' => spellbook::$instance->getLogsType($logdiscard),
                                    
                    )
                    );
                

            }

            if($reste == 2)
            {
                $emplacementlibre = array_slice($diff, 0, 2);
                $premier = $emplacementlibre[0];
                $deuxieme = $emplacementlibre[1];
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $premier);
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $deuxieme);

                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  $location,
                    'emplacement' => $premier,
                    'player_name' => $this->player_name,
                    )
                    );

                spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 500] );

                $idmateria2 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$deuxieme}");
                $colormateria2 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$deuxieme}");
                $runemateria2 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$deuxieme}");

                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria2,
                    'color' => $colormateria2,
                    'rune' => $runemateria2,
                    'location' =>  $location,
                    'emplacement' => $deuxieme,
                    'player_name' => $this->player_name,
                    )
                    );

                $col1= intval($colormateria1);
                $signe1= intval($runemateria1);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($colormateria2);
                $signe2= intval($runemateria2);
                $log2 = ($col2*10)+$signe2;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "SACRIFICE", discard ${discard} and draws ${log1} ${log2}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'discard' => spellbook::$instance->getLogsType($logdiscard),
                                    
                    )
                    );
                
            }

            if($reste == 3)
            {
                $emplacementlibre = array_slice($diff, 0, 3);
                $premier = $emplacementlibre[0];
                $deuxieme = $emplacementlibre[1];
                $troisieme = $emplacementlibre[2];
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $premier);
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $deuxieme);
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $troisieme);

                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  $location,
                    'emplacement' => $premier,
                    'player_name' => $this->player_name,
                    )
                    );

                spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 500] );

                $idmateria2 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$deuxieme}");
                $colormateria2 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$deuxieme}");
                $runemateria2 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$deuxieme}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria2,
                    'color' => $colormateria2,
                    'rune' => $runemateria2,
                    'location' =>  $location,
                    'emplacement' => $deuxieme,
                    'player_name' => $this->player_name,
                    )
                    );

                spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 500] );

                $idmateria3 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$troisieme}");
                $colormateria3 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$troisieme}");
                $runemateria3 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$troisieme}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria3,
                    'color' => $colormateria3,
                    'rune' => $runemateria3,
                    'location' =>  $location,
                    'emplacement' => $troisieme,
                    'player_name' => $this->player_name,
                    )
                    );

                $col1= intval($colormateria1);
                $signe1= intval($runemateria1);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($colormateria2);
                $signe2= intval($runemateria2);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($colormateria3);
                $signe3= intval($runemateria3);
                $log3 = ($col3*10)+$signe3;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "SACRIFICE", discard ${discard} and draws ${log1} ${log2} ${log3}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                    'discard' => spellbook::$instance->getLogsType($logdiscard),
                                    
                    )
                    );
    
                
            }

            if($reste >= 4)
            {
                $emplacementlibre = array_slice($diff, 0, 4);
                $premier = $emplacementlibre[0];
                $deuxieme = $emplacementlibre[1];
                $troisieme = $emplacementlibre[2];
                $quatrieme = $emplacementlibre[3];
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $premier);
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $deuxieme);
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $troisieme);
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $quatrieme);

                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premier}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  $location,
                    'emplacement' => $premier,
                    'player_name' => $this->player_name,
                    )
                    );

                spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 500] );

                $idmateria2 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$deuxieme}");
                $colormateria2 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$deuxieme}");
                $runemateria2 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$deuxieme}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria2,
                    'color' => $colormateria2,
                    'rune' => $runemateria2,
                    'location' =>  $location,
                    'emplacement' => $deuxieme,
                    'player_name' => $this->player_name,
                    )
                    );

                spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 500] );

                $idmateria3 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$troisieme}");
                $colormateria3 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$troisieme}");
                $runemateria3 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$troisieme}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria3,
                    'color' => $colormateria3,
                    'rune' => $runemateria3,
                    'location' =>  $location,
                    'emplacement' => $troisieme,
                    'player_name' => $this->player_name,
                    )
                    );

                spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 500] );

                $idmateria4 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$quatrieme}");
                $colormateria4 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$quatrieme}");
                $runemateria4 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$quatrieme}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria4,
                    'color' => $colormateria4,
                    'rune' => $runemateria4,
                    'location' =>  $location,
                    'emplacement' => $quatrieme,
                    'player_name' => $this->player_name,
                    )
                    );

                $col1= intval($colormateria1);
                $signe1= intval($runemateria1);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($colormateria2);
                $signe2= intval($runemateria2);
                $log2 = ($col2*10)+$signe2;
                $col3= intval($colormateria3);
                $signe3= intval($runemateria3);
                $log3 = ($col3*10)+$signe3;
                $col4= intval($colormateria4);
                $signe4= intval($runemateria4);
                $log4 = ($col4*10)+$signe4;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "SACRIFICE", discard ${discard} and draws ${log1} ${log2} ${log3} ${log4}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                    'log4' => spellbook::$instance->getLogsType($log4),
                    'discard' => spellbook::$instance->getLogsType($logdiscard),
                                    
                    )
                    );
                
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