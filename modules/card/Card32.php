<?php 

class Card32 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        

        $reserve = 'materiareserve_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$reserve}'", true ));
        $level = intval(self::getUniqueValueFromDB("SELECT power FROM cards WHERE player_id={$this->player_id} AND set_color = 2 AND typerune !=0")); //////ATTENTION set_color

        //$ret["selected"][] = $parg1;
        /*$explode = explode("_", $parg1);
        $card = intval($explode[1]);
        $joueur = intval($explode[2]);*/
     

        if (($level >= 3)&&($countreserve <=7))
        {
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_2_'.$this->player_id.'_1';
        }

        if ($level >= 4)
        {
            //$ret['buttons'][] = 'level4';
            $ret["selectable"][] = 'materiacard_2_'.$this->player_id.'_2';
        }

        if (($level >= 5)&&($countreserve <=7))
        {
           //$ret['buttons'][] = 'level5';
           $ret["selectable"][] = 'materiacard_2_'.$this->player_id.'_3';
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
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Confirm", 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Confirm", 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Confirm", 5);
        }
            }

            else
            {
        $explode = explode("_", $varg1);
        $level = intval($explode[3]);

        if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Confirm", 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Confirm", 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Confirm", 5);
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
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Draw Materia for Altar)');
        
        if($parg1 =="3")
            {                
                $ret["selected"][] = 'materiacard_2_'.$this->player_id.'_1';
            }

            if($parg1 =="4")
            {
                $ret["selected"][] = 'materiacard_2_'.$this->player_id.'_2';
            }

            if($parg1 =="5")
            {
                $ret["selected"][] = 'materiacard_2_'.$this->player_id.'_3';
            }

        /*$ret['buttons'][]='confirm';         
        $ret['buttons'][]='cancel'; */      
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
            
            $countautel = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true ));

            for ($i=1; $i<=2;$i++)
            {
                $emplacement = $countautel + $i;
                spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiaautel', $emplacement);
                $id = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = 'materiaautel' AND card_location_arg = {$emplacement}");
                $color = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = 'materiaautel' AND card_location_arg = {$emplacement}");
                $rune = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = 'materiaautel' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $id,
                    'color' => $color,
                    'rune' => $rune,
                    'location' =>  'materiaautel',
                    'emplacement' => $emplacement,
                    )
                    );
            }

            if($parg1 =="3")
            {
                spellbook::$instance->setGameStateValue('testdiscard32', 1);
                spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Take2");

            }

            if($parg1 =="4")
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Color");
            }

            if($parg1 =="5")
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Take2");
            }
            

        //}

        

    }

    public function argColor($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        

        $reserve = 'materiareserve_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$reserve}'", true ));

        if($countreserve <=8)
        {
            $ret['titleyou'] = clienttranslate('${you} can select the first Materia to take');
            $ids = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true );
            foreach ($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t take Materia');
        }
                
        $ret['buttons'][]='pass';       
        return $ret;
     }
    
    public function Color($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "pass")
        {
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

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "ColorStep2", $varg1);
        }
        

        

    }

    public function argColorStep2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        

        $ret["selected3"][] = $parg1;

        $reserve = 'materiareserve_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$reserve}'", true ));

        $explode = explode("_", $parg1);
        $idselected = intval($explode[1]);
        $color = intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id = {$idselected}"));
        
        $ids = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel' AND card_id != {$idselected} AND card_type = {$color}", true );

        if(($ids != NULL)&&($countreserve<=7))
        {
            $ret['titleyou'] = clienttranslate('${you} can select a second Materia of the same color');
            foreach ($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }
        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t select a second Materia');
        }

        $ret['buttons'][]='takeonlyone';         
        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function ColorStep2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Color");
        }

        if($varg1 == "takeonlyone")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "ColorStep3", $parg1);
        }
        
        if(($varg1 != "cancel")&&($varg1 != "takeonlyone"))
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "ColorStep3", $parg1, $varg1);
        }
        

    }

    public function argColorStep3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');
        

        $ret["selected3"][] = $parg1;
        if($parg2 != NULL)
        {
        $ret["selected3"][] = $parg2;
        }

        /*$ret['buttons'][]='confirm';         
        $ret['buttons'][]='cancel';  */     
        return $ret;
     }
    
    public function ColorStep3($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Color");
        }

        if($varg1 == "confirm")
        {*/
            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacementmateria = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
            $diff = array_diff($tableau1, $emplacementmateria);
            $emplacementlibre = array_slice($diff, 0, 1);
            $emplacement = $emplacementlibre[0];

            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);
            spellbook::$instance->materia->moveCard( $idmateria, $location, $emplacement );

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg1,
                'parent' => $location.'_'.$emplacement,
                'player_name' => $this->player_name,
                )
                );

                $p36 = $idmateria;

            if($parg2 !=NULL)

            {
                $emplacementmateria = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
                $diff = array_diff($tableau1, $emplacementmateria);
                $emplacementlibre = array_slice($diff, 0, 1);
                $emplacement = $emplacementlibre[0];

                $explode = explode("_", $parg2);
                $idmateria2 = intval($explode[1]);
                spellbook::$instance->materia->moveCard( $idmateria2, $location, $emplacement );

                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  $parg2,
                    'parent' => $location.'_'.$emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

                $p36 = $idmateria.'_'.$idmateria2;

            }

            spellbook::$instance->AutelReorganisation();

            $log = array();

            $col1= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateria}"));
            $signe1= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateria}"));
            $log[] = ($col1*10)+$signe1;

            if ($parg2 ==NULL)
            {

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "DIVINATION" and takes ${log1}"'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                                    
                    )
                    );
            }

            if($parg2 !=NULL)
            {
                $col2= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateria2}"));
                $signe2= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateria2}"));
                $log[] = ($col2*10)+$signe2;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "DIVINATION" and takes ${log1} ${log2}"'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                    'log2' => spellbook::$instance->getLogsType($log[1]),
                                    
                    )
                    );

            }

            

            spellbook::$instance->Permanent36($p36);

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

    public function argTake2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 2 Materia to take');
        
          
        $ids = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true );
        foreach ($ids as $id)
        {
            $ret["selectablemulti"][] = 'materia_'.$id;
        }
        
        $ret['buttons'][]='validate32take2';          
        return $ret;
     }
    
    public function Take2($parg1, $parg2, $varg1, $varg2)
    {
        // attention pas le droit ici a un cancel car add 2 sur Altar (triche)

    }

    public function argTake2Confirm($parg1, $parg2)
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
        $ret['buttons'][]='cancel'; */
                 
        return $ret;
     }
    
    public function Take2Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Take2");
        }

        if($varg1 == "confirm")
        { */
            $log = array();

            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacementmateria = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
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

                $col= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateria}"));
                $signe= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateria}"));
                $log[] = ($col*10)+$signe;

            }

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "DIVINATION" and takes ${log1} ${log2}"'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                'log2' => spellbook::$instance->getLogsType($log[1]),
                                
                )
                );

            spellbook::$instance->AutelReorganisation();

            spellbook::$instance->Permanent36($parg1);

            if(spellbook::$instance->getGameStateValue('testdiscard32') == 1)
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Discard");
            }

            else
            {
                
    
                
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

        //}
        

    }

    public function argDiscard($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 Materia to discard');
        
        $location = 'materiareserve_'.$this->player_id;
        $ids = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$location}'", true );
        foreach ($ids as $id)
        {
            $ret["selectable"][] = 'materia_'.$id;
        }
        
                 
        return $ret;
     }
    
    public function Discard($parg1, $parg2, $varg1, $varg2)
    {

        spellbook::$instance->addPendingTarget($this->player_id, "Card32", "DiscardConfirm", $varg1);

    }

    public function argDiscardConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
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
    
    public function DiscardConfirm($parg1, $parg2, $varg1, $varg2)
    {

        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card32", "Discard");
        }

        if($varg1 == "confirm")
        {*/
            spellbook::$instance->setGameStateValue('testdiscard32', 0);

            $log = array();
            
            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);
            spellbook::$instance->materia->moveCard( $idmateria, 'discard');
            spellbook::$instance->notifyAllPlayers('discard','', array(
                    'mobile' => $idmateria,
                    )
                    );

            $col= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateria}"));
            $log[] = ($col*10)+$signe;
                    
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${log1}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                                
                )
                );

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );



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