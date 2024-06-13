<?php 

class Clonecard26 extends Card
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
        spellbook::$instance->setGameStateValue('matinos', 1);
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinSupp");
        
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
        spellbook::$instance->setGameStateValue('matinos', 1);
        spellbook::$instance->addPendingTarget($this->player_id, "Card35", "MatinSupp");
        
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
        self::DbQuery( "UPDATE player set p26 = 1 WHERE player_id = {$this->player_id}" );
        spellbook::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains the permanent power “Swiffness” Level 5' ), array(
            'player_name' => $this->player_name,
                            
            )
            );
        spellbook::$instance->addPending($this->player_id, "Soir");
        
    }

}