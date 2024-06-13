<?php 

class Card35 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        

        $reserve = 'materiareserve_'.$this->player_id;
        $level = intval(self::getUniqueValueFromDB("SELECT power FROM cards WHERE player_id={$this->player_id} AND set_color = 5 AND typerune !=0")); //////ATTENTION set_color
        $rune = intval(self::getUniqueValueFromDB("SELECT typerune FROM cards WHERE player_id={$this->player_id} AND set_color = 5 AND typerune !=0"));
        
        $runereserve = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = {$rune}", true ));

        //$ret["selected"][] = $parg1;
        $explode = explode("_", $parg1);
        $card = intval($explode[1]);
        $joueur = intval($explode[2]);
        
        
        if ($level >= 3)
        {
            //$ret['buttons'][] = 'level3';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_1';
        }

        if ($level >= 4)
        {
            //$ret['buttons'][] = 'level4';
            $ret["selectable"][] = 'materiacard_'.$card.'_'.$joueur.'_2';
        }

        if (($level >= 5)&&($runereserve >=1))
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
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        else
        {
            if($varg1 == NULL)
            {
                $level = spellbook::$instance->getGameStateValue('variable1');
                if ($level == 1)
                {
                    spellbook::$instance->addPendingTarget($this->player_id, "Card35", "Midi");
                }
        
                if ($level == 2)
                {
                    spellbook::$instance->addPendingTarget($this->player_id, "Card35", "Soir");
                }
        
                if ($level == 3)
                {
                    spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinDiscard");
                }
            }

            else
            {
        $explode = explode("_", $varg1);
        $level = intval($explode[3]);

        if ($level == 1)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card35", "Midi");
        }

        if ($level == 2)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card35", "Soir");
        }

        if ($level == 3)
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinDiscard");
        }
    }
    }


    }

    public function argMatinDiscard($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 1 Materia to discard');
        

        $reserve = 'materiareserve_'.$this->player_id;
        $rune = intval(self::getUniqueValueFromDB("SELECT typerune FROM cards WHERE player_id={$this->player_id} AND set_color = 5 AND typerune !=0"));
       

        $selectable = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type_arg = {$rune}", true );
        foreach ($selectable as $materia)
        {
            $ret["selectable"][] = 'materia_'.$materia;
        }
         



        $ret['buttons'][]='cancel';       
        return $ret;
     }
    
    public function MatinDiscard($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Midi"); // attention
        }

        else
        {
            $explode = explode("_", $varg1);
            $idmateria = intval($explode[1]);
            $discard = 'materiadiscard_'.$this->player_id;
            spellbook::$instance->materia->moveCard( $idmateria, $discard);

            spellbook::$instance->notifyAllPlayers('move','', array(
                'mobile' =>  $varg1,
                'parent' => $discard,
                
                )
                );

                spellbook::$instance->addPendingTarget($this->player_id, "Card35", "Matin");
        }
        


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
    $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
    

    $counttake = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true ));
    $location = 'materiareserve_'.$this->player_id;
    $countreserve = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$location}'", true ));
    
    if (spellbook::$instance->getGameStateValue('solo')==0)
    {
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


    $countcard = count(self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id !={$this->player_id} AND typerune !=0 AND jour = 1 AND type = 0", true ));
    if ($countcard >= 1)
    {
        $ret['buttons'][]='cardaction'; 
    }

    $ret['buttons'][]='cancel';


    $countbuttons = count($ret['buttons']);
    if($countbuttons == 1)
    {
        $ret['titleyou'] = clienttranslate('${you} can\'t clone morning action');
    }

    if($countbuttons > 1)
    {
        $ret['titleyou'] = clienttranslate('${you} can clone a morning action from another player');
    }

    }

    if (spellbook::$instance->getGameStateValue('solo')==1)
    {
        $test = 0;
        $countcolors = count(self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND power = 3 AND jour = 1 ", true ));
        if($countcolors >= 1)
        {
        $colors = self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND power = 3 AND jour = 1 ", true );
        foreach ($colors as $color)
        {
            $rune = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id ={$this->player_id} AND set_color = {$color}", true );
            $tableau = array_map('intval', $rune);
            if (array_values($tableau) === [0, 0, 0])
            {
                $test = 1;
            }
        }
        }

    
        
        if($test == 0)
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t clone morning spell');
            $ret['buttons'][]='cancel';
        }

        if($test == 1)
        {
            $ret['titleyou'] = clienttranslate('${you} must select the Spell to clone (level 4)');
        }
    }
    
    

    
    return $ret;
}

function Matin($parg1, $parg2, $varg1, $varg2)
{
           
    if($varg1 == "cancel")
    {
        $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $location = 'materiareserve_'.$this->player_id;
        $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
        $diff = array_diff($tableau1, $emplacement);
        $emplacementlibre = array_slice($diff, 0, 1);
        $premieremplacementlibre = $emplacementlibre[0];

        $locationdiscard = 'materiadiscard_'.$this->player_id;
        $idmateria = intval(self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$locationdiscard}'"));
        spellbook::$instance->materia->moveCard( $idmateria, $location, $premieremplacementlibre );

        spellbook::$instance->notifyAllPlayers('move','', array(
            'mobile' =>  'materia_'.$idmateria,
            'parent' => $location.'_'.$premieremplacementlibre,
            
            )
            );

        spellbook::$instance->addPending($this->player_id, "Midi");
    }

    if($varg1 == "take")
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinTake");
    }
    if(($varg1 == "draw")||($varg1 == "draw1"))
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinDraw", $varg1);
        
    }
    if($varg1 == "cardaction")
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinCardAction");
    }

    if($varg1 == NULL)
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinCardAction");
    }
}

function argMatinSupp($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
    

    $counttake = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true ));
    $location = 'materiareserve_'.$this->player_id;
    $countreserve = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$location}'", true ));

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

    $countcard = count(self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND typerune !=0 AND jour = 1 AND type = 0", true ));
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
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinTake");
    }
    if(($varg1 == "draw")||($varg1 == "draw1"))
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinDraw", $varg1);
    }
    if($varg1 == "pass")
    {
        
        spellbook::$instance->setGameStateValue('matinos', 0);
        spellbook::$instance->addPending($this->player_id, "Soir");
        
        

    }
    if($varg1 == "cardaction")
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinCardActionSupp");
        
    }
}

function argMatinTake($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
    $ret['titleyou'] = clienttranslate('${you} must select a Materia (Clone)');

    $selectable = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='materiaautel'", true );
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
        if(spellbook::$instance->getGameStateValue('matinos')==1)
        {
            
            spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinSupp");
        }
        else
        {
            $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
            $location = 'materiareserve_'.$this->player_id;
            $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
            $diff = array_diff($tableau1, $emplacement);
            $emplacementlibre = array_slice($diff, 0, 1);
            $premieremplacementlibre = $emplacementlibre[0];

            $locationdiscard = 'materiadiscard_'.$this->player_id;
            $idmateria = intval(self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$locationdiscard}'"));
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
        
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinTakeConfirm", $varg1);

    }

}

function argMatinTakeConfirm($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret["selected"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
    $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Take Materia) (Clone)');

    $ret["selected"][] = $parg1;

    /*$ret['buttons'][]='confirm';
    $ret['buttons'][]='cancel';*/


            
    return $ret;
}

function MatinTakeConfirm($parg1, $parg2, $varg1, $varg2)
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
            $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
            $diff = array_diff($tableau1, $emplacement);
            $emplacementlibre = array_slice($diff, 0, 1);
            $premieremplacementlibre = $emplacementlibre[0];

            $locationdiscard = 'materiadiscard_'.$this->player_id;
            $idmateria = intval(self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$locationdiscard}'"));
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
    $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    $location = 'materiareserve_'.$this->player_id;
    $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
    $diff = array_diff($tableau1, $emplacement);
    $emplacementlibre = array_slice($diff, 0, 1);
    $premieremplacementlibre = $emplacementlibre[0];

    $explode = explode("_", $parg1);
    $idmateria = intval($explode[1]);
    spellbook::$instance->materia->moveCard( $idmateria, $location, $premieremplacementlibre );

    $col= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateria}"));
    $signe= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateria}"));
    $log = ($col*10)+$signe;

    spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} takes ${log} (Clone)' ), array(
        'mobile' =>  $parg1,
        'parent' => $location.'_'.$premieremplacementlibre,
        'player_name' => $this->player_name,
        'log' => spellbook::$instance->getLogsType($log),
        )
        );

            spellbook::$instance->AutelReorganisation();

            spellbook::$instance->Permanent36($idmateria);

            //discard clone
            if(spellbook::$instance->getGameStateValue('matinos')==0)
            {
            $locationdiscard = 'materiadiscard_'.$this->player_id;
            $idmateriadiscardclone = intval(self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$locationdiscard}'"));
            spellbook::$instance->materia->moveCard( $idmateriadiscardclone, 'discard');
                spellbook::$instance->notifyAllPlayers('discard','', array(
                    'mobile' => $idmateriadiscardclone,
                    )
                    );

            $log = array();
            $col= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriadiscardclone}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriadiscardclone}"));
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

function argMatinDraw($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
    $ret['titleyou'] = clienttranslate('${you} must confirm your choice (Draw Materia) (Clone)');

    /*$ret['buttons'][]='confirm';
    $ret['buttons'][]='cancel';*/

    return $ret;
}

function MatinDraw($parg1, $parg2, $varg1, $varg2)
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
            $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
            $diff = array_diff($tableau1, $emplacement);
            $emplacementlibre = array_slice($diff, 0, 1);
            $premieremplacementlibre = $emplacementlibre[0];

            $locationdiscard = 'materiadiscard_'.$this->player_id;
            $idmateria = intval(self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$locationdiscard}'"));
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
    
    $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    $location = 'materiareserve_'.$this->player_id;
    $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
    $diff = array_diff($tableau1, $emplacement);
    

        if($parg1 == "draw1")
        {
            $emplacementlibre = array_slice($diff, 0, 1);
            $premieremplacementlibre = $emplacementlibre[0];

            spellbook::$instance->materia->pickCardForLocation( 'deck', $location, $premieremplacementlibre);
            $idmateria1 = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$premieremplacementlibre}");
            $colormateria1 = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$premieremplacementlibre}");
            $runemateria1 = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$premieremplacementlibre}");

            $col1= intval($colormateria1);
            $signe1= intval($runemateria1);
            $log1 = ($col1*10)+$signe1;


            spellbook::$instance->notifyAllPlayers('draw',clienttranslate( '${player_name} draws ${log1} (Clone)' ), array(
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

            $idmateria1 = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$premieremplacementlibre}");
            $colormateria1 = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$premieremplacementlibre}");
            $runemateria1 = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$premieremplacementlibre}");
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

            $idmateria2 = self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$secondemplacementlibre}");
            $colormateria2 = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$secondemplacementlibre}");
            $runemateria2 = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_location = '{$location}' AND card_location_arg = {$secondemplacementlibre}");

            $col1= intval($colormateria1);
            $signe1= intval($runemateria1);
            $log1 = ($col1*10)+$signe1;
            $col2= intval($colormateria2);
            $signe2= intval($runemateria2);
            $log2 = ($col2*10)+$signe2;

            spellbook::$instance->notifyAllPlayers('draw',clienttranslate( '${player_name} draws ${log1} ${log2} (Clone)' ), array(
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

        //discard clone
        if(spellbook::$instance->getGameStateValue('matinos')==0)
        {
        $locationdiscard = 'materiadiscard_'.$this->player_id;
        $idmateriadiscardclone = intval(self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$locationdiscard}'"));
        spellbook::$instance->materia->moveCard( $idmateriadiscardclone, 'discard');
            spellbook::$instance->notifyAllPlayers('discard','', array(
                'mobile' => $idmateriadiscardclone,
                )
                );
        
        $log = array();
        $col= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateriadiscardclone}"));
        $signe= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateriadiscardclone}"));
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

function argMatinCardAction($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
    

    if (spellbook::$instance->getGameStateValue('solo')==0)
    {
        $ret['titleyou'] = clienttranslate('${you} must select a card from another player (Clone)');

    $liste = self::getObjectListFromDB( "SELECT set_color color, player_id id FROM cards WHERE player_id !={$this->player_id} AND typerune !=0 AND jour = 1 AND type = 0");
    
    foreach ($liste as $card)
    {
        $ret["selectable"][] = "card_".$card['color']."_".$card['id'];
    }
         
    $ret['buttons'][]='cancel';
    }

    if (spellbook::$instance->getGameStateValue('solo')==1)
    {
        $ret['titleyou'] = clienttranslate('${you} must select the Spell to clone (level 4)');


        $colors = self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND power = 3 AND jour = 1 ", true );
        foreach ($colors as $color)
        {
            $rune = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id ={$this->player_id} AND set_color = {$color}", true );
            $tableau = array_map('intval', $rune);
            if (array_values($tableau) === [0, 0, 0])
            {
                $ret["selectable"][] = "card_".$color."_".$this->player_id;
            }
        }


        $ret['buttons'][]='cancel';
    }

    return $ret;
}

function MatinCardAction($parg1, $parg2, $varg1, $varg2)
{
    if($varg1 == "cancel")
    {
        $tableau1 = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $location = 'materiareserve_'.$this->player_id;
        $emplacement = self::getObjectListFromDB( "SELECT card_location_arg FROM materia WHERE card_location ='{$location}' ORDER BY card_location_arg ASC", true );
        $diff = array_diff($tableau1, $emplacement);
        $emplacementlibre = array_slice($diff, 0, 1);
        $premieremplacementlibre = $emplacementlibre[0];

        $locationdiscard = 'materiadiscard_'.$this->player_id;
        $idmateria = intval(self::getUniqueValueFromDB("SELECT card_id FROM materia WHERE card_location = '{$locationdiscard}'"));
        spellbook::$instance->materia->moveCard( $idmateria, $location, $premieremplacementlibre );

        spellbook::$instance->notifyAllPlayers('move','', array(
            'mobile' =>  'materia_'.$idmateria,
            'parent' => $location.'_'.$premieremplacementlibre,
            
            )
            );

        spellbook::$instance->addPending($this->player_id, "Midi");
    }
    else
    {
        $explode = explode("_", $varg1);
        $color = intval($explode[1]);
        $player = intval($explode[2]);
        $set = self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE player_id={$player} AND set_color = {$color} AND power = 3");

        

        spellbook::$instance->setGameStateValue('idclone', $player);
        spellbook::$instance->addPendingTarget($this->player_id, "Clonecard".$set.$color, "init", $varg1);
    }

}

function argMatinCardActionSupp($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select a card');

        $color = self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND typerune !=0 AND jour = 1 AND type = 0", true );
        
        foreach ($color as $card)
        {
            $ret["selectable"][] = "card_".$card."_".$this->player_id;
        }
             
        $ret['buttons'][]='cancel';

        return $ret;
    }

    function MatinCardActionSupp($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinSupp");
        }
        else
        {
            $explode = explode("_", $varg1);
            $color = intval($explode[1]);
            $set = self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE player_id={$this->player_id} AND set_color = {$color} AND typerune !=0");

            spellbook::$instance->setGameStateValue('idclone', $this->player_id);
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard".$set.$color, "init", $varg1);
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
    $countreserve = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$location}'", true ));
    $countfamilier = count(self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$familier}'", true ));

    if (spellbook::$instance->getGameStateValue('solo')==0)
    {
    if(($countreserve >=1)&&($countfamilier < 14))
    {
    $ret['buttons'][]='store';
    }

    $countcard = count(self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id !={$this->player_id} AND typerune !=0 AND jour = 2 AND set_color != 5", true ));
    if ($countcard >= 1)
    {
        $ret['buttons'][]='cardaction'; 
    }

    $ret['buttons'][]='cancel';
    
    $countbuttons = count($ret['buttons']);
    if($countbuttons == 1)
    {
        $ret['titleyou'] = clienttranslate('${you} can\'t clone midday action');
    }

    if($countbuttons > 1)
    {
        $ret['titleyou'] = clienttranslate('${you} can clone a midday action from another player');
    }
    }

    if (spellbook::$instance->getGameStateValue('solo')==1)
    {
        $test = 0;
        $countcolors = count(self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND power = 3 AND jour = 2 AND set_color!=5", true ));
        
        if($countcolors >= 1)
        {
        $colors = self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND power = 3 AND jour = 2 AND set_color!=5", true );
        foreach ($colors as $color)
        {
            $rune = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id ={$this->player_id} AND set_color = {$color}", true );
            $tableau = array_map('intval', $rune);
            if (array_values($tableau) === [0, 0, 0])
            {
                $test = 1;
            }
        }
        }

    
        
        if($test == 0)
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t clone midday spell');
            $ret['buttons'][]='cancel';
        }

        if($test == 1)
        {
            $ret['titleyou'] = clienttranslate('${you} must select the Spell to clone (level 4)');
        }
    }

    return $ret;
}

function Midi($parg1, $parg2, $varg1, $varg2)
{
    
    
    if($varg1 == "store")
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MidiStore");
    }
    if($varg1 == "cancel")
    {
        
            spellbook::$instance->addPending($this->player_id, "Midi");
    }

    if($varg1 == "cardaction")
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MidiCardAction");
    }

    if($varg1 == NULL)
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MidiCardAction");
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
    $selectable = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$location}'", true );
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
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MidiStoreConfirm", $varg1);
    }
  

}

function argMidiStoreConfirm($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret["selected"] = array();
    $ret['buttons'] = array();
    $ret['title'] = clienttranslate('${actplayer} takes a Materia');
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
        $countfamilier = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location ='{$familier}'", true ));
        $nouvelemplacementfamilier = $countfamilier +1;

        spellbook::$instance->materia->moveCard( $idmateria, $familier, $nouvelemplacementfamilier);
    

    spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} stores 1 Materia (Clone)' ), array(
        'mobile' =>  $parg1,
        'parent' => $familier.'_'.$nouvelemplacementfamilier,
        'player_name' => $this->player_name,
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
    
    if (spellbook::$instance->getGameStateValue('solo')==0)
    {
        $ret['titleyou'] = clienttranslate('${you} must select a card from another player (Clone)');
    $liste = self::getObjectListFromDB( "SELECT set_color color, player_id id FROM cards WHERE player_id !={$this->player_id} AND typerune !=0 AND jour = 2 AND set_color != 5");
    
    foreach ($liste as $card)
    {
        $ret["selectable"][] = "card_".$card['color']."_".$card['id'];
    }
         
    $ret['buttons'][]='cancel';
    }

    if (spellbook::$instance->getGameStateValue('solo')==1)
    {
        $ret['titleyou'] = clienttranslate('${you} must select the Spell to clone (level 4)');


        $colors = self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND power = 3 AND jour = 2 AND set_color !=5 ", true );
        foreach ($colors as $color)
        {
            $rune = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id ={$this->player_id} AND set_color = {$color}", true );
            $tableau = array_map('intval', $rune);
            if (array_values($tableau) === [0, 0, 0])
            {
                $ret["selectable"][] = "card_".$color."_".$this->player_id;
            }
        }


        $ret['buttons'][]='cancel';
    }

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
        $player = intval($explode[2]);
        $set = self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE player_id={$player} AND set_color = {$color} AND power =3");

        spellbook::$instance->setGameStateValue('idclone', $player);
        spellbook::$instance->addPendingTarget($this->player_id, "Clonecard".$set.$color, "init", $varg1);
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
    
    if (spellbook::$instance->getGameStateValue('solo')==0)
    {

    /////////////////////////// Test si au moins un sort peut être appris au minimum Niveau 3 /////////////////////////////
    $test = 0;
    $reserve = 'materiareserve_'.$this->player_id;
    
    ////// Test Rouge //////

    ///// est ce que sort a déjà été appris?////
    $pouvoirrouge = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 1", true );
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
        $nombrerouge = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 1", true ));
        if ($nombrerouge >= 1)
        {
        $nombretrianglenonrouge = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 1 AND card_type_arg = 1", true ));
        $nombrecarrenonrouge = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 1 AND card_type_arg = 2", true ));
        $nombrerondnonrouge = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 1 AND card_type_arg = 3", true ));
        $calcul = $nombrerouge + floor($nombrecarrenonrouge/3) + floor($nombretrianglenonrouge/3) + floor($nombrerondnonrouge/3); 
        if ($calcul >= 3)
        {
            $test = 1;
        }
        }
        
    }

    ////// Test Violet //////

    ///// est ce que sort a déjà été appris?////
    $pouvoirviolet = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 2", true );
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
        $nombreviolet = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 2", true ));
        if ($nombreviolet >= 1)
        {
        $nombretrianglenonviolet = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 2 AND card_type_arg = 1", true ));
        $nombrecarrenonviolet = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 2 AND card_type_arg = 2", true ));
        $nombrerondnonviolet = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 2 AND card_type_arg = 3", true ));
        $calcul = $nombreviolet + floor($nombrecarrenonviolet/3) + floor($nombretrianglenonviolet/3) + floor($nombrerondnonviolet/3); 
        if ($calcul >= 3)
        {
            $test = 1;
        }
        }
        
    }

    ////// Test Vert //////

    ///// est ce que sort a déjà été appris?////
    $pouvoirvert = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 3", true );
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
        $nombrevert = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 3", true ));
        if ($nombrevert >= 1)
        {
        $nombretrianglenonvert = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 3 AND card_type_arg = 1", true ));
        $nombrecarrenonvert = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 3 AND card_type_arg = 2", true ));
        $nombrerondnonvert = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 3 AND card_type_arg = 3", true ));
        $calcul = $nombrevert + floor($nombrecarrenonvert/3) + floor($nombretrianglenonvert/3) + floor($nombrerondnonvert/3); 
        if ($calcul >= 3)
        {
            $test = 1;
        }
        }
        
    }

    ////// Test Noir //////

    ///// est ce que sort a déjà été appris?////
    $pouvoirnoir = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 4", true );
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
        $nombrenoir = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 4", true ));
        if ($nombrenoir >= 1)
        {
        $nombretrianglenonnoir = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 4 AND card_type_arg = 1", true ));
        $nombrecarrenonnoir = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 4 AND card_type_arg = 2", true ));
        $nombrerondnonnoir = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 4 AND card_type_arg = 3", true ));
        $calcul = $nombrenoir + floor($nombrecarrenonnoir/3) + floor($nombretrianglenonnoir/3) + floor($nombrerondnonnoir/3); 
        if ($calcul >= 3)
        {
            $test = 1;
        }
        }
        
    }


    ////// Test Blanc //////

    ///// est ce que sort a déjà été appris?////
    $pouvoirblanc = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 5", true );
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
        $nombreblanc = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 5", true ));
        if ($nombreblanc >= 1)
        {
        $nombretrianglenonblanc = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 5 AND card_type_arg = 1", true ));
        $nombrecarrenonblanc = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 5 AND card_type_arg = 2", true ));
        $nombrerondnonblanc = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 5 AND card_type_arg = 3", true ));
        $calcul = $nombreblanc + floor($nombrecarrenonblanc/3) + floor($nombretrianglenonblanc/3) + floor($nombrerondnonblanc/3); 
        if ($calcul >= 3)
        {
            $test = 1;
        }
        }
        
    }


    ////// Test Bleu //////

    ///// est ce que sort a déjà été appris?////
    $pouvoirbleu = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 6", true );
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
        $nombrebleu = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 6", true ));
        if ($nombrebleu >= 1)
        {
        $nombretrianglenonbleu = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 6 AND card_type_arg = 1", true ));
        $nombrecarrenonbleu = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 6 AND card_type_arg = 2", true ));
        $nombrerondnonbleu = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 6 AND card_type_arg = 3", true ));
        $calcul = $nombrebleu + floor($nombrecarrenonbleu/3) + floor($nombretrianglenonbleu/3) + floor($nombrerondnonbleu/3); 
        if ($calcul >= 3)
        {
            $test = 1;
        }
        }
       
    }

    ////// Test Jaune //////

    ///// est ce que sort a déjà été appris?////
    $pouvoirjaune = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 7", true );
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
        $nombrejaune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 7", true ));
        if ($nombrejaune >= 1)
        {
        $nombretrianglenonjaune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 7 AND card_type_arg = 1", true ));
        $nombrecarrenonjaune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 7 AND card_type_arg = 2", true ));
        $nombrerondnonjaune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 7 AND card_type_arg = 3", true ));
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

    $countcard = count(self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id !={$this->player_id} AND typerune !=0 AND jour = 3 AND type = 0", true ));
    if ($countcard >= 1)
    {
        $ret['buttons'][]='cardaction'; 
    }

    $ret['buttons'][]='cancel';
    
    $countbuttons = count($ret['buttons']);
    if($countbuttons == 1)
    {
        $ret['titleyou'] = clienttranslate('${you} can\'t clone evening action');
    }

    if($countbuttons > 1)
    {
        $ret['titleyou'] = clienttranslate('${you} can clone an evening action from another player');
    }
    }

    if (spellbook::$instance->getGameStateValue('solo')==1)
    {
        $test = 0;
        $countcolors = count(self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND power = 3 AND jour = 3 ", true ));
        if($countcolors >= 1)
        {
        $colors = self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND power = 3 AND jour = 3 ", true );
        foreach ($colors as $color)
        {
            $rune = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id ={$this->player_id} AND set_color = {$color}", true );
            $tableau = array_map('intval', $rune);
            if (array_values($tableau) === [0, 0, 0])
            {
                $test = 1;
            }
        }
        }

    
        
        if($test == 0)
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t clone evening spell');
            $ret['buttons'][]='cancel';
        }

        if($test == 1)
        {
            $ret['titleyou'] = clienttranslate('${you} must select the Spell to clone (level 4)');
        }
    }

    return $ret;
}


function Soir($parg1, $parg2, $varg1, $varg2)
{
    spellbook::$instance->setGameStateValue('idclone', 1);

    if($varg1 == "cancel")
    {
        spellbook::$instance->addPending($this->player_id, "Midi");
    }

    if($varg1 == "learn")
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn1");
    }

    
    if($varg1 == "cardaction")
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirCardAction");
    }

    if($varg1 == NULL)
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirCardAction");
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
   $pouvoirrouge = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 1", true );
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
       $nombrerouge = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 1", true ));
       if ($nombrerouge >= 1)
        {
       $nombretrianglenonrouge = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 1 AND card_type_arg = 1", true ));
       $nombrecarrenonrouge = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 1 AND card_type_arg = 2", true ));
       $nombrerondnonrouge = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 1 AND card_type_arg = 3", true ));
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
   $pouvoirviolet = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 2", true );
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
       $nombreviolet = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 2", true ));
       if ($nombreviolet >= 1)
        {
       $nombretrianglenonviolet = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 2 AND card_type_arg = 1", true ));
       $nombrecarrenonviolet = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 2 AND card_type_arg = 2", true ));
       $nombrerondnonviolet = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 2 AND card_type_arg = 3", true ));
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
   $pouvoirvert = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 3", true );
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
       $nombrevert = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 3", true ));
       if ($nombrevert >= 1)
        {
       $nombretrianglenonvert = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 3 AND card_type_arg = 1", true ));
       $nombrecarrenonvert = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 3 AND card_type_arg = 2", true ));
       $nombrerondnonvert = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 3 AND card_type_arg = 3", true ));
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
   $pouvoirnoir = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 4", true );
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
       $nombrenoir = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 4", true ));
       if ($nombrenoir >= 1)
        {
       $nombretrianglenonnoir = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 4 AND card_type_arg = 1", true ));
       $nombrecarrenonnoir = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 4 AND card_type_arg = 2", true ));
       $nombrerondnonnoir = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 4 AND card_type_arg = 3", true ));
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
   $pouvoirblanc = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 5", true );
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
       $nombreblanc = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 5", true ));
       if ($nombreblanc >= 1)
        {
       $nombretrianglenonblanc = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 5 AND card_type_arg = 1", true ));
       $nombrecarrenonblanc = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 5 AND card_type_arg = 2", true ));
       $nombrerondnonblanc = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 5 AND card_type_arg = 3", true ));
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
   $pouvoirbleu = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 6", true );
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
       $nombrebleu = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 6", true ));
       if ($nombrebleu >= 1)
        {
       $nombretrianglenonbleu = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 6 AND card_type_arg = 1", true ));
       $nombrecarrenonbleu = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 6 AND card_type_arg = 2", true ));
       $nombrerondnonbleu = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 6 AND card_type_arg = 3", true ));
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
   $pouvoirjaune = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id = {$this->player_id} AND set_color = 7", true );
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
       $nombrejaune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = 7", true ));
       if ($nombrejaune >= 1)
        {
       $nombretrianglenonjaune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 7 AND card_type_arg = 1", true ));
       $nombrecarrenonjaune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 7 AND card_type_arg = 2", true ));
       $nombrerondnonjaune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 7 AND card_type_arg = 3", true ));
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
        spellbook::$instance->addPending($this->player_id, "Midi");
    }

    else
    {
        $explode = explode("_", $varg1);
        
        spellbook::$instance->setGameStateValue('color', $explode[1]);
        spellbook::$instance->setGameStateValue('position', $explode[3]);

        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn3", $varg1);
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
    $nombre = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = {$color}", true ));
    $nombretriangle = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != {$color} AND card_type_arg = 1", true ));
    $nombrecarre = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != {$color} AND card_type_arg = 2", true ));
    $nombrerond = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != {$color} AND card_type_arg = 3", true ));
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
        spellbook::$instance->addPending($this->player_id, "Midi");
    }

    else
    {
        $explode = explode("_", $varg1);
        
        spellbook::$instance->setGameStateValue('color', $explode[1]);
        spellbook::$instance->setGameStateValue('position', $explode[3]);


        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn3", $varg1);
    }


}

function argSoirLearn3($parg1, $parg2)
{
    $ret = array();
    $ret["selectable"] = array();
    $ret["selectable2"] = array();
    $ret["selected"] = array();
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
        $selectable = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$location}' AND card_type !={$color}", true );
        
        $selected = self::getObjectListFromDB( "SELECT card_id id FROM materia WHERE card_location ='{$location}' AND card_type ={$color}", true );
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
        spellbook::$instance->addPending($this->player_id, "Midi");
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
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn3", $selected, 'error');
    }

    if ((spellbook::$instance->getGameStateValue('position') == '2') && ($count !=4) && ($count !=6) && ($count !=8))
    {
        $test =1;
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn3", $selected, 'error');
    }

    if ((spellbook::$instance->getGameStateValue('position') == '3') && ($count !=5) && ($count !=7) && ($count !=9))
    {
        $test =1;
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn3", $selected, 'error');
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
        $testcolor = intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id = {$id}"));
        if($testcolor == spellbook::$instance->getGameStateValue('color'))
        {
            $nombre = $nombre +1; 
        }
        
        else
        {
            $testrune = intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id = {$id}"));
            
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
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn5", $parg1);
    }

    else
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn3", $selected, 'error');
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
        $testcolor = self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id = {$materia}");
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
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn3", $selected);
    }

    else
    {
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirConfirm", $parg1, $varg1);
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
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn5", $parg1);
    }

    if($varg1 == "confirm")
    {*/
        $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');

        $setcardjaune = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE player_id={$this->player_id} AND set_color = 7 AND power = 3"));
        $familier = 'materiafamilier_'.$this->player_id;
        $countfamilier = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location ='{$familier}'", true ));

        if (((spellbook::$instance->getGameStateValue('color')==7)&&(spellbook::$instance->getGameStateValue('position')==3)&&($setcardjaune==3)&&($countfamilier <=12))||(($this->player_p37 == 1)&&($countfamilier <=12)))
        {
            self::DbQuery( "INSERT INTO bonuslearn (player_id, materiacard, listeselected, placeoncard) VALUES ({$this->player_id}, '{$selected}', '{$parg1}', '{$parg2}')" );
            spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearnBonus", $parg1);
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
        $runeselected = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idselected}");
        $location = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id;
        $emplacement = spellbook::$instance->getGameStateValue('position');
        $power = intval(spellbook::$instance->getGameStateValue('position')) +2;
        $color = spellbook::$instance->getGameStateValue('color');

        spellbook::$instance->materia->moveCard( $idselected, $location, $emplacement);
        self::DbQuery( "UPDATE cards set typerune = {$runeselected} WHERE set_color = '{$color}' AND power = {$power} AND player_id = {$this->player_id}" );

        $set = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
            $index = $set.$color;
        
        spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} learns "${name}" level ${power} (Clone)' ), array(
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

        $type = intval(self::getUniqueValueFromDB("SELECT type FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
        if(($type == 1)||($type == 2))
        {
            $set = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard".$set.$color, "Power".$power);
        }
        else
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
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
    
    if (spellbook::$instance->getGameStateValue('solo')==0)
    {
        $ret['titleyou'] = clienttranslate('${you} must select a card from another player (Clone)');
    
    $liste = self::getObjectListFromDB( "SELECT set_color color, player_id id FROM cards WHERE player_id !={$this->player_id} AND typerune !=0 AND jour = 3 AND type = 0");
    
    
    foreach ($liste as $card)
    {
        $ret["selectable"][] = "card_".$card['color']."_".$card['id'];
    }
         
    $ret['buttons'][]='cancel';
    }

    if (spellbook::$instance->getGameStateValue('solo')==1)
    {
        $ret['titleyou'] = clienttranslate('${you} must select the Spell to clone (level 4)');


        $colors = self::getObjectListFromDB( "SELECT set_color FROM cards WHERE player_id ={$this->player_id} AND power = 3 AND jour = 3 ", true );
        foreach ($colors as $color)
        {
            $rune = self::getObjectListFromDB( "SELECT typerune FROM cards WHERE player_id ={$this->player_id} AND set_color = {$color}", true );
            $tableau = array_map('intval', $rune);
            if (array_values($tableau) === [0, 0, 0])
            {
                $ret["selectable"][] = "card_".$color."_".$this->player_id;
            }
        }


        $ret['buttons'][]='cancel';
    }

    return $ret;
}

function SoirCardAction($parg1, $parg2, $varg1, $varg2)
{
    if($varg1 == "cancel")
    {
        spellbook::$instance->addPending($this->player_id, "Midi");
    }
    else
    {
        $explode = explode("_", $varg1);
        $color = intval($explode[1]);
        $player = intval($explode[2]);
        $set = self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE player_id={$player} AND set_color = {$color} AND power = 3");

        spellbook::$instance->setGameStateValue('idclone', $player);
        spellbook::$instance->addPendingTarget($this->player_id, "Clonecard".$set.$color, "init", $varg1);
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
    $countfamilier = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location ='{$familier}'", true ));

    $selected = self::getUniqueValueFromDB("SELECT placeoncard FROM bonuslearn WHERE player_id={$this->player_id}");
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
        self::DbQuery("DELETE FROM bonuslearn WHERE player_id = {$this->player_id}");
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn5", $parg1);
    }

    else
        {
            $log = array();
            $explode1 = explode("_", $varg1);
            $idstore1 = intval($explode1[1]);
            $familier = 'materiafamilier_'.$this->player_id;

            $listeselected = self::getUniqueValueFromDB("SELECT listeselected FROM bonuslearn WHERE player_id={$this->player_id}");
            $explode = explode('_', $listeselected);
            $tableau = array_map('intval', $explode);
            /// enlever les zero du tableau
            $tableausanszero = array_filter($tableau, function($valeur) {
                return $valeur != 0;
            });
            // Réindexer le tableau pour réorganiser les clés
            $tableausanszero = array_values($tableausanszero);

            $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');
            $placeoncard = self::getUniqueValueFromDB("SELECT placeoncard FROM bonuslearn WHERE player_id={$this->player_id}");
            $explode2 = explode('_', $placeoncard);
            $idselected = intval($explode2[1]);
            $runeselected = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idselected}");
            $location = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id;
            $emplacement = spellbook::$instance->getGameStateValue('position');
            $power = intval(spellbook::$instance->getGameStateValue('position')) +2;
            $color = spellbook::$instance->getGameStateValue('color');

            spellbook::$instance->materia->moveCard( $idselected, $location, $emplacement);
            self::DbQuery( "UPDATE cards set typerune = {$runeselected} WHERE set_color = '{$color}' AND power = {$power} AND player_id = {$this->player_id}" );

            $set = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
            $index = $set.$color;

            spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} learns "${name}" level ${power} (Clone)'), array(
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


            
            $countfamilier = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location ='{$familier}'", true ));
            
                $nouvelemplacementfamilier = $countfamilier +1;
                spellbook::$instance->materia->moveCard( $explode1[1], $familier, $nouvelemplacementfamilier);
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$explode1[1],
                    'parent' => $familier.'_'.$nouvelemplacementfamilier,
                    'player_name' => $this->player_name,
                    )
                    );
            
            $col= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idstore1}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idstore1}"));
            $log[] = ($col*10)+$signe;
    
            spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} stores ${log1} ("COMMUNION")'), array(
                'player_name' => $this->player_name,
                'log1' => spellbook::$instance->getLogsType($log[0]),
                
                                
                )
                );


            spellbook::$instance->CalculPv();

            spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

            self::DbQuery("DELETE FROM bonuslearn WHERE player_id = {$this->player_id}");


            $type = intval(self::getUniqueValueFromDB("SELECT type FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
            if(($type == 1)||($type == 2))
            {
                $set = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
                spellbook::$instance->addPendingTarget($this->player_id, "Clonecard".$set.$color, "Power".$power);
            }
            else
            {
                spellbook::$instance->addPending($this->player_id, "Soir");
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
        $selected = self::getUniqueValueFromDB("SELECT listeselected FROM bonuslearn WHERE player_id={$this->player_id}");
        self::DbQuery("DELETE FROM bonuslearn WHERE player_id = {$this->player_id}");
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "SoirLearn5", $selected);
    }

    if($varg1 == "confirm")
    {*/
        $log = array();
        $explode1 = explode("_", $parg1);
        $idstore1 = intval($explode1[0]);
        $idstore2 = intval($explode1[1]);
        $familier = 'materiafamilier_'.$this->player_id;

        $listeselected = self::getUniqueValueFromDB("SELECT listeselected FROM bonuslearn WHERE player_id={$this->player_id}");
        $explode = explode('_', $listeselected);
        $tableau = array_map('intval', $explode);
        /// enlever les zero du tableau
        $tableausanszero = array_filter($tableau, function($valeur) {
            return $valeur != 0;
        });
        // Réindexer le tableau pour réorganiser les clés
        $tableausanszero = array_values($tableausanszero);

        $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');
        $placeoncard = self::getUniqueValueFromDB("SELECT placeoncard FROM bonuslearn WHERE player_id={$this->player_id}");
        $explode2 = explode('_', $placeoncard);
        $idselected = intval($explode2[1]);
        $runeselected = self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idselected}");
        $location = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id;
        $emplacement = spellbook::$instance->getGameStateValue('position');
        $power = intval(spellbook::$instance->getGameStateValue('position')) +2;
        $color = spellbook::$instance->getGameStateValue('color');

        spellbook::$instance->materia->moveCard( $idselected, $location, $emplacement);
        self::DbQuery( "UPDATE cards set typerune = {$runeselected} WHERE set_color = '{$color}' AND power = {$power} AND player_id = {$this->player_id}" );

        $set = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
        $index = $set.$color;

        spellbook::$instance->notifyAllPlayers('move',clienttranslate( '${player_name} learns "${name}" level ${power} (Clone)' ), array(
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


        
        $countfamilier = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location ='{$familier}'", true ));
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

        $col1= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idstore1}"));
        $signe1= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idstore1}"));
        $log[] = ($col1*10)+$signe1;
        $col2= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idstore2}"));
        $signe2= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idstore2}"));
        $log[] = ($col2*10)+$signe2;

        spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} stores ${log1} ${log2} ("COMMUNION")'), array(
            'player_name' => $this->player_name,
            'log1' => spellbook::$instance->getLogsType($log[0]),
            'log2' => spellbook::$instance->getLogsType($log[1]),
            
                            
            )
            );


        spellbook::$instance->CalculPv();

        spellbook::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1500] );

        self::DbQuery("DELETE FROM bonuslearn WHERE player_id = {$this->player_id}");


        $type = intval(self::getUniqueValueFromDB("SELECT type FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
        if(($type == 1)||($type == 2))
        {
            $set = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard".$set.$color, "Power".$power);
        }
        else
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
        }




        
   //}
    

}




}