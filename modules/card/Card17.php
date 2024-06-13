<?php 

class Card17 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select the level to trigger');
         
        return $ret;
     }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        
    }

    public function argPower3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select the level to trigger');
         
        return $ret;
     }
    
    public function Power3($parg1, $parg2, $varg1, $varg2)
    {
        $tableau = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $location = 'materiareserve_'.$this->player_id;
        $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
        $diff = array_diff($tableau, $emplacement);
        $nbreplacelibre = count($diff);
        if ($nbreplacelibre != 0)
        {
            $emplacementlibre = array_slice($diff, 0, $nbreplacelibre);
        }

        if ($nbreplacelibre >=1)
        {
            $emplacement = $emplacementlibre[0];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

            $nbredraw = 1;
            $col1 = intval($colormateria);
            $signe1 = intval($runemateria);
            $log1 = ($col1*10)+$signe1;

        }

        if ($nbreplacelibre >=2)
        {
            $emplacement = $emplacementlibre[1];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

            $nbredraw = 2;
            $col2 = intval($colormateria);
            $signe2 = intval($runemateria);
            $log2 = ($col2*10)+$signe2;

        }

        if ($nbredraw == 1)
        {


            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ABUNDANCE" and draws ${log1}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                                
                )
                );

        }

        if ($nbredraw == 2)
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ABUNDANCE" and draws ${log1} ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                                
                )
                );

            
        }

        

        spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1000] );
        
        spellbook::$instance->addPending($this->player_id, "Autel");
        
    }

    public function argPower4($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select the level to trigger');
         
        return $ret;
     }

    public function Power4($parg1, $parg2, $varg1, $varg2)
    {
        $tableau = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $location = 'materiareserve_'.$this->player_id;
        $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
        $diff = array_diff($tableau, $emplacement);
        $nbreplacelibre = count($diff);
        if ($nbreplacelibre != 0)
        {
            $emplacementlibre = array_slice($diff, 0, $nbreplacelibre);
        }

        if ($nbreplacelibre >=1)
        {
            $emplacement = $emplacementlibre[0];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

            $nbredraw = 1;
            $col1 = intval($colormateria);
            $signe1 = intval($runemateria);
            $log1 = ($col1*10)+$signe1;

        }

        if ($nbreplacelibre >=2)
        {
            $emplacement = $emplacementlibre[1];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

                    $nbredraw = 2;
                    $col2 = intval($colormateria);
                    $signe2 = intval($runemateria);
                    $log2 = ($col2*10)+$signe2;
        

        }

        if ($nbreplacelibre >=3)
        {
            $emplacement = $emplacementlibre[2];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

            $nbredraw = 3;
            $col3 = intval($colormateria);
            $signe3 = intval($runemateria);
            $log3 = ($col3*10)+$signe3;


        }

        if ($nbredraw == 1)
        {


            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ABUNDANCE" and draws ${log1}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                                
                )
                );

        }

        if ($nbredraw == 2)
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ABUNDANCE" and draws ${log1} ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                                
                )
                );

            
        }

        if ($nbredraw == 3)
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ABUNDANCE" and draws ${log1} ${log2} ${log3}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                'log3' => spellbook::$instance->getLogsType($log3),
                                
                )
                );

            
        }

        spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1000] );
        
        spellbook::$instance->addPending($this->player_id, "Autel");
        
    }

    public function argPower5($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select the level to trigger');
         
        return $ret;
     }

    public function Power5($parg1, $parg2, $varg1, $varg2)
    {
        $tableau = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $location = 'materiareserve_'.$this->player_id;
        $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
        $diff = array_diff($tableau, $emplacement);
        $nbreplacelibre = count($diff);
        if ($nbreplacelibre != 0)
        {
            $emplacementlibre = array_slice($diff, 0, $nbreplacelibre);
        }

        if ($nbreplacelibre >=1)
        {
            $emplacement = $emplacementlibre[0];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

            $nbredraw = 1;
            $col1 = intval($colormateria);
            $signe1 = intval($runemateria);
            $log1 = ($col1*10)+$signe1;

        }

        if ($nbreplacelibre >=2)
        {
            $emplacement = $emplacementlibre[1];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

                    $nbredraw = 2;
                    $col2 = intval($colormateria);
                    $signe2 = intval($runemateria);
                    $log2 = ($col2*10)+$signe2;

        }

        if ($nbreplacelibre >=3)
        {
            $emplacement = $emplacementlibre[2];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

            $nbredraw = 3;
            $col3 = intval($colormateria);
            $signe3 = intval($runemateria);
            $log3 = ($col3*10)+$signe3;

        }

        if ($nbreplacelibre >=4)
        {
            $emplacement = $emplacementlibre[3];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $emplacement);
                $idmateria = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $colormateria = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                $runemateria = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$emplacement}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria,
                    'color' => $colormateria,
                    'rune' => $runemateria,
                    'location' =>  $location,
                    'emplacement' => $emplacement,
                    'player_name' => $this->player_name,
                    )
                    );

            $nbredraw = 4;
            $col4 = intval($colormateria);
            $signe4 = intval($runemateria);
            $log4 = ($col4*10)+$signe4;

        }

        if ($nbredraw == 1)
        {


            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ABUNDANCE" and draws ${log1}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                                
                )
                );

        }

        if ($nbredraw == 2)
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ABUNDANCE" and draws ${log1} ${log2}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                                
                )
                );

            
        }

        if ($nbredraw == 3)
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ABUNDANCE" and draws ${log1} ${log2} ${log3}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                'log3' => spellbook::$instance->getLogsType($log3),
                                
                )
                );

            
        }

        if ($nbredraw == 4)
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "ABUNDANCE" and draws ${log1} ${log2} ${log3} ${log4}'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log1),
                'log2' => spellbook::$instance->getLogsType($log2),
                'log3' => spellbook::$instance->getLogsType($log3),
                'log4' => spellbook::$instance->getLogsType($log4),
                                
                )
                );

            
        }

        spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1000] );
        
        spellbook::$instance->addPending($this->player_id, "Autel");
        
    }



    

}