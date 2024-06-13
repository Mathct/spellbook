<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * spellbook implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * spellbook.action.php
 *
 * spellbook main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/spellbook/spellbook/myAction.html", ...)
 *
 */
  
  
  class action_spellbook extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "spellbook_spellbook";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	public function actSelect()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    
  	    $this->game->actSelect( $arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actButton()
  	{
		self::setAjaxMode();
		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    $this->game->actButton($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidateselectionsoir()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
		$arg4 = self::getArg( "arg4", AT_alphanum );
		$arg5 = self::getArg( "arg5", AT_alphanum );
	  	$arg6 = self::getArg( "arg6", AT_alphanum );
		$arg7 = self::getArg( "arg7", AT_alphanum );
  	    $arg8 = self::getArg( "arg8", AT_alphanum );
		$arg9 = self::getArg( "arg9", AT_alphanum );
  	    
  	    $this->game->actValidateselectionsoir( $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9 );
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidateselectionsoir2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
		$arg4 = self::getArg( "arg4", AT_alphanum );
		$arg5 = self::getArg( "arg5", AT_alphanum );
	  	$arg6 = self::getArg( "arg6", AT_alphanum );
		$arg7 = self::getArg( "arg7", AT_alphanum );
  	    $arg8 = self::getArg( "arg8", AT_alphanum );
		$arg9 = self::getArg( "arg9", AT_alphanum );
  	    
  	    $this->game->actValidateselectionsoir2( $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9 );
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidateselectioncard12()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		  	    
  	    $this->game->actValidateselectioncard12( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidateswap2autel()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		  	    
  	    $this->game->actValidateswap2autel( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidateswap2reserve()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    $arg4 = self::getArg( "arg4", AT_alphanum );
		  	    
  	    $this->game->actValidateswap2reserve( $arg1, $arg2, $arg3, $arg4);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidateswap3autel()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
		  	    
  	    $this->game->actValidateswap3autel( $arg1, $arg2, $arg3);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidateswap3reserve()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    $arg4 = self::getArg( "arg4", AT_alphanum );
		$arg5 = self::getArg( "arg5", AT_alphanum );
  	    $arg6 = self::getArg( "arg6", AT_alphanum );
		  	    
  	    $this->game->actValidateswap3reserve( $arg1, $arg2, $arg3, $arg4, $arg5, $arg6);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidatestore2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
		  	    
  	    $this->game->actValidatestore2( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidatestore3()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    
		  	    
  	    $this->game->actValidatestore3( $arg1, $arg2, $arg3);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidatestore4()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    $arg4 = self::getArg( "arg4", AT_alphanum );
		
		  	    
  	    $this->game->actValidatestore4( $arg1, $arg2, $arg3, $arg4);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate22take2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
		
		  	    
  	    $this->game->actValidate22take2( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate22take3()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    
		
		  	    
  	    $this->game->actValidate22take3( $arg1, $arg2, $arg3);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate23discard2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
		
		  	    
  	    $this->game->actValidate23discard2( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate23discard3()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    
		
		  	    
  	    $this->game->actValidate23discard3( $arg1, $arg2, $arg3);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate24store2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
		
		  	    
  	    $this->game->actValidate24store2( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate24take2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
		
		  	    
  	    $this->game->actValidate24take2( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate24store3()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    
		
		  	    
  	    $this->game->actValidate24store3( $arg1, $arg2, $arg3);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate25selectautel()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
		$arg4 = self::getArg( "arg4", AT_alphanum );
		$arg5 = self::getArg( "arg5", AT_alphanum );
	  	$arg6 = self::getArg( "arg6", AT_alphanum );
		$arg7 = self::getArg( "arg7", AT_alphanum );
  	    $arg8 = self::getArg( "arg8", AT_alphanum );
		$arg9 = self::getArg( "arg9", AT_alphanum );
		$arg10 = self::getArg( "arg10", AT_alphanum );
  	    
  	    $this->game->actValidate25selectautel( $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10 );
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate25take3()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    
		
		  	    
  	    $this->game->actValidate25take3( $arg1, $arg2, $arg3);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate25take2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
  	    
		
		  	    
  	    $this->game->actValidate25take2( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate32take2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
		
		  	    
  	    $this->game->actValidate32take2( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate33store2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
		
		  	    
  	    $this->game->actValidate33store2( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate33store3()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    
		
		  	    
  	    $this->game->actValidate33store3( $arg1, $arg2, $arg3);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidatebonuslearn()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
		
		  	    
  	    $this->game->actValidatebonuslearn( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate16bonuslearn()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
		
		  	    
  	    $this->game->actValidate16bonuslearn( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate37store3()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		$arg3 = self::getArg( "arg3", AT_alphanum );
  	    
		
		  	    
  	    $this->game->actValidate37store3( $arg1, $arg2, $arg3);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actValidate37store2()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    $arg2 = self::getArg( "arg2", AT_alphanum );
		
  	    
		
		  	    
  	    $this->game->actValidate37store2( $arg1, $arg2);
  	    
  	    self::ajaxResponse( );
  	}
    

  }
  

