<?php 

for($i = 11; $i<=17;$i++)
{
    include("card/Card{$i}.php");    
}

for($j = 21; $j<=26;$j++)
{
    include("card/Card{$j}.php");    
}

for($k = 31; $k<=37;$k++)
{
    include("card/Card{$k}.php");    
}

include("card/Clonecard11.php"); 
include("card/Clonecard12.php"); 
include("card/Clonecard13.php"); 
include("card/Clonecard14.php");
include("card/Clonecard16.php"); 
include("card/Clonecard17.php");
include("card/Clonecard21.php"); 
include("card/Clonecard22.php");
include("card/Clonecard23.php"); 
include("card/Clonecard24.php");
include("card/Clonecard26.php"); 
include("card/Clonecard31.php");
include("card/Clonecard32.php"); 
include("card/Clonecard33.php");
include("card/Clonecard34.php"); 
include("card/Clonecard36.php");
include("card/Clonecard37.php"); 


class Card extends APP_GameClass
{
    //public $type = 0;
        
    public function __construct()
    {
        //$this->type = (int) filter_var(get_class($this), FILTER_SANITIZE_NUMBER_INT);


        $player_id = spellbook::$instance->getActivePlayerId();
        
        $p = self::getObjectFromDB("SELECT * FROM `player` WHERE `player_id` = {$player_id}");        
        $this->player_no = $p['player_no'];
        $this->player_id = $p['player_id'];
        $this->player_name = $p['player_name'];
        $this->player_score = $p['player_score'];
        $this->player_color = $p['player_color'];
        $this->player_p26 = intval(self::getUniqueValueFromDB("SELECT `p26` FROM `player` WHERE `player_id` = {$this->player_id}"));
        $this->player_p36 = intval(self::getUniqueValueFromDB("SELECT `p36` FROM `player` WHERE `player_id` = {$this->player_id}"));
        $this->player_p37 = intval(self::getUniqueValueFromDB("SELECT `p37` FROM `player` WHERE `player_id` = {$this->player_id}"));
       


    }
    
    public function arginit($parg1, $parg2)
    {
        
    }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        
    }
    
    
}