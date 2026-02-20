<?php 

class Clonecard23 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        
        $player = spellbook::$instance->getGameStateValue('idclone');
        $reserve = 'materiareserve_'.$this->player_id;
        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$player} AND `set_color` = 3 AND `typerune` !=0")); //////ATTENTION set_color

        //$ret["selected"][] = $parg1;
        $explode = explode("_", $parg1);
        $card = intval($explode[1]);
        $joueur = intval($explode[2]);
        
        
        
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));

        if (spellbook::$instance->getGameStateValue('solo')==0)
        {
        if (($level >= 3)&&($countreserve <=8))
        {
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
        }

        if (($level >= 4)&&($countreserve <=7))
        {
             //$ret['buttons'][] = 'level4';
             $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
        }

        if (($level >= 5)&&($countreserve <=6))
        {
            //$ret['buttons'][] = 'level5';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_3';
        }

        if ($ret["selectable"] != NULL)
        {
            $ret['titleyou'] = clienttranslate('${you} must select the level to trigger');
        }

        if ($ret["selectable"] == NULL)
        {
            $ret['titleyou'] = clienttranslate('${you} cannot trigger this spell');
        }



        $ret['buttons'][]='cancel';  
        }   
        
        if (spellbook::$instance->getGameStateValue('solo')==1)
        {
            if ($countreserve >7)
            {
                $ret['titleyou'] = clienttranslate('${you} cannot trigger this spell');
                $ret['buttons'][]='cancel';

            }
            else
            {
                $ret['titleyou'] = clienttranslate('${you} must select the level to trigger');
            }

        }

        return $ret;
     }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        else
        {
            if($varg1 == NULL)
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step2", 4);
            }
            else
            {
        $explode = explode("_", $varg1);
        $level = intval($explode[3]);

        if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step2", 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step2", 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step2", 5);
        }
            }
        }


    }

    public function argStep2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Draw Materia)');

        if ($parg1 == "3")
        {
            $ret["selected"][] = 'materiacard_3_'.spellbook::$instance->getGameStateValue('idclone').'_1';
        }
        if ($parg1 == "4")
        {
            $ret["selected"][] = 'materiacard_3_'.spellbook::$instance->getGameStateValue('idclone').'_2';
        }
        if ($parg1 == "5")
        {
            $ret["selected"][] = 'materiacard_3_'.spellbook::$instance->getGameStateValue('idclone').'_3';
        }

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel'; */
             
        return $ret;
     }
    
    public function Step2($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        if($varg1 == "confirm")
        {*/

        $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $location = 'materiareserve_'.$this->player_id;
        $emplacementmateria = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
        $diff = array_diff($tableau1, $emplacementmateria);

        $logcolor = array();
        $logsigne = array();

        if ($parg1 == "3")
        {
            $emplacementlibre = array_slice($diff, 0, 1);
            $emplacement = $emplacementlibre[0];

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

                $col1 = intval($colormateria);
                $signe1 = intval($runemateria);
                $log1 = ($col1*10)+$signe1;
    
                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "CURE" (Clone) and draws ${log1}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                                    
                    )
                    );
            
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step3", 3);

        }

        if ($parg1 == "4")
        {
            $emplacementlibre = array_slice($diff, 0, 2);

            for ($i=1; $i<=2; $i++)
            {
                $emplacement = $emplacementlibre[$i-1];

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

                $logcolor[] = intval($colormateria);
                $logsigne[] = intval($runemateria);

            }

            $col1 = intval($logcolor[0]);
            $signe1 = intval($logsigne[0]);
            $log1 = ($col1*10)+$signe1;
            $col2 = intval($logcolor[1]);
            $signe2 = intval($logsigne[1]);
            $log2 = ($col2*10)+$signe2;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "CURE" (Clone) and draws ${log1} ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                                
                )
                );

            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step3", 4);
        }

        if ($parg1 == "5")
        {
            $emplacementlibre = array_slice($diff, 0, 3);
            
            for ($i=1; $i<=3; $i++)
            {
                $emplacement = $emplacementlibre[$i-1];

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

                    $logcolor[] = intval($colormateria);
                    $logsigne[] = intval($runemateria);

            }

            $col1 = intval($logcolor[0]);
            $signe1 = intval($logsigne[0]);
            $log1 = ($col1*10)+$signe1;
            $col2 = intval($logcolor[1]);
            $signe2 = intval($logsigne[1]);
            $log2 = ($col2*10)+$signe2;
            $col3 = intval($logcolor[2]);
            $signe3 = intval($logsigne[2]);
            $log3 = ($col3*10)+$signe3;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "CURE" (Clone) and draws ${log1} ${log2} ${log3}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                'log3' => spellbook::$instance->getLogsType($log3),
                                
                )
                );

            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step3", 5);
        }
    //}

    }


    public function argStep3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        
        $location = 'materiareserve_'.$this->player_id;

        if ($parg1 == "3")
        {
            $ret['titleyou'] = clienttranslate('${you} must select 1 Materia to discard');
            $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true );
            foreach ($selectable as $materia)
            {
                $ret["selectable"][] = 'materia_'.$materia;
            }

        }

        if ($parg1 == "4")
        {
            $ret['titleyou'] = clienttranslate('${you} must select 2 Materia to discard');
            $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true );
            foreach ($selectable as $materia)
            {
                $ret["selectablemulti"][] = 'materia_'.$materia;
            }
            $ret['buttons'][]='validate23discard2'; 

        }

        if ($parg1 == "5")
        {
            $ret['titleyou'] = clienttranslate('${you} must select 3 Materia to discard');
            $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true );
            foreach ($selectable as $materia)
            {
                $ret["selectablemulti"][] = 'materia_'.$materia;
            }
            $ret['buttons'][]='validate23discard3'; 

        }
             
        return $ret;
     }
    
    public function Step3($parg1, $parg2, $varg1, $varg2)
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Confirm1", $varg1);

    }


    public function argConfirm1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Discard Materia)');

        $ret["selected3"][] = $parg1;
        

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }
    
    public function Confirm1($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step3", 3);
        
        }

        if($varg1 == "confirm")
        {*/
            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);
            spellbook::$instance->materia->moveCard( $idmateria, 'discard');
            spellbook::$instance->notifyAllPlayers('discard','', array(
                    'mobile' => $idmateria,
                    )
                    );
                    
            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log = ($col*10)+$signe;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${log}'), array(
                'player_name' => $this->player_name,
                'log' => spellbook::$instance->getLogsType($log),
                                
                )
                );

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

            spellbook::$instance->addPending($this->player_id, "Soir");
            
        //}

        
    }

    public function argConfirm2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Discard Materia)');

        $explode = explode("_", $parg1);
        foreach ($explode as $id)
        {
            $ret["selected3"][] = 'materia_'.$id;
        }
        

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }
    
    public function Confirm2($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step3", 4);
        
        }

        if($varg1 == "confirm")
        {*/
            $log = array();

            $explode = explode("_", $parg1);
            for ($i=0; $i<=1; $i++)
            {
            $idmateria = intval($explode[$i]);
            spellbook::$instance->materia->moveCard( $idmateria, 'discard');
            spellbook::$instance->notifyAllPlayers('discard','', array(
                    'mobile' => $idmateria,
                    )
                    );
            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log[] = ($col*10)+$signe;

            }
                    
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${log1} ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                'log2' => spellbook::$instance->getLogsType($log[1]),
                                
                )
                );

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

            spellbook::$instance->addPending($this->player_id, "Soir");
            
       // }

        
    }

    public function argConfirm3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Discard Materia)');

        $explode = explode("_", $parg1);
        foreach ($explode as $id)
        {
            $ret["selected3"][] = 'materia_'.$id;
        }
        

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/       
        return $ret;
     }
    
    public function Confirm3($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard23", "Step3", 5);
        
        }

        if($varg1 == "confirm")
        {*/
            $log = array();

            $explode = explode("_", $parg1);
            for ($i=0; $i<=2; $i++)
            {
            $idmateria = intval($explode[$i]);
            spellbook::$instance->materia->moveCard( $idmateria, 'discard');
            spellbook::$instance->notifyAllPlayers('discard','', array(
                    'mobile' => $idmateria,
                    )
                    );
                    
            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log[] = ($col*10)+$signe;
        
            }
                    
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${log1} ${log2} ${log3}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                'log2' => spellbook::$instance->getLogsType($log[1]),
                'log3' => spellbook::$instance->getLogsType($log[2]),
                                
                )
                );

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

            spellbook::$instance->addPending($this->player_id, "Soir");
            
        //}

        
    }











}