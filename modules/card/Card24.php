<?php 

class Card24 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        

        $reserve = 'materiareserve_'.$this->player_id;
        $familier = 'materiafamilier_'.$this->player_id;
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0"));

        $nombrereserve = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}'", true ));
        $nombrefamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$familier}'", true ));
        $nombrerunereserve = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = {$rune}", true ));
        $nombreruneautel = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = {$rune}", true ));

        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0")); //////ATTENTION set_color

        //$ret["selected"][] = $parg1;
        $explode = explode("_", $parg1);
        $card = intval($explode[1]);
        $joueur = intval($explode[2]);
        

        if (($level >= 3)&&($nombrerunereserve>=1)&&($nombrefamilier<=13))
        {
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
        }

        if (($level >= 4)&&((($nombrerunereserve>=2)&&($nombrefamilier<=12))||(($nombrereserve<=8)&&($nombreruneautel>=1))))
        {
            //$ret['buttons'][] = 'level4';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
        }

        if (($level >= 5)&&((($nombrerunereserve>=3)&&($nombrefamilier<=11))||(($nombrereserve<=7)&&($nombreruneautel>=2))))
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
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        else
        {
            if($varg1 == NULL)
            {
                $level = spellbook::$instance->getGameStateValue('variable1');
                if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power3");
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power4");
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power5");
        }
            }

            else
            {
        $explode = explode("_", $varg1);
        $level = intval($explode[3]);

        if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power3");
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power4");
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power5");
        }
    }
    }


    }



    public function argPower3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 Materia to store');
        

        $ret["selected"][] = 'materiacard_4_'.$this->player_id.'_1';

        $reserve = 'materiareserve_'.$this->player_id;
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0"));

        $ids = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = {$rune}", true );

        foreach($ids as $id)
        {
            $ret["selectable"][] = 'materia_'.$id;
        }


        $ret['buttons'][]='cancel';       
        return $ret;
     }

    
    public function Power3($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power3Confirm", $varg1);
        }

        

    }

    public function argPower3Confirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Store Materia)');
        
        $ret["selected3"][] = $parg1;
        

        /*$ret['buttons'][]='confirm'; 
        $ret['buttons'][]='cancel';   */    
        return $ret;
     }

    
    public function Power3Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        if($varg1 == "confirm")
        {*/
            $log = array();

            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);

            $familier = 'materiafamilier_'.$this->player_id;
            $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
            $nouvelemplacementfamilier = $countfamilier +1;

            spellbook::$instance->materia->moveCard( $idmateria, $familier, $nouvelemplacementfamilier);
        

        spellbook::$instance->notifyAllPlayers('move','', array(
            'mobile' =>  $parg1,
            'parent' => $familier.'_'.$nouvelemplacementfamilier,
            'player_name' => $this->player_name,
            )
            );

            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log[] = ($col*10)+$signe;

        spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "FOCUS" and stores ${log1}'), array(
            'player_name' => $this->player_name,
            'log1' => spellbook::$instance->getLogsType($log[0]),
                            
            )
            );


        spellbook::$instance->CalculPv();
        spellbook::$instance->addPending($this->player_id, "Autel");

        //}

        

    }


    public function argPower4($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 action');

        $ret["selected"][] = 'materiacard_4_'.$this->player_id.'_2';
        
        $reserve = 'materiareserve_'.$this->player_id;
        $familier = 'materiafamilier_'.$this->player_id;
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0"));

        $nombrereserve = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}'", true ));
        $nombrefamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$familier}'", true ));
        $nombrerunereserve = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = {$rune}", true ));
        $nombreruneautel = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = {$rune}", true ));
        
        

        if(($nombrerunereserve>=2)&&($nombrefamilier<=12))
        {
            $ret['buttons'][]='card24store2';   
        }

        if(($nombrereserve<=8)&&($nombreruneautel>=1))
        {
            $ret['buttons'][]='card24take1';   
        }

        $ret['buttons'][]='cancel';       
        return $ret;
     }

    
    public function Power4($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        if($varg1 == "card24store2")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power4Store2");
        }

        if($varg1 == "card24take1")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power4Take1");
        }
    

    }

    public function argPower4Take1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 Materia to take');
        
        
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0"));
       
        $ids = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = {$rune}", true );

        foreach($ids as $id)
        {
            $ret["selectable"][] = 'materia_'.$id;
        }

        $ret['buttons'][]='cancel';       
        return $ret;
     }

    
    public function Power4Take1($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power4Take1Confirm", $varg1);
        }
    

    }

    public function argPower4Take1Confirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');
        
        $ret["selected3"][] = $parg1;
        
        /*$ret['buttons'][]='confirm'; 
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }

    
    public function Power4Take1Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        if($varg1 == "confirm")
        {*/
            $log = array();

            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacementmateria = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacementmateria);

            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);

            $emplacementlibre = array_slice($diff, 0, 1);
            $emplacement = $emplacementlibre[0];

            spellbook::$instance->materia->moveCard( $idmateria, $location, $emplacement );

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg1,
                'parent' => $location.'_'.$emplacement,
                'player_name' => $this->player_name,
                )
                );

            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log[] = ($col*10)+$signe;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "FOCUS" and takes ${log1}'), array(
            'player_name' => $this->player_name,
            'log1' => spellbook::$instance->getLogsType($log[0]),
                            
            )
            );

            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->Permanent36($idmateria);
            spellbook::$instance->addPending($this->player_id, "Autel");
        //}
    

    }

    public function argPower4Store2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 2 Materia to store');
        
        
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0"));
        $location = 'materiareserve_'.$this->player_id;
       
        $ids = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_type_arg` = {$rune}", true );

        foreach($ids as $id)
        {
            $ret["selectablemulti"][] = 'materia_'.$id;
        }

        $ret['buttons'][]='validate24store2'; 

        $ret['buttons'][]='cancel';       
        return $ret;
     }

    
    public function Power4Store2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

       
    }

    public function argPower4Store2Confirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Store Materia)');
        
        $explode = explode("_", $parg1);
        foreach ($explode as $id)
        {
            $ret["selected3"][] = 'materia_'.$id;
        }
        
        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }

    
    public function Power4Store2Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        if($varg1 == "confirm")
        {*/
            $log = array();

            $explode = explode("_", $parg1);
            $familier = 'materiafamilier_'.$this->player_id;
            $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
            for ($i=1; $i <= 2; $i++)
            {
                $nouvelemplacementfamilier = $countfamilier +$i;
                spellbook::$instance->materia->moveCard( $explode[$i-1], $familier, $nouvelemplacementfamilier);
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$explode[$i-1],
                    'parent' => $familier.'_'.$nouvelemplacementfamilier,
                    'player_name' => $this->player_name,
                    )
                    );

            $idmateria = intval($explode[$i-1]);
            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log[] = ($col*10)+$signe;

            }

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "FOCUS" and stores ${log1} ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                'log2' => spellbook::$instance->getLogsType($log[1]),
                                
                )
                );
    
    
            spellbook::$instance->CalculPv();
            spellbook::$instance->addPending($this->player_id, "Autel");
            

        //}
       
    }

    public function argPower5($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 action');

        $ret["selected"][] = 'materiacard_4_'.$this->player_id.'_3';
        
        $reserve = 'materiareserve_'.$this->player_id;
        $familier = 'materiafamilier_'.$this->player_id;
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0"));

        $nombrereserve = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}'", true ));
        $nombrefamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$familier}'", true ));
        $nombrerunereserve = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type_arg` = {$rune}", true ));
        $nombreruneautel = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = {$rune}", true ));
        
        

        if(($nombrerunereserve>=3)&&($nombrefamilier<=11))
        {
            $ret['buttons'][]='card24store3';   
        }

        if(($nombrereserve<=7)&&($nombreruneautel>=2))
        {
            $ret['buttons'][]='card24take2';   
        }

        $ret['buttons'][]='cancel';       
        return $ret;
     }

    
    public function Power5($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        if($varg1 == "card24store3")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power5Store3");
        }

        if($varg1 == "card24take2")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card24", "Power5Take2");
        }
    

    }

    public function argPower5Take2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 2 Materia to take');
        
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0"));
       
        $ids = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = {$rune}", true );

        foreach($ids as $id)
        {
            $ret["selectablemulti"][] = 'materia_'.$id;
        }

        $ret['buttons'][]='validate24take2';  

        $ret['buttons'][]='cancel';       
        return $ret;
         
     }

    
    public function Power5Take2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

           

    }

    public function argPower5Take2Confirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');
        
        $explode = explode("_", $parg1);
        foreach ($explode as $id)
        {
            $ret["selected3"][] = 'materia_'.$id;
        }
        
        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/       
        return $ret;
         
     }

    
    public function Power5Take2Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        if($varg1 == "confirm")
        {*/  
            $log = array();

            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacementmateria = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacementmateria);
            $emplacementlibre = array_slice($diff, 0, 2);

            $explode = explode("_", $parg1);

            for ($i=0; $i<=1; $i++)
            {
                $idmateria = intval($explode[$i]);
                $emplacement = $emplacementlibre[$i];

                spellbook::$instance->materia->moveCard( $idmateria, $location, $emplacement );
                
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$idmateria,
                    'parent' => $location.'_'.$emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

                $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
                $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
                $log[] = ($col*10)+$signe;

            }

            
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "FOCUS" and takes ${log1} ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                'log2' => spellbook::$instance->getLogsType($log[1]),
                                
                )
                );

            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->Permanent36($parg1);
            spellbook::$instance->addPending($this->player_id, "Autel");

        //}

    }

    public function argPower5Store3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 3 Materia to store');
        
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0"));
        $location = 'materiareserve_'.$this->player_id;
       
        $ids = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_type_arg` = {$rune}", true );

        foreach($ids as $id)
        {
            $ret["selectablemulti"][] = 'materia_'.$id;
        }

        $ret['buttons'][]='validate24store3';  

        $ret['buttons'][]='cancel';       
        return $ret;
         
     }

    
    public function Power5Store3($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

           

    }

    public function argPower5Store3Confirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Store Materia)');
        
        $explode = explode("_", $parg1);
        foreach ($explode as $id)
        {
            $ret["selected3"][] = 'materia_'.$id;
        }
        
        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/       
        return $ret;
         
     }

    
    public function Power5Store3Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        if($varg1 == "confirm")
        { */ 
            $log = array();

            $explode = explode("_", $parg1);
            $familier = 'materiafamilier_'.$this->player_id;
            $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
            for ($i=1; $i <= 3; $i++)
            {
                $nouvelemplacementfamilier = $countfamilier +$i;
                spellbook::$instance->materia->moveCard( $explode[$i-1], $familier, $nouvelemplacementfamilier);
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$explode[$i-1],
                    'parent' => $familier.'_'.$nouvelemplacementfamilier,
                    'player_name' => $this->player_name,
                    )
                    );

            $idmateria = intval($explode[$i-1]);
            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log[] = ($col*10)+$signe;

            }

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "FOCUS" and stores ${log1} ${log2} ${log3}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                'log2' => spellbook::$instance->getLogsType($log[1]),
                'log3' => spellbook::$instance->getLogsType($log[2]),
                                
                )
                );
    
    
            spellbook::$instance->CalculPv();
            spellbook::$instance->addPending($this->player_id, "Autel");

        //}

    }







}