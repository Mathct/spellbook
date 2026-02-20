<?php 

class Card25 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        

        $reserve = 'materiareserve_'.$this->player_id;
        $nombrereserve = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}'", true ));
        $nombreautel = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel'", true ));


        $level = intval(self::getUniqueValueFromDB("SELECT `power` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 5 AND `typerune` !=0")); //////ATTENTION set_color

        $ret["selected"][] = $parg1;
        
       

        if (($level >= 4)&&($nombrereserve<=8)&&($nombreautel>=1))
        {
            $ret['titleyou'] = clienttranslate('${you} must select 1 to 10 Materia to replace on the Altar');
            $ids = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel'", true );
            foreach($ids as $id)
            {
                $ret["selectablemulti"][] = 'materia_'.$id;
            }
            $ret['buttons'][]='validate25selectautel';  

        }


        if (($level < 4)||($nombrereserve==9)||($nombreautel<1))
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
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        
    }

    public function argConfirmReplace($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Replace Materia)');
        
        $explode = explode('_', $parg1);
        $tableau = array_map('intval', $explode); //pour transformer les string du tableau en entier et recreer un nouveau tableau

        /// enlever les zero du tableau
        $tableausanszero = array_filter($tableau, function($valeur) {
            return $valeur != 0;
        });

        // Réindexer le tableau pour réorganiser les clés
        $tableausanszero = array_values($tableausanszero);

        foreach($tableausanszero as $materiaselected)
        {
            $ret["selected3"][] = 'materia_'.$materiaselected;
        }
        

        /*$ret['buttons'][]='confirm'; 
        $ret['buttons'][]='cancel'; */      
        return $ret;
     }

    
    public function ConfirmReplace($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir"); // attention
        }

        if($varg1 == "confirm")
        {*/

        $explode = explode('_', $parg1);
        $tableau = array_map('intval', $explode); //pour transformer les string du tableau en entier et recreer un nouveau tableau

        /// enlever les zero du tableau
        $tableausanszero = array_filter($tableau, function($valeur) {
            return $valeur != 0;
        });

        // Réindexer le tableau pour réorganiser les clés
        $tableausanszero = array_values($tableausanszero);

        $emplacementautel = array();
        

        foreach($tableausanszero as $iddiscard)
        {
            $emplacement = intval(self::getUniqueValueFromDB("SELECT `card_location_arg` FROM `materia` WHERE `card_id` = {$iddiscard}"));
            $emplacementautel[] = $emplacement;
            spellbook::$instance->materia->moveCard( $iddiscard, 'discard');
            spellbook::$instance->notifyAllPlayers('discard','', array(
                'mobile' =>  $iddiscard,
                )
                );
        
        }

        spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

        foreach($emplacementautel as $positionautel)
        {
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiaautel', $positionautel);
            $id = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$positionautel}");
            $color = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$positionautel}");
            $rune = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$positionautel}");
            spellbook::$instance->notifyAllPlayers('draw','', array(
                'id' => $id,
                'color' => $color,
                'rune' => $rune,
                'location' =>  'materiaautel',
                'emplacement' => $positionautel,
                )
                );
        
        }

        spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1000] );

        $reserve = 'materiareserve_'.$this->player_id;
        $nombrereserve = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}'", true ));
        $nombreautel = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel'", true ));

        if (($nombrereserve <= 6)&&($nombreautel >=3))
        {
        spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Take3");
        }
        if (($nombrereserve == 7)||($nombreautel ==2))
        {
        spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Take2");
        }
        if (($nombrereserve == 8)||($nombreautel ==1))
        {
        spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Take1");
        }




        //}

        

    }

    public function argTake3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 3 Materia to take');
        

        
        $materiaautel = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel'", true );
        

        
        foreach($materiaautel as $id)
        {
            $ret["selectablemulti"][] = 'materia_'.$id;
        }

        $ret['buttons'][]='validate25take3';  

        
             
        return $ret;
     }
    
    public function Take3($parg1, $parg2, $varg1, $varg2)
    {
        
        
    }

    public function argTake3Confirm($parg1, $parg2)
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

    
    public function Take3Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Take3");
        }

        if($varg1 == "confirm")
        { */ 
            $log = array();

            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacementmateria = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacementmateria);
            $emplacementlibre = array_slice($diff, 0, 3);

            $explode = explode("_", $parg1);

            for ($i=0; $i<=2; $i++)
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

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "STORM" and takes ${log1} ${log2} ${log3}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                'log2' => spellbook::$instance->getLogsType($log[1]),
                'log3' => spellbook::$instance->getLogsType($log[2]),
                                
                )
                );
            
            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->Permanent36($parg1);
            spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Level");

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
        

        
        $materiaautel = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel'", true );
        

        
        foreach($materiaautel as $id)
        {
            $ret["selectablemulti"][] = 'materia_'.$id;
        }

        $ret['buttons'][]='validate25take2';  

        
             
        return $ret;
     }
    
    public function Take2($parg1, $parg2, $varg1, $varg2)
    {
        
        
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
            spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Take2");
        }

        if($varg1 == "confirm")
        {  */
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

            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "STORM" and takes ${log1} ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                'log2' => spellbook::$instance->getLogsType($log[1]),
                
                                
                )
                );

            
            
            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->Permanent36($parg1);
            spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Level");

        //}
    }

    public function argTake1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 Materia to take');
        

        
        $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );
        foreach ($selectable as $materia)
        {
            $ret["selectable"][] = 'materia_'.$materia;
        }
        
        

        
             
        return $ret;
     }
    
    public function Take1($parg1, $parg2, $varg1, $varg2)
    {
        
        spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Take1Confirm", $varg1);
    }

    public function argTake1Confirm($parg1, $parg2)
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
        $id = intval($explode[1]);
        
        $ret["selected3"][] = 'materia_'.$id;
       
        
        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';  */     
        return $ret;
         
     }

    
    public function Take1Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Take1");
        }

        if($varg1 == "confirm")
        { */
            $log = array();

            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacementmateria = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
            $diff = array_diff($tableau1, $emplacementmateria);
            $emplacementlibre = array_slice($diff, 0, 1);

            $explode = explode("_", $parg1);

            
                $idmateria = intval($explode[1]);
                $emplacement = $emplacementlibre[0];

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
    
                
    
                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "STORM" and takes ${log1}'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                    
                                    
                    )
                    );
           

            
            
            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->Permanent36($idmateria);
            spellbook::$instance->addPendingTarget($this->player_id, "Card25", "Level");

        //}
    }

    
    public function argLevel($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');
        
               
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

        spellbook::$instance->addPending($this->player_id, "Autel");   


    }






}