<?php 

class Clonecard33 extends Card
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
        $familier = 'materiafamilier_'.$this->player_id;
        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$player} AND `set_color` = 3 AND `typerune` !=0")); //////ATTENTION set_color

        //$ret["selected"][] = $parg1;
        $explode = explode("_", $parg1);
        $card = intval($explode[1]);
        $joueur = intval($explode[2]);
        
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));
        $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
        $countautel = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true ));

        if (spellbook::$instance->getGameStateValue('solo')==0)
        {
        if (($level >= 3)&&($countreserve >=1)&&($countfamilier>=1))
        {
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
        }

        if (($level >= 4)&&($countautel >=2)&&($countfamilier<=12))
        {
            //$ret['buttons'][] = 'level4';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
        }

        if (($level >= 5)&&($countautel >=3)&&($countfamilier<=11))
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
            if (($countautel <=1)||($countfamilier>=13))
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
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard33", "AltarStore2");
            }
            else
            {
            $explode = explode("_", $varg1);
            $level = intval($explode[3]);

            if ($level == 1)
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard33", "Swap");
            }

            if ($level == 2)
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard33", "AltarStore2");
            }

            if ($level == 3)
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard33", "AltarStore3");
            }
            }
        }


    }

    public function argSwap($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 `Materia` from the reserve to swap');

        $ret["selected"][] = 'materiacard_3_'.spellbook::$instance->getGameStateValue('idclone').'_1';
        

        $reserve = 'materiareserve_'.$this->player_id;
        $familier = 'materiafamilier_'.$this->player_id;
        $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true );
       
        foreach($ids as $id)
        {
            $ret["selectable"][] = 'materia_'.$id;
        }


        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Swap($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard33", "SwapStep2", $varg1);
        }


    }

    public function argSwapStep2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 `Materia` from the familiar to swap');
        
        $ret["selected3"][] = $parg1;

        $reserve = 'materiareserve_'.$this->player_id;
        $familier = 'materiafamilier_'.$this->player_id;
        $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$familier}'", true );
       
        foreach($ids as $id)
        {
            $ret["selectable"][] = 'materia_'.$id;
        }


        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function SwapStep2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard33", "SwapConfirm", $parg1, $varg1);
        }


    }

    public function argSwapConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Swap Materia)');
        
        $ret["selected3"][] = $parg1;
        $ret["selected3"][] = $parg2;

        

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/       
        return $ret;
     }
    
    public function SwapConfirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        if($varg1 == "confirm")
        {*/

            $explode1 = explode("_", $parg1);
            $idmateriareserve = intval($explode1[1]);
            $explode2 = explode("_", $parg2);
            $idmateriafamilier = intval($explode2[1]);

            $reserve = 'materiareserve_'.$this->player_id;
            $familier = 'materiafamilier_'.$this->player_id;
            $locationmateriareserve = self::getUniqueValueFromDB("SELECT `card_location_arg` FROM `materia` WHERE `card_id` = {$idmateriareserve}");
            $locationmateriafamilier = self::getUniqueValueFromDB("SELECT `card_location_arg` FROM `materia` WHERE `card_id` = {$idmateriafamilier}");

            spellbook::$instance->materia->moveCard( $idmateriareserve, $familier, $locationmateriafamilier);
            spellbook::$instance->materia->moveCard( $idmateriafamilier, $reserve, $locationmateriareserve);

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg1,
                'parent' => $familier.'_'.$locationmateriafamilier,
                
                )
                );

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg2,
                'parent' => $reserve.'_'.$locationmateriareserve,
                
                )
                );

                $log = array();
                $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateriareserve}"));
                $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateriareserve}"));
                $log[] = ($col1*10)+$signe1;
                $col2= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateriafamilier}"));
                $signe2= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateriafamilier}"));
                $log[] = ($col2*10)+$signe2;
    
                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "GROWTH" (Clone) and swaps ${log1} from the reserve with ${log2} from the familiar'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                    'log2' => spellbook::$instance->getLogsType($log[1]),
                                    
                    )
                    );


            spellbook::$instance->addPending($this->player_id, "Soir"); 
        //}


    }

    public function argAltarStore2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 2 `Materia` from the Altar to store');

        $ret["selected"][] = 'materiacard_3_'.spellbook::$instance->getGameStateValue('idclone').'_2';
        

        $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );
       
        foreach($ids as $id)
        {
            $ret["selectablemulti"][] = 'materia_'.$id;
        }

        $ret['buttons'][]='validate33store2'; 
        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function AltarStore2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }
    
    }

    public function argAltarStore2Confirm($parg1, $parg2)
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
        $ret['buttons'][]='cancel';  */     
        return $ret;
     }
    
    public function AltarStore2Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
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
                $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
                $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
                $log[] = ($col1*10)+$signe1;
            }
    
                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "GROWTH" (Clone) and stores ${log1} ${log2} from the Altar'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                    'log2' => spellbook::$instance->getLogsType($log[1]),
                                    
                    )
                    );

            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard33", "Level");
        //}
    
    }

    public function argAltarStore3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 3 `Materia` from the Altar to store');
        
        $ret["selected"][] = 'materiacard_3_'.spellbook::$instance->getGameStateValue('idclone').'_3';

        $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );
       
        foreach($ids as $id)
        {
            $ret["selectablemulti"][] = 'materia_'.$id;
        }

        $ret['buttons'][]='validate33store3'; 
        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function AltarStore3($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

    }

    public function argAltarStore3Confirm($parg1, $parg2)
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
    
    public function AltarStore3Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        if($varg1 == "confirm")
        {*/
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
                    $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
                    $log[] = ($col1*10)+$signe1;
            }
        
                    spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "GROWTH" (Clone) and stores ${log1} ${log2} ${log3} from the Altar'), array(
                        'player_name' => $this->player_name,
                        'log1' => spellbook::$instance->getLogsType($log[0]),
                        'log2' => spellbook::$instance->getLogsType($log[1]),
                        'log3' => spellbook::$instance->getLogsType($log[2]),
                                        
                        )
                        );

            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard33", "Level");
        //}
    
    }

    public function argLevel($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 3 `Materia` from the Altar to store');
        
      
        return $ret;
     }
    
    public function Level($parg1, $parg2, $varg1, $varg2)
    {
        $materiacard = 'materiacard_5_'.$this->player_id;
        $id = intval(self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location`='{$materiacard}'"));
        $emplacement = intval(self::getUniqueValueFromDB("SELECT `card_location_arg` FROM `materia` WHERE `card_location`='{$materiacard}'")) - 1;
        $power = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 5 AND `typerune` != 0"));
        $rune = intval(self::getUniqueValueFromDB("SELECT `typerune` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 5 AND `typerune` != 0"));

        $powerdown = $power-1;

        self::DbQuery( "UPDATE `cards` set `typerune` = 0 WHERE `set_color` = 5 AND `power` = {$power} AND `player_id` = {$this->player_id}" );
        self::DbQuery( "UPDATE `cards` set `typerune` = {$rune} WHERE `set_color` = 5 AND `power` = {$powerdown} AND `player_id` = {$this->player_id}" );

        spellbook::$instance->materia->moveCard( $id, $materiacard, $emplacement);
            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  'materia_'.$id,
                'parent' => $materiacard.'_'.$emplacement,
                
                )
                );

        
        

        spellbook::$instance->CalculPv();
        spellbook::$instance->addPending($this->player_id, "Soir"); 

    }



}