<?php 

class Card15 extends Card
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

        $reserve = 'materiareserve_'.$this->player_id;
        
        $nombretriangle = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = 1", true ));
        $nombrecarre = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = 2", true ));
        $nombrerond = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = 3", true ));

        $level = intval(self::getUniqueValueFromDB("SELECT power FROM cards WHERE player_id={$this->player_id} AND set_color = 5 AND typerune !=0")); //////ATTENTION set_color

        $spell = count(self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND typerune !=0 AND power != 5 AND set_color != 5", true ));
        
        if (($level >= 3)&&($nombrerond>=1)&&($spell>=1))
        {
            //$ret["selected"][] = 'materiacard_5_'.$this->player_id.'_1';
            $id1 = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = 3", true );
            foreach($id1 as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }

        if (($level >= 4)&&($nombretriangle>=1)&&($spell>=1))
        {
            //$ret["selected"][] = 'materiacard_5_'.$this->player_id.'_2';
            $id2 = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = 1", true );
            foreach($id2 as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }

        if (($level == 5)&&($nombrecarre>=1)&&($spell>=1))
        {
            //$ret["selected"][] = 'materiacard_5_'.$this->player_id.'_3';
            $id3 = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = 2", true );
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
            spellbook::$instance->addPending($this->player_id, "Soir");
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card15", "Step3", $varg1);
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

        

        if ($parg1 == "3")
        {
            $ret["selected"][] = 'materiacard_5_'.$this->player_id.'_1';
            $id = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = 3", true );
            foreach($id as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }

        if ($parg1 == "4")
        {
            $ret["selected"][] = 'materiacard_5_'.$this->player_id.'_2';
            $id = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = 1", true );
            foreach($id as $idmateria)
            {
                $ret["selectable"][] = "materia_".$idmateria;
            }
        }

        if ($parg1 == "5")
        {
            $ret["selected"][] = 'materiacard_5_'.$this->player_id.'_3';
            $id = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = 2", true );
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
            spellbook::$instance->addPending($this->player_id, "Soir");
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card15", "Step3", $varg1);
        }


    }

    public function argStep3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select the spell to upgrade');
        
        $ret["selected3"][] = $parg1;
        $reserve = 'materiareserve_'.$this->player_id;

        $spell = self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND typerune !=0 AND power != 5 AND set_color != 5", true );
        
        foreach ($spell as $card)
        {
            $ret["selectable"][] = "card_".$card."_".$this->player_id;
        }
        
        


        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Step3($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card15", "Confirm", $parg1, $varg1);
        }


    }

    public function argConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Discard Materia and Upgrade Spell)');
        
        $ret["selected3"][] = $parg1;

        $explode = explode("_", $parg2);
        $color = intval($explode[1]);
        $materiacard = 'materiacard_'.$color.'_'.$this->player_id;
        $id = intval(self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location='{$materiacard}'"));
        $ret["selected"][] = 'materia_'.$id;
        

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }
    
    public function Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
        }

        if($varg1 == "confirm")
        {*/
            $explode1 = explode("_", $parg1);
            spellbook::$instance->materia->moveCard( intval($explode1[1]), 'discard');

            spellbook::$instance->notifyAllPlayers('discard','', array(
                'mobile' => intval($explode1[1]),
                )
                );

        
            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1200] );

            $explode2 = explode("_", $parg2);
            $color = intval($explode2[1]);
            $materiacard = 'materiacard_'.$color.'_'.$this->player_id;
            $id = intval(self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location='{$materiacard}'"));
            $emplacement = intval(self::getUniqueValueFromDB("SELECT card_location_arg FROM materia WHERE card_location='{$materiacard}'")) + 1;

            $power = intval(self::getUniqueValueFromDB("SELECT power FROM cards WHERE player_id={$this->player_id} AND set_color = {$color} AND typerune != 0"));
            $rune = intval(self::getUniqueValueFromDB("SELECT typerune FROM cards WHERE player_id={$this->player_id} AND set_color = {$color} AND typerune != 0"));
            $powerup = $power+1;
            self::DbQuery( "UPDATE cards set typerune = 0 WHERE set_color = '{$color}' AND power = {$power} AND player_id = {$this->player_id}" );
            self::DbQuery( "UPDATE cards set typerune = {$rune} WHERE set_color = '{$color}' AND power = {$powerup} AND player_id = {$this->player_id}" );

            spellbook::$instance->materia->moveCard( $id, $materiacard, $emplacement);
            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  'materia_'.$id,
                'parent' => $materiacard.'_'.$emplacement,
                
                )
                );



            $set = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
            $index = $set.$color;
            $idmateria = intval($explode1[1]);
            $col= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateria}"));
            $logdiscard = ($col*10)+$signe;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "TIME TRAVEL", discards ${discard} and increases by one level "${name}"'), array(
                'i18n' => array( 'name' ),
                'player_name' => $this->player_name,
                'name' => spellbook::$instance->listecards[$index]['name'],
                'discard' => spellbook::$instance->getLogsType($logdiscard),
                                
                )
                );

            spellbook::$instance->CalculPv();

            // si le powerup déclenche un effet permanent
            
            $type = intval(self::getUniqueValueFromDB("SELECT type FROM cards WHERE power = {$powerup} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
            if($type == 2)
            {
                $set = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE power = {$powerup} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
                spellbook::$instance->addPendingTarget($this->player_id, "Card".$set.$color, "Power".$powerup);
            }
            else
            {
            spellbook::$instance->addPending($this->player_id, "Autel");
            }
        //}


    }

    

}