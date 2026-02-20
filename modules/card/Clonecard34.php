<?php 

class Clonecard34 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        
        $player = spellbook::$instance->getGameStateValue('idclone');

        //$ret["selected"][] = $parg1;
        $explode = explode("_", $parg1);
        $card = intval($explode[1]);
        $joueur = intval($explode[2]);

        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$player} AND `set_color` = 4 AND `typerune` !=0")); //////ATTENTION set_color

        $reserve = 'materiareserve_'.$this->player_id;
        $familier = 'materiafamilier_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));
        $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
        $countautel = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true ));

        $rougef = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 1", true ));
        $violetf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 2", true ));
        $vertf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 3", true ));
        $noirf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 4", true ));
        $blancf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 5", true ));
        $bleuf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 6", true ));
        $jaunef = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 7", true ));

        $rougea = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 1", true ));
        $violeta = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 2", true ));
        $verta = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 3", true ));
        $noira = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 4", true ));
        $blanca = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 5", true ));
        $bleua = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 6", true ));
        $jaunea = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 7", true ));

        if (spellbook::$instance->getGameStateValue('solo')==0)
        {
        
        if (($level >= 3)&&($countreserve<=8)&&((($rougef >=1)&&($rougea>=1))||(($violetf >=1)&&($violeta>=1))||(($vertf >=1)&&($verta>=1))||(($noirf >=1)&&($noira>=1))||(($blancf >=1)&&($blanca>=1))||(($bleuf >=1)&&($bleua>=1))||(($jaunef >=1)&&($jaunea>=1))))
        {
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
        }

        if (($level >= 4)&&($countautel >=1)&&($countfamilier<=13))
        {
            //$ret['buttons'][] = 'level4';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
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
            if (($countautel == 0)||($countfamilier==14))
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
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard34", "Power4");
            }
            else
            {
            $explode = explode("_", $varg1);
            $level = intval($explode[3]);

            if ($level == 1)
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard34", "Power3");
            }

            if ($level == 2)
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard34", "Power4");
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
        $ret['titleyou'] = clienttranslate('${you} must select 1 Materia to take');

        $ret["selected"][] = 'materiacard_4_'.spellbook::$instance->getGameStateValue('idclone').'_1';
        

        $reserve = 'materiareserve_'.$this->player_id;
        $familier = 'materiafamilier_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));
        $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
        $countautel = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true ));

        $rougef = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 1", true ));
        $violetf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 2", true ));
        $vertf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 3", true ));
        $noirf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 4", true ));
        $blancf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 5", true ));
        $bleuf = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 6", true ));
        $jaunef = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = '{$familier}' AND `card_type` = 7", true ));

        $rougea = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 1", true ));
        $violeta = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 2", true ));
        $verta = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 3", true ));
        $noira = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 4", true ));
        $blanca = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 5", true ));
        $bleua = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 6", true ));
        $jaunea = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 7", true ));
        
        
        if(($rougef >=1)&&($rougea>=1))
        {
            $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 1", true );
            foreach($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }
        if(($violetf >=1)&&($violeta>=1))
        {
            $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 2", true );
            foreach($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }
        if(($vertf >=1)&&($verta>=1))
        {
            $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 3", true );
            foreach($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }
        if(($noirf >=1)&&($noira>=1))
        {
            $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 4", true );
            foreach($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }
        if(($blancf >=1)&&($blanca>=1))
        {
            $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 5", true );
            foreach($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }
        if(($bleuf >=1)&&($bleua>=1))
        {
            $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 6", true );
            foreach($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }
        if(($jaunef >=1)&&($jaunea>=1))
        {
            $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type` = 7", true );
            foreach($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }


        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Power3($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard34", "Power3Confirm", $varg1);
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
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');
        

        $ret["selected3"][] = $parg1;
        
        /*$ret['buttons'][]='confirm'; 
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }
    
    public function Power3Confirm($parg1, $parg2, $varg1, $varg2)
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

                $log = array();
                $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
                $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
                $log[] = ($col*10)+$signe;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "FEAST" (Clone) and takes ${log1}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                                
                )
                );

            spellbook::$instance->AutelReorganisation();

            spellbook::$instance->Permanent36($idmateria);

            spellbook::$instance->addPending($this->player_id, "Soir");
        //}

    }

    public function argPower4($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 Materia to store');

        $ret["selected"][] = 'materiacard_4_'.spellbook::$instance->getGameStateValue('idclone').'_2';
        

        $ids = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );

        foreach($ids as $id)
        {
            $ret["selectable"][] = 'materia_'.$id;
        }


        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Power4($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard34", "Power4Confirm", $varg1);
        }

    }

    public function argPower4Confirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Store Materia)');
        
        $ret["selected3"][] = $parg1;
        
        /*$ret['buttons'][]='confirm'; 
        
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }
    
    public function Power4Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        if($varg1 == "confirm")
        {*/

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

                $log = array();
                $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
                $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
                $log[] = ($col*10)+$signe;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "FEAST" (Clone) and stores ${log1} from the Altar'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                                
                )
                );


            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->CalculPv();
            spellbook::$instance->addPending($this->player_id, "Soir");
        //}

    }



}