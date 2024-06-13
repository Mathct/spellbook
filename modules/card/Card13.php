<?php 

class Card13 extends Card
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

        
        $location = 'materiareserve_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$location}'", true ));
        $countautel = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true ));

        
        $level = intval(self::getUniqueValueFromDB("SELECT power FROM cards WHERE player_id={$this->player_id} AND set_color = 3 AND typerune !=0")); //////ATTENTION set_color = coleur de la carte

        
        if (($level >= 3)&&($countreserve>=1)&&($countautel>=1))
        {
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
        }

        if (($level >= 4)&&($countreserve>=2)&&($countautel>=2))
        {
            //$ret['buttons'][] = 'level4';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
        }

        if (($level >= 5)&&($countreserve>=3)&&($countautel>=3))
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
            spellbook::$instance->addPending($this->player_id, "Midi"); //attention
        }

        else
        {
            if($varg1 == NULL)
            {
                $level = spellbook::$instance->getGameStateValue('variable1');
                if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card13", "Swap1");
        }

        if ($level == 2)
        {
            spellbook::$instance->setGameStateValue('selectautel', 0);
            spellbook::$instance->addPendingTarget($this->player_id, "Card13", "Swap2");
        }

        if ($level == 3)
        {
            spellbook::$instance->setGameStateValue('selectautel', 0);
            spellbook::$instance->addPendingTarget($this->player_id, "Card13", "Swap3");
        }
            }

            else
            {
        $explode = explode("_", $varg1);
        $level = intval($explode[3]);
        if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card13", "Swap1");
        }

        if ($level == 2)
        {
            spellbook::$instance->setGameStateValue('selectautel', 0);
            spellbook::$instance->addPendingTarget($this->player_id, "Card13", "Swap2");
        }

        if ($level == 3)
        {
            spellbook::$instance->setGameStateValue('selectautel', 0);
            spellbook::$instance->addPendingTarget($this->player_id, "Card13", "Swap3");
        }
    }
    }


    }

    public function argSwap1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must must choose 1 Materia from the altar to exchange');
        
        $ret["selected"][] = 'materiacard_3_'.$this->player_id.'_1';

        $materiaautel = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true );
        foreach ($materiaautel as $id)
        {
            $ret["selectable"][] = "materia_".$id;
        }
       
        
        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Swap1($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); //attention
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card13", "Swap12", $varg1);
        }


    }

    public function argSwap12($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must must choose 1 Materia from the reserve to exchange');
        
        $ret["selected3"][] = $parg1;

        $reserve = 'materiareserve_'.$this->player_id;
        $materiareserve = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$reserve}'", true );

        foreach ($materiareserve as $id)
        {
            $ret["selectable"][] = "materia_".$id;
        }
       
        
        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Swap12($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); //attention
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card13", "Swap1Confirm", $parg1, $varg1);
        }


    }

    public function argSwap1Confirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
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
    
    public function Swap1Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); //attention
        }

        if($varg1 == "confirm")
        {*/
            $explode1 = explode("_", $parg1);
            $idmateriaautel = intval($explode1[1]);
            $explode2 = explode("_", $parg2);
            $idmateriareserve = intval($explode2[1]);

            $reserve = 'materiareserve_'.$this->player_id;
            $locationmateriaautel = self::getUniqueValueFromDB("SELECT card_location_arg FROM materia WHERE card_id = {$idmateriaautel}");
            $locationmateriareserve = self::getUniqueValueFromDB("SELECT card_location_arg FROM materia WHERE card_id = {$idmateriareserve}");

            spellbook::$instance->materia->moveCard( $idmateriaautel, $reserve, $locationmateriareserve);
            spellbook::$instance->materia->moveCard( $idmateriareserve, 'materiaautel', $locationmateriaautel);

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg1,
                'parent' => $reserve.'_'.$locationmateriareserve,
                
                )
                );

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg2,
                'parent' => 'materiaautel_'.$locationmateriaautel,
                
                )
                );

            $col1= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriareserve}"));
            $signe1= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriareserve}"));
            $logreserve1 = ($col1*10)+$signe1;

            $col2= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriaautel}"));
            $signe2= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriaautel}"));
            $logaltar1 = ($col2*10)+$signe2;

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "PURIFICATION" and swaps ${log1} from the reserve with ${log2} from the altar'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($logreserve1),
                'log2' => spellbook::$instance->getLogsType($logaltar1),
                                
                )
                );

            spellbook::$instance->addPending($this->player_id, "Soir");
        //}


    }

    public function argSwap2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectableswap2"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');

        $ret["selected"] = array();
        $ret["selected"][] = 'materiacard_3_'.$this->player_id.'_2';
        
        if (spellbook::$instance->getGameStateValue('selectautel') == 0)
        {
        $ret['titleyou'] = clienttranslate('${you} must must choose 2 Materia from the altar to exchange');
        $materiaautel = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true );
        foreach ($materiaautel as $id)
        {
            $ret["selectableswap2"][] = "materia_".$id;
            
        }
        $ret['buttons'][]='validateswap2autel';
        }

        if (spellbook::$instance->getGameStateValue('selectautel') == 1)
        {
        $ret['titleyou'] = clienttranslate('${you} must must choose 2 Materia from the reserve to exchange');
        $reserve = 'materiareserve_'.$this->player_id;
        $materiareserve = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$reserve}'", true );

        $explode = explode("_", $parg1);
        $ret["selected3"][] = 'materia_'.intval($explode[0]);
        $ret["selected3"][] = 'materia_'.intval($explode[1]);
        

        foreach ($materiareserve as $id)
        {
            $ret["selectableswap2"][] = "materia_".$id;
            
        }
        $ret['buttons'][]='validateswap2reserve';
        }
       
        
        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Swap2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); //attention
        }

        
    }

    public function argSwap2Confirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectableswap2"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Swap Materia)');
        
        $explode1 = explode("_", $parg1);
        $ret["selected3"][] = 'materia_'.intval($explode1[0]);
        $ret["selected3"][] = 'materia_'.intval($explode1[1]);
        $explode2 = explode("_", $parg2);
        $ret["selected3"][] = 'materia_'.intval($explode2[0]);
        $ret["selected3"][] = 'materia_'.intval($explode2[1]);
        

              
        /*$ret['buttons'][]='confirm'; 
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }
    
    public function Swap2Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); //attention
        }

        if($varg1 == "confirm")
        {*/
            $reserve = 'materiareserve_'.$this->player_id;
            $explode1 = explode("_", $parg1);
            $explode2 = explode("_", $parg2);
        
            for ($i = 0; $i<=1; $i++)
            {
                $idmateriaautel = intval($explode1[$i]);
                $idmateriareserve = intval($explode2[$i]);

                $locationmateriaautel = self::getUniqueValueFromDB("SELECT card_location_arg FROM materia WHERE card_id = {$idmateriaautel}");
                $locationmateriareserve = self::getUniqueValueFromDB("SELECT card_location_arg FROM materia WHERE card_id = {$idmateriareserve}");

                spellbook::$instance->materia->moveCard( $idmateriaautel, $reserve, $locationmateriareserve);
                spellbook::$instance->materia->moveCard( $idmateriareserve, 'materiaautel', $locationmateriaautel);

                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$idmateriaautel,
                    'parent' => $reserve.'_'.$locationmateriareserve,
                    
                    )
                    );
    
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$idmateriareserve,
                    'parent' => 'materiaautel_'.$locationmateriaautel,
                    
                    )
                    );
    
                

            }

            $idmateriareserve1 = intval($explode2[0]);
            $idmateriareserve2 = intval($explode2[1]);
            $col1= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriareserve1}"));
            $signe1= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriareserve1}"));
            $logreserve1 = ($col1*10)+$signe1;
            $col2= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriareserve2}"));
            $signe2= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriareserve2}"));
            $logreserve2 = ($col2*10)+$signe2;

            $idmateriaautel1 = intval($explode1[0]);
            $idmateriaautel2 = intval($explode1[1]);
            $col3= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriaautel1}"));
            $signe3= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriaautel1}"));
            $logaltar1 = ($col3*10)+$signe3;
            $col4= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriaautel2}"));
            $signe4= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriaautel2}"));
            $logaltar2 = ($col4*10)+$signe4;


            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "PURIFICATION" and swaps ${log1} ${log2} from the reserve with ${log3} ${log4} from the altar'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($logreserve1),
                'log2' => spellbook::$instance->getLogsType($logreserve2),
                'log3' => spellbook::$instance->getLogsType($logaltar1),
                'log4' => spellbook::$instance->getLogsType($logaltar2),
                                
                )
                );



            spellbook::$instance->addPending($this->player_id, "Soir"); //attention
        //}

        
    }


    public function argSwap3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectableswap2"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        
        $ret["selected"] = array();
        $ret["selected"][] = 'materiacard_3_'.$this->player_id.'_3';

        if (spellbook::$instance->getGameStateValue('selectautel') == 0)
        {
        $ret['titleyou'] = clienttranslate('${you} must must choose 3 Materia from the altar to exchange');
        $materiaautel = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true );
        foreach ($materiaautel as $id)
        {
            $ret["selectableswap2"][] = "materia_".$id;
            
        }
        $ret['buttons'][]='validateswap3autel';
        }

        if (spellbook::$instance->getGameStateValue('selectautel') == 1)
        {
        $ret['titleyou'] = clienttranslate('${you} must must choose 3 Materia from the reserve to exchange');
        $reserve = 'materiareserve_'.$this->player_id;
        $materiareserve = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$reserve}'", true );

        $explode = explode("_", $parg1);
        $ret["selected3"][] = 'materia_'.intval($explode[0]);
        $ret["selected3"][] = 'materia_'.intval($explode[1]);
        $ret["selected3"][] = 'materia_'.intval($explode[2]);
        

        foreach ($materiareserve as $id)
        {
            $ret["selectableswap2"][] = "materia_".$id;
            
        }
        $ret['buttons'][]='validateswap3reserve';
        }
       
        
        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function Swap3($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); //attention
        }

        
    }

    public function argSwap3Confirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectableswap2"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Swap Materia)');
        
        $explode1 = explode("_", $parg1);
        $ret["selected3"][] = 'materia_'.intval($explode1[0]);
        $ret["selected3"][] = 'materia_'.intval($explode1[1]);
        $ret["selected3"][] = 'materia_'.intval($explode1[2]);
        $explode2 = explode("_", $parg2);
        $ret["selected3"][] = 'materia_'.intval($explode2[0]);
        $ret["selected3"][] = 'materia_'.intval($explode2[1]);
        $ret["selected3"][] = 'materia_'.intval($explode2[2]);
        

              
        /*$ret['buttons'][]='confirm'; 
        $ret['buttons'][]='cancel';*/       
        return $ret;
     }
    
    public function Swap3Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); //attention
        }

        if($varg1 == "confirm")
        {*/
            $reserve = 'materiareserve_'.$this->player_id;
            $explode1 = explode("_", $parg1);
            $explode2 = explode("_", $parg2);
        
            for ($i = 0; $i<=2; $i++)
            {
                $idmateriaautel = intval($explode1[$i]);
                $idmateriareserve = intval($explode2[$i]);

                $locationmateriaautel = self::getUniqueValueFromDB("SELECT card_location_arg FROM materia WHERE card_id = {$idmateriaautel}");
                $locationmateriareserve = self::getUniqueValueFromDB("SELECT card_location_arg FROM materia WHERE card_id = {$idmateriareserve}");

                spellbook::$instance->materia->moveCard( $idmateriaautel, $reserve, $locationmateriareserve);
                spellbook::$instance->materia->moveCard( $idmateriareserve, 'materiaautel', $locationmateriaautel);

                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$idmateriaautel,
                    'parent' => $reserve.'_'.$locationmateriareserve,
                    
                    )
                    );
    
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$idmateriareserve,
                    'parent' => 'materiaautel_'.$locationmateriaautel,
                    
                    )
                    );
    
                

            }

            $idmateriareserve1 = intval($explode2[0]);
            $idmateriareserve2 = intval($explode2[1]);
            $idmateriareserve3 = intval($explode2[2]);
            $col1= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriareserve1}"));
            $signe1= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriareserve1}"));
            $logreserve1 = ($col1*10)+$signe1;
            $col2= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriareserve2}"));
            $signe2= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriareserve2}"));
            $logreserve2 = ($col2*10)+$signe2;
            $col5= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriareserve3}"));
            $signe5= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriareserve3}"));
            $logreserve3 = ($col5*10)+$signe5;

            $idmateriaautel1 = intval($explode1[0]);
            $idmateriaautel2 = intval($explode1[1]);
            $idmateriaautel3 = intval($explode1[2]);
            $col3= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriaautel1}"));
            $signe3= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriaautel1}"));
            $logaltar1 = ($col3*10)+$signe3;
            $col4= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriaautel2}"));
            $signe4= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriaautel2}"));
            $logaltar2 = ($col4*10)+$signe4;
            $col6= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriaautel3}"));
            $signe6= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriaautel3}"));
            $logaltar3 = ($col6*10)+$signe6;



            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "PURIFICATION" and swaps ${log1} ${log2} ${log3} from the reserve with ${log4} ${log5} ${log6} from the altar'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($logreserve1),
                'log2' => spellbook::$instance->getLogsType($logreserve2),
                'log3' => spellbook::$instance->getLogsType($logreserve3),
                'log4' => spellbook::$instance->getLogsType($logaltar1),
                'log5' => spellbook::$instance->getLogsType($logaltar2),
                'log6' => spellbook::$instance->getLogsType($logaltar3),
                                
                )
                );



            spellbook::$instance->addPending($this->player_id, "Soir"); //attention
        //}

        
    }
    

}