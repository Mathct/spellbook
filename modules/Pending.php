<?php
class Pending extends APP_GameClass
{
    public function __construct($player_id)
    {
        $this->player_id = $player_id;
        $p = self::getObjectFromDB("SELECT * FROM `player` WHERE `player_id` = {$player_id}");        
        $this->player_no = $p['player_no'];
        $this->player_id = $p['player_id'];
        $this->player_name = $p['player_name'];
        $this->player_score = $p['player_score'];
        $this->player_color = $p['player_color'];
        $this->player_p26 = intval(self::getUniqueValueFromDB("SELECT `p26` FROM `player` WHERE `player_id` = {$this->player_id}")); //permanent 2 matins par journée
        $this->player_p36 = intval(self::getUniqueValueFromDB("SELECT `p36` FROM `player` WHERE `player_id` = {$this->player_id}"));
        $this->player_p37 = intval(self::getUniqueValueFromDB("SELECT `p37` FROM `player` WHERE `player_id` = {$this->player_id}"));
    }

////////////////////////////////////////////////////    
//   __  __       _   _       
//  |  \/  |     | | (_)      
//  | \  / | __ _| |_ _ _ __  
//  | |\/| |/ _` | __| | '_ \ 
//  | |  | | (_| | |_| | | | |
//  |_|  |_|\__,_|\__|_|_| |_|
//                            
////////////////////////////////////////////////////                           
 
    
    function argMatin($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose a morning action');
        

        $counttake = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true ));
        $location = 'materiareserve_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true ));
        
        if(($counttake >= 1) && ($countreserve<9))
        {
            $ret['buttons'][]='take'; 
        }
        if($countreserve<8)
        {
            $ret['buttons'][]='draw'; 
        }
        if($countreserve == 8)
        {
            $ret['buttons'][]='draw1'; 
        }

        $countcard = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` ={$this->player_id} AND `typerune` !=0 AND `jour` = 1 AND `type` = 0", true ));
        if ($countcard >= 1)
        {
            $ret['buttons'][]='cardaction'; 
        }
        
        $ret['buttons'][]='pass';


        $countbuttons = count($ret['buttons']);
        if($countbuttons == 1)
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do morning action');
        }

        if($countbuttons > 1)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose a morning action');
        }

        
        return $ret;
    }

    function Matin($parg1, $parg2, $varg1, $varg2)
    {
        spellbook::$instance->setGameStateValue('idclone', 0);
               
        
        if($varg1 == "take")
        {
            spellbook::$instance->addPending($this->player_id, "MatinTake");
        }
        if(($varg1 == "draw")||($varg1 == "draw1"))
        {
            spellbook::$instance->addPending($this->player_id, "MatinDraw", $varg1);
        }
        if($varg1 == "pass")
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} passes the morning action' ), array(
                'player_name' => $this->player_name,
                                
                )
                );

            spellbook::$instance->addPending($this->player_id, "Midi");
        }
        if($varg1 == "cardaction")
        {
            spellbook::$instance->addPending($this->player_id, "MatinCardAction");
        }
    }

    function argMatinSupp($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose a morning action (Bonus)');
        

        $counttake = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true ));
        $location = 'materiareserve_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true ));

        if(($counttake >= 1) && ($countreserve<9))
        {
            $ret['buttons'][]='take'; 
        }
        if($countreserve<8)
        {
            $ret['buttons'][]='draw'; 
        }
        if($countreserve == 8)
        {
            $ret['buttons'][]='draw1'; 
        }

        $countcard = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` ={$this->player_id} AND `typerune` !=0 AND `jour` = 1 AND `type` = 0", true ));
        if ($countcard >= 1)
        {
            $ret['buttons'][]='cardaction'; 
        }
        
        $ret['buttons'][]='pass';

        $countbuttons = count($ret['buttons']);
        if($countbuttons == 1)
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do morning action (Bonus)');
        }

        if($countbuttons > 1)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose a morning action (Bonus)');
        }
        
        return $ret;
    }

    function MatinSupp($parg1, $parg2, $varg1, $varg2)
    {
        
        
        if($varg1 == "take")
        {
            spellbook::$instance->addPending($this->player_id, "MatinTake");
        }
        if(($varg1 == "draw")||($varg1 == "draw1"))
        {
            spellbook::$instance->addPending($this->player_id, "MatinDraw", $varg1);
        }
        if($varg1 == "pass")
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} passes the morning action' ), array(
                'player_name' => $this->player_name,
                                
                )
                );
            
            if(spellbook::$instance->getGameStateValue('matinperm')==1)
            {
            spellbook::$instance->setGameStateValue('matinperm', 0);
            spellbook::$instance->addPending($this->player_id, "Midi");
            }
            if(spellbook::$instance->getGameStateValue('matinos')==1)
            {
            spellbook::$instance->setGameStateValue('matinos', 0);
            spellbook::$instance->addPending($this->player_id, "Autel");
            }
            

        }
        if($varg1 == "cardaction")
        {
            spellbook::$instance->addPending($this->player_id, "MatinCardAction");
        }
    }

    function argMatinTake($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} takes a Materia');
        $ret['titleyou'] = clienttranslate('${you} must select a Materia');

        $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='materiaautel'", true );
        foreach ($selectable as $materia)
        {
            $ret["selectable"][] = 'materia_'.$materia;
        }
        
        $ret['buttons'][]='cancel';

                
        return $ret;
    }

    function MatinTake($parg1, $parg2, $varg1, $varg2)
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
            spellbook::$instance->addPending($this->player_id, "MatinTakeConfirm", $varg1);

        }

    }

    function argMatinTakeConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} takes a Materia');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia)');

        $ret["selected"][] = $parg1;

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/


                
        return $ret;
    }

    function MatinTakeConfirm($parg1, $parg2, $varg1, $varg2)
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
       
        if($varg1 == "confirm")
        {*/
        $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $location = 'materiareserve_'.$this->player_id;
        $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
        $diff = array_diff($tableau1, $emplacement);
        $emplacementlibre = array_slice($diff, 0, 1);
        $premieremplacementlibre = $emplacementlibre[0];

        $explode = explode("_", $parg1);
        $idmateria = intval($explode[1]);
        spellbook::$instance->materia->moveCard( $idmateria, $location, $premieremplacementlibre );

        $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
        $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
        $log = ($col*10)+$signe;

        spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} takes ${log}' ), array(
            'mobile' =>  $parg1,
            'parent' => $location.'_'.$premieremplacementlibre,
            'player_name' => $this->player_name,
            'log' => spellbook::$instance->getLogsType($log),
            )
            );

        spellbook::$instance->AutelReorganisation();

        spellbook::$instance->Permanent36($idmateria);

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

    function argMatinDraw($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} draws Materia');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Draw Materia)');

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/

        return $ret;
    }

    function MatinDraw($parg1, $parg2, $varg1, $varg2)
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
       
        if($varg1 == "confirm")
        {*/
        
        $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $location = 'materiareserve_'.$this->player_id;
        $emplacement = self::getObjectListFromDB( "SELECT `card_location_arg` FROM `materia` WHERE `card_location` ='{$location}' ORDER BY `card_location_arg` ASC", true );
        $diff = array_diff($tableau1, $emplacement);
        

            if($parg1 == "draw1")
            {
                $emplacementlibre = array_slice($diff, 0, 1);
                $premieremplacementlibre = $emplacementlibre[0];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $premieremplacementlibre);
                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");

                $col1= intval($colormateria1);
                $signe1= intval($runemateria1);
                $log1 = ($col1*10)+$signe1;

                spellbook::$instance->notifyAllPlayers('draw',clienttranslate( '${player_name} draws ${log1}' ), array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  $location,
                    'emplacement' => $premieremplacementlibre,
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    )
                    );
            }

            if($parg1 == "draw")
            {
                $emplacementlibre = array_slice($diff, 0, 2);
                $premieremplacementlibre = $emplacementlibre[0];
                $secondemplacementlibre = $emplacementlibre[1];

                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $premieremplacementlibre);
                spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $secondemplacementlibre);

                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$premieremplacementlibre}");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  $location,
                    'emplacement' => $premieremplacementlibre,
                    'player_name' => $this->player_name,
                    )
                    );

                spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 500] );

                $idmateria2 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$secondemplacementlibre}");
                $colormateria2 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$secondemplacementlibre}");
                $runemateria2 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = '{$location}' AND `card_location_arg` = {$secondemplacementlibre}");

                $col1= intval($colormateria1);
                $signe1= intval($runemateria1);
                $log1 = ($col1*10)+$signe1;
                $col2= intval($colormateria2);
                $signe2= intval($runemateria2);
                $log2 = ($col2*10)+$signe2;


                spellbook::$instance->notifyAllPlayers('draw',clienttranslate( '${player_name} draws ${log1} ${log2}' ), array(
                    'id' => $idmateria2,
                    'color' => $colormateria2,
                    'rune' => $runemateria2,
                    'location' =>  $location,
                    'emplacement' => $secondemplacementlibre,
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log1),
                    'log2' => spellbook::$instance->getLogsType($log2),
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
            

        //}

    }

    function argMatinCardAction($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select a card');

        $color = self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` ={$this->player_id} AND `typerune` !=0 AND `jour` = 1 AND `type` = 0", true );
        
        foreach ($color as $card)
        {
            $ret["selectable"][] = "card_".$card."_".$this->player_id;
        }
             
        $ret['buttons'][]='cancel';

        return $ret;
    }

    function MatinCardAction($parg1, $parg2, $varg1, $varg2)
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
            $explode = explode("_", $varg1);
            $color = intval($explode[1]);
            $set = self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = {$color} AND `typerune` !=0");

            spellbook::$instance->addPendingTarget($this->player_id, "Card".$set.$color, "init", $varg1);
        }

    }


////////////////////////////////////////////////////    
//     __  __ _     _ _ 
//    |  \/  (_)   | (_)
//    | \  / |_  __| |_ 
//    | |\/| | |/ _` | |
//    | |  | | | (_| | |
//    |_|  |_|_|\__,_|_|
//
////////////////////////////////////////////////////    
    
    function argMidi($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose a midday action');
        

        $location = 'materiareserve_'.$this->player_id;
        $familier = 'materiafamilier_'.$this->player_id;
        $countreserve = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true ));
        $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));

        if(($countreserve >=1)&&($countfamilier < 14))
        {
        $ret['buttons'][]='store';
        }

        $countcard = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` ={$this->player_id} AND `typerune` !=0 AND `jour` = 2", true ));
        if ($countcard >= 1)
        {
            $ret['buttons'][]='cardaction'; 
        }

        $ret['buttons'][]='pass';
        
        $countbuttons = count($ret['buttons']);
        if($countbuttons == 1)
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do midday action');
        }

        if($countbuttons > 1)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose a midday action');
        }

        return $ret;
    }

    function Midi($parg1, $parg2, $varg1, $varg2)
    {
        spellbook::$instance->setGameStateValue('idclone', 0);
        
        if($varg1 == "store")
        {
            spellbook::$instance->addPending($this->player_id, "MidiStore");
        }
        if($varg1 == "pass")
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} passes the midday action' ), array(
                'player_name' => $this->player_name,
                                
                )
                );

            spellbook::$instance->addPending($this->player_id, "Soir");
        }

        if($varg1 == "cardaction")
        {
            spellbook::$instance->addPending($this->player_id, "MidiCardAction");
        }

        

    }

    function argMidiStore($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} stores a Materia');
        $ret['titleyou'] = clienttranslate('${you} must choose the Materia to store');

        $location = 'materiareserve_'.$this->player_id;
        $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}'", true );
        foreach ($selectable as $materia)
        {
            $ret["selectable"][] = 'materia_'.$materia;
        }

        
        $ret['buttons'][]='cancel';

        return $ret;
    }

    function MidiStore($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi");
        }

        else
        {
            spellbook::$instance->addPending($this->player_id, "MidiStoreConfirm", $varg1);
        }
      

    }

    function argMidiStoreConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} stores a Materia');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Store Materia)');

        $ret["selected"][] = $parg1;

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/


                
        return $ret;
    }

    function MidiStoreConfirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi");
        }
       
        if($varg1 == "confirm")
        {*/

            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);

            $familier = 'materiafamilier_'.$this->player_id;
            $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
            $nouvelemplacementfamilier = $countfamilier +1;

            spellbook::$instance->materia->moveCard( $idmateria, $familier, $nouvelemplacementfamilier);

            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idmateria}"));
            $log = ($col*10)+$signe;
        

        spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} stores ${log}' ), array(
            'mobile' =>  $parg1,
            'parent' => $familier.'_'.$nouvelemplacementfamilier,
            'player_name' => $this->player_name,
            'log' => spellbook::$instance->getLogsType($log),
            )
            );


        spellbook::$instance->CalculPv();
        spellbook::$instance->addPending($this->player_id, "Soir");

        //}

    }

    function argMidiCardAction($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select a card');

        $color = self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` ={$this->player_id} AND `typerune` !=0 AND `jour` = 2", true );
        
        foreach ($color as $card)
        {
            $ret["selectable"][] = "card_".$card."_".$this->player_id;
        }
             
        $ret['buttons'][]='cancel';

        return $ret;
    }

    function MidiCardAction($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi");
        }
        else
        {
            $explode = explode("_", $varg1);
            $color = intval($explode[1]);
            $set = self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = {$color} AND `typerune` !=0");

            spellbook::$instance->addPendingTarget($this->player_id, "Card".$set.$color, "init", $varg1);
        }

    }

////////////////////////////////////////////////////    
//    _____       _      
//   / ____|     (_)     
//  | (___   ___  _ _ __ 
//   \___ \ / _ \| | '__|
//   ____) | (_) | | |   
//  |_____/ \___/|_|_|   
//                       
////////////////////////////////////////////////////   
                      
   
    function argSoir($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an evening action');
        

        /////////////////////////// Test si au moins un sort peut être appris au minimum Niveau 3 /////////////////////////////
        $test = 0;
        $reserve = 'materiareserve_'.$this->player_id;
        
        ////// Test Rouge //////

        ///// est ce que sort a déjà été appris?////
        $pouvoirrouge = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 1", true );
        $testpouvoirrouge = 0;
        foreach ($pouvoirrouge as $valeur) 
        {
            if ($valeur != 0) 
            {
                $testpouvoirrouge = 1;
                break;
            }
        }
        //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
        if($testpouvoirrouge == 0)
        {
            $nombrerouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 1", true ));
            if ($nombrerouge >= 1)
            {
            $nombretrianglenonrouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 1 AND `card_type_arg` = 1", true ));
            $nombrecarrenonrouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 1 AND `card_type_arg` = 2", true ));
            $nombrerondnonrouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 1 AND `card_type_arg` = 3", true ));
            $calcul = $nombrerouge + floor($nombrecarrenonrouge/3) + floor($nombretrianglenonrouge/3) + floor($nombrerondnonrouge/3); 
            if ($calcul >= 3)
            {
                $test = 1;
            }
            }
            
        }

        ////// Test Violet //////

        ///// est ce que sort a déjà été appris?////
        $pouvoirviolet = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 2", true );
        $testpouvoirviolet = 0;
        foreach ($pouvoirviolet as $valeur) 
        {
            if ($valeur != 0) 
            {
                $testpouvoirviolet = 1;
                break;
            }
        }
        //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
        if($testpouvoirviolet == 0)
        {
            $nombreviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 2", true ));
            if ($nombreviolet >= 1)
            {
            $nombretrianglenonviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 2 AND `card_type_arg` = 1", true ));
            $nombrecarrenonviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 2 AND `card_type_arg` = 2", true ));
            $nombrerondnonviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 2 AND `card_type_arg` = 3", true ));
            $calcul = $nombreviolet + floor($nombrecarrenonviolet/3) + floor($nombretrianglenonviolet/3) + floor($nombrerondnonviolet/3); 
            if ($calcul >= 3)
            {
                $test = 1;
            }
            }
            
        }

        ////// Test Vert //////

        ///// est ce que sort a déjà été appris?////
        $pouvoirvert = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 3", true );
        $testpouvoirvert = 0;
        foreach ($pouvoirvert as $valeur) 
        {
            if ($valeur != 0) 
            {
                $testpouvoirvert = 1;
                break;
            }
        }
        //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
        if($testpouvoirvert == 0)
        {
            $nombrevert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 3", true ));
            if ($nombrevert >= 1)
            {
            $nombretrianglenonvert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 3 AND `card_type_arg` = 1", true ));
            $nombrecarrenonvert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 3 AND `card_type_arg` = 2", true ));
            $nombrerondnonvert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 3 AND `card_type_arg` = 3", true ));
            $calcul = $nombrevert + floor($nombrecarrenonvert/3) + floor($nombretrianglenonvert/3) + floor($nombrerondnonvert/3); 
            if ($calcul >= 3)
            {
                $test = 1;
            }
            }
            
        }

        ////// Test Noir //////

        ///// est ce que sort a déjà été appris?////
        $pouvoirnoir = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 4", true );
        $testpouvoirnoir = 0;
        foreach ($pouvoirnoir as $valeur) 
        {
            if ($valeur != 0) 
            {
                $testpouvoirnoir = 1;
                break;
            }
        }
        //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
        if($testpouvoirnoir == 0)
        {
            $nombrenoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 4", true ));
            if ($nombrenoir >= 1)
            {
            $nombretrianglenonnoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 4 AND `card_type_arg` = 1", true ));
            $nombrecarrenonnoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 4 AND `card_type_arg` = 2", true ));
            $nombrerondnonnoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 4 AND `card_type_arg` = 3", true ));
            $calcul = $nombrenoir + floor($nombrecarrenonnoir/3) + floor($nombretrianglenonnoir/3) + floor($nombrerondnonnoir/3); 
            if ($calcul >= 3)
            {
                $test = 1;
            }
            }
            
        }


        ////// Test Blanc //////

        ///// est ce que sort a déjà été appris?////
        $pouvoirblanc = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 5", true );
        $testpouvoirblanc = 0;
        foreach ($pouvoirblanc as $valeur) 
        {
            if ($valeur != 0) 
            {
                $testpouvoirblanc = 1;
                break;
            }
        }
        //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
        if($testpouvoirblanc == 0)
        {
            $nombreblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 5", true ));
            if ($nombreblanc >= 1)
            {
            $nombretrianglenonblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 5 AND `card_type_arg` = 1", true ));
            $nombrecarrenonblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 5 AND `card_type_arg` = 2", true ));
            $nombrerondnonblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 5 AND `card_type_arg` = 3", true ));
            $calcul = $nombreblanc + floor($nombrecarrenonblanc/3) + floor($nombretrianglenonblanc/3) + floor($nombrerondnonblanc/3); 
            if ($calcul >= 3)
            {
                $test = 1;
            }
            }
            
        }


        ////// Test Bleu //////

        ///// est ce que sort a déjà été appris?////
        $pouvoirbleu = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 6", true );
        $testpouvoirbleu = 0;
        foreach ($pouvoirbleu as $valeur) 
        {
            if ($valeur != 0) 
            {
                $testpouvoirbleu = 1;
                break;
            }
        }
        //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
        if($testpouvoirbleu == 0)
        {
            $nombrebleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 6", true ));
            if ($nombrebleu >= 1)
            {
            $nombretrianglenonbleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 6 AND `card_type_arg` = 1", true ));
            $nombrecarrenonbleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 6 AND `card_type_arg` = 2", true ));
            $nombrerondnonbleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 6 AND `card_type_arg` = 3", true ));
            $calcul = $nombrebleu + floor($nombrecarrenonbleu/3) + floor($nombretrianglenonbleu/3) + floor($nombrerondnonbleu/3); 
            if ($calcul >= 3)
            {
                $test = 1;
            }
            }
           
        }

        ////// Test Jaune //////

        ///// est ce que sort a déjà été appris?////
        $pouvoirjaune = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 7", true );
        $testpouvoirjaune = 0;
        foreach ($pouvoirjaune as $valeur) 
        {
            if ($valeur != 0) 
            {
                $testpouvoirjaune = 1;
                break;
            }
        }
        //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
        if($testpouvoirjaune == 0)
        {
            $nombrejaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 7", true ));
            if ($nombrejaune >= 1)
            {
            $nombretrianglenonjaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 7 AND `card_type_arg` = 1", true ));
            $nombrecarrenonjaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 7 AND `card_type_arg` = 2", true ));
            $nombrerondnonjaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 7 AND `card_type_arg` = 3", true ));
            $calcul = $nombrejaune + floor($nombrecarrenonjaune/3) + floor($nombretrianglenonjaune/3) + floor($nombrerondnonjaune/3); 
            if ($calcul >= 3)
            {
                $test = 1;
            }
            }
           
        }



        if ($test == 1)
        {
            $ret['buttons'][]='learn';
        }

        $countcard = count(self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` ={$this->player_id} AND `typerune` !=0 AND `jour` = 3 AND `type` = 0", true ));
        if ($countcard >= 1)
        {
            $ret['buttons'][]='cardaction'; 
        }

        $ret['buttons'][]='pass';
        
        $countbuttons = count($ret['buttons']);
        if($countbuttons == 1)
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do evening action');
        }

        if($countbuttons > 1)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an evening action');
        }

        return $ret;
    }


    function Soir($parg1, $parg2, $varg1, $varg2)
    {
        spellbook::$instance->setGameStateValue('idclone', 0);

        if($varg1 == "pass")
        {
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} passes the evening action' ), array(
                'player_name' => $this->player_name,
                                
                )
                );

            spellbook::$instance->addPending($this->player_id, "Autel");
        }

        if($varg1 == "learn")
        {
            spellbook::$instance->addPending($this->player_id, "SoirLearn1");
        }

        
        if($varg1 == "cardaction")
        {
            spellbook::$instance->addPending($this->player_id, "SoirCardAction");
        }

    }

    function argSoirLearn1($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} learns a spell');
        $ret['titleyou'] = clienttranslate('${you} must choose the spell to learn');

       /////////////////////////// Test si au moins un sort peut être appris au minimum Niveau 3 /////////////////////////////
       
       $reserve = 'materiareserve_'.$this->player_id;
       
       ////// Test Rouge //////

       ///// est ce que sort a déjà été appris?////
       $pouvoirrouge = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 1", true );
       $testpouvoirrouge = 0;
       foreach ($pouvoirrouge as $valeur) 
       {
           if ($valeur != 0) 
           {
               $testpouvoirrouge = 1;
               break;
           }
       }
       //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
       if($testpouvoirrouge == 0)
       {
           $nombrerouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 1", true ));
           if ($nombrerouge >= 1)
            {
           $nombretrianglenonrouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 1 AND `card_type_arg` = 1", true ));
           $nombrecarrenonrouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 1 AND `card_type_arg` = 2", true ));
           $nombrerondnonrouge = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 1 AND `card_type_arg` = 3", true ));
           $calcul = $nombrerouge + floor($nombrecarrenonrouge/3) + floor($nombretrianglenonrouge/3) + floor($nombrerondnonrouge/3); 
           if ($calcul >= 3)
           {
                //$ret["selectable"][] = 'card_1_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_1_'.$this->player_id.'_1';
           }
           if ($calcul >= 4)
            {
                $ret["selectable"][] = 'materiacard_1_'.$this->player_id.'_2';
            }

            if ($calcul >= 5)
            {
                $ret["selectable"][] = 'materiacard_1_'.$this->player_id.'_3';
            }
            }
           
       }

       ////// Test Violet //////

       ///// est ce que sort a déjà été appris?////
       $pouvoirviolet = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 2", true );
       $testpouvoirviolet = 0;
       foreach ($pouvoirviolet as $valeur) 
       {
           if ($valeur != 0) 
           {
               $testpouvoirviolet = 1;
               break;
           }
       }
       //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
       if($testpouvoirviolet == 0)
       {
           $nombreviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 2", true ));
           if ($nombreviolet >= 1)
            {
           $nombretrianglenonviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 2 AND `card_type_arg` = 1", true ));
           $nombrecarrenonviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 2 AND `card_type_arg` = 2", true ));
           $nombrerondnonviolet = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 2 AND `card_type_arg` = 3", true ));
           $calcul = $nombreviolet + floor($nombrecarrenonviolet/3) + floor($nombretrianglenonviolet/3) + floor($nombrerondnonviolet/3); 
           if ($calcul >= 3)
           {
                //$ret["selectable"][] = 'card_2_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_2_'.$this->player_id.'_1';
           }
           if ($calcul >= 4)
            {
                $ret["selectable"][] = 'materiacard_2_'.$this->player_id.'_2';
            }

            if ($calcul >= 5)
            {
                $ret["selectable"][] = 'materiacard_2_'.$this->player_id.'_3';
            }
            }
           
       }

       ////// Test Vert //////

       ///// est ce que sort a déjà été appris?////
       $pouvoirvert = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 3", true );
       $testpouvoirvert = 0;
       foreach ($pouvoirvert as $valeur) 
       {
           if ($valeur != 0) 
           {
               $testpouvoirvert = 1;
               break;
           }
       }
       //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
       if($testpouvoirvert == 0)
       {
           $nombrevert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 3", true ));
           if ($nombrevert >= 1)
            {
           $nombretrianglenonvert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 3 AND `card_type_arg` = 1", true ));
           $nombrecarrenonvert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 3 AND `card_type_arg` = 2", true ));
           $nombrerondnonvert = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 3 AND `card_type_arg` = 3", true ));
           $calcul = $nombrevert + floor($nombrecarrenonvert/3) + floor($nombretrianglenonvert/3) + floor($nombrerondnonvert/3); 
           if ($calcul >= 3)
           {
                //$ret["selectable"][] = 'card_3_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_3_'.$this->player_id.'_1';
           }
           if ($calcul >= 4)
            {
                $ret["selectable"][] = 'materiacard_3_'.$this->player_id.'_2';
            }

            if ($calcul >= 5)
            {
                $ret["selectable"][] = 'materiacard_3_'.$this->player_id.'_3';
            }
            }
           
       }

       ////// Test Noir //////

       ///// est ce que sort a déjà été appris?////
       $pouvoirnoir = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 4", true );
       $testpouvoirnoir = 0;
       foreach ($pouvoirnoir as $valeur) 
       {
           if ($valeur != 0) 
           {
               $testpouvoirnoir = 1;
               break;
           }
       }
       //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
       if($testpouvoirnoir == 0)
       {
           $nombrenoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 4", true ));
           if ($nombrenoir >= 1)
            {
           $nombretrianglenonnoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 4 AND `card_type_arg` = 1", true ));
           $nombrecarrenonnoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 4 AND `card_type_arg` = 2", true ));
           $nombrerondnonnoir = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 4 AND `card_type_arg` = 3", true ));
           $calcul = $nombrenoir + floor($nombrecarrenonnoir/3) + floor($nombretrianglenonnoir/3) + floor($nombrerondnonnoir/3); 
           if ($calcul >= 3)
           {
                //$ret["selectable"][] = 'card_4_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_4_'.$this->player_id.'_1';
           }
           if ($calcul >= 4)
            {
                $ret["selectable"][] = 'materiacard_4_'.$this->player_id.'_2';
            }

            if ($calcul >= 5)
            {
                $ret["selectable"][] = 'materiacard_4_'.$this->player_id.'_3';
            }
            }
           
       }


       ////// Test Blanc //////

       ///// est ce que sort a déjà été appris?////
       $pouvoirblanc = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 5", true );
       $testpouvoirblanc = 0;
       foreach ($pouvoirblanc as $valeur) 
       {
           if ($valeur != 0) 
           {
               $testpouvoirblanc = 1;
               break;
           }
       }
       //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
       if($testpouvoirblanc == 0)
       {
           $nombreblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 5", true ));
           if ($nombreblanc >= 1)
            {
           $nombretrianglenonblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 5 AND `card_type_arg` = 1", true ));
           $nombrecarrenonblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 5 AND `card_type_arg` = 2", true ));
           $nombrerondnonblanc = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 5 AND `card_type_arg` = 3", true ));
           $calcul = $nombreblanc + floor($nombrecarrenonblanc/3) + floor($nombretrianglenonblanc/3) + floor($nombrerondnonblanc/3); 
           if ($calcul >= 3)
           {
                //$ret["selectable"][] = 'card_5_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_5_'.$this->player_id.'_1';
           }
           if ($calcul >= 4)
            {
                $ret["selectable"][] = 'materiacard_5_'.$this->player_id.'_2';
            }

            if ($calcul >= 5)
            {
                $ret["selectable"][] = 'materiacard_5_'.$this->player_id.'_3';
            }
            }
           
       }


       ////// Test Bleu //////

       ///// est ce que sort a déjà été appris?////
       $pouvoirbleu = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 6", true );
       $testpouvoirbleu = 0;
       foreach ($pouvoirbleu as $valeur) 
       {
           if ($valeur != 0) 
           {
               $testpouvoirbleu = 1;
               break;
           }
       }
       //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
       if($testpouvoirbleu == 0)
       {
           $nombrebleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 6", true ));
           if ($nombrebleu >= 1)
            {
           $nombretrianglenonbleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 6 AND `card_type_arg` = 1", true ));
           $nombrecarrenonbleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 6 AND `card_type_arg` = 2", true ));
           $nombrerondnonbleu = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 6 AND `card_type_arg` = 3", true ));
           $calcul = $nombrebleu + floor($nombrecarrenonbleu/3) + floor($nombretrianglenonbleu/3) + floor($nombrerondnonbleu/3); 
           if ($calcul >= 3)
           {
                //$ret["selectable"][] = 'card_6_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_6_'.$this->player_id.'_1';
           }
           if ($calcul >= 4)
            {
                $ret["selectable"][] = 'materiacard_6_'.$this->player_id.'_2';
            }

            if ($calcul >= 5)
            {
                $ret["selectable"][] = 'materiacard_6_'.$this->player_id.'_3';
            }
            }
          
       }

       ////// Test Jaune //////

       ///// est ce que sort a déjà été appris?////
       $pouvoirjaune = self::getObjectListFromDB( "SELECT `typerune` FROM `cards` WHERE `player_id` = {$this->player_id} AND `set_color` = 7", true );
       $testpouvoirjaune = 0;
       foreach ($pouvoirjaune as $valeur) 
       {
           if ($valeur != 0) 
           {
               $testpouvoirjaune = 1;
               break;
           }
       }
       //// est ce qu'au moins le pouvoir de niveau 3 peut etre appris?/////
       if($testpouvoirjaune == 0)
       {
           $nombrejaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = 7", true ));
           if ($nombrejaune >= 1)
            {
           $nombretrianglenonjaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 7 AND `card_type_arg` = 1", true ));
           $nombrecarrenonjaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 7 AND `card_type_arg` = 2", true ));
           $nombrerondnonjaune = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != 7 AND `card_type_arg` = 3", true ));
           $calcul = $nombrejaune + floor($nombrecarrenonjaune/3) + floor($nombretrianglenonjaune/3) + floor($nombrerondnonjaune/3); 
           if ($calcul >= 3)
           {
                //$ret["selectable"][] = 'card_7_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_7_'.$this->player_id.'_1';
           }
           if ($calcul >= 4)
            {
                $ret["selectable"][] = 'materiacard_7_'.$this->player_id.'_2';
            }

            if ($calcul >= 5)
            {
                $ret["selectable"][] = 'materiacard_7_'.$this->player_id.'_3';
            }
            }
          
       }

       $ret['buttons'][]='cancel';

                       
        return $ret;
    }

    function SoirLearn1($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
        }

        else
        {
            $explode = explode("_", $varg1);
            
            spellbook::$instance->setGameStateValue('color', $explode[1]);
            spellbook::$instance->setGameStateValue('position', $explode[3]);
            spellbook::$instance->addPending($this->player_id, "SoirLearn3", $varg1);
        }


    }


    function argSoirLearn2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} learns a spell');
        $ret['titleyou'] = clienttranslate('${you} must choose the learning level');

        $explode = explode("_", $parg1);
        $color = intval($explode[1]);

        /////////// test niveau possible //////
        $reserve = 'materiareserve_'.$this->player_id;
        $nombre = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` = {$color}", true ));
        $nombretriangle = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != {$color} AND `card_type_arg` = 1", true ));
        $nombrecarre = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != {$color} AND `card_type_arg` = 2", true ));
        $nombrerond = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = '{$reserve}' AND `card_type` != {$color} AND `card_type_arg` = 3", true ));
        $calcul = $nombre + floor($nombrecarre/3) + floor($nombretriangle/3) + floor($nombrerond/3);

        $ret["selectable"][] = 'materiacard_'.$color.'_'.$this->player_id.'_1';

        if ($calcul >= 4)
        {
            $ret["selectable"][] = 'materiacard_'.$color.'_'.$this->player_id.'_2';
        }

        if ($calcul >= 5)
        {
            $ret["selectable"][] = 'materiacard_'.$color.'_'.$this->player_id.'_3';
        }



        $ret['buttons'][]='cancel';

                       
        return $ret;
    }

    function SoirLearn2($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
        }

        else
        {
            $explode = explode("_", $varg1);
            
            spellbook::$instance->setGameStateValue('color', $explode[1]);
            spellbook::$instance->setGameStateValue('position', $explode[3]);


            spellbook::$instance->addPending($this->player_id, "SoirLearn3", $varg1);
        }


    }

    function argSoirLearn3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectable2"] = array();
        $ret["selected"] = array();
        $ret["selected2"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} learns a spell');

        if($parg2 !== 'error')
        {
        $ret['titleyou'] = clienttranslate('${you} can change/complete the selection of Materia to use to learn this spell');
        }

        if($parg2 === 'error')
        {
        $ret['titleyou'] = clienttranslate('Your selection is not compliant. Please do it again.');
        }

        $location = 'materiareserve_'.$this->player_id;
        $explode = explode("_", $parg1);
        $color = intval ($explode[1]);
        $lvl = intval ($explode[3]);
        $selectable = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}' AND `card_type` !={$color}", true );
        
        $selected = self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` ='{$location}' AND `card_type` ={$color}", true );
        $countselected = count($selected);

        foreach ($selectable as $materia)
        {
            $ret["selectable2"][] = 'materia_'.$materia;
        }

        if ($countselected <= $lvl +2)
        {
        foreach ($selected as $m)
        {
            $ret["selected2"][] = 'materia_'.$m;
        }
        }

        if ($countselected > $lvl +2)
        {
            $nbre = 0;
        foreach ($selected as $m)
        {
            $nbre = $nbre+1;
            if ($nbre <= $lvl +2)
            {
                $ret["selected2"][] = 'materia_'.$m;
            }

            if ($nbre > $lvl +2)
            {
                $ret["selectable2"][] = 'materia_'.$m;
            }


            
        }
        }
        
        
        $ret["selected"][] = $parg1;

        if ($lvl == 1)
        {
        $ret['buttons'][]='validateselectionsoir13';
        }
        if ($lvl == 2)
        {
        $ret['buttons'][]='validateselectionsoir14';
        }
        if ($lvl == 3)
        {
        $ret['buttons'][]='validateselectionsoir15';
        }

        $ret['buttons'][]='cancel';

                       
        return $ret;
    }

    function SoirLearn3($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
        }

        
    }

    function argSoirLearn4($parg1, $parg2)  /// controle de la selection
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');
                               
        return $ret;
    }

    function SoirLearn4($parg1, $parg2, $varg1, $varg2)     /// controle de la selection
    {
        $test =0;
        $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');
        $explode = explode('_', $parg1);
        $tableau = array_map('intval', $explode); //pour transformer les string du tableau en entier et recreer un nouveau tableau

        /// enlever les zero du tableau
        $tableausanszero = array_filter($tableau, function($valeur) {
            return $valeur != 0;
        });

        // Réindexer le tableau pour réorganiser les clés
        $tableausanszero = array_values($tableausanszero);

        /// test si le nombre de materia utilisé correspond à l'attendu possible
        $count = count($tableausanszero);
                
        if ((spellbook::$instance->getGameStateValue('position') == '1') && ($count !=3) && ($count !=5) &&($count !=7))
        {
            $test =1;
            spellbook::$instance->addPending($this->player_id, "SoirLearn3", $selected, 'error');
        }

        if ((spellbook::$instance->getGameStateValue('position') == '2') && ($count !=4) && ($count !=6) && ($count !=8))
        {
            $test =1;
            spellbook::$instance->addPending($this->player_id, "SoirLearn3", $selected, 'error');
        }

        if ((spellbook::$instance->getGameStateValue('position') == '3') && ($count !=5) && ($count !=7) && ($count !=9))
        {
            $test =1;
            spellbook::$instance->addPending($this->player_id, "SoirLearn3", $selected, 'error');
        }

        if ($test == 0)
        {

        ///// on va tester maintenant si la combinaison est ok

        $nombre = 0;
        $nombretriangle = 0;
        $nombrecarre = 0;
        $nombrerond = 0;

        foreach ($tableausanszero as $id)
        {
            $testcolor = intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id` = {$id}"));
            if($testcolor == spellbook::$instance->getGameStateValue('color'))
            {
                $nombre = $nombre +1; 
            }
            
            else
            {
                $testrune = intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id` = {$id}"));
                
                if ($testrune == 1)
                {
                    $nombretriangle = $nombretriangle +1;
                }
                if ($testrune == 2)
                {
                    $nombrecarre = $nombrecarre +1;
                }
                if ($testrune == 3)
                {
                    $nombrerond = $nombrerond +1;
                    
                }
            }

        }

        

        $calcul = $nombre + floor($nombrecarre/3) + floor($nombretriangle/3) + floor($nombrerond/3);

        if (((spellbook::$instance->getGameStateValue('position') == '1') && ($calcul == 3) && ($nombre >=1) && !(($nombre == 3) && ($nombretriangle+$nombrecarre+$nombrerond>0))) || ((spellbook::$instance->getGameStateValue('position') == '2') && ($calcul == 4) && ($nombre >=1) && !(($nombre == 4) && ($nombretriangle+$nombrecarre+$nombrerond>0))) || ((spellbook::$instance->getGameStateValue('position') == '3') && ($calcul == 5) && ($nombre >=1) && !(($nombre == 5) && ($nombretriangle+$nombrecarre+$nombrerond>0))))
        {
            spellbook::$instance->addPending($this->player_id, "SoirLearn5", $parg1);
        }

        else
        {
            spellbook::$instance->addPending($this->player_id, "SoirLearn3", $selected, 'error');
        }
         

        }
    }

    function argSoirLearn5($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} learns a spell');
        $ret['titleyou'] = clienttranslate('${you} must select the Materia to place on the card');

        $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');
        $ret["selected"][] = $selected;

        $explode = explode('_', $parg1);
        $tableau = array_map('intval', $explode);
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

        foreach($tableausanszero as $materia)
        {
            $testcolor = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id` = {$materia}");
            if($testcolor == spellbook::$instance->getGameStateValue('color'))
            {
                $ret["selectable"][] = 'materia_'.$materia;
            }
            
        }

        
        $ret['buttons'][]='cancel';


                
        return $ret;
    }

    function SoirLearn5($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');
            spellbook::$instance->addPending($this->player_id, "SoirLearn3", $selected);
        }

        else
        {
            spellbook::$instance->addPending($this->player_id, "SoirConfirm", $parg1, $varg1);
        }
    
    }

    function argSoirConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} learns a spell');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice');

        $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');
        $ret["selected"][] = $selected;
        $ret["selected"][] = $parg2;

        $explode = explode('_', $parg1);
        $tableau = array_map('intval', $explode);
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
        $ret['buttons'][]='cancel';*/


                
        return $ret;
    }

    function SoirConfirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "SoirLearn5", $parg1);
        }

        if($varg1 == "confirm")
        {*/
            $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');

            $setcardjaune = intval(self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = 7 AND `power` = 3"));
            $familier = 'materiafamilier_'.$this->player_id;
            $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));

            if (((spellbook::$instance->getGameStateValue('color')==7)&&(spellbook::$instance->getGameStateValue('position')==3)&&($setcardjaune==3)&&($countfamilier <=13))||(($this->player_p37 == 1)&&($countfamilier <=13)))
            {
                self::DbQuery( "INSERT INTO `bonuslearn` (`player_id`, `materiacard`, `listeselected`, `placeoncard`) VALUES ({$this->player_id}, '{$selected}', '{$parg1}', '{$parg2}')" );
                spellbook::$instance->addPending($this->player_id, "SoirLearnBonus", $parg1);
            }
        
            else
            {

            $explode = explode('_', $parg1);
            $tableau = array_map('intval', $explode);
            /// enlever les zero du tableau
            $tableausanszero = array_filter($tableau, function($valeur) {
                return $valeur != 0;
            });
            // Réindexer le tableau pour réorganiser les clés
            $tableausanszero = array_values($tableausanszero);

            $explode2 = explode('_', $parg2);
            $idselected = intval($explode2[1]);
            $runeselected = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idselected}");
            $location = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id;
            $emplacement = spellbook::$instance->getGameStateValue('position');
            $power = intval(spellbook::$instance->getGameStateValue('position')) +2;
            $color = spellbook::$instance->getGameStateValue('color');

            spellbook::$instance->materia->moveCard( $idselected, $location, $emplacement);
            self::DbQuery( "UPDATE `cards` set `typerune` = {$runeselected} WHERE `set_color` = '{$color}' AND `power` = {$power} AND `player_id` = {$this->player_id}" );

            $set = intval(self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `power` = {$power} AND `set_color` = '{$color}' AND `player_id` = {$this->player_id}"));
            $index = $set.$color;

            spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} learns "${name}" level ${power}' ), array(
                'i18n' => array( 'name' ),
                'mobile' =>  $parg2,
                'parent' => $selected,
                'player_name' => $this->player_name,
                'name' => spellbook::$instance->listecards[$index]['name'],
                'power'=> $power,
                )
                );

            foreach ($tableausanszero as $iddiscard)
            {
                if ($iddiscard != $idselected)
                {
                    
                    spellbook::$instance->materia->moveCard( $iddiscard, 'discard');
                    spellbook::$instance->notifyAllPlayers('discard','', array(
                        'mobile' =>  $iddiscard,
                        )
                        );
                }
            }

            spellbook::$instance->CalculPv();

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

            ///// test immediat ou permanent

            $type = intval(self::getUniqueValueFromDB("SELECT `type` FROM `cards` WHERE `power` = {$power} AND `set_color` = '{$color}' AND `player_id` = {$this->player_id}"));
            if(($type == 1)||($type == 2))
            {
                $set = intval(self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `power` = {$power} AND `set_color` = '{$color}' AND `player_id` = {$this->player_id}"));
                spellbook::$instance->addPendingTarget($this->player_id, "Card".$set.$color, "Power".$power);
            }
            else
            {
                spellbook::$instance->addPending($this->player_id, "Autel");
            }

            }

        //}
    
    }

    function argSoirCardAction($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select a card');

        $color = self::getObjectListFromDB( "SELECT `set_color` FROM `cards` WHERE `player_id` ={$this->player_id} AND `typerune` !=0 AND `jour` = 3 AND `type` = 0", true );
        
        foreach ($color as $card)
        {
            $ret["selectable"][] = "card_".$card."_".$this->player_id;
        }
             
        $ret['buttons'][]='cancel';

        return $ret;
    }

    function SoirCardAction($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
        }
        else
        {
            $explode = explode("_", $varg1);
            $color = intval($explode[1]);
            $set = self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `player_id`={$this->player_id} AND `set_color` = {$color} AND `typerune` !=0");

            spellbook::$instance->addPendingTarget($this->player_id, "Card".$set.$color, "init", $varg1);
        }

    }

    function argSoirLearnBonus($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} learns a spell');
        

        $familier = 'materiafamilier_'.$this->player_id;
        $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));

        $selected = self::getUniqueValueFromDB("SELECT `placeoncard` FROM `bonuslearn` WHERE `player_id`={$this->player_id}");
        $explode2 = explode('_', $selected);
        $idselected = $explode2[1];

        $explode = explode('_', $parg1);
        $tableau = array_map('intval', $explode);
        /// enlever les zero du tableau
        $tableausanszero = array_filter($tableau, function($valeur) {
            return $valeur != 0;
        });
        // Réindexer le tableau pour réorganiser les clés
        $tableausanszero = array_values($tableausanszero);

        if ($countfamilier <= 12)
        {
        $ret['titleyou'] = clienttranslate('${you} must select 2 Materia to store (Bonus "COMMUNION")');
        foreach($tableausanszero as $materiaselected)
        {
            if ($materiaselected != $idselected)
            {
            $ret["selectablemulti"][] = 'materia_'.$materiaselected;
            }
        }
        
        $ret['buttons'][]='validatebonuslearn';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} must select 1 Materia to store (Bonus "COMMUNION")');
            foreach($tableausanszero as $materiaselected)
        {
            if ($materiaselected != $idselected)
            {
            $ret["selectable"][] = 'materia_'.$materiaselected;
            }
        }
        }
        $ret['buttons'][]='cancel';

        return $ret;
    }

    function SoirLearnBonus($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            self::DbQuery("DELETE FROM `bonuslearn` WHERE `player_id` = {$this->player_id}");
            spellbook::$instance->addPending($this->player_id, "SoirLearn5", $parg1);
        }

        else
        {
            $log = array();
            $explode1 = explode("_", $varg1);
            $idstore1 = intval($explode1[1]);
            $familier = 'materiafamilier_'.$this->player_id;

            $listeselected = self::getUniqueValueFromDB("SELECT `listeselected` FROM `bonuslearn` WHERE `player_id`={$this->player_id}");
            $explode = explode('_', $listeselected);
            $tableau = array_map('intval', $explode);
            /// enlever les zero du tableau
            $tableausanszero = array_filter($tableau, function($valeur) {
                return $valeur != 0;
            });
            // Réindexer le tableau pour réorganiser les clés
            $tableausanszero = array_values($tableausanszero);

            $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');
            $placeoncard = self::getUniqueValueFromDB("SELECT `placeoncard` FROM `bonuslearn` WHERE `player_id`={$this->player_id}");
            $explode2 = explode('_', $placeoncard);
            $idselected = intval($explode2[1]);
            $runeselected = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idselected}");
            $location = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id;
            $emplacement = spellbook::$instance->getGameStateValue('position');
            $power = intval(spellbook::$instance->getGameStateValue('position')) +2;
            $color = spellbook::$instance->getGameStateValue('color');

            spellbook::$instance->materia->moveCard( $idselected, $location, $emplacement);
            self::DbQuery( "UPDATE `cards` set `typerune` = {$runeselected} WHERE `set_color` = '{$color}' AND `power` = {$power} AND `player_id` = {$this->player_id}" );

            $set = intval(self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `power` = {$power} AND `set_color` = '{$color}' AND `player_id` = {$this->player_id}"));
            $index = $set.$color;

            spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} learns "${name}" level ${power}' ), array(
                'i18n' => array( 'name' ),
                'mobile' =>  $placeoncard,
                'parent' => $selected,
                'player_name' => $this->player_name,
                'name' => spellbook::$instance->listecards[$index]['name'],
                'power'=> $power,
                )
                );

            foreach ($tableausanszero as $iddiscard)
            {
                if (($iddiscard != $idselected)&&($iddiscard != $idstore1))
                {
                    
                    spellbook::$instance->materia->moveCard( $iddiscard, 'discard');
                    spellbook::$instance->notifyAllPlayers('discard','', array(
                        'mobile' =>  $iddiscard,
                        )
                        );
                }
            }


            
            $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
            
                $nouvelemplacementfamilier = $countfamilier +1;
                spellbook::$instance->materia->moveCard( $explode1[1], $familier, $nouvelemplacementfamilier);
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$explode1[1],
                    'parent' => $familier.'_'.$nouvelemplacementfamilier,
                    'player_name' => $this->player_name,
                    )
                    );

            $col= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idstore1}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idstore1}"));
            $log[] = ($col*10)+$signe;
    
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} stores ${log1} ("COMMUNION")'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                
                                
                )
                );

            spellbook::$instance->CalculPv();

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

            self::DbQuery("DELETE FROM `bonuslearn` WHERE `player_id` = {$this->player_id}");


            $type = intval(self::getUniqueValueFromDB("SELECT `type` FROM `cards` WHERE `power` = {$power} AND `set_color` = '{$color}' AND `player_id` = {$this->player_id}"));
            if(($type == 1)||($type == 2))
            {
                $set = intval(self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `power` = {$power} AND `set_color` = '{$color}' AND `player_id` = {$this->player_id}"));
                spellbook::$instance->addPendingTarget($this->player_id, "Card".$set.$color, "Power".$power);
            }
            else
            {
                spellbook::$instance->addPending($this->player_id, "Autel");
            }


        }
        

    }

    function argConfirmBonusLearn($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} learns a spell');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Learn Spell and Store Materia)');

        $explode = explode("_", $parg1);
        foreach ($explode as $id)
        {
            $ret["selected3"][] = 'materia_'.$id;
        }
        

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/       
        

        return $ret;
    }

    function ConfirmBonusLearn($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            $selected = self::getUniqueValueFromDB("SELECT `listeselected` FROM `bonuslearn` WHERE `player_id`={$this->player_id}");
            self::DbQuery("DELETE FROM `bonuslearn` WHERE `player_id` = {$this->player_id}");
            spellbook::$instance->addPending($this->player_id, "SoirLearn5", $selected);
        }

        if($varg1 == "confirm")
        {*/
            $log = array();
            $explode1 = explode("_", $parg1);
            $idstore1 = intval($explode1[0]);
            $idstore2 = intval($explode1[1]);
            $familier = 'materiafamilier_'.$this->player_id;

            $listeselected = self::getUniqueValueFromDB("SELECT `listeselected` FROM `bonuslearn` WHERE `player_id`={$this->player_id}");
            $explode = explode('_', $listeselected);
            $tableau = array_map('intval', $explode);
            /// enlever les zero du tableau
            $tableausanszero = array_filter($tableau, function($valeur) {
                return $valeur != 0;
            });
            // Réindexer le tableau pour réorganiser les clés
            $tableausanszero = array_values($tableausanszero);

            $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');
            $placeoncard = self::getUniqueValueFromDB("SELECT `placeoncard` FROM `bonuslearn` WHERE `player_id`={$this->player_id}");
            $explode2 = explode('_', $placeoncard);
            $idselected = intval($explode2[1]);
            $runeselected = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idselected}");
            $location = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id;
            $emplacement = spellbook::$instance->getGameStateValue('position');
            $power = intval(spellbook::$instance->getGameStateValue('position')) +2;
            $color = spellbook::$instance->getGameStateValue('color');

            spellbook::$instance->materia->moveCard( $idselected, $location, $emplacement);
            self::DbQuery( "UPDATE `cards` set `typerune` = {$runeselected} WHERE `set_color` = '{$color}' AND `power` = {$power} AND `player_id` = {$this->player_id}" );

            $set = intval(self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `power` = {$power} AND `set_color` = '{$color}' AND `player_id` = {$this->player_id}"));
            $index = $set.$color;

            spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} learns "${name}" level ${power}' ), array(
                'i18n' => array( 'name' ),
                'mobile' =>  $placeoncard,
                'parent' => $selected,
                'player_name' => $this->player_name,
                'name' => spellbook::$instance->listecards[$index]['name'],
                'power'=> $power,
                )
                );

            foreach ($tableausanszero as $iddiscard)
            {
                if (($iddiscard != $idselected)&&($iddiscard != $idstore1)&&($iddiscard != $idstore2))
                {
                    
                    spellbook::$instance->materia->moveCard( $iddiscard, 'discard');
                    spellbook::$instance->notifyAllPlayers('discard','', array(
                        'mobile' =>  $iddiscard,
                        )
                        );
                }
            }


            
            $countfamilier = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` ='{$familier}'", true ));
            for ($i=1; $i <= 2; $i++)
            {
                $nouvelemplacementfamilier = $countfamilier +$i;
                spellbook::$instance->materia->moveCard( $explode1[$i-1], $familier, $nouvelemplacementfamilier);
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$explode1[$i-1],
                    'parent' => $familier.'_'.$nouvelemplacementfamilier,
                    'player_name' => $this->player_name,
                    )
                    );
            }

            $col1= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idstore1}"));
            $signe1= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idstore1}"));
            $log[] = ($col1*10)+$signe1;
            $col2= intval(self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_id`={$idstore2}"));
            $signe2= intval(self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_id`={$idstore2}"));
            $log[] = ($col2*10)+$signe2;
    
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} stores ${log1} ${log2} ("COMMUNION")'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                'log2' => spellbook::$instance->getLogsType($log[1]),
                
                                
                )
                );


            spellbook::$instance->CalculPv();

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

            self::DbQuery("DELETE FROM `bonuslearn` WHERE `player_id` = {$this->player_id}");


            $type = intval(self::getUniqueValueFromDB("SELECT `type` FROM `cards` WHERE `power` = {$power} AND `set_color` = '{$color}' AND `player_id` = {$this->player_id}"));
            if(($type == 1)||($type == 2))
            {
                $set = intval(self::getUniqueValueFromDB("SELECT `set_id` FROM `cards` WHERE `power` = {$power} AND `set_color` = '{$color}' AND `player_id` = {$this->player_id}"));
                spellbook::$instance->addPendingTarget($this->player_id, "Card".$set.$color, "Power".$power);
            }
            else
            {
                spellbook::$instance->addPending($this->player_id, "Autel");
            }




            
        //}
        

    }

////////////////////////////////////////////////////    
//                _       _ 
//     /\        | |     | |
//    /  \  _   _| |_ ___| |
//   / /\ \| | | | __/ _ \ |
//  / ____ \ |_| | ||  __/ |
// /_/    \_\__,_|\__\___|_|
//   
////////////////////////////////////////////////////  

function argAutel($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');
                        
        return $ret;
    }

function Autel($parg1, $parg2, $varg1, $varg2)
{
    spellbook::$instance->setGameStateValue('idclone', 0);

    if (spellbook::$instance->getGameStateValue('solo')==0)
    {       
    $nbre = count(self::getObjectListFromDB( "SELECT `card_id` `id` FROM `materia` WHERE `card_location` = 'materiaautel'", true ));    
    if ($nbre < 5)
    {
        for($i = $nbre+1; $i <=5; $i++)
        {
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiaautel', $i);
            $id = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$i}");
            $color = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$i}");
            $rune = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$i}");
            spellbook::$instance->notifyAllPlayers('draw','', array(
                'id' => $id,
                'color' => $color,
                'rune' => $rune,
                'location' =>  'materiaautel',
                'emplacement' => $i,
                )
                );

        }
    }
    
    if (($nbre >=5) && ($nbre <=9))
    {
            $emplacement = $nbre+1;
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiaautel', $emplacement);
            $id = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$emplacement}");
            $color = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$emplacement}");
            $rune = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$emplacement}");
            spellbook::$instance->notifyAllPlayers('draw','', array(
                'id' => $id,
                'color' => $color,
                'rune' => $rune,
                'location' =>  'materiaautel',
                'emplacement' => $emplacement,
                )
                );

    }

    if ($nbre >=10)
    {
            
        for($i = 1; $i <=$nbre; $i++)
        {
            $ids = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel'", true );
            foreach($ids as $idmateriadiscard)
            {
                spellbook::$instance->materia->moveCard( $idmateriadiscard, 'discard');
                spellbook::$instance->notifyAllPlayers('discard','', array(
                    'mobile' => $idmateriadiscard,
                    )
                    );

            }
        }

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );
            
        for($j=1 ; $j<=5; $j++)
        {
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiaautel', $j);
            
            $id = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$j}");
            $color = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$j}");
            $rune = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$j}");
            spellbook::$instance->notifyAllPlayers('draw','', array(
                'id' => $id,
                'color' => $color,
                'rune' => $rune,
                'location' =>  'materiaautel',
                'emplacement' => $j,
                )
                );

        }
    }


    spellbook::$instance->notifyAllPlayers('message',clienttranslate( 'Altar update' ), array()); 
    spellbook::$instance->giveExtraTime($this->player_id);
    spellbook::$instance->EndGame($this->player_id);
    spellbook::$instance->addPendingFirst($this->player_id, "Matin");

    }

    if (spellbook::$instance->getGameStateValue('solo')==1)
    {
        spellbook::$instance->addPending($this->player_id, "SoloAutel");
    }
 
}


////////////////////////////////////////////////////    
//   _____       _       
//  / ____|     | |      
// | (___   ___ | | ___  
//  \___ \ / _ \| |/ _ \ 
//  ____) | (_) | | (_) |
// |_____/ \___/|_|\___/ 
//                     
//////////////////////////////////////////////////// 

function argSoloInit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose a handicap');
        $ret['titleyou'] = clienttranslate('${you} must choose the handicap level');

        $ret['buttons'][]='0';
        $ret['buttons'][]='1';
        $ret['buttons'][]='2';
        $ret['buttons'][]='3';
                        
        return $ret;
    }

function SoloInit($parg1, $parg2, $varg1, $varg2)
{
        spellbook::$instance->addPending($this->player_id, "SoloInitConfirm", $varg1);
}

function argSoloInitConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose a handicap');
        

        if($parg1 == "0")
        {
            $ret['titleyou'] = clienttranslate('${you} must confirm your choice (No handicap)');
        }

        if($parg1 == "1")
        {
            $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Handicap level 1)');
        }

        if($parg1 == "2")
        {
            $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Handicap level 2)');
        }

        if($parg1 == "3")
        {
            $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Handicap level 3)');
        }

        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/
                        
        return $ret;
    }

function SoloInitConfirm($parg1, $parg2, $varg1, $varg2)
{
    /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "SoloInit");
        }
    if($varg1 == "confirm")
    {*/
        
        if($parg1 == "1")
        {
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiareserveopponent', 1);
                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 1");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 1");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 1");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  'materiareserveopponent',
                    'emplacement' => 1,
                    )
                    );
            

        }

        if($parg1 == "2")
        {
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiareserveopponent', 1);
                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 1");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 1");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 1");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  'materiareserveopponent',
                    'emplacement' => 1,
                    )
                    );
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiareserveopponent', 2);
            $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 2");
            $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 2");
            $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 2");
            spellbook::$instance->notifyAllPlayers('draw','', array(
                'id' => $idmateria1,
                'color' => $colormateria1,
                'rune' => $runemateria1,
                'location' =>  'materiareserveopponent',
                'emplacement' => 2,
                )
                );
            

        }

        if($parg1 == "3")
        {
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiareserveopponent', 1);
                $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 1");
                $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 1");
                $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 1");
                spellbook::$instance->notifyAllPlayers('draw','', array(
                    'id' => $idmateria1,
                    'color' => $colormateria1,
                    'rune' => $runemateria1,
                    'location' =>  'materiareserveopponent',
                    'emplacement' => 1,
                    )
                    );
            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiareserveopponent', 2);
            $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 2");
            $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 2");
            $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 2");
            spellbook::$instance->notifyAllPlayers('draw','', array(
                'id' => $idmateria1,
                'color' => $colormateria1,
                'rune' => $runemateria1,
                'location' =>  'materiareserveopponent',
                'emplacement' => 2,
                )
                );

            spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiareserveopponent', 3);
            $idmateria1 = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 3");
            $colormateria1 = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 3");
            $runemateria1 = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiareserveopponent' AND `card_location_arg` = 3");
            spellbook::$instance->notifyAllPlayers('draw','', array(
                'id' => $idmateria1,
                'color' => $colormateria1,
                'rune' => $runemateria1,
                'location' =>  'materiareserveopponent',
                'emplacement' => 3,
                )
                );
            

        }
        
        spellbook::$instance->CalculPv();
        spellbook::$instance->addPendingFirst($this->player_id, "Matin");
    //}
    
        
}

function argSoloAutel($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must select a Materia for the opponent\'s board');
        $ret['titleyou'] = clienttranslate('${you} must select a Materia for the opponent\'s board');

        $ids = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel'", true );
            foreach($ids as $idmateria)
            {
                $ret["selectable"][] = 'materia_'.$idmateria;

            }
                        
        return $ret;
    }

function SoloAutel($parg1, $parg2, $varg1, $varg2)
{
    spellbook::$instance->addPending($this->player_id, "SoloAutelConfirm", $varg1);
}

function argSoloAutelConfirm($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected3"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must select a Materia for the opponent\'s board');
        $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia for opponent');

        
        $ret["selected3"][] = $parg1;
        
        
        /*$ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';*/
                        
        return $ret;
    }

function SoloAutelConfirm($parg1, $parg2, $varg1, $varg2)
{
    /*if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "SoloAutel");
        }

    if($varg1 == "confirm")
        {*/
            $nbreopponent = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaopponent'", true ));
            $emplacementopponent = $nbreopponent+1;
            $explode = explode("_", $parg1);
            $idmateria = intval($explode[1]);

            spellbook::$instance->materia->moveCard( $idmateria, 'materiaopponent', $emplacementopponent);
        

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $parg1,
                'parent' => 'materiaopponent_'.$emplacementopponent,
                'player_name' => $this->player_name,
                )
                );

            spellbook::$instance->AutelReorganisation();

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

            $nbre = count(self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel'", true ));

            if (($nbre < 7)&&($emplacementopponent!=4)&&($emplacementopponent!=9)&&($emplacementopponent!=14))
            {
                for($i = $nbre+1; $i <=7; $i++)
                {
                    spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiaautel', $i);
                    $id = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$i}");
                    $color = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$i}");
                    $rune = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$i}");
                    spellbook::$instance->notifyAllPlayers('draw','', array(
                        'id' => $id,
                        'color' => $color,
                        'rune' => $rune,
                        'location' =>  'materiaautel',
                        'emplacement' => $i,
                        )
                        );

                }

            }

            if (($nbre > 7)&&($emplacementopponent!=4)&&($emplacementopponent!=9)&&($emplacementopponent!=14))
            {
                for($i = 1; $i <=($nbre-7); $i++)
                    {
                        $id = self::getUniqueValueFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` ={$i}");
                        
                            spellbook::$instance->materia->moveCard( $id, 'discard');
                            spellbook::$instance->notifyAllPlayers('discard','', array(
                                'mobile' => $id,
                                )
                                );

                        
                    }

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

              
            }

            if (($emplacementopponent==4)||($emplacementopponent==9)||($emplacementopponent==14))
            {
                for($i = 1; $i <=$nbre; $i++)
                {
                    $ids = self::getObjectListFromDB( "SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel'", true );
                    foreach($ids as $idmateriadiscard)
                    {
                        spellbook::$instance->materia->moveCard( $idmateriadiscard, 'discard');
                        spellbook::$instance->notifyAllPlayers('discard','', array(
                            'mobile' => $idmateriadiscard,
                            )
                            );
        
                    }
                }
        
                    spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );
                    
                for($j=1 ; $j<=7; $j++)
                {
                    spellbook::$instance->materia->pickCardForLocation( 'deck', 'materiaautel', $j);
                    
                    $id = self::getUniqueValueFromDB("SELECT `card_id` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$j}");
                    $color = self::getUniqueValueFromDB("SELECT `card_type` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$j}");
                    $rune = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `materia` WHERE `card_location` = 'materiaautel' AND `card_location_arg` = {$j}");
                    spellbook::$instance->notifyAllPlayers('draw','', array(
                        'id' => $id,
                        'color' => $color,
                        'rune' => $rune,
                        'location' =>  'materiaautel',
                        'emplacement' => $j,
                        )
                        );
        
                }

            

              
            }




            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( 'Altar update' ), array()); 
            spellbook::$instance->CalculPv();
            spellbook::$instance->EndGame($this->player_id);
            spellbook::$instance->addPendingFirst($this->player_id, "Matin");
            
       // }
}













///////////////////// END OF CLASS //////////////////
        
}
