<?php 

class Card22 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        
        $reserve = 'materiareserve_'.$this->player_id;
        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 2 AND `typerune` !=0")); //////ATTENTION set_color

         //$ret["selected"][] = $parg1;
         $explode = explode("_", $parg1);
         $card = intval($explode[1]);
         $joueur = intval($explode[2]);

        $counttake = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true ));
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));
        

        if (($level >= 3)&&($counttake>=1)&&($countreserve <=7))
        {
            
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
        }

        if (($level >= 4)&&($counttake>=2)&&($countreserve <=7))
        {
             //$ret['buttons'][] = 'level4';
             $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
        }

        if (($level >= 5)&&($counttake>=3)&&($countreserve <=6))
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
            spellbook::$instance->addPendingTarget($this->player_id, "Card22", "Step2", 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card22", "Step2", 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card22", "Step2", 5);
        }
            }

            else
            {
        $explode = explode("_", $varg1);
        $level = intval($explode[3]);

        if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card22", "Step2", 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card22", "Step2", 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card22", "Step2", 5);
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

        
        

        $reserve = 'materiareserve_'.$this->player_id;
        
        if ($parg1 == "3")
        {
            $ret["selected"][] = 'materiacard_2_'.$this->player_id.'_1';
            $ret['titleyou'] = clienttranslate('${you} must select 1 Materia');
            $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );
            foreach ($selectable as $materia)
            {
                $ret["selectable"][] = 'materia_'.$materia;
            }

        } 
        
        if ($parg1 == "4")
        {
            $ret["selected"][] = 'materiacard_2_'.$this->player_id.'_2';
            $ret['titleyou'] = clienttranslate('${you} must select 2 Materia');
            $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );
            foreach ($selectable as $materia)
            {
                $ret["selectablemulti"][] = 'materia_'.$materia;
                
            }

            $ret['buttons'][] = 'validate22take2';

        }  
        
        if ($parg1 == "5")
        {
            $ret["selected"][] = 'materiacard_2_'.$this->player_id.'_3';
            $ret['titleyou'] = clienttranslate('${you} must select 3 Materia');
            $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );
            foreach ($selectable as $materia)
            {
                $ret["selectablemulti"][] = 'materia_'.$materia;
                
            }
            $ret['buttons'][] = 'validate22take3';

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
            spellbook::$instance->addPendingTarget($this->player_id, "Card22", "Confirm1", $varg1); 
        }

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
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take and Draw Materia)');

        $ret["selected3"][] = $parg1;
        

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }
    
    public function Confirm1($parg1, $parg2, $varg1, $varg2)
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
            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacement);
            $emplacementlibre = array_slice($diff, 0, 2);
            $emplacementlibre1 = $emplacementlibre[0];
            $emplacementlibre2 = $emplacementlibre[1];

            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);
            spellbook::$instance->materia->moveCard( $idmateria, $location, $emplacementlibre1 );

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg1,
                'parent' => $location.'_'.$emplacementlibre1,
                'player_name' => $this->player_name,
                )
                );

            spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacementlibre2);
            $idmateria2 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacementlibre2}");
            $colormateria = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacementlibre2}");
            $runemateria = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$emplacementlibre2}");
            spellbook::$instance->notifyAllPlayers('draw','', array(
                'id' => $idmateria2,
                'color' => $colormateria,
                'rune' => $runemateria,
                'location' =>  $location,
                'emplacement' => $emplacementlibre2,
                'player_name' => $this->player_name,
                )
                );

            $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log1 = ($col1*10)+$signe1;
            $col2= intval($colormateria);
            $signe2= intval($runemateria);
            $log2 = ($col2*10)+$signe2;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "SHARING", takes ${log1} and draws ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                                
                )
                );

            
    
            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->Permanent36($idmateria);
            spellbook::$instance->addPendingTarget($this->player_id, "Card22", "DrawOther"); 
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
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');

        $explode = explode("_", $parg1);
        foreach ($explode as $id)
        {
            $ret["selected3"][] = 'materia_'.$id;
        }
                 

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';  */     
        return $ret;
     }
    
    public function Confirm2($parg1, $parg2, $varg1, $varg2)
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
            $nombremateria = count($explode);

            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacementmateria = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacementmateria);

            
            if ($nombremateria == 2)
            {
                $emplacementlibre = array_slice($diff, 0, 2);
                for ($i=1; $i<=2; $i++)
                {
                    spellbook::$instance->materia->moveCard( $explode[$i-1], $location, $emplacementlibre[$i-1]);
                    spellbook::$instance->notifyAllPlayers('move','', array(
                        'mobile' =>  'materia_'.$explode[$i-1],
                        'parent' => $location.'_'.$emplacementlibre[$i-1],
                        'player_name' => $this->player_name,
                        )
                        );

                }

                $id1 = intval($explode[0]);
                $id2 = intval($explode[1]);
                $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id1}"));
                $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id1}"));
                $log1 = ($col1*10)+$signe1;
                $col2= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id2}"));
                $signe2= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id2}"));
                $log2 = ($col2*10)+$signe2;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "SHARING" and takes ${log1} ${log2}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                                    
                    )
                    );
    

            }

            if ($nombremateria == 3)
            {
                $emplacementlibre = array_slice($diff, 0, 3);
                for ($i=1; $i<=3; $i++)
                {
                    spellbook::$instance->materia->moveCard( $explode[$i-1], $location, $emplacementlibre[$i-1]);
                    spellbook::$instance->notifyAllPlayers('move','', array(
                        'mobile' =>  'materia_'.$explode[$i-1],
                        'parent' => $location.'_'.$emplacementlibre[$i-1],
                        'player_name' => $this->player_name,
                        )
                        );

                }

                $id1 = intval($explode[0]);
                $id2 = intval($explode[1]);
                $id3 = intval($explode[2]);
                $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id1}"));
                $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id1}"));
                $log1 = ($col1*10)+$signe1;
                $col2= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id2}"));
                $signe2= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id2}"));
                $log2 = ($col2*10)+$signe2;
                $col3= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id3}"));
                $signe3= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id3}"));
                $log3 = ($col3*10)+$signe3;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "SHARING" and takes ${log1} ${log2} ${log3}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                                    
                    )
                    );

            }

            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->Permanent36($parg1);
            spellbook::$instance->addPendingTarget($this->player_id, "Card22", "DrawOther"); 
        //}

        
    }

    public function argDrawOther($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');
       
        return $ret;
     }
    
    public function DrawOther($parg1, $parg2, $varg1, $varg2)
    {
        if (spellbook::$instance->getGameStateValue('solo')==0)
        {
            $otherplayer = self::getObjectListFromDB( "SELECT `player_id` FROM `player` WHERE `player_id` != {$this->player_id}", true );

            foreach ($otherplayer as $id)
            {
                $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                $location = 'materiareserve_'.$id;
                $emplacementmateria = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
                $nbre = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true ));
                $diff = array_diff($tableau1, $emplacementmateria);

                if($nbre <=8)
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
                        'player_name' => $id,
                        )
                        );
                
                        
                $col= intval($colormateria);
                $signe= intval($runemateria);
                $log = ($col*10)+$signe;


                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} draws ${log1}'), array(
                    'player_name' => self::getUniqueValueFromDB("SELECT `player_name` FROM `player` WHERE `player_id` = {$id}"),
                    'log1' => spellbook::$instance->getLogsType($log),
                    
                                    
                    )
                    );
                
                        

                }


            }
        }

        if (spellbook::$instance->getGameStateValue('solo')==1)
        {
            $nbreopp = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiareserveopponent'", true ));
            $emplacement = $nbreopp + 1;
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiareserveopponent', $emplacement);
                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = {$emplacement}");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = {$emplacement}");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  'materiareserveopponent',
                    'emplacement' => $emplacement,
                    )
                    );

            spellbook::$instance->CalculPv();
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
        

    }



}