<?php 

class Card16 extends Card
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        
        

        $level = intval(self::getUniqueValueFromDB("SELECT power FROM cards WHERE player_id={$this->player_id} AND set_color = 6 AND typerune !=0")); //////ATTENTION set_color
        $typerune = intval(self::getUniqueValueFromDB("SELECT typerune FROM cards WHERE player_id={$this->player_id} AND set_color = 6 AND typerune !=0"));

        /////////////////////////// Test si au moins un sort peut être appris au minimum Niveau 3 /////////////////////////////
        $test = 0;
        $reserve = 'materiareserve_'.$this->player_id;

        $maxrune = $level -3;
        
        
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
            $nombrerunenonrouge = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 1 AND card_type_arg = {$typerune}", true ));
            
            $nbremaxrune = 0;
            if (($maxrune == 1)&&($nombrerunenonrouge>=1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonrouge == 1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonrouge >=2))
            {
                $nbremaxrune = 2;
            }

            $calcul = $nombrerouge + $nbremaxrune;
            if ($calcul >= 3)
            {
                $test = 1;
                //$ret["selectable"][] = 'card_1_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_1_'.$this->player_id.'_1';
            }
            if($calcul >=4)
            {
                $ret["selectable"][] = 'materiacard_1_'.$this->player_id.'_2';
            }

            if($calcul >=5)
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
            $nombrerunenonviolet = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 2 AND card_type_arg = {$typerune}", true ));
            
            $nbremaxrune = 0;
            if (($maxrune == 1)&&($nombrerunenonviolet>=1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonviolet == 1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonviolet >=2))
            {
                $nbremaxrune = 2;
            }

            $calcul = $nombreviolet + $nbremaxrune;
            if ($calcul >= 3)
            {
                $test = 1;
                //$ret["selectable"][] = 'card_2_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_2_'.$this->player_id.'_1';
            }
            if($calcul >=4)
            {
                $ret["selectable"][] = 'materiacard_2_'.$this->player_id.'_2';
            }

            if($calcul >=5)
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
            $nombrerunenonvert = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 3 AND card_type_arg = {$typerune}", true ));
            $nbremaxrune = 0;
            if (($maxrune == 1)&&($nombrerunenonvert>=1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonvert == 1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonvert >=2))
            {
                $nbremaxrune = 2;
            }
            $calcul = $nombrevert + $nbremaxrune; 
            if ($calcul >= 3)
            {
                $test = 1;
                //$ret["selectable"][] = 'card_3_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_3_'.$this->player_id.'_1';
            }
            if($calcul >=4)
            {
                $ret["selectable"][] = 'materiacard_3_'.$this->player_id.'_2';
            }

            if($calcul >=5)
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
            $nombrerunenonnoir = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 4 AND card_type_arg = {$typerune}", true ));
            $nbremaxrune = 0;
            if (($maxrune == 1)&&($nombrerunenonnoir>=1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonnoir == 1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonnoir >=2))
            {
                $nbremaxrune = 2;
            }
            $calcul = $nombrenoir + $nbremaxrune; 
            if ($calcul >= 3)
            {
                $test = 1;
                //$ret["selectable"][] = 'card_4_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_4_'.$this->player_id.'_1';
            }
            if($calcul >=4)
            {
                $ret["selectable"][] = 'materiacard_4_'.$this->player_id.'_2';
            }

            if($calcul >=5)
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
            $nombrerunenonblanc = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 5 AND card_type_arg = {$typerune}", true ));
            $nbremaxrune = 0;
            if (($maxrune == 1)&&($nombrerunenonblanc>=1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonblanc == 1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonblanc >=2))
            {
                $nbremaxrune = 2;
            }
            $calcul = $nombreblanc + $nbremaxrune; 
            if ($calcul >= 3)
            {
                $test = 1;
                //$ret["selectable"][] = 'card_5_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_5_'.$this->player_id.'_1';
            }
            if($calcul >=4)
            {
                $ret["selectable"][] = 'materiacard_5_'.$this->player_id.'_2';
            }

            if($calcul >=5)
            {
                $ret["selectable"][] = 'materiacard_5_'.$this->player_id.'_3';
            }
            }
            
        }


        ////// PAS de Test Bleu //////

        
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
            $nombrerunenonjaune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != 7 AND card_type_arg = {$typerune}", true ));
            $nbremaxrune = 0;
            if (($maxrune == 1)&&($nombrerunenonjaune>=1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonjaune == 1))
            {
                $nbremaxrune = 1;
            }
            if (($maxrune == 2)&&($nombrerunenonjaune >=2))
            {
                $nbremaxrune = 2;
            }
            $calcul = $nombrejaune + $nbremaxrune; 
            if ($calcul >= 3)
            {
                $test = 1;
                //$ret["selectable"][] = 'card_7_'.$this->player_id;
                $ret["selectable"][] = 'materiacard_7_'.$this->player_id.'_1';
            }
            if($calcul >=4)
            {
                $ret["selectable"][] = 'materiacard_7_'.$this->player_id.'_2';
            }

            if($calcul >=5)
            {
                $ret["selectable"][] = 'materiacard_7_'.$this->player_id.'_3';
            }
            }
           
        }



        if ($test == 1)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose the spell to learn');
        }

        if ($test == 0)
        {
            $ret['titleyou'] = clienttranslate('${you} cannot learn a spell');
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
            $explode = explode("_", $varg1);
            
            spellbook::$instance->setGameStateValue('color', $explode[1]);
            spellbook::$instance->setGameStateValue('position', $explode[3]);

            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step3", $varg1);
        }


    }

    public function argStep2($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} wants to trigger an action card');
        $ret['titleyou'] = clienttranslate('${you} must choose the learning level');

        $explode = explode("_", $parg1);
        $color = intval($explode[1]);
        $level = intval(self::getUniqueValueFromDB("SELECT power FROM cards WHERE player_id={$this->player_id} AND set_color = 6 AND typerune !=0")); //////ATTENTION set_color
        $typerune = intval(self::getUniqueValueFromDB("SELECT typerune FROM cards WHERE player_id={$this->player_id} AND set_color = 6 AND typerune !=0"));
        
        $reserve = 'materiareserve_'.$this->player_id;

        
        $nbrecouleur = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type = {$color}", true ));
        $nombrerune = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location = '{$reserve}' AND card_type != {$color} AND card_type_arg = {$typerune}", true ));
        
        $maxrune = $level -3;
        $nbremaxrune = 0;
        if (($maxrune == 1)&&($nombrerune>=1))
        {
            $nbremaxrune = 1;
        }
        if (($maxrune == 2)&&($nombrerune == 1))
        {
            $nbremaxrune = 1;
        }
        if (($maxrune == 2)&&($nombrerune >=2))
        {
            $nbremaxrune = 2;
        }
        
        $calcul = $nbrecouleur + $nbremaxrune;

        if($calcul >=3)
        {
            $ret["selectable"][] = 'materiacard_'.$color.'_'.$this->player_id.'_1';
        }

        if($calcul >=4)
        {
            $ret["selectable"][] = 'materiacard_'.$color.'_'.$this->player_id.'_2';
        }

        if($calcul >=5)
        {
            $ret["selectable"][] = 'materiacard_'.$color.'_'.$this->player_id.'_3';
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
            $explode = explode("_", $varg1);
            
            spellbook::$instance->setGameStateValue('color', $explode[1]);
            spellbook::$instance->setGameStateValue('position', $explode[3]);

            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step3", $varg1);
        }

    }

    function argStep3($parg1, $parg2)
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
        $ret['buttons'][]='validateselectionsoir23';
        }
        if ($lvl == 2)
        {
        $ret['buttons'][]='validateselectionsoir24';
        }
        if ($lvl == 3)
        {
        $ret['buttons'][]='validateselectionsoir25';
        }

        $ret['buttons'][]='cancel';

                       
        return $ret;
    }

    function Step3($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            spellbook::$instance->addPending($this->player_id, "Soir");
        }

        
    }

    function argControle($parg1, $parg2)  /// controle de la selection
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');
                               
        return $ret;
    }

    function Controle ($parg1, $parg2, $varg1, $varg2)     /// controle de la selection
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
                
        if ((spellbook::$instance->getGameStateValue('position') == '1') && ($count !=3))
        {
            $test =1;
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step3", $selected, 'error');
        }

        if ((spellbook::$instance->getGameStateValue('position') == '2') && ($count !=4))
        {
            $test =1;
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step3", $selected, 'error');
        }

        if ((spellbook::$instance->getGameStateValue('position') == '3') && ($count !=5))
        {
            $test =1;
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step3", $selected, 'error');
        }

        if ($test == 0)
        {

        ///// on va tester maintenant si la combinaison est ok

        $nombre = 0;
        $rune =0;
        $typerune = intval(self::getUniqueValueFromDB("SELECT typerune FROM cards WHERE player_id={$this->player_id} AND set_color = 6 AND typerune !=0"));
        $level = intval(self::getUniqueValueFromDB("SELECT power FROM cards WHERE player_id={$this->player_id} AND set_color = 6 AND typerune !=0")); //////ATTENTION set_color

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
                
                if ($testrune == $typerune)
                {
                    $rune = $rune +1;
                }
                
            }

        }

        $nbrerunemax = 0;
        if (($level == 4) && ($rune>=1))
        {
            $nbrerunemax = 1;
        }
        if (($level == 5) && ($rune == 1))
        {
            $nbrerunemax = 1;
        }
        if (($level == 5) && ($rune >=2))
        {
            $nbrerunemax = 2;
        }

        $calcul = $nombre + $nbrerunemax;

        if (((spellbook::$instance->getGameStateValue('position') == '1') && ($calcul == 3) && ($nombre >=1)) || ((spellbook::$instance->getGameStateValue('position') == '2') && ($calcul == 4) && ($nombre >=1)) || ((spellbook::$instance->getGameStateValue('position') == '3') && ($calcul == 5) && ($nombre >=1)))
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step4", $parg1);
            
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step3", $selected, 'error');
           
        }
         

        }
    }

    function argStep4($parg1, $parg2)
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

    function Step4($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step3", $selected);
        }

        else
        {
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Confirm", $parg1, $varg1);
            
        }
    
    }

    function argConfirm($parg1, $parg2)
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

    function Confirm($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {            
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step4", $parg1);
        }

        if($varg1 == "confirm")
        {*/
            $selected = 'materiacard_'.spellbook::$instance->getGameStateValue('color').'_'.$this->player_id.'_'.spellbook::$instance->getGameStateValue('position');

            $setcardjaune = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE player_id={$this->player_id} AND set_color = 7 AND power = 3"));
            $familier = 'materiafamilier_'.$this->player_id;
            $countfamilier = count(self::getObjectListFromDB( "SELECT card_id FROM materia WHERE card_location ='{$familier}'", true ));

            if (((spellbook::$instance->getGameStateValue('color')==7)&&(spellbook::$instance->getGameStateValue('position')==3)&&($setcardjaune==3)&&($countfamilier <=13))||(($this->player_p37 == 1)&&($countfamilier <=13)))
            {
                self::DbQuery( "INSERT INTO bonuslearn (player_id, materiacard, listeselected, placeoncard) VALUES ({$this->player_id}, '{$selected}', '{$parg1}', '{$parg2}')" );
                spellbook::$instance->addPendingTarget($this->player_id, "Card16", "LearnBonus", $parg1);
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

            $type = intval(self::getUniqueValueFromDB("SELECT type FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
            if(($type == 1)||($type == 2))
            {
                $set = intval(self::getUniqueValueFromDB("SELECT set_id FROM cards WHERE power = {$power} AND set_color = '{$color}' AND player_id = {$this->player_id}"));
                spellbook::$instance->addPendingTarget($this->player_id, "Card".$set.$color, "Power".$power);
            }
            else
            {
                spellbook::$instance->addPending($this->player_id, "Autel");
            }

            }  

        //}
    
    }

    function argLearnBonus($parg1, $parg2)
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
        
        $ret['buttons'][]='validate16bonuslearn';
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

    function LearnBonus($parg1, $parg2, $varg1, $varg2)
    {
        if($varg1 == "cancel")
        {
            self::DbQuery("DELETE FROM bonuslearn WHERE player_id = {$this->player_id}");
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step4", $parg1);
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
        $ret['buttons'][]='cancel';  */     
        

        return $ret;
    }

    function ConfirmBonusLearn($parg1, $parg2, $varg1, $varg2)
    {
        /*if($varg1 == "cancel")
        {
            $selected = self::getUniqueValueFromDB("SELECT listeselected FROM bonuslearn WHERE player_id={$this->player_id}");
            self::DbQuery("DELETE FROM bonuslearn WHERE player_id = {$this->player_id}");
            spellbook::$instance->addPendingTarget($this->player_id, "Card16", "Step4", $selected);
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
                spellbook::$instance->addPendingTarget($this->player_id, "Card".$set.$color, "Power".$power);
            }
            else
            {
                spellbook::$instance->addPending($this->player_id, "Autel");
            }




            
        //}
        

    }




}