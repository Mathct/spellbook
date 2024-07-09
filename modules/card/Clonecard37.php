<?php 

class Clonecard37 extends Card
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
        self::DbQuery( "UPDATE player set p37 = 1 WHERE player_id = {$this->player_id}" );
        spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains the permanent power “COMMUNION” Level 5' ), array(
            'player_name' => $this->player_name,
                            
            )
            );
        spellbook::$instance->addPending($this->player_id, "Soir");
        
    }

    public function argPower3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selectablemulti"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must select 3 Materia from the Altar to store');

        $familier = 'materiafamilier_'.$this->player_id;
        $nombrefamilier = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$familier}'", true ));
        $nombreautel = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = 'materiaautel'", true ));

        if (($nombreautel>=3)&&($nombrefamilier<=11))
        {
            $ret['titleyou'] = clienttranslate('${you} must select 3 Materia from the Altar to store');
            $ids = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = 'materiaautel'", true );
            foreach($ids as $id)
            {
                $ret["selectablemulti"][] = 'materia_'.$id;
            }
            $ret['buttons'][] = 'validate37store3';
        }

        if ((($nombreautel==2)&&($nombrefamilier<=12))||(($nombreautel>=2)&&($nombrefamilier==12)))
        {
            $ret['titleyou'] = clienttranslate('${you} must select 2 Materia from the Altar to store');
            $ids = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = 'materiaautel'", true );
            foreach($ids as $id)
            {
                $ret["selectablemulti"][] = 'materia_'.$id;
            }
            $ret['buttons'][] = 'validate37store2';
        }

        if ((($nombreautel==1)&&($nombrefamilier<=13))||(($nombreautel>=1)&&($nombrefamilier==13)))
        {
            $ret['titleyou'] = clienttranslate('${you} must select 1 Materia from the Altar to store');
            $ids = self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = 'materiaautel'", true );
            foreach($ids as $id)
            {
                $ret["selectable"][] = 'materia_'.$id;
            }
        }

        


         
        return $ret;
     }

    public function Power3($parg1, $parg2, $varg1, $varg2)
    {
        

        if ($varg1 == NULL)
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
        }
        else
        {
            $explode = explode("_", $varg1);
            $id = intval($explode[1]);
            $familier = 'materiafamilier_'.$this->player_id;
            $countfamilier = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location ='{$familier}'", true ));
            
                $nouvelemplacementfamilier = $countfamilier +1;
                spellbook::$instance->materia->moveCard( $id, $familier, $nouvelemplacementfamilier);
                spellbook::$instance->notifyAllPlayers('move','', array(
                    'mobile' =>  'materia_'.$id,
                    'parent' => $familier.'_'.$nouvelemplacementfamilier,
                    'player_name' => $this->player_name,
                    )
                    );
            

            $log = array();
            $idmateria = $id;
            $col= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateria}"));
            $signe= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateria}"));
            $log[] = ($col*10)+$signe;
            
                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "COMMUNION" and store ${log1} from the Altar'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                                    
                    )
                    );

                          
    
    
            spellbook::$instance->CalculPv();
            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->addPending($this->player_id, "Soir");

        }
        
    }

    public function argPower3Confirm($parg1, $parg2)
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

    public function Power3Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            
            spellbook::$instance->addPendingTarget($this->player_id, "Clonecard37", "Power3");
            
        }

        if($varg1 == "confirm")
        {*/

            $log = array();

            $explode = explode("_", $parg1);
            $count = count($explode);
            $familier = 'materiafamilier_'.$this->player_id;
            $countfamilier = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location ='{$familier}'", true ));
            for ($i=1; $i <= $count; $i++)
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
                    $col= intval(self::getUniqueValueFromDB("SELECT card_type FROM materia WHERE card_id={$idmateria}"));
                    $signe= intval(self::getUniqueValueFromDB("SELECT card_type_arg FROM materia WHERE card_id={$idmateria}"));
                    $log[] = ($col*10)+$signe;
            }

            if ($count ==2)
            {
                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "COMMUNION" and store ${log1} ${log2} from the Altar'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                    'log2' => spellbook::$instance->getLogsType($log[1]),
                                    
                    )
                    );
                
            }

            if ($count ==3)
            {

                spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} triggers "COMMUNION" and store ${log1} ${log2} ${log3} from the Altar'), array(
                    'player_name' => $this->player_name,
                    'log1' => spellbook::$instance->getLogsType($log[0]),
                    'log2' => spellbook::$instance->getLogsType($log[1]),
                    'log3' => spellbook::$instance->getLogsType($log[2]),
                                    
                    )
                    );
                
            }
    
    
            spellbook::$instance->CalculPv();
            spellbook::$instance->AutelReorganisation();
            spellbook::$instance->addPending($this->player_id, "Soir");
        //}
        
    }



}