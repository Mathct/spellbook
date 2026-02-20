<?php 

class Card14 extends Card
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
        $familier = 'materiafamilier_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$reserve}'", true ));
        $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));

        $nombrerouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 1", true ));
        $nombreviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 2", true ));
        $nombrevert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 3", true ));
        $nombrenoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 4", true ));
        $nombreblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 5", true ));
        $nombrebleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 6", true ));
        $nombrejaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 7", true ));
        
        
        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 4 AND `typerune` !=0")); //////ATTENTION set_color

                
        if (($level >= 3)&&($countreserve>=2)&&($countfamilier<=12)&& (($nombrerouge >=2)||($nombreviolet >=2)||($nombrevert >=2)||($nombrenoir >=2)||($nombreblanc >=2)||($nombrebleu >=2)||($nombrejaune >=2)))
        {
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
        }

        if (($level >= 4)&&($countreserve>=3)&&($countfamilier<=11)&& (($nombrerouge >=3)||($nombreviolet >=3)||($nombrevert >=3)||($nombrenoir >=3)||($nombreblanc >=3)||($nombrebleu >=3)||($nombrejaune >=3)))
        {
            //$ret['buttons'][] = 'level4';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
        }

        if (($level >= 5)&&($countreserve>=4)&&($countfamilier<=10)&& (($nombrerouge >=4)||($nombreviolet >=4)||($nombrevert >=4)||($nombrenoir >=4)||($nombreblanc >=4)||($nombrebleu >=4)||($nombrejaune >=4)))
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
            spellbook::$instance->addPending($this->player_id, "Midi");
        }

        else
        {

            if($varg1 == NULL)
            {
                $level = spellbook::$instance->getGameStateValue('variable1');
                if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card14", "Step2", 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card14", "Step2", 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card14", "Step2", 5);
        }
            }

            else{
        $explode = explode("_", $varg1);
        $level = intval($explode[3]);

        if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card14", "Step2", 3);
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card14", "Step2", 4);
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card14", "Step2", 5);
        }
    }
    }


    }

    public function argStep2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select the first Materia to store (this will set the desired color)');
        
        $level = intval($parg1);

        $reserve = 'materiareserve_'.$this->player_id;
        
        $nombrerouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 1", true ));
        $rouge = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 1", true );
        $nombreviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 2", true ));
        $violet = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 2", true );
        $nombrevert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 3", true ));
        $vert = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 3", true );
        $nombrenoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 4", true ));
        $noir = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 4", true );
        $nombreblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 5", true ));
        $blanc = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 5", true );
        $nombrebleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 6", true ));
        $bleu = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 6", true );
        $nombrejaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 7", true ));
        $jaune = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 7", true );
        
            
                
        if ($level == 3)
        {
            $ret["selected"][] = 'materiacard_4_'.$this->player_id.'_1';
            if ($nombrerouge>=2)
            {
                foreach ($rouge as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombreviolet>=2)
            {
                foreach ($violet as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrevert>=2)
            {
                foreach ($vert as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrenoir>=2)
            {
                foreach ($noir as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombreblanc>=2)
            {
                foreach ($blanc as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrebleu>=2)
            {
                foreach ($bleu as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrejaune>=2)
            {
                foreach ($jaune as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }

            
        }

        if ($level == 4)
        {
            $ret["selected"][] = 'materiacard_4_'.$this->player_id.'_2';
            if ($nombrerouge>=3)
            {
                foreach ($rouge as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombreviolet>=3)
            {
                foreach ($violet as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrevert>=3)
            {
                foreach ($vert as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrenoir>=3)
            {
                foreach ($noir as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombreblanc>=3)
            {
                foreach ($blanc as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrebleu>=3)
            {
                foreach ($bleu as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrejaune>=3)
            {
                foreach ($jaune as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }

            
        }

        if ($level == 5)
        {
            $ret["selected"][] = 'materiacard_4_'.$this->player_id.'_3';
            if ($nombrerouge>=4)
            {
                foreach ($rouge as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombreviolet>=4)
            {
                foreach ($violet as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrevert>=4)
            {
                foreach ($vert as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrenoir>=4)
            {
                foreach ($noir as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombreblanc>=4)
            {
                foreach ($blanc as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrebleu>=4)
            {
                foreach ($bleu as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }
            if ($nombrejaune>=4)
            {
                foreach ($jaune as $materia)
                {
                    $ret["selectable"][] = "materia_".$materia;
                }
            }

            
        }


        $ret['buttons'][]='cancel';       
        return $ret;

     }
    
    public function Step2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi");
        }
        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card14", "Step3", $parg1, $varg1);
        }


    }


    public function argStep3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablestore"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        
        
        $level = intval($parg1);

        $ret["selected3"][] = $parg2;
        $explode = explode("_", $parg2);
        $firstid = intval($explode[1]);
        $color = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id` = {$firstid}");

        
        $reserve = 'materiareserve_'.$this->player_id;

        $materia = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = '{$color}' AND `card_id` != {$firstid}", true );
        foreach ($materia as $id)   
        {
            $ret["selectablestore"][] = 'materia_'.$id;
        }  

        if ($level == 3)
        {
            $ret['titleyou'] = clienttranslate('${you} must complete your selection (2 Materia selected in total)');
            $ret['buttons'][]='validatestore2'; 
            
        }

        if ($level == 4)
        {
            $ret['titleyou'] = clienttranslate('${you} must complete your selection (3 Materia selected in total)');
            $ret['buttons'][]='validatestore3'; 
            
        }

        if ($level == 5)
        {
            $ret['titleyou'] = clienttranslate('${you} must complete your selection (4 Materia selected in total)');
            $ret['buttons'][]='validatestore4'; 
            
        }


        $ret['buttons'][]='cancel';       
        return $ret;

     }
    
    public function Step3($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi");
        }
        

    }

    public function argConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
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
    
    public function Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi");
        }

        if($varg1 == "confirm")
        {*/
            $familier = 'materiafamilier_'.$this->player_id;
            $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
            $explode = explode("_", $parg1);
            $nombremateria = count($explode);

            for ($i=1; $i<=$nombremateria; $i++)

            {
                $position = $countfamilier+$i;
                spellbook::$instance->materia->moveCard( intval($explode[$i-1]), $familier, $countfamilier+$i);
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$explode[$i-1],
                    'parent' => $familier.'_'.$position,
                    
                    )
                    );
    
            }

            
            if ($nombremateria == 2)
            {
                $id1 = intval($explode[0]);
                $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id1}"));
                $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id1}"));
                $log1 = ($col1*10)+$signe1;
                $id2 = intval($explode[1]);
                $col2= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id2}"));
                $signe2= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id2}"));
                $log2 = ($col2*10)+$signe2;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "OFFERING" and stores ${log1} ${log2}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                                    
                    )
                    );
            }

            if ($nombremateria == 3)
            {
                $id1 = intval($explode[0]);
                $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id1}"));
                $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id1}"));
                $log1 = ($col1*10)+$signe1;
                $id2 = intval($explode[1]);
                $col2= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id2}"));
                $signe2= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id2}"));
                $log2 = ($col2*10)+$signe2;
                $id3 = intval($explode[2]);
                $col3= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id3}"));
                $signe3= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id3}"));
                $log3 = ($col3*10)+$signe3;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "OFFERING" and stores ${log1} ${log2} ${log3}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                                    
                    )
                    );
                
            }

            if ($nombremateria == 4)
            {
                $id1 = intval($explode[0]);
                $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id1}"));
                $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id1}"));
                $log1 = ($col1*10)+$signe1;
                $id2 = intval($explode[1]);
                $col2= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id2}"));
                $signe2= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id2}"));
                $log2 = ($col2*10)+$signe2;
                $id3 = intval($explode[2]);
                $col3= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id3}"));
                $signe3= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id3}"));
                $log3 = ($col3*10)+$signe3;
                $id4 = intval($explode[3]);
                $col4= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$id4}"));
                $signe4= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$id4}"));
                $log4 = ($col4*10)+$signe4;

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "OFFERING" and stores ${log1} ${log2} ${log3} ${log4}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
                    'log3' => spellbook::$instance->getLogsType($log3),
                    'log4' => spellbook::$instance->getLogsType($log4),
                                    
                    )
                    );
                
            }


            

            spellbook::$instance->CalculPv();
            spellbook::$instance->addPending($this->player_id, "Soir");
        //}
        

    }













    

}