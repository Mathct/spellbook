<?php 

class Clonecard12 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        
        //$ret["selected"][] = $parg1;
        $explode = explode("_", $parg1);
        $card = intval($explode[1]);
        $joueur = intval($explode[2]);

        $player = spellbook::$instance->getGameStateValue('idclone');

        
        $nombretriangle = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = 1", true ));
        $nombrecarre = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = 2", true ));
        $nombrerond = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = 3", true ));

        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$player} AND `set_color` = 2 AND `typerune` !=0")); //////ATTENTION set_color

        $location = 'materiareserve_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true ));

        if (spellbook::$instance->getGameStateValue('solo')==0)
        {
        if($countreserve <8)
        {
            if (($level >= 3)&&($nombrerond>=2))
            {
                //$ret['buttons'][] = 'level3';
                $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
            }

            if (($level >= 4)&&($nombretriangle>=2))
            {
                //$ret['buttons'][] = 'level4';
                $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
            }

            if (($level == 5)&&($nombrecarre>=2))
            {
                //$ret['buttons'][] = 'level5';
                $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_3';
            }
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
            if (($countreserve >=8)||($nombretriangle<2))
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
            if(spellbook::$instance->getGameStateValue('matinos')==1)
            {
                
                spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinSupp");
            }
            else
            {
                $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                $location = 'materiareserve_'.$this->player_id;
                $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
                $diff = array_diff($tableau1, $emplacement);
                $emplacementlibre = array_slice($diff, 0, 1);
                $premieremplacementlibre = $emplacementlibre[0];

                $locationdiscard = 'materiadiscard_'.$this->player_id;
                $idmateria = intval(self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$locationdiscard}'"));
                spellbook::$instance->materia->moveCard( $idmateria, $location, $premieremplacementlibre );

                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$idmateria,
                    'parent' => $location.'_'.$premieremplacementlibre,
                    
                    )
                    );

                spellbook::$instance->addPending($this->player_id, "Midi");
            }
        }

        else
        {
            if($varg1 == NULL)
            {
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard12", "Step2", $parg1, 4);
            }
            else
            {
        $explode = explode("_", $varg1);
        $level = intval($explode[3]);
        if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard12", "Step2", $parg1, 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard12", "Step2", $parg1, 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard12", "Step2", $parg1, 5);
        }
            }
        }


    }

    public function argStep2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selectable2M"] = array();
        $ret["selected2M"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select the 2 Materia');
        
        
        if ($parg2 == "3")
        {
            $ret["selected"][] = 'materiacard_2_'.spellbook::$instance->getGameStateValue('idclone').'_1';
            $id = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = 3", true );
            foreach($id as $idmateria)
            {
                $ret["selectable2M"][] = "materia_".$idmateria;
            }
        }

        if ($parg2 == "4")
        {
            $ret["selected"][] = 'materiacard_2_'.spellbook::$instance->getGameStateValue('idclone').'_2';
            $id = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = 1", true );
            foreach($id as $idmateria)
            {
                $ret["selectable2M"][] = "materia_".$idmateria;
            }
        }

        if ($parg2 == "5")
        {
            $ret["selected"][] = 'materiacard_2_'.spellbook::$instance->getGameStateValue('idclone').'_3';
            $id = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_type_arg` = 2", true );
            foreach($id as $idmateria)
            {
                $ret["selectable2M"][] = "materia_".$idmateria;
            }
        }
        
        $ret['buttons'][]='validateselectioncard12';  
        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Step2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            if(spellbook::$instance->getGameStateValue('matinos')==1)
            {
                
                spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinSupp");
            }
            else
            {
                $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                $location = 'materiareserve_'.$this->player_id;
                $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
                $diff = array_diff($tableau1, $emplacement);
                $emplacementlibre = array_slice($diff, 0, 1);
                $premieremplacementlibre = $emplacementlibre[0];

                $locationdiscard = 'materiadiscard_'.$this->player_id;
                $idmateria = intval(self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$locationdiscard}'"));
                spellbook::$instance->materia->moveCard( $idmateria, $location, $premieremplacementlibre );

                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$idmateria,
                    'parent' => $location.'_'.$premieremplacementlibre,
                    
                    )
                    );

                spellbook::$instance->addPending($this->player_id, "Midi");
            }
        }
        

    }

    public function argValidation($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');
        
        $ret["selected3"][] = $parg1;
        $ret["selected3"][] = $parg2;
        
        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';    */   
        return $ret;
     }
    
    public function Validation($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            if(spellbook::$instance->getGameStateValue('matinos')==1)
            {
                
                spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinSupp");
            }
            else
            {
                $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                $location = 'materiareserve_'.$this->player_id;
                $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
                $diff = array_diff($tableau1, $emplacement);
                $emplacementlibre = array_slice($diff, 0, 1);
                $premieremplacementlibre = $emplacementlibre[0];

                $locationdiscard = 'materiadiscard_'.$this->player_id;
                $idmateria = intval(self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$locationdiscard}'"));
                spellbook::$instance->materia->moveCard( $idmateria, $location, $premieremplacementlibre );

                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$idmateria,
                    'parent' => $location.'_'.$premieremplacementlibre,
                    
                    )
                    );

                spellbook::$instance->addPending($this->player_id, "Midi");
            }
        }

        if($varg1 == "confirm")
        {*/
            $explode1 = explode("_", $parg1);
            $idmateriarecup1 = intval($explode1[1]);
            $explode2 = explode("_", $parg2);
            $idmateriarecup2 = intval($explode2[1]);

            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacement);
            $emplacementlibre = array_slice($diff, 0, 2);
            $premier = $emplacementlibre[0];
            $deuxieme = $emplacementlibre[1];

            spellbook::$instance->materia->moveCard( $idmateriarecup1, $location, $premier);
            spellbook::$instance->materia->moveCard( $idmateriarecup2, $location, $deuxieme);

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg1,
                'parent' => $location.'_'.$premier,
                
                )
                );

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg2,
                'parent' => $location.'_'.$deuxieme,
                
                )
                );
    
            spellbook::$instance->AutelReorganisation();


            $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateriarecup1}"));
            $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateriarecup1}"));
            $log1 = ($col1*10)+$signe1;
            $col2= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateriarecup2}"));
            $signe2= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateriarecup2}"));
            $log2 = ($col2*10)+$signe2;


            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "LEVITATION" (Clone) and takes ${log1} ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                                
                )
                );

            $p36 = $parg1.'_'.$parg2;
            spellbook::$instance->Permanent36($p36);

            
            //discard clone
            if(spellbook::$instance->getGameStateValue('matinos')==0)
            {
            $locationdiscard = 'materiadiscard_'.$this->player_id;
            $idmateriadiscardclone = intval(self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$locationdiscard}'"));
            spellbook::$instance->materia->moveCard( $idmateriadiscardclone, 'discard');
                spellbook::$instance->notifyAllPlayers('discard','', array(
                    'mobile' => $idmateriadiscardclone,
                    )
                    );


            $log = array();
            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateriadiscardclone}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateriadiscardclone}"));
            $log[] = ($col*10)+$signe;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discard ${log1}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                
                                
                )
                );


                    
            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );
            }

            spellbook::$instance->setGameStateValue('matinos', 0);
            spellbook::$instance->addPending($this->player_id, "Soir");



        //}
        

    }

    

}