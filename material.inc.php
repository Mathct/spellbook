<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * spellbook implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
 * 
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * material.inc.php
 *
 * spellbook game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


$this->listecards = [
  '11' => [
    'name' => clienttranslate("SACRIFICE"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player discards 1 \"Round\" Materia from their pool, then draws 4 Materia from the Pouch and adds them to their pool."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player discards 1 \"Triangle\" Materia from their pool, then draws 4 Materia from the Pouch and adds them to their pool."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player discards 1 \"Square\" Materia from their pool, then draws 4 Materia from the Pouch and adds them to their pool."),
  ],
 
  '12' => [
    'name' => clienttranslate("LEVITATION"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player takes 2 \"Round\" Materia from the Altar and adds them to their pool."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player takes 2 \"Triangle\" Materia from the Altar and adds them to their pool."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player takes 2 \"Square\" Materia from the Altar and adds them to their pool."),
  ],

  '13' => [
    'name' => clienttranslate("PURIFICATION"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player swaps 1 Materia from their pool with 1 Materia from the Altar."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player swaps 2 Materia from their pool with 2 Materia from the Altar."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player swaps 3 Materia from their pool with 3 Materia from the Altar."),
  ],

  '14' => [
    'name' => clienttranslate("OFFERING"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player takes 2 Materia of the same color from their pool and stores them on their Familiar board's first 2 available spaces."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player takes 3 Materia of the same color from their pool and stores them on their Familiar board's first 3 available spaces."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player takes 4 Materia of the same color from their pool and stores them on their Familiar board's first 4 available spaces."),
  ],

  '15' => [
    'name' => clienttranslate("TIME TRAVEL"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player discards 1 \"Round\" Materia from their pool, then increases the level of a Spell other than Time Travel by one."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player discards 1 \"Triangle\" Materia from their pool, then increases the level of a Spell other than Time Travel by one."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player discards 1 \"Square\" Materia from their pool, then increases the level of a Spell other than Time Travel by one."),
  ],

  '16' => [
    'name' => clienttranslate("TRANSMUTATION"),
    'description3' => clienttranslate("<strong>Level 3:</strong> No effect."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player may Learn 1 Spell, but instead of spending sets of 3 identical runes as a wild Materia, they may spend 1 rune matching the rune on this card as a wild Materia."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player may Learn 1 Spell, but instead of spending sets of 3 identical runes as a wild Materia, they may spend 2 runes matching the rune on this card, each counting as a wild Materia."),
  ],

  '17' => [
    'name' => clienttranslate("ABUNDANCE"),
    'description3' => clienttranslate("<strong>Level 3:</strong> (Instant) When learning the Spell, the player immediately draws 2 Materia from the Pouch and adds them to their pool."),
    'description4' => clienttranslate("<strong>Level 4:</strong> (Instant) When learning the Spell, the player immediately draws 3 Materia from the Pouch and adds them to their pool."),
    'description5' => clienttranslate("<strong>Level 5:</strong> (Instant) When learning the Spell, the player immediately draws 4 Materia from the Pouch and adds them to their pool."),
  ],

  '21' => [
    'name' => clienttranslate("ERUPTION"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player draws from the Pouch until they have 4 Materia in their pool."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player draws from the Pouch until they have 5 Materia in their pool."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player draws from the Pouch until they have 6 Materia in their pool."),
  ],

  '22' => [
    'name' => clienttranslate("SHARING"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player takes 1 Materia from the Altar, then draws 1 Materia from the Pouch. All other players draw 1 Materia from the Pouch."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player takes 2 Materia from the Altar. All other players draw 1 Materia from the Pouch."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player takes 3 Materia from the Altar. All other players draw 1 Materia from the Pouch."),
  ],

  '23' => [
    'name' => clienttranslate("CURE"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player draws 1 Materia from the Pouch and adds it to their pool; then, they discard 1 Materia from their pool."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player draws 2 Materia from the Pouch and adds them to their pool; then, they discard 2 Materia from their pool."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player draws 3 Materia from the Pouch and adds them to their pool; then, they discard 3 Materia from their pool."),
  ],

  '24' => [
    'name' => clienttranslate("FOCUS"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player takes 1 Materia from their pool whose rune matches the one placed on the Spell and stores it on their Familiar board's first available space."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player takes 2 Materia from their pool whose runes match the one placed on the Spell and stores them on their Familiar board's first 2 available spaces; OR the player takes 1 Materia from the Altar whose rune matches the one placed on the Spell and adds it to their pool."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player takes 3 Materia from their pool whose runes match the one placed on the Spell and stores them on their Familiar board's first 3 available spaces; OR the player takes 2 Materia from the Altar whose runes match the one placed on the Spell and adds them to their pool."),
  ],

  '25' => [
    'name' => clienttranslate("STORM"),
    'description3' => clienttranslate("<strong>Level 3:</strong> No effect."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player discards 1 to 10 Materia from the Altar and replaces them with an equal number of Materia drawn from the Pouch. Then, they take 3 Materia, which they add to their pool. Finally, on their Storm card, they move their Materia down one level."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player discards 1 to 10 Materia from the Altar and replaces them with an equal number of Materia drawn from the Pouch. Then, they take 3 Materia, which they add to their pool. Finally, on their Storm card, they move their Materia down one level."),
  ],

  '26' => [
    'name' => clienttranslate("SWIFTNESS"),
    'description3' => clienttranslate("<strong>Level 3:</strong> (Instant) When learning this Spell, the player immediately performs 1 Morning action available to them."),
    'description4' => clienttranslate("<strong>Level 4:</strong> (Instant) When learning this Spell, the player immediately performs 1 Morning action available to them."),
    'description5' => clienttranslate("<strong>Level 5:</strong> (Permanent) Each Day, the player may perform 1 additional Morning action, for a total of 2 Morning actions available to them."),
  ],

  '27' => [
    'name' => clienttranslate("KNOWLEDGE"),
    'description3' => clienttranslate("<strong>Level 3:</strong> At the end of the game, the player scores 1 point for each learned Spell (other than Knowledge), in addition to the points awarded by those Spells."),
    'description4' => clienttranslate("<strong>Level 4:</strong> At the end of the game, the player scores 2 points for each learned Spell (other than Knowledge) at level 4 or level 5, and 1 point for each learned Spell at a lower level; this is in addition to the points awarded by those Spells."),
    'description5' => clienttranslate("<strong>Level 5:</strong> At the end of the game, the player scores 2 points for each learned Spell (other than Knowledge), in addition to the points awarded by those Spells."),
  ],

  '31' => [
    'name' => clienttranslate("BLAZE"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player draws 4 Materia from the Pouch and adds them to their pool. All other players take 1 Materia from the Altar."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player draws 4 Materia from the Pouch and adds them to their pool. All other players take 1 Materia from the Altar."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player draws 4 Materia from the Pouch and adds them to their pool. All other players take 1 Materia from the Altar."),
  ],

  '32' => [
    'name' => clienttranslate("DIVINATION"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player draws 2 Materia from the Pouch and adds them to the Altar, then takes 2 Materia from the Altar and adds them to their pool before discarding 1 Materia."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player draws 2 Materia from the Pouch and adds them to the Altar, then takes up to 2 Materia of the same color from the Altar and adds them to their pool."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player draws 2 Materia from the Pouch and adds them to the Altar, then takes 2 Materia from the Altar and adds them to their pool."),
  ],

  '33' => [
    'name' => clienttranslate("GROWTH"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player swaps 1 Materia from their pool with 1 Materia from their Familiar board. Do not move the Materia token on the card."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player takes 2 Materia from the Altar and stores them on their Familiar board's first 2 available spaces. Then, on their Growth card, they move their Materia down one level."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player takes 3 Materia from the Altar and stores them on their Familiar board's first 3 available spaces. Then, on their Growth card, they move their Materia down one level."),
  ],

  '34' => [
    'name' => clienttranslate("FEAST"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player takes 1 Materia from the Altar whose color matches that of one of the stored Materia on their Familiar board and adds it to their pool."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player takes 1 Materia from the Altar and stores it on their Familiar board's first available space."),
    'description5' => clienttranslate("<strong>Level 5:</strong> At the end of the game, the player scores 1 point for each different color Materia stored on their Familiar board, in addition to the points awarded by the Familiar board itself. This level has no Midday action."),
  ],

  '35' => [
    'name' => clienttranslate("CLONE"),
    'description3' => clienttranslate("<strong>Level 3:</strong> The player chooses a Spell learned by another player featuring a Midday action and applies its effect as if they had learned it themselves. This Spell can be used to copy another player's primary actions.<br><strong>Solo Mode:</strong> When the player performs the action of this Spell, they can copy the level 4 effect of a Spell they have not yet learned."),
    'description4' => clienttranslate("<strong>Level 4:</strong> The player chooses a Spell learned by another player featuring an Evening action and applies its effect as if they had learned it themselves. This Spell can be used to copy another player's primary actions."),
    'description5' => clienttranslate("<strong>Level 5:</strong> The player discards 1 Materia from their pool whose rune matches the one placed on the Spell. Then, they choose a Spell learned by another player featuring a Morning action and apply its effect as if they had learned it themselves. This Spell can be used to copy another player's primary actions."),
  ],

  '36' => [
    'name' => clienttranslate("MIRAGE"),
    'description3' => clienttranslate("<strong>Level 3:</strong> (Permanent) During their Day, whenever the player takes 1 Materia from the Altar (not the Pouch) whose rune matches the one placed on the Spell, they draw 1 Materia from the pouch and add it to their pool."),
    'description4' => clienttranslate("<strong>Level 4:</strong> (Permanent) During their Day, whenever the player takes 1 Materia from the Altar (not the Pouch) whose rune matches the one placed on the Spell, they draw 2 Materia from the pouch and add them to their pool."),
    'description5' => clienttranslate("<strong>Level 5:</strong> (Permanent) During their Day, whenever the player takes 1 Materia from the Altar (not the Pouch) whose rune matches the one placed on the Spell, they draw 2 Materia from the pouch and add them to their pool."),
  ],

  '37' => [
    'name' => clienttranslate("COMMUNION"),
    'description3' => clienttranslate("<strong>Level 3:</strong> (Instant) When learning the Spell, the player takes 3 Materia from the Altar and stores them on the first 3 available spaces on their Familiar board."),
    'description4' => clienttranslate("<strong>Level 4:</strong> At the end of the game, the player scores 1 point for each stored Materia on their Familiar board whose rune matches the one placed on the Spell, in addition to the points awarded by the Familiar board itself."),
    'description5' => clienttranslate("<strong>Level 5:</strong> (Permanent) When learning a Spell (including this one), the player stores 2 of the discarded Materia onto the first 2 available spaces on their Familiar board."),
  ],


];

$this->listeaide = [
  '1' => [
    'name1' => clienttranslate("GAME TURN"),
    'texte1' => clienttranslate("<strong>Pool:</strong> can never have more than 9 Materia."),
    'texte2' => clienttranslate("<strong>Morning:</strong> Take 1 or Draw 2 or Morning card action."),
    'texte3' => clienttranslate("<strong>Midday:</strong> Store 1 (on Familiar board) or Midday card action."),
    'texte4' => clienttranslate("<strong>Evening:</strong> Learn 1 Spell (3 identical runes = 1 wild) or Evening card action."),
    'texte5' => clienttranslate("<strong>Altar:</strong> -5: Refill up to 5. 5-9: Add 1. 10 and more: Discard all and refill up to 5. In solo mode, always refill up to 7."),
    'name2' => clienttranslate("GAME END"),
    'texte6' => clienttranslate("7 Spells learned or Familiar board filled."),

  ],
];

