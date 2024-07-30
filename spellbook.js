/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * spellbook implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * spellbook.js
 *
 * spellbook user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter"
],
function (dojo, declare) {
    return declare("bgagame.spellbook", ebg.core.gamegui, {
        constructor: function(){
            console.log('spellbook constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );

/////////////////////////////////////////////////////////////////////////////////           
//    _____                      _____        _            
//   / ____|                    |  __ \      | |           
//  | |  __  __ _ _ __ ___   ___| |  | | __ _| |_ __ _ ___ 
//  | | |_ |/ _` | '_ ` _ \ / _ \ |  | |/ _` | __/ _` / __|
//  | |__| | (_| | | | | | |  __/ |__| | (_| | || (_| \__ \
//   \_____|\__,_|_| |_| |_|\___|_____/ \__,_|\__\__,_|___/
//                                                        
/////////////////////////////////////////////////////////////////////////////////  

            this.players = gamedatas.players;

            if (gamedatas.nbreplayers == 3)
            {
                /*var element = document.getElementById("global");
                element.style.height = "2240px";*/
                dojo.query("#global").addClass("global3");
            }

            if (gamedatas.nbreplayers == 4)
            {
                /*var element = document.getElementById("global");
                element.style.height = "2985px";*/
                dojo.query("#global").addClass("global4");
            }

            if (gamedatas.nbreplayers == 1)
            {
                /*var element = document.getElementById("global");
                element.style.height = "1130px";*/
                dojo.query("#global").addClass("global1");
            }

            if (gamedatas.nbreplayers == 2)
            {
                dojo.query("#global").addClass("global2");
            }


            
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                         
                // TODO: Setting up players boards if needed
            }

            for( var cards in gamedatas.cards )
            {
                var card = gamedatas.cards[cards];
                this.addCard(card.id, card.color, card.player);
               
            }

            for( var familier in gamedatas.familier )
            {
                var cardfamilier = gamedatas.familier[familier];
                this.addFamilier(cardfamilier.player, cardfamilier.no);
                
               
            }

            for( var materia in gamedatas.materia)
            {
                var materia = gamedatas.materia[materia];
                this.addMateria(materia.id, materia.type, materia.type_arg, materia.location, materia.location_arg);
                
               
            }

            

            for( var player_id in gamedatas.players )
            {
            var player = gamedatas.players[player_id];
            var name1 = _(this.gamedatas.listeaide[1].name1);
            var name2 = _(this.gamedatas.listeaide[1].name2);
            var texte1 = _(this.gamedatas.listeaide[1].texte1);
            var texte2 = _(this.gamedatas.listeaide[1].texte2);
            var texte3 = _(this.gamedatas.listeaide[1].texte3);
            var texte4 = _(this.gamedatas.listeaide[1].texte4);
            var texte5 = _(this.gamedatas.listeaide[1].texte5);
            var texte6 = _(this.gamedatas.listeaide[1].texte6);
            
            var html = '<div class="anatooltip"><div class="anatcard">'+this.format_block('jstpl_aidetool',{name1:name1, name2:name2, texte1:texte1, texte2:texte2, texte3:texte3, texte4:texte4, texte5:texte5, texte6:texte6})+'</div></div>';
            this.addTooltipHtml( 'zonecard_8_'+player.id, html,500);
            }

            if (gamedatas.final >= 1)
            {
            dojo.query("#messagealerte").removeClass("masque");
            }

            for( var player_id in gamedatas.players )   
            {
                                 
                var player_board_div = $('player_board_'+player_id);
                dojo.place( this.format_block('jstpl_player_progession', {id: player_id, pos: gamedatas.players[player_id].pos } ), player_board_div );
                
                
            }

            if (gamedatas.nbreplayers != 1)
            {
            dojo.place( this.format_block('jstpl_firstplayer', {} ), 'player_board_'+gamedatas.firstplayer);
            dojo.query("#solo").addClass("masque");
            this.addTooltip( 'firstplayer', _('First Player'),'' );
            }

            if (gamedatas.nbreplayers == 1)
            {
                for( var player_id in gamedatas.players )   
            {
            dojo.place( this.format_block('jstpl_pannelia', {} ), 'player_board_'+gamedatas.firstplayer);
            this.addTooltip( 'iconesolo', _('Opponent score'),'' );
            }
            $('scoresolo').innerHTML = gamedatas.scoresolo;
            }
            
            


            for( var player_id in gamedatas.players )   
            {
                                 
                this.addTooltip( 'iconefamilier_'+player_id, _('Number of materia on the familiar'),'' );
                this.addTooltip( 'iconecard_'+player_id, _('Number of spells learned'),'' );
                
                
            }

            for( var player_id in gamedatas.players )   
            {
                                 
                
                $('nbrefamilier_'+player_id).innerHTML = gamedatas.nbrefamilier[player_id];
                $('nbrecard_'+player_id).innerHTML = gamedatas.nbrespell[player_id];
                
                
            }

            

            // TODO: Set up your game interface here, according to "gamedatas"
            
 
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            dojo.query(".materiacard").connect('onclick', this, 'onSelect' );

            console.log( "Ending game setup" );
        },
       
       
/////////////////////////////////////////////////////////////////////////////////   
//         _____ _        _            
//        / ____| |      | |           
//       | (___ | |_ __ _| |_ ___  ___ 
//        \___ \| __/ _` | __/ _ \/ __|
//        ____) | || (_| | ||  __/\__ \
//       |_____/ \__\__,_|\__\___||___/
//                                    
/////////////////////////////////////////////////////////////////////////////////                                        
  
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );

            dojo.query(".selectable").removeClass("selectable");
            dojo.query(".selected").removeClass("selected");
            dojo.query(".selectable2").removeClass("selectable2");
            dojo.query(".selected2").removeClass("selected2");
            dojo.query(".selected3").removeClass("selected3");
            dojo.query(".selectable2M").removeClass("selectable2M");
            dojo.query(".selected2M").removeClass("selected2M");
            dojo.query(".selectableswap2").removeClass("selectableswap2");
            dojo.query(".selectedswap2").removeClass("selectedswap2");
            dojo.query(".selectablestore").removeClass("selectablestore");
            dojo.query(".selectedstore").removeClass("selectedstore");
            dojo.query(".selectablemulti").removeClass("selectablemulti");
            dojo.query(".selectedmulti").removeClass("selectedmulti");
            
            


            switch( stateName )
            {
            
            case 'playerTurn':
                this.args = args.args;
                for( var sid in this.args.selectable)
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.selectable[sid]).addClass("selectable");
                        }
                    }
                
                for( var sid in this.args.selected)
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.selected[sid]).addClass("selected");
                        }
                    }

                for( var sid in this.args.selected2)
                {
                    if(this.isCurrentPlayerActive())
                    {
                    dojo.query("#"+this.args.selected2[sid]).addClass("selected2");
                    }
                }

                for( var sid in this.args.selectable2)
                {
                    if(this.isCurrentPlayerActive())
                    {
                    dojo.query("#"+this.args.selectable2[sid]).addClass("selectable2");
                    }
                }

                for( var sid in this.args.selected3)
                {
                    if(this.isCurrentPlayerActive())
                    {
                    dojo.query("#"+this.args.selected3[sid]).addClass("selected3");
                    }
                }

                for( var sid in this.args.selectable2M)
                {
                    if(this.isCurrentPlayerActive())
                    {
                    dojo.query("#"+this.args.selectable2M[sid]).addClass("selectable2M");
                    }
                }

                for( var sid in this.args.selectableswap2)
                {
                    if(this.isCurrentPlayerActive())
                    {
                    dojo.query("#"+this.args.selectableswap2[sid]).addClass("selectableswap2");
                    }
                }

                for( var sid in this.args.selectablestore)
                {
                    if(this.isCurrentPlayerActive())
                    {
                    dojo.query("#"+this.args.selectablestore[sid]).addClass("selectablestore");
                    }
                }

                for( var sid in this.args.selectablemulti)
                {
                    if(this.isCurrentPlayerActive())
                    {
                    dojo.query("#"+this.args.selectablemulti[sid]).addClass("selectablemulti");
                    }
                }



                this.gamedatas.gamestate.descriptionmyturn = _(this.args.titleyou);
                this.gamedatas.gamestate.description = _(this.args.title);
                this.updatePageTitle();
                
                break;
    
           
           
            case 'dummmy':
                break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {

                    case "playerTurn":
                        for( var nb in args.buttons )
 		                { 
                                 
                            if(args.buttons[nb] == "confirm")
                            {
                            this.addActionButton( 'confirm', _("Confirm") ,'onOpButton', null, null, 'red' );
                            }       
                            if(args.buttons[nb] == "cancel")
                            {
                            this.addActionButton( 'cancel', _("Cancel") ,'onOpButton', null, null, 'red' );
                            }
                            if(args.buttons[nb] == "pass")
                            {
                            this.addActionButton( 'pass', _("Pass") ,'onOpButton', null, null, 'red' );
                            }
                            if(args.buttons[nb] == "take")
                            {
                            this.addActionButton( 'take', _("Take 1 Materia") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "draw")
                            {
                            this.addActionButton( 'draw', _("Draw 2 Materia") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "draw1")
                            {
                            this.addActionButton( 'draw1', _("Draw 1 Materia") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "store")
                            {
                            this.addActionButton( 'store', _("Store") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "learn")
                            {
                            this.addActionButton( 'learn', _("Learn") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "validateselectionsoir13")
                            {
                            this.addActionButton( 'validateselectionsoir13', _("Validate selection") ,'onOpValidateselectionsoir', null, null, 'blue' );
                            dojo.addClass( 'validateselectionsoir13', 'disabled');
                                    var elements = document.querySelectorAll('.selected2');
                                    var nombreElements = elements.length;

                                    var boutonvalidate13 = document.getElementById('validateselectionsoir13');
                                    if (boutonvalidate13 !== null)
                                    {
                                        if (nombreElements >= 3)
                                        {
                                        dojo.removeClass( 'validateselectionsoir13', 'disabled');
                                        }
                                    }
                            }
                            if(args.buttons[nb] == "validateselectionsoir14")
                            {
                            this.addActionButton( 'validateselectionsoir14', _("Validate selection") ,'onOpValidateselectionsoir', null, null, 'blue' );
                            dojo.addClass( 'validateselectionsoir14', 'disabled');
                                    var elements = document.querySelectorAll('.selected2');
                                    var nombreElements = elements.length;

                                    var boutonvalidate14 = document.getElementById('validateselectionsoir14');
                                    if (boutonvalidate14 !== null)
                                    {
                                        if (nombreElements >= 4)
                                        {
                                        dojo.removeClass( 'validateselectionsoir14', 'disabled');
                                        }
                                    }
                            }
                            if(args.buttons[nb] == "validateselectionsoir15")
                            {
                            this.addActionButton( 'validateselectionsoir15', _("Validate selection") ,'onOpValidateselectionsoir', null, null, 'blue' );
                            dojo.addClass( 'validateselectionsoir15', 'disabled');
                                var elements = document.querySelectorAll('.selected2');
                                var nombreElements = elements.length;

                                var boutonvalidate15 = document.getElementById('validateselectionsoir15');
                                if (boutonvalidate15 !== null)
                                {
                                    if (nombreElements >= 5)
                                    {
                                    dojo.removeClass( 'validateselectionsoir15', 'disabled');
                                    }
                                }
                            }
                            if(args.buttons[nb] == "validateselectionsoir23")
                            {
                            this.addActionButton( 'validateselectionsoir23', _("Validate selection") ,'onOpValidateselectionsoir2', null, null, 'blue' );
                            dojo.addClass( 'validateselectionsoir23', 'disabled');
                            var elements = document.querySelectorAll('.selected2');
                                    var nombreElements = elements.length;

                                    var boutonvalidate23 = document.getElementById('validateselectionsoir23');
                                    if (boutonvalidate23 !== null)
                                    {
                                        if (nombreElements >= 3)
                                        {
                                        dojo.removeClass( 'validateselectionsoir23', 'disabled');
                                        }
                                    }
                            }
                            if(args.buttons[nb] == "validateselectionsoir24")
                            {
                            this.addActionButton( 'validateselectionsoir24', _("Validate selection") ,'onOpValidateselectionsoir2', null, null, 'blue' );
                            dojo.addClass( 'validateselectionsoir24', 'disabled');
                            var elements = document.querySelectorAll('.selected2');
                                    var nombreElements = elements.length;

                                    var boutonvalidate24 = document.getElementById('validateselectionsoir24');
                                    if (boutonvalidate24 !== null)
                                    {
                                        if (nombreElements >= 4)
                                        {
                                        dojo.removeClass( 'validateselectionsoir24', 'disabled');
                                        }
                                    }
                            }
                            if(args.buttons[nb] == "validateselectionsoir25")
                            {
                            this.addActionButton( 'validateselectionsoir25', _("Validate selection") ,'onOpValidateselectionsoir2', null, null, 'blue' );
                            dojo.addClass( 'validateselectionsoir25', 'disabled');
                            var elements = document.querySelectorAll('.selected2');
                                    var nombreElements = elements.length;

                                    var boutonvalidate25 = document.getElementById('validateselectionsoir25');
                                    if (boutonvalidate25 !== null)
                                    {
                                        if (nombreElements >= 5)
                                        {
                                        dojo.removeClass( 'validateselectionsoir25', 'disabled');
                                        }
                                    }
                            }
                            if(args.buttons[nb] == "cardaction")
                            {
                            this.addActionButton( 'cardaction', _("Card Action") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "level3")
                            {
                            this.addActionButton( 'level3', _("Level 3") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "level4")
                            {
                            this.addActionButton( 'level4', _("Level 4") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "level5")
                            {
                            this.addActionButton( 'level5', _("Level 5") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "validateselectioncard12")
                            {
                            this.addActionButton( 'validateselectioncard12', _("Validate selection") ,'onOpValidateselectioncard12', null, null, 'blue' );
                            dojo.addClass( 'validateselectioncard12', 'disabled');
                            }
                            if(args.buttons[nb] == "validateswap2autel")
                            {
                            this.addActionButton( 'validateswap2autel', _("Validate selection") ,'onOpValidateswap2autel', null, null, 'blue' );
                            dojo.addClass( 'validateswap2autel', 'disabled');
                            }
                            if(args.buttons[nb] == "validateswap2reserve")
                            {
                            this.addActionButton( 'validateswap2reserve', _("Validate selection") ,'onOpValidateswap2reserve', null, null, 'blue' );
                            dojo.addClass( 'validateswap2reserve', 'disabled');
                            }
                            if(args.buttons[nb] == "validateswap3autel")
                            {
                            this.addActionButton( 'validateswap3autel', _("Validate selection") ,'onOpValidateswap3autel', null, null, 'blue' );
                            dojo.addClass( 'validateswap3autel', 'disabled');
                            }
                            if(args.buttons[nb] == "validateswap3reserve")
                            {
                            this.addActionButton( 'validateswap3reserve', _("Validate selection") ,'onOpValidateswap3reserve', null, null, 'blue' );
                            dojo.addClass( 'validateswap3reserve', 'disabled');
                            }
                            if(args.buttons[nb] == "validatestore2")
                            {
                            this.addActionButton( 'validatestore2', _("Validate selection") ,'onOpValidatestore2', null, null, 'blue' );
                            dojo.addClass( 'validatestore2', 'disabled');
                            }
                            if(args.buttons[nb] == "validatestore3")
                            {
                            this.addActionButton( 'validatestore3', _("Validate selection") ,'onOpValidatestore3', null, null, 'blue' );
                            dojo.addClass( 'validatestore3', 'disabled');
                            }
                            if(args.buttons[nb] == "validatestore4")
                            {
                            this.addActionButton( 'validatestore4', _("Validate selection") ,'onOpValidatestore4', null, null, 'blue' );
                            dojo.addClass( 'validatestore4', 'disabled');
                            }
                            if(args.buttons[nb] == "validate22take2")
                            {
                            this.addActionButton( 'validate22take2', _("Validate selection") ,'onOpValidate22take2', null, null, 'blue' );
                            dojo.addClass( 'validate22take2', 'disabled');
                            }
                            if(args.buttons[nb] == "validate22take3")
                            {
                            this.addActionButton( 'validate22take3', _("Validate selection") ,'onOpValidate22take3', null, null, 'blue' );
                            dojo.addClass( 'validate22take3', 'disabled');
                            }
                            if(args.buttons[nb] == "validate23discard2")
                            {
                            this.addActionButton( 'validate23discard2', _("Validate selection") ,'onOpValidate23discard2', null, null, 'blue' );
                            dojo.addClass( 'validate23discard2', 'disabled');
                            }
                            if(args.buttons[nb] == "validate23discard3")
                            {
                            this.addActionButton( 'validate23discard3', _("Validate selection") ,'onOpValidate23discard3', null, null, 'blue' );
                            dojo.addClass( 'validate23discard3', 'disabled');
                            }
                            if(args.buttons[nb] == "card24store2")
                            {
                            this.addActionButton( 'card24store2', _("Store 2 Materia") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "card24take1")
                            {
                            this.addActionButton( 'card24take1', _("Take 1 Materia") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "validate24store2")
                            {
                            this.addActionButton( 'validate24store2', _("Validate selection") ,'onOpValidate24store2', null, null, 'blue' );
                            dojo.addClass( 'validate24store2', 'disabled');
                            }
                            if(args.buttons[nb] == "card24store3")
                            {
                            this.addActionButton( 'card24store3', _("Store 3 Materia") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "card24take2")
                            {
                            this.addActionButton( 'card24take2', _("Take 2 Materia") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "validate24take2")
                            {
                            this.addActionButton( 'validate24take2', _("Validate selection") ,'onOpValidate24take2', null, null, 'blue' );
                            dojo.addClass( 'validate24take2', 'disabled');
                            }
                            if(args.buttons[nb] == "validate24store3")
                            {
                            this.addActionButton( 'validate24store3', _("Validate selection") ,'onOpValidate24store3', null, null, 'blue' );
                            dojo.addClass( 'validate24store3', 'disabled');
                            }
                            if(args.buttons[nb] == "validate25selectautel")
                            {
                            this.addActionButton( 'validate25selectautel', _("Validate selection") ,'onOpValidate25selectautel', null, null, 'blue' );
                            dojo.addClass( 'validate25selectautel', 'disabled');
                            }
                            if(args.buttons[nb] == "validate25take3")
                            {
                            this.addActionButton( 'validate25take3', _("Validate selection") ,'onOpValidate25take3', null, null, 'blue' );
                            dojo.addClass( 'validate25take3', 'disabled');
                            }
                            if(args.buttons[nb] == "validate25take2")
                            {
                            this.addActionButton( 'validate25take2', _("Validate selection") ,'onOpValidate25take2', null, null, 'blue' );
                            dojo.addClass( 'validate25take2', 'disabled');
                            }
                            if(args.buttons[nb] == "takeonlyone")
                            {
                            this.addActionButton( 'takeonlyone', _("Take only 1 Materia") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "validate32take2")
                            {
                            this.addActionButton( 'validate32take2', _("Validate selection") ,'onOpValidate32take2', null, null, 'blue' );
                            dojo.addClass( 'validate32take2', 'disabled');
                            }
                            if(args.buttons[nb] == "validate33store2")
                            {
                            this.addActionButton( 'validate33store2', _("Validate selection") ,'onOpValidate33store2', null, null, 'blue' );
                            dojo.addClass( 'validate33store2', 'disabled');
                            }
                            if(args.buttons[nb] == "validate33store3")
                            {
                            this.addActionButton( 'validate33store3', _("Validate selection") ,'onOpValidate33store3', null, null, 'blue' );
                            dojo.addClass( 'validate33store3', 'disabled');
                            }
                            if(args.buttons[nb] == "validatebonuslearn")
                            {
                            this.addActionButton( 'validatebonuslearn', _("Validate selection") ,'onOpValidatebonuslearn', null, null, 'blue' );
                            dojo.addClass( 'validatebonuslearn', 'disabled');
                            }
                            if(args.buttons[nb] == "validate16bonuslearn")
                            {
                            this.addActionButton( 'validate16bonuslearn', _("Validate selection") ,'onOpValidate16bonuslearn', null, null, 'blue' );
                            dojo.addClass( 'validate16bonuslearn', 'disabled');
                            }
                            if(args.buttons[nb] == "validate37store3")
                            {
                            this.addActionButton( 'validate37store3', _("Validate selection") ,'onOpValidate37store3', null, null, 'blue' );
                            dojo.addClass( 'validate37store3', 'disabled');
                            }
                            if(args.buttons[nb] == "validate37store2")
                            {
                            this.addActionButton( 'validate37store2', _("Validate selection") ,'onOpValidate37store2', null, null, 'blue' );
                            dojo.addClass( 'validate37store2', 'disabled');
                            }
                            if(args.buttons[nb] == "0")
                            {
                            this.addActionButton( '0', _("- 0 -") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "1")
                            {
                            this.addActionButton( '1', _("- 1 -") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "2")
                            {
                            this.addActionButton( '2', _("- 2 -") ,'onOpButton', null, null, 'blue' );
                            }
                            if(args.buttons[nb] == "3")
                            {
                            this.addActionButton( '3', _("- 3 -") ,'onOpButton', null, null, 'blue' );
                            }
                            
                            
                            
                        }
                                  
                        
                        break;



                }
            }
        },   

     
/////////////////////////////////////////////////////////////////////////////////         
//   _    _ _   _ _ _ _                          _   _               _     
//  | |  | | | (_) (_) |                        | | | |             | |    
//  | |  | | |_ _| |_| |_ _   _   _ __ ___   ___| |_| |__   ___   __| |___ 
//  | |  | | __| | | | __| | | | | '_ ` _ \ / _ \ __| '_ \ / _ \ / _` / __|
//  | |__| | |_| | | | |_| |_| | | | | | | |  __/ |_| | | | (_) | (_| \__ \
//   \____/ \__|_|_|_|\__|\__, | |_| |_| |_|\___|\__|_| |_|\___/ \__,_|___/
//                         __/ |                                           
//                        |___/                                            
/////////////////////////////////////////////////////////////////////////////////  

attachToNewParentNoDestroy: function (mobile_in, new_parent_in, relation, place_position) 
        {
    
            const mobile = $(mobile_in);
            const new_parent = $(new_parent_in);

            var src = dojo.position(mobile);
            if (place_position)
                mobile.style.position = place_position;
            dojo.place(mobile, new_parent, relation);
            mobile.offsetTop;//force re-flow
            var tgt = dojo.position(mobile);
            var box = dojo.marginBox(mobile);
            var cbox = dojo.contentBox(mobile);
            var left = box.l + src.x - tgt.x;
            var top = box.t + src.y - tgt.y;

            mobile.style.position = "absolute";
            mobile.style.left = left + "px";
            mobile.style.top = top + "px";
            box.l += box.w - cbox.w;
            box.t += box.h - cbox.h;
            mobile.offsetTop;//force re-flow
            return box;
        },
 
addCard: function( set, color, player )  
{
    var img = g_gamethemeurl+"img/cardset"+set+".png";

    if(color <= 4)
    {
    dojo.place( this.format_block( 'jstpl_card', {
        color: color,
        player_id: player,
        imgfull: img,
        x: (color-1)*(-100),
        y: 0,
                               
    } ) , 'zonecard_'+color+'_'+player );

        var type = set+color;
        var name = _(this.gamedatas.listecards[type].name);
        var description3 = _(this.gamedatas.listecards[type].description3);
        var description4 = _(this.gamedatas.listecards[type].description4);
        var description5 = _(this.gamedatas.listecards[type].description5);
    
        if(set ==1)
        {
        var html = '<div class="anatooltip"><div class="anatcard">'+this.format_block('jstpl_cardtool1',{name: name, description3: description3, description4: description4, description5: description5, x: (color-1)*(-100), y: 0})+'</div></div>';
        this.addTooltipHtml( 'card_'+color+'_'+player, html,500);
        }
        if(set ==2)
        {
        var html = '<div class="anatooltip"><div class="anatcard">'+this.format_block('jstpl_cardtool2',{name: name, description3: description3, description4: description4, description5: description5, x: (color-1)*(-100), y: 0})+'</div></div>';
        this.addTooltipHtml( 'card_'+color+'_'+player, html,500);
        }
        if(set ==3)
        {
        var html = '<div class="anatooltip"><div class="anatcard">'+this.format_block('jstpl_cardtool3',{name: name, description3: description3, description4: description4, description5: description5, x: (color-1)*(-100), y: 0})+'</div></div>';
        this.addTooltipHtml( 'card_'+color+'_'+player, html,500);
        }

    } 

    if(color >= 5)
    {
    dojo.place( this.format_block( 'jstpl_card', {
        color: color,
        player_id: player,
        imgfull: img,
        x: (color-5)*(-100),
        y: -100,
                               
    } ) , 'zonecard_'+color+'_'+player ); 

        var type = set+color;
        var name = _(this.gamedatas.listecards[type].name);
        var description3 = _(this.gamedatas.listecards[type].description3);
        var description4 = _(this.gamedatas.listecards[type].description4);
        var description5 = _(this.gamedatas.listecards[type].description5);

        if(set ==1)
        {
        var html = '<div class="anatooltip"><div class="anatcard">'+this.format_block('jstpl_cardtool1',{name: name, description3: description3, description4: description4, description5: description5, x: (color-5)*(-100), y: -100})+'</div></div>';
        this.addTooltipHtml( 'card_'+color+'_'+player, html,500);
        }
        if(set ==2)
        {
        var html = '<div class="anatooltip"><div class="anatcard">'+this.format_block('jstpl_cardtool2',{name: name, description3: description3, description4: description4, description5: description5, x: (color-5)*(-100), y: -100})+'</div></div>';
        this.addTooltipHtml( 'card_'+color+'_'+player, html,500);
        }
        if(set ==3)
        {
        var html = '<div class="anatooltip"><div class="anatcard">'+this.format_block('jstpl_cardtool3',{name: name, description3: description3, description4: description4, description5: description5, x: (color-5)*(-100), y: -100})+'</div></div>';
        this.addTooltipHtml( 'card_'+color+'_'+player, html,500);
        }

    } 
    
    dojo.query("#card_"+color+'_'+player).connect('onclick', this, 'onSelect' ); 
    

},

addFamilier: function( player, no)  
{
    
    dojo.place( this.format_block( 'jstpl_familier', {
        player_id: player,
        x: (no-1)*(-100),
                               
    } ) , 'zonefamilier_'+player); 
   
                        
},

addMateria: function(id, color, rune, location, emplacement)  
{
    if ((emplacement != 0)&&(emplacement <= 14)) 
    {
    dojo.place( this.format_block( 'jstpl_materia', {
        x: (rune-1)*(-100),
        y: (color-1)*(-100),
        id: id,

                               
    } ) , location+'_'+emplacement); 
   
    dojo.query("#materia_"+id).connect('onclick', this, 'onSelect' );
    }

    if (emplacement == 0) // dans le cas où le materia est sur le discard du clone
    {
    dojo.place( this.format_block( 'jstpl_materia', {
        x: (rune-1)*(-100),
        y: (color-1)*(-100),
        id: id,

                               
    } ) , location); 

    dojo.query("#materia_"+id).connect('onclick', this, 'onSelect' );
   
    
    }
          
                        
},




/////////////////////////////////////////////////////////////////////////////////  
//         _____  _                       _                  _   _             
//        |  __ \| |                     ( )                | | (_)            
//        | |__) | | __ _ _   _  ___ _ __|/ ___    __ _  ___| |_ _  ___  _ __  
//        |  ___/| |/ _` | | | |/ _ \ '__| / __|  / _` |/ __| __| |/ _ \| '_ \ 
//        | |    | | (_| | |_| |  __/ |    \__ \ | (_| | (__| |_| | (_) | | | |
//        |_|    |_|\__,_|\__, |\___|_|    |___/  \__,_|\___|\__|_|\___/|_| |_|
//                         __/ |                                               
//                        |___/                                                
/////////////////////////////////////////////////////////////////////////////////  

        
        onSelect: function(evt)
        {        	 
            // Preventing default browser reaction
             dojo.stopEvent( evt );

            
             
            if( !this.isCurrentPlayerActive() || (!(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti'))))
                {   
                    return; 
                }
            
            if(this.isCurrentPlayerActive() && evt.currentTarget.classList.contains('selectable') && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                this.ajaxcall( "/spellbook/spellbook/actSelect.html", { 
                    lock: true,
                    arg1: evt.currentTarget.id
                    
                 }, 
                 this, function( result ) {}, function( is_error) {} );
            }

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && evt.currentTarget.classList.contains('selectable2') && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selectable2");
                dojo.query("#"+evt.currentTarget.id).addClass("selected2");

                var elements = document.querySelectorAll('.selected2');
                var nombreElements = elements.length;

                var boutonvalidate13 = document.getElementById('validateselectionsoir13');
                var boutonvalidate14 = document.getElementById('validateselectionsoir14');
                var boutonvalidate15 = document.getElementById('validateselectionsoir15');
                var boutonvalidate23 = document.getElementById('validateselectionsoir23');
                var boutonvalidate24 = document.getElementById('validateselectionsoir24');
                var boutonvalidate25 = document.getElementById('validateselectionsoir25');
                

                if (boutonvalidate13 !== null)
                {
                    if (nombreElements >= 3)
                    {
                    dojo.removeClass( 'validateselectionsoir13', 'disabled');
                    }
                }

                if (boutonvalidate14 !== null)
                {
                    if (nombreElements >= 4)
                    {
                    dojo.removeClass( 'validateselectionsoir14', 'disabled');
                    }
                }

                if (boutonvalidate15 !== null)
                {
                    if (nombreElements >= 5)
                    {
                    dojo.removeClass( 'validateselectionsoir15', 'disabled');
                    }
                }

                if (boutonvalidate23 !== null)
                {
                    if (nombreElements >= 3)
                    {
                    dojo.removeClass( 'validateselectionsoir23', 'disabled');
                    }
                }

                if (boutonvalidate24 !== null)
                {
                    if (nombreElements >= 4)
                    {
                    dojo.removeClass( 'validateselectionsoir24', 'disabled');
                    }
                }

                if (boutonvalidate25 !== null)
                {
                    if (nombreElements >= 5)
                    {
                    dojo.removeClass( 'validateselectionsoir25', 'disabled');
                    }
                }
                
                                    
                
            }

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && evt.currentTarget.classList.contains('selected2') && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selected2");
                dojo.query("#"+evt.currentTarget.id).addClass("selectable2");

                var elements = document.querySelectorAll('.selected2');
                var nombreElements = elements.length;

                var boutonvalidate13 = document.getElementById('validateselectionsoir13');
                var boutonvalidate14 = document.getElementById('validateselectionsoir14');
                var boutonvalidate15 = document.getElementById('validateselectionsoir15');
                var boutonvalidate23 = document.getElementById('validateselectionsoir23');
                var boutonvalidate24 = document.getElementById('validateselectionsoir24');
                var boutonvalidate25 = document.getElementById('validateselectionsoir25');

                
                if (boutonvalidate13 !== null)
                {
                if (nombreElements < 3)
                {
                    dojo.addClass( 'validateselectionsoir13', 'disabled');
                }
                }
                if (boutonvalidate14 !== null)
                {
                if (nombreElements < 4)
                {
                    dojo.addClass( 'validateselectionsoir14', 'disabled');
                }
                }
                if (boutonvalidate15 !== null)
                {
                if (nombreElements < 5)
                {
                    dojo.addClass( 'validateselectionsoir15', 'disabled');
                }
                }

                if (boutonvalidate23 !== null)
                {
                if (nombreElements < 3)
                {
                    dojo.addClass( 'validateselectionsoir23', 'disabled');
                }
                }
                if (boutonvalidate24 !== null)
                {
                if (nombreElements < 4)
                {
                    dojo.addClass( 'validateselectionsoir24', 'disabled');
                }
                }
                if (boutonvalidate25 !== null)
                {
                if (nombreElements < 5)
                {
                    dojo.addClass( 'validateselectionsoir25', 'disabled');
                }
                }
                 
            }

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && evt.currentTarget.classList.contains('selectable2M') && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selectable2M");
                dojo.query("#"+evt.currentTarget.id).addClass("selected2M");

                var elements = document.querySelectorAll('.selected2M');
                var nombreElements = elements.length;
              
                if (nombreElements == 2)
                {
                dojo.removeClass( 'validateselectioncard12', 'disabled');
                }
                if (nombreElements != 2)
                {
                dojo.addClass( 'validateselectioncard12', 'disabled');
                }
                                                    
                
            }

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && evt.currentTarget.classList.contains('selected2M') && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selected2M");
                dojo.query("#"+evt.currentTarget.id).addClass("selectable2M");

                var elements = document.querySelectorAll('.selected2M');
                var nombreElements = elements.length;

                if (nombreElements == 2)
                {
                dojo.removeClass( 'validateselectioncard12', 'disabled');
                }
                if (nombreElements != 2)
                {
                dojo.addClass( 'validateselectioncard12', 'disabled');
                }
                                 
            }

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && evt.currentTarget.classList.contains('selectableswap2') && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selectableswap2");
                dojo.query("#"+evt.currentTarget.id).addClass("selectedswap2");

                var elements = document.querySelectorAll('.selectedswap2');
                var nombreElements = elements.length;

                var boutonautel = document.getElementById('validateswap2autel');
                var boutonreserve = document.getElementById('validateswap2reserve');
                var boutonautel2 = document.getElementById('validateswap3autel');
                var boutonreserve2 = document.getElementById('validateswap3reserve');
              
                if (boutonautel !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validateswap2autel', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validateswap2autel', 'disabled');
                    }
                }
                if (boutonreserve !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validateswap2reserve', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validateswap2reserve', 'disabled');
                    }
                }

                if (boutonautel2 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validateswap3autel', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validateswap3autel', 'disabled');
                    }
                }
                if (boutonreserve2 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validateswap3reserve', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validateswap3reserve', 'disabled');
                    }
                }
                                                    
                
            }

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && evt.currentTarget.classList.contains('selectedswap2') && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selectedswap2");
                dojo.query("#"+evt.currentTarget.id).addClass("selectableswap2");

                var elements = document.querySelectorAll('.selectedswap2');
                var nombreElements = elements.length;

                var boutonautel = document.getElementById('validateswap2autel');
                var boutonreserve = document.getElementById('validateswap2reserve');
                var boutonautel2 = document.getElementById('validateswap3autel');
                var boutonreserve2 = document.getElementById('validateswap3reserve');
              
                if (boutonautel !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validateswap2autel', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validateswap2autel', 'disabled');
                    }
                }
                if (boutonreserve !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validateswap2reserve', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validateswap2reserve', 'disabled');
                    }
                }

                if (boutonautel2 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validateswap3autel', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validateswap3autel', 'disabled');
                    }
                }
                if (boutonreserve2 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validateswap3reserve', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validateswap3reserve', 'disabled');
                    }
                }
                                 
            }

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && evt.currentTarget.classList.contains('selectablestore') && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selectablestore");
                dojo.query("#"+evt.currentTarget.id).addClass("selectedstore");

                var elements = document.querySelectorAll('.selectedstore');
                var nombreElements = elements.length;

                var boutonvalidate1 = document.getElementById('validatestore2');
                var boutonvalidate2 = document.getElementById('validatestore3');
                var boutonvalidate3 = document.getElementById('validatestore4');
                
              
                if (boutonvalidate1 !== null)
                {
                    if (nombreElements == 1)
                    {
                    dojo.removeClass( 'validatestore2', 'disabled');
                    }
                    if (nombreElements != 1)
                    {
                    dojo.addClass( 'validatestore2', 'disabled');
                    }
                }
                if (boutonvalidate2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validatestore3', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validatestore3', 'disabled');
                    }
                }

                if (boutonvalidate3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validatestore4', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validatestore4', 'disabled');
                    }
                }
                                                                    
                
            }

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && evt.currentTarget.classList.contains('selectedstore') && !(evt.currentTarget.classList.contains('selectablemulti')) && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selectedstore");
                dojo.query("#"+evt.currentTarget.id).addClass("selectablestore");

                var elements = document.querySelectorAll('.selectedstore');
                var nombreElements = elements.length;

                var boutonvalidate1 = document.getElementById('validatestore2');
                var boutonvalidate2 = document.getElementById('validatestore3');
                var boutonvalidate3 = document.getElementById('validatestore4');
              
                if (boutonvalidate1 !== null)
                {
                    if (nombreElements == 1)
                    {
                    dojo.removeClass( 'validatestore2', 'disabled');
                    }
                    if (nombreElements != 1)
                    {
                    dojo.addClass( 'validatestore2', 'disabled');
                    }
                }
                if (boutonvalidate2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validatestore3', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validatestore3', 'disabled');
                    }
                }

                if (boutonvalidate3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validatestore4', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validatestore4', 'disabled');
                    }
                }
                
                                 
            }

            // multi select

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && evt.currentTarget.classList.contains('selectablemulti') && !(evt.currentTarget.classList.contains('selectedmulti')) && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selectablemulti");
                dojo.query("#"+evt.currentTarget.id).addClass("selectedmulti");

                var elements = document.querySelectorAll('.selectedmulti');
                var nombreElements = elements.length;

                var boutonvalidate22take2 = document.getElementById('validate22take2');
                var boutonvalidate22take3 = document.getElementById('validate22take3');
                var boutonvalidate23discard2 = document.getElementById('validate23discard2');
                var boutonvalidate23discard3 = document.getElementById('validate23discard3');
                var boutonvalidate24store2 = document.getElementById('validate24store2');
                var boutonvalidate24take2 = document.getElementById('validate24take2');
                var boutonvalidate24store3 = document.getElementById('validate24store3');
                var boutonvalidate25selectautel = document.getElementById('validate25selectautel');
                var boutonvalidate25take3 = document.getElementById('validate25take3');
                var boutonvalidate25take2 = document.getElementById('validate25take2');
                var boutonvalidate32take2 = document.getElementById('validate32take2');
                var boutonvalidate33store2 = document.getElementById('validate33store2');
                var boutonvalidate33store3 = document.getElementById('validate33store3');
                var boutonvalidatebonuslearn = document.getElementById('validatebonuslearn');
                var boutonvalidate16bonuslearn = document.getElementById('validate16bonuslearn');
                var boutonvalidate37store3 = document.getElementById('validate37store3');
                var boutonvalidate37store2 = document.getElementById('validate37store2');
                
                if (boutonvalidate22take2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate22take2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate22take2', 'disabled');
                    }
                }

                if (boutonvalidate22take3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate22take3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate22take3', 'disabled');
                    }
                }

                if (boutonvalidate23discard2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate23discard2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate23discard2', 'disabled');
                    }
                }

                if (boutonvalidate23discard3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate23discard3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate23discard3', 'disabled');
                    }
                }

                if (boutonvalidate24store2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate24store2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate24store2', 'disabled');
                    }
                }

                if (boutonvalidate24store3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate24store3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate24store3', 'disabled');
                    }
                }

                if (boutonvalidate24take2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate24take2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate24take2', 'disabled');
                    }
                }

                if (boutonvalidate25selectautel !== null)
                {
                    if ((nombreElements >= 1)&&(nombreElements <= 10))
                    {
                    dojo.removeClass( 'validate25selectautel', 'disabled');
                    }
                    if ((nombreElements < 1)||(nombreElements > 10))
                    {
                    dojo.addClass( 'validate25selectautel', 'disabled');
                    }
                }

                if (boutonvalidate25take3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate25take3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate25take3', 'disabled');
                    }
                }

                if (boutonvalidate25take2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate25take2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate25take2', 'disabled');
                    }
                }

                if (boutonvalidate32take2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate32take2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate32take2', 'disabled');
                    }
                }

                if (boutonvalidate33store2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate33store2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate33store2', 'disabled');
                    }
                }

                if (boutonvalidate33store3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate33store3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate33store3', 'disabled');
                    }
                }

                if (boutonvalidatebonuslearn !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validatebonuslearn', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validatebonuslearn', 'disabled');
                    }
                }

                if (boutonvalidate16bonuslearn !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate16bonuslearn', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate16bonuslearn', 'disabled');
                    }
                }

                if (boutonvalidate37store3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate37store3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate37store3', 'disabled');
                    }
                }

                if (boutonvalidate37store2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate37store2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate37store2', 'disabled');
                    }
                }

                
                                                                    
                
            }

            else if(this.isCurrentPlayerActive() && !(evt.currentTarget.classList.contains('selectable')) && !(evt.currentTarget.classList.contains('selectable2')) && !(evt.currentTarget.classList.contains('selected2')) && !(evt.currentTarget.classList.contains('selectable2M')) && !(evt.currentTarget.classList.contains('selected2M')) && !(evt.currentTarget.classList.contains('selectableswap2')) && !(evt.currentTarget.classList.contains('selectedswap2')) && !(evt.currentTarget.classList.contains('selectablestore')) && !(evt.currentTarget.classList.contains('selectedstore')) && !(evt.currentTarget.classList.contains('selectablemulti')) && evt.currentTarget.classList.contains('selectedmulti') && this.checkAction( "actSelect" ))
            {
                
                dojo.query("#"+evt.currentTarget.id).removeClass("selectedmulti");
                dojo.query("#"+evt.currentTarget.id).addClass("selectablemulti");

                var elements = document.querySelectorAll('.selectedmulti');
                var nombreElements = elements.length;

                var boutonvalidate22take2 = document.getElementById('validate22take2');
                var boutonvalidate22take3 = document.getElementById('validate22take3');
                var boutonvalidate23discard2 = document.getElementById('validate23discard2');
                var boutonvalidate23discard3 = document.getElementById('validate23discard3');
                var boutonvalidate24store2 = document.getElementById('validate24store2');
                var boutonvalidate24take2 = document.getElementById('validate24take2');
                var boutonvalidate24store3 = document.getElementById('validate24store3');
                var boutonvalidate25selectautel = document.getElementById('validate25selectautel');
                var boutonvalidate25take3 = document.getElementById('validate25take3');
                var boutonvalidate25take2 = document.getElementById('validate25take2');
                var boutonvalidate32take2 = document.getElementById('validate32take2');
                var boutonvalidate33store2 = document.getElementById('validate33store2');
                var boutonvalidate33store3 = document.getElementById('validate33store3');
                var boutonvalidatebonuslearn = document.getElementById('validatebonuslearn');
                var boutonvalidate16bonuslearn = document.getElementById('validate16bonuslearn');
                var boutonvalidate37store3 = document.getElementById('validate37store3');
                var boutonvalidate37store2 = document.getElementById('validate37store2');
                
                if (boutonvalidate22take2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate22take2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate22take2', 'disabled');
                    }
                }

                if (boutonvalidate22take3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate22take3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate22take3', 'disabled');
                    }
                }

                if (boutonvalidate23discard2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate23discard2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate23discard2', 'disabled');
                    }
                }

                if (boutonvalidate23discard3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate23discard3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate23discard3', 'disabled');
                    }
                }

                
                if (boutonvalidate24store2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate24store2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate24store2', 'disabled');
                    }
                }

                if (boutonvalidate24store3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate24store3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate24store3', 'disabled');
                    }
                }

                if (boutonvalidate24take2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate24take2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate24take2', 'disabled');
                    }
                }

                if (boutonvalidate25selectautel !== null)
                {
                    if ((nombreElements >= 1)&&(nombreElements <= 10))
                    {
                    dojo.removeClass( 'validate25selectautel', 'disabled');
                    }
                    if ((nombreElements < 1)||(nombreElements > 10))
                    {
                    dojo.addClass( 'validate25selectautel', 'disabled');
                    }
                }

                if (boutonvalidate25take3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate25take3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate25take3', 'disabled');
                    }
                }

                if (boutonvalidate25take2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate25take2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate25take2', 'disabled');
                    }
                }

                if (boutonvalidate32take2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate32take2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate32take2', 'disabled');
                    }
                }

                if (boutonvalidate33store2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate33store2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate33store2', 'disabled');
                    }
                }

                if (boutonvalidate33store3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate33store3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate33store3', 'disabled');
                    }
                }

                if (boutonvalidatebonuslearn !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validatebonuslearn', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validatebonuslearn', 'disabled');
                    }
                }

                if (boutonvalidate16bonuslearn !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate16bonuslearn', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate16bonuslearn', 'disabled');
                    }
                }

                
                if (boutonvalidate37store3 !== null)
                {
                    if (nombreElements == 3)
                    {
                    dojo.removeClass( 'validate37store3', 'disabled');
                    }
                    if (nombreElements != 3)
                    {
                    dojo.addClass( 'validate37store3', 'disabled');
                    }
                }

                if (boutonvalidate37store2 !== null)
                {
                    if (nombreElements == 2)
                    {
                    dojo.removeClass( 'validate37store2', 'disabled');
                    }
                    if (nombreElements != 2)
                    {
                    dojo.addClass( 'validate37store2', 'disabled');
                    }
                }
                
                
                                 
            }

            


        },

        onOpButton: function(evt)
        {
            dojo.stopEvent( evt );
            this.ajaxcall( "/spellbook/spellbook/actButton.html", { 
                lock: true,
                arg1: evt.currentTarget.id
                            
                }, 
                this, function( result ) {}, function( is_error) {} );

        },

        onOpValidateselectionsoir: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selected2");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

        const nombreElements = ids.length;

        for (var i=nombreElements; i<=8; i++)
        {
            ids.push(0);
        }

        
        this.ajaxcall( "/spellbook/spellbook/actValidateselectionsoir.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2],
                arg4: ids[3],
                arg5: ids[4],
                arg6: ids[5],
                arg7: ids[6],
                arg8: ids[7],
                arg9: ids[8]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidateselectionsoir2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selected2");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

        const nombreElements = ids.length;

        for (var i=nombreElements; i<=8; i++)
        {
            ids.push(0);
        }

        
        this.ajaxcall( "/spellbook/spellbook/actValidateselectionsoir2.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2],
                arg4: ids[3],
                arg5: ids[4],
                arg6: ids[5],
                arg7: ids[6],
                arg8: ids[7],
                arg9: ids[8]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidateselectioncard12: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selected2M");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

                
        this.ajaxcall( "/spellbook/spellbook/actValidateselectioncard12.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidateswap2autel: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedswap2");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

                
        this.ajaxcall( "/spellbook/spellbook/actValidateswap2autel.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidateswap2reserve: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse1 = document.querySelectorAll(".selected3");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids1 = Array.from(elementsAvecClasse1, element => element.id);

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse2 = document.querySelectorAll(".selectedswap2");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids2 = Array.from(elementsAvecClasse2, element => element.id);

                
        this.ajaxcall( "/spellbook/spellbook/actValidateswap2reserve.html", { 
                lock: true,
                arg1: ids1[0],
                arg2: ids1[1],
                arg3: ids2[0],
                arg4: ids2[1]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidateswap3autel: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedswap2");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

                
        this.ajaxcall( "/spellbook/spellbook/actValidateswap3autel.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidateswap3reserve: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse1 = document.querySelectorAll(".selected3");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids1 = Array.from(elementsAvecClasse1, element => element.id);

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse2 = document.querySelectorAll(".selectedswap2");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids2 = Array.from(elementsAvecClasse2, element => element.id);

                
        this.ajaxcall( "/spellbook/spellbook/actValidateswap3reserve.html", { 
                lock: true,
                arg1: ids1[0],
                arg2: ids1[1],
                arg3: ids1[2],
                arg4: ids2[0],
                arg5: ids2[1],
                arg6: ids2[2]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidatestore2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse1 = document.querySelectorAll(".selected3");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids1 = Array.from(elementsAvecClasse1, element => element.id);

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse2 = document.querySelectorAll(".selectedstore");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids2 = Array.from(elementsAvecClasse2, element => element.id);

                
        this.ajaxcall( "/spellbook/spellbook/actValidatestore2.html", { 
                lock: true,
                arg1: ids1[0],
                arg2: ids2[0]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidatestore3: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse1 = document.querySelectorAll(".selected3");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids1 = Array.from(elementsAvecClasse1, element => element.id);

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse2 = document.querySelectorAll(".selectedstore");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids2 = Array.from(elementsAvecClasse2, element => element.id);

                
        this.ajaxcall( "/spellbook/spellbook/actValidatestore3.html", { 
                lock: true,
                arg1: ids1[0],
                arg2: ids2[0],
                arg3: ids2[1]
                
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidatestore4: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse1 = document.querySelectorAll(".selected3");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids1 = Array.from(elementsAvecClasse1, element => element.id);

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse2 = document.querySelectorAll(".selectedstore");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids2 = Array.from(elementsAvecClasse2, element => element.id);

                
        this.ajaxcall( "/spellbook/spellbook/actValidatestore4.html", { 
                lock: true,
                arg1: ids1[0],
                arg2: ids2[0],
                arg3: ids2[1],
                arg4: ids2[2]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate22take2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate22take2.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate22take3: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate22take3.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate23discard2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate23discard2.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate23discard3: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate23discard3.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate24store2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate24store2.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate24take2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate24take2.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate24store3: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate24store3.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate25selectautel: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

        const nombreElements = ids.length;

        for (var i=nombreElements; i<=9; i++)
        {
            ids.push(0);
        }

        
        this.ajaxcall( "/spellbook/spellbook/actValidate25selectautel.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2],
                arg4: ids[3],
                arg5: ids[4],
                arg6: ids[5],
                arg7: ids[6],
                arg8: ids[7],
                arg9: ids[8],
                arg10: ids[9]
                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate25take3: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate25take3.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate25take2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate25take2.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate32take2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate32take2.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate33store2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate33store2.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate33store3: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate33store3.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidatebonuslearn: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidatebonuslearn.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate16bonuslearn: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate16bonuslearn.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate37store3: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate37store3.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1],
                arg3: ids[2]
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },

        onOpValidate37store2: function(evt)

        {
            dojo.stopEvent( evt );

        // Sélectionnez tous les éléments avec la classe spécifiée
        const elementsAvecClasse = document.querySelectorAll(".selectedmulti");

        // Convertissez la NodeList en un tableau et extrayez les IDs
        const ids = Array.from(elementsAvecClasse, element => element.id);

       
                
        this.ajaxcall( "/spellbook/spellbook/actValidate37store2.html", { 
                lock: true,
                arg1: ids[0],
                arg2: ids[1]
            
                                            
                }, 
        this, function( result ) {}, function( is_error) {} );

        },
        
        

///////////////////////////////////////////////////////////////////////////////// 
//       _   _       _   _  __ _           _   _                 
//      | \ | |     | | (_)/ _(_)         | | (_)                
//      |  \| | ___ | |_ _| |_ _  ___ __ _| |_ _  ___  _ __  ___ 
//      | . ` |/ _ \| __| |  _| |/ __/ _` | __| |/ _ \| '_ \/ __|
//      | |\  | (_) | |_| | | | | (_| (_| | |_| | (_) | | | \__ \
//      |_| \_|\___/ \__|_|_| |_|\___\__,_|\__|_|\___/|_| |_|___/
//                                                                 
/////////////////////////////////////////////////////////////////////////////////  


        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );
            
            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
            dojo.subscribe( 'move', this, "notif_move" );
            dojo.subscribe( 'draw', this, "notif_draw" );
            dojo.subscribe( 'discard', this, "notif_discard" );
            dojo.subscribe( 'score', this, "notif_score" );
            dojo.subscribe( 'alerte', this, "notif_alerte" );
            dojo.subscribe( 'pannel', this, "notif_pannel" );
            dojo.subscribe( 'pannelsolo', this, "notif_pannelsolo" );
        },  
        
        notif_move: function( notif )
            {
                /*const mobile = document.getElementById(notif.args.mobile);
                const target = document.getElementById(notif.args.parent);
                mobile.style.left = (mobile.offsetLeft - target.offsetLeft) + "px";
                mobile.style.top = (mobile.offsetTop - target.offsetTop) + "px";
                dojo.place(mobile, target);
                mobile.offsetTop;//force re-flow
                mobile.style.left = "0px";
                mobile.style.top = "0px";*/

                this.attachToNewParentNoDestroy( notif.args.mobile, notif.args.parent );
                this.slideToObject( notif.args.mobile, notif.args.parent ).play();
            },

        notif_draw: function( notif )
            {
                this.addMateria (notif.args.id, notif.args.color, notif.args.rune, notif.args.location, notif.args.emplacement);
                this.placeOnObject( 'materia_'+notif.args.id, 'pochon' );
                this.slideToObject( 'materia_'+notif.args.id, notif.args.location+'_'+notif.args.emplacement).play();
            },
        
        notif_discard: function( notif )
        {
            this.fadeOutAndDestroy ('materia_'+notif.args.mobile, 1000, 100);
        },

        notif_score: function( notif )
        {
            this.scoreCtrl[ notif.args.player ].toValue( notif.args.score );
        },

        notif_alerte: function( notif )
        {
            dojo.query("#messagealerte").removeClass("masque");
        },

        notif_pannel: function( notif )
        {
            $('nbrefamilier_'+notif.args.id).innerHTML = notif.args.familier;
            $('nbrecard_'+notif.args.id).innerHTML = notif.args.spell;
        },

        notif_pannelsolo: function( notif )
        {
            $('scoresolo').innerHTML = notif.args.score;
            
        },



        


   });             
});
