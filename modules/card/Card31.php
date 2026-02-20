<?php 

class Card31 extends Card
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

        $ret["selected"][] = $parg1;
        
        
        
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));

        if ($countreserve <=8)
        {
            $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Draw Materia)');
            //$ret['buttons'][]='confirm';   
        }

        if ($countreserve == 9)
        {
            $ret['titleyou'] = clienttranslate('${you} cannot trigger this spell');
            $ret['buttons'][]='cancel'; 
        }

        

        //$ret['buttons'][]='cancel';       
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
        //if($varg1 == "confirm") 
        {
            $location = 'materiareserve_'.$this->player_id;
            $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true ));

            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $emplacementmateria = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacementmateria);

            $log = array();

            if ($countreserve == 8)
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

                    
                    $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $log[] = ($col*10)+$signe;

                    spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "BLAZE" and draws ${log1}'), array(
                        'player_name' => $this->player_name,
                        'log1' => spellbook::$instance->getLogsType($log[0]),
                        
                                        
                        )
                        );

            }

            if ($countreserve == 7)
            {
                $emplacementlibre = array_slice($diff, 0, 2);
                for ($i=0; $i<=1; $i++)
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

                        
                    $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $log[] = ($col*10)+$signe;
                }
                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "BLAZE" and draws ${log1} ${log2}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                    'log2' => spellbook::$instance->getLogsType($log[1]),
                    
                                    
                    )
                    );
            }

            if ($countreserve == 6)
            {
                $emplacementlibre = array_slice($diff, 0, 3);
                for ($i=0; $i<=2; $i++)
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

                        
                    $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $log[] = ($col*10)+$signe;
                }

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "BLAZE" and draws ${log1} ${log2} ${log3}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                    'log2' => spellbook::$instance->getLogsType($log[1]),
                    'log3' => spellbook::$instance->getLogsType($log[2]),
                    
                                    
                    )
                    );
            }

            if ($countreserve <= 5)
            {
                $emplacementlibre = array_slice($diff, 0, 4);
                for ($i=0; $i<=3; $i++)
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

                        
                    $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $log[] = ($col*10)+$signe;
                }

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "BLAZE" and draws ${log1} ${log2} ${log3} ${log4}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                    'log2' => spellbook::$instance->getLogsType($log[1]),
                    'log3' => spellbook::$instance->getLogsType($log[2]),
                    'log4' => spellbook::$instance->getLogsType($log[3]),
                    
                                    
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



            $nbreplayer = count(self::getObjectListFromDB( "SELECT `player_id` FROM `player`", true ));
            
            if ($nbreplayer==1)
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Card31", "TakeOtherSolo");
            }
            if ($nbreplayer==2)
            {
                if ($this->player_no == 1)
                {
                    $id2 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 2"));
                    spellbook::$instance->addPendingTarget($id2, "Card31", "TakeOther", $id2);
                }
                if ($this->player_no == 2)
                {
                    $id1 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 1"));
                    spellbook::$instance->addPendingTarget($id1, "Card31", "TakeOther", $id1);
                }
               
               
            }
            if ($nbreplayer==3)
            {
                if ($this->player_no == 1)
                {
                    $id3 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 3"));
                    spellbook::$instance->addPendingTarget($id3, "Card31", "TakeOther", $id3);
                    $id2 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 2"));
                    spellbook::$instance->addPendingTarget($id2, "Card31", "TakeOther", $id2);

                }
                if ($this->player_no == 2)
                {
                    $id1 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 1"));
                    spellbook::$instance->addPendingTarget($id1, "Card31", "TakeOther", $id1);
                    $id3 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 3"));
                    spellbook::$instance->addPendingTarget($id3, "Card31", "TakeOther", $id3);

                }
                if ($this->player_no == 3)
                {
                    $id2 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 2"));
                    spellbook::$instance->addPendingTarget($id2, "Card31", "TakeOther", $id2);
                    $id1 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 1"));
                    spellbook::$instance->addPendingTarget($id1, "Card31", "TakeOther", $id1);

                }
                
                
            }
            if ($nbreplayer==4)
            {
                if ($this->player_no == 1)
                {
                    $id4 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 4"));
                    spellbook::$instance->addPendingTarget($id4, "Card31", "TakeOther", $id4);
                    $id3 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 3"));
                    spellbook::$instance->addPendingTarget($id3, "Card31", "TakeOther", $id3);
                    $id2 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 2"));
                    spellbook::$instance->addPendingTarget($id2, "Card31", "TakeOther", $id2);

                }
                if ($this->player_no == 2)
                {
                    $id1 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 1"));
                    spellbook::$instance->addPendingTarget($id1, "Card31", "TakeOther", $id1);
                    $id4 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 4"));
                    spellbook::$instance->addPendingTarget($id4, "Card31", "TakeOther", $id4);
                    $id3 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 3"));
                    spellbook::$instance->addPendingTarget($id3, "Card31", "TakeOther", $id3);

                }
                if ($this->player_no == 3)
                {
                    $id2 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 2"));
                    spellbook::$instance->addPendingTarget($id2, "Card31", "TakeOther", $id2);
                    $id1 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 1"));
                    spellbook::$instance->addPendingTarget($id1, "Card31", "TakeOther", $id1);
                    $id4 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 4"));
                    spellbook::$instance->addPendingTarget($id4, "Card31", "TakeOther", $id4);

                }
                if ($this->player_no == 4)
                {
                    $id3 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 3"));
                    spellbook::$instance->addPendingTarget($id3, "Card31", "TakeOther", $id3);
                    $id2 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 2"));
                    spellbook::$instance->addPendingTarget($id2, "Card31", "TakeOther", $id2);
                    $id1 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 1"));
                    spellbook::$instance->addPendingTarget($id1, "Card31", "TakeOther", $id1);

                }
                
            }
            

        }

        

    }

    public function argTakeOther($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must take 1 Materia');
        $ret['titleyou'] = clienttranslate('${you} must take 1 Materia');

        $reserveother = 'materiareserve_'.$parg1;
        $countreserveother = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserveother}'", true ));

        if ($countreserveother <= 8)
        {
            $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );
            foreach ($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
             
        }
       
        return $ret;
     }
    
    public function TakeOther($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 != NULL)
        {
            spellbook::$instance->addPendingTarget($parg1, "Card31", "Confirm", $parg1, $varg1);
        }
          
        

    }

    public function argConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must take 1 Materia');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');

        
        $ret["selected3"][] = $parg2;
        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/
        return $ret;
     }
    
    public function Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel") //attention otherplayer
        {
            spellbook::$instance->addPendingTarget($parg1, "Card31", "TakeOther", $parg1);
        }

        if($varg1 == "confirm")
        {*/
            
            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$parg1;
            $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacement);
            $emplacementlibre = array_slice($diff, 0, 1);
            $premieremplacementlibre = $emplacementlibre[0];

            $explode = explode("_", $parg2);
            $idmateria = intval($explode[1]);
            spellbook::$instance->materia->moveCard( $idmateria, $location, $premieremplacementlibre );

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg2,
                'parent' => $location.'_'.$premieremplacementlibre,
                
                )
                );

            
            
            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log = ($col*10)+$signe;


            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} takes ${log1}'), array(
                'player_name' => self::getUniqueValueFromDB("SELECT `player_name` FROM `player` WHERE `player_id` = {$parg1}"),
                'log1' => spellbook::$instance->getLogsType($log),
                
                                
                )
                );

            spellbook::$instance->AutelReorganisation();


        //}
          
        

    }



    public function argTakeOtherSolo($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must take 1 Materia');
        $ret['titleyou'] = clienttranslate('${you} must take 1 Materia for the opponent');

        
        

            $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );
            foreach ($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
             
       
       
        return $ret;
     }
    
    public function TakeOtherSolo($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 != NULL)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card31", "ConfirmSolo", $varg1);
        }
          
        

    }

    public function argConfirmSolo($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must take 1 Materia');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia for the opponent)');

        
        $ret["selected3"][] = $parg1;
        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/
        return $ret;
     }
    
    public function ConfirmSolo($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel") 
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card31", "TakeOtherSolo");
        }

        if($varg1 == "confirm")
        {*/
            
            $nbreopp = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiareserveopponent'", true ));
            $emplacement = $nbreopp + 1;

            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);

            
            spellbook::$instance->materia->moveCard( $idmateria, 'materiareserveopponent', $emplacement );

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg1,
                'parent' => 'materiareserveopponent_'.$emplacement,
                
                )
                );

            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->CalculPv();


        //}
          
        

    }



}