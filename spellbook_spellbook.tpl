{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- spellbook implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

-->

<div id="pochon"></div>
<div id="global">

        <div id="messagealerte" class="masque">{LASTTURN}</div>
        
        <div id="autel">
        <div id="materiaautel_1" class="materiacard" style="left: 39px; top: 43px;"></div>
        <div id="materiaautel_2" class="materiacard" style="left: 97px; top: 39px;"></div>
        <div id="materiaautel_3" class="materiacard" style="left: 156px; top: 36px;"></div>
        <div id="materiaautel_4" class="materiacard" style="left: 215px; top: 36px;"></div>
        <div id="materiaautel_5" class="materiacard" style="left: 275px; top: 38px;"></div>
        <div id="materiaautel_6" class="materiacard" style="left: 266px; top: 91px;"></div>
        <div id="materiaautel_7" class="materiacard" style="left: 208px; top: 91px;"></div>
        <div id="materiaautel_8" class="materiacard" style="left: 149px; top: 100px;"></div>
        <div id="materiaautel_9" class="materiacard" style="left: 91px; top: 95px;"></div>
        <div id="materiaautel_10" class="materiacard" style="left: 31px; top: 93px;"></div>
        <div id="materiaautel_11" class="materiacard" style="left: 97px; top: -15px;"></div>
        <div id="materiaautel_12" class="materiacard" style="left: 156px; top: -15px;"></div>
        <div id="materiaautel_13" class="materiacard" style="left: 215px; top: -15px;"></div>
        <div id="materiaautel_14" class="materiacard" style="left: 39px; top: -15px;"></div>
        <div id="materiaautel_15" class="materiacard" style="left: 275px; top: -15px;"></div>
        <div id="materiaautel_16" class="materiacard" style="left: 266px; top: 140px;"></div>
        </div>



        <!-- BEGIN player -->
        <div id="playername_{PLAYER_ID}" class="playername pos{POS}" style="color:#{COLOR}; outline: 0.5px solid #{COLOR};">{PLAYER_NAME}</div>
        <div id="playerzone_{PLAYER_ID}" class="playerzone">

        <div id="playerzonecard_{PLAYER_ID}" class="playerzonecard">

                <div id="zonecard_1_{PLAYER_ID}" class="zonecard" style="right: 615px;">
                <div id="materiacard_1_{PLAYER_ID}_3" class="materiacard" style="left: 12px; top: 198px;"></div>
                <div id="materiacard_1_{PLAYER_ID}_2" class="materiacard" style="left: 12px; top: 244px;"></div>
                <div id="materiacard_1_{PLAYER_ID}_1" class="materiacard" style="left: 12px; top: 291px;"></div>             
                </div>

                <div id="zonecard_2_{PLAYER_ID}" class="zonecard" style="right: 410px;">
                <div id="materiacard_2_{PLAYER_ID}_3" class="materiacard" style="left: 12px; top: 198px;"></div>
                <div id="materiacard_2_{PLAYER_ID}_2" class="materiacard" style="left: 12px; top: 244px;"></div>
                <div id="materiacard_2_{PLAYER_ID}_1" class="materiacard" style="left: 12px; top: 291px;"></div>   
                </div>

                <div id="zonecard_3_{PLAYER_ID}" class="zonecard" style="right: 205px;">
                <div id="materiacard_3_{PLAYER_ID}_3" class="materiacard" style="left: 12px; top: 198px;"></div>
                <div id="materiacard_3_{PLAYER_ID}_2" class="materiacard" style="left: 12px; top: 244px;"></div>
                <div id="materiacard_3_{PLAYER_ID}_1" class="materiacard" style="left: 12px; top: 291px;"></div>   
                </div>

                <div id="zonecard_4_{PLAYER_ID}" class="zonecard" style="right: 0px;">
                <div id="materiacard_4_{PLAYER_ID}_3" class="materiacard" style="left: 12px; top: 198px;"></div>
                <div id="materiacard_4_{PLAYER_ID}_2" class="materiacard" style="left: 12px; top: 244px;"></div>
                <div id="materiacard_4_{PLAYER_ID}_1" class="materiacard" style="left: 12px; top: 291px;"></div>   
                </div>

                
                <div id="zonecard_5_{PLAYER_ID}" class="zonecard" style="right: 615px; top: 347.7px;">
                <div id="materiacard_5_{PLAYER_ID}_3" class="materiacard" style="left: 12px; top: 198px;"></div>
                <div id="materiacard_5_{PLAYER_ID}_2" class="materiacard" style="left: 12px; top: 244px;"></div>
                <div id="materiacard_5_{PLAYER_ID}_1" class="materiacard" style="left: 12px; top: 291px;"></div>
                <div id="materiadiscard_{PLAYER_ID}" class="materiacard" style="left: 130px; top: 130px;"></div>   
                </div>
                
                <div id="zonecard_6_{PLAYER_ID}" class="zonecard" style="right: 410px; top: 347.7px;">
                <div id="materiacard_6_{PLAYER_ID}_3" class="materiacard" style="left: 12px; top: 198px;"></div>
                <div id="materiacard_6_{PLAYER_ID}_2" class="materiacard" style="left: 12px; top: 244px;"></div>
                <div id="materiacard_6_{PLAYER_ID}_1" class="materiacard" style="left: 12px; top: 291px;"></div>   
                </div>
                

                <div id="zonecard_7_{PLAYER_ID}" class="zonecard" style="right: 205px; top: 347.7px;">
                <div id="materiacard_7_{PLAYER_ID}_3" class="materiacard" style="left: 12px; top: 198px;"></div>
                <div id="materiacard_7_{PLAYER_ID}_2" class="materiacard" style="left: 12px; top: 244px;"></div>
                <div id="materiacard_7_{PLAYER_ID}_1" class="materiacard" style="left: 12px; top: 291px;"></div>   
                </div>
                
                <div id="zonecard_8_{PLAYER_ID}" class="aide" style="right: 0px; top: 347.7px;"></div>

                
        </div>
                

                

                <div id="zonefamilier_{PLAYER_ID}" class="zonefamilier">
                <div id="materiafamilier_{PLAYER_ID}_1" class="materiacard" style="left: 53px; top: 264px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_2" class="materiacard" style="left: 23px; top: 224px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_3" class="materiacard" style="left: 9px; top: 178px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_4" class="materiacard" style="left: 11px; top: 133px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_5" class="materiacard" style="left: 25px; top: 89px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_6" class="materiacard" style="left: 53px; top: 50px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_7" class="materiacard" style="left: 94px; top: 21px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_8" class="materiacard" style="left: 143px; top: 11px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_9" class="materiacard" style="left: 192px; top: 23px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_10" class="materiacard" style="left: 231px; top: 54px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_11" class="materiacard" style="left: 257px; top: 96px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_12" class="materiacard" style="left: 268px; top: 143px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_13" class="materiacard" style="left: 266px; top: 190px;"></div>
                <div id="materiafamilier_{PLAYER_ID}_14" class="materiacard" style="left: 248px; top: 234px;"></div>
                </div>

                <div id="reserve_{PLAYER_ID}" class="reserve">
                
                
                <div id="materiareserve_{PLAYER_ID}_1" class="materiacard" style="left: 73px; top: 8px;"></div>
                <div id="materiareserve_{PLAYER_ID}_2" class="materiacard" style="left: 148px; top: 8px;"></div>
                <div id="materiareserve_{PLAYER_ID}_3" class="materiacard" style="left: 216px; top: 8px;"></div>
                <div id="materiareserve_{PLAYER_ID}_4" class="materiacard" style="left: 73px; top: 65px;"></div>
                <div id="materiareserve_{PLAYER_ID}_5" class="materiacard" style="left: 148px; top: 65px;"></div>
                <div id="materiareserve_{PLAYER_ID}_6" class="materiacard" style="left: 216px; top: 65px;"></div>
                <div id="materiareserve_{PLAYER_ID}_7" class="materiacard" style="left: 73px; top: 123px;"></div>
                <div id="materiareserve_{PLAYER_ID}_8" class="materiacard" style="left: 148px; top: 123px;"></div>
                <div id="materiareserve_{PLAYER_ID}_9" class="materiacard" style="left: 216px; top: 123px;"></div>
                </div>
        </div>
        <!-- END player -->


        <div id="solo">
        <div id="materiaopponent_1" class="materiacard" style="left: 70px; top: 71px;"></div>
        <div id="materiaopponent_2" class="materiacard" style="left: 123px; top: 71px;"></div>
        <div id="materiaopponent_3" class="materiacard" style="left: 179px; top: 71px;"></div>
        <div id="materiaopponent_4" class="materiacard" style="left: 234px; top: 71px;"></div>
        <div id="materiaopponent_5" class="materiacard" style="left: 42px; top: 112px;"></div>
        <div id="materiaopponent_6" class="materiacard" style="left: 95px; top: 112px;"></div>
        <div id="materiaopponent_7" class="materiacard" style="left: 151px; top: 112px;"></div>
        <div id="materiaopponent_8" class="materiacard" style="left: 206px; top: 112px;"></div>
        <div id="materiaopponent_9" class="materiacard" style="left: 262px; top: 112px;"></div>
        <div id="materiaopponent_10" class="materiacard" style="left: 42px; top: 156px;"></div>
        <div id="materiaopponent_11" class="materiacard" style="left: 95px; top: 156px;"></div>
        <div id="materiaopponent_12" class="materiacard" style="left: 151px; top: 156px;"></div>
        <div id="materiaopponent_13" class="materiacard" style="left: 206px; top: 156px;"></div>
        <div id="materiaopponent_14" class="materiacard" style="left: 262px; top: 156px;"></div>
        <div id="materiaopponent_15" class="materiacard" style="left: 70px; top: 194px;"></div>
        <div id="materiaopponent_16" class="materiacard" style="left: 123px; top: 194px;"></div>
        <div id="materiaopponent_17" class="materiacard" style="left: 179px; top: 194px;"></div>
        <div id="materiareserveopponent_1" class="materiacard" style="left: 84px; top: 242px;"></div>
        <div id="materiareserveopponent_2" class="materiacard" style="left: 107px; top: 242px;"></div>
        <div id="materiareserveopponent_3" class="materiacard" style="left: 130px; top: 242px;"></div>
        <div id="materiareserveopponent_4" class="materiacard" style="left: 152px; top: 242px;"></div>
        <div id="materiareserveopponent_5" class="materiacard" style="left: 175px; top: 242px;"></div>
        <div id="materiareserveopponent_6" class="materiacard" style="left: 198px; top: 242px;"></div>
        <div id="materiareserveopponent_7" class="materiacard" style="left: 222px; top: 242px;"></div>
        <div id="materiareserveopponent_8" class="materiacard" style="left: 245px; top: 242px;"></div>
        <div id="materiareserveopponent_9" class="materiacard" style="left: 269px; top: 242px;"></div>
        <div id="materiareserveopponent_10" class="materiacard" style="left: 13px; top: 285px;"></div>
        <div id="materiareserveopponent_11" class="materiacard" style="left: 36px; top: 285px;"></div>
        <div id="materiareserveopponent_12" class="materiacard" style="left: 59px; top: 285px;"></div>
        <div id="materiareserveopponent_13" class="materiacard" style="left: 81px; top: 285px;"></div>
        <div id="materiareserveopponent_14" class="materiacard" style="left: 104px; top: 285px;"></div>
        <div id="materiareserveopponent_15" class="materiacard" style="left: 127px; top: 285px;"></div>
        <div id="materiareserveopponent_16" class="materiacard" style="left: 151px; top: 285px;"></div>
        <div id="materiareserveopponent_17" class="materiacard" style="left: 174px; top: 285px;"></div>
        <div id="materiareserveopponent_18" class="materiacard" style="left: 198px; top: 285px;"></div>
        <div id="materiareserveopponent_19" class="materiacard" style="left: 221px; top: 285px;"></div>
        <div id="materiareserveopponent_20" class="materiacard" style="left: 244px; top: 285px;"></div>
        <div id="materiareserveopponent_21" class="materiacard" style="left: 268px; top: 285px;"></div>
        <div id="materiareserveopponent_22" class="materiacard" style="left: 291px; top: 285px;"></div>
        <div id="materiareserveopponent_23" class="materiacard" style="left: 13px; top: 329px;"></div>
        <div id="materiareserveopponent_24" class="materiacard" style="left: 36px; top: 329px;"></div>
        <div id="materiareserveopponent_25" class="materiacard" style="left: 59px; top: 329px;"></div>
        <div id="materiareserveopponent_26" class="materiacard" style="left: 81px; top: 329px;"></div>
        <div id="materiareserveopponent_27" class="materiacard" style="left: 104px; top: 329px;"></div>
        <div id="materiareserveopponent_28" class="materiacard" style="left: 127px; top: 329px;"></div>
        <div id="materiareserveopponent_29" class="materiacard" style="left: 151px; top: 329px;"></div>
        <div id="materiareserveopponent_30" class="materiacard" style="left: 174px; top: 329px;"></div>
        <div id="materiareserveopponent_31" class="materiacard" style="left: 198px; top: 329px;"></div>
        <div id="materiareserveopponent_32" class="materiacard" style="left: 221px; top: 329px;"></div>
        <div id="materiareserveopponent_33" class="materiacard" style="left: 244px; top: 329px;"></div>
        <div id="materiareserveopponent_34" class="materiacard" style="left: 268px; top: 329px;"></div>
        <div id="materiareserveopponent_35" class="materiacard" style="left: 291px; top: 329px;"></div>
        </div>
        



</div>



<script type="text/javascript">

var jstpl_card='<div id="card_${color}_${player_id}" class="card" style="background-image: url(${imgfull}); background-position-x: ${x}%; background-position-y: ${y}%;"></div>';
var jstpl_familier='<div id="familier_${player_id}" class="familier" style="background-position-x: ${x}%;"></div>';
var jstpl_materia='<div id="materia_${id}" class="materia" style="background-position-x: ${x}%; background-position-y: ${y}%;"></div>';

var jstpl_firstplayer='<div id="firstplayer"></div>';
var jstpl_cardtool1='<div class="cardtoolset1" style="background-position-x: ${x}%; background-position-y: ${y}%;"></div><br><div style="text-align: center; font-weight: bold;">${name}</div><div style="text-align: left;">${description5}</div><div style="text-align: left;">${description4}</div><div style="text-align: left;">${description3}</div>';
var jstpl_cardtool2='<div class="cardtoolset2" style="background-position-x: ${x}%; background-position-y: ${y}%;"></div><br><div style="text-align: center; font-weight: bold;">${name}</div><div style="text-align: left;">${description5}</div><div style="text-align: left;">${description4}</div><div style="text-align: left;">${description3}</div>';
var jstpl_cardtool3='<div class="cardtoolset3" style="background-position-x: ${x}%; background-position-y: ${y}%;"></div><br><div style="text-align: center; font-weight: bold;">${name}</div><div style="text-align: left;">${description5}</div><div style="text-align: left;">${description4}</div><div style="text-align: left;">${description3}</div>';
var jstpl_aidetool='<div class="aidetool"></div><br><div style="text-align: center; font-weight: bold;">${name1}</div><div style="text-align: justify;">${texte1}</div><br><div style="text-align: justify;">${texte2}</div><div style="text-align: justify;">${texte3}</div><div style="text-align: justify;">${texte4}</div><br><div style="text-align: justify;">${texte5}</div><br><div style="text-align: center; font-weight: bold;">${name2}</div><div style="text-align: justify;">${texte6}</div>';

var jstpl_player_progession = '<div class="player_progession" id="player_progession_${id}" style="display: flex; align-items: center; flex-direction: column; justify-content: center; z-index: 100; position: relative;">\
<div id="icone_player_progession_${id}" style="text-align: center; align-items: center; display: flex; margin-top: 10px; margin-bottom: 5px;">\
<div id="iconefamilier_${id}" class="iconefamilier_${pos}" style="display: inline-block; margin-right: 6px;"></div>\
<div id="nbrefamilier_${id}" class="nbre_familier" style="display: inline-block; margin-right: 0px;"></div>\
<div class="texteprogression" style="display: inline-block; margin-right: 6px;">/14</div>\
<div id="iconecard_${id}" class="iconecard" style="display: inline-block; margin-right: 6px;"></div>\
<div id="nbrecard_${id}" class="nbre_card" style="display: inline-block; margin-right: 0px;"></div>\
<div class="texteprogression" style="display: inline-block; margin-right: 6px;">/7</div>\
</div>\
</div>';


var jstpl_pannelia = '<div id="pannelia" style="display: flex; align-items: center; flex-direction: column; justify-content: center; z-index: 100; position: relative;">\
<div id="scoreia" style="text-align: center; align-items: center; display: flex; margin-top: 10px; margin-bottom: 5px;">\
<div id="iconesolo" style="display: inline-block; margin-right: 6px;"></div>\
<div id="scoresolo" style="display: inline-block; margin-right: 0px;"></div>\
</div>\
</div>';

</script>  

{OVERALL_GAME_FOOTER}
