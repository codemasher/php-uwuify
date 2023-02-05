<?php
/**
 * uwu.php
 *
 * @created      04.02.2023
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2023 smiley
 * @license      MIT
 */

use codemasher\Uwuify\Uwuify;
use codemasher\Uwuify\UwuifyOptions;

require_once __DIR__.'/../vendor/autoload.php';

// please excuse the IDE yelling: https://youtrack.jetbrains.com/issue/WI-66549
$options = new UwuifyOptions;

// all threshold values range [0-100], -1 to disable

// controls how much the text will be uwufied
$options->uwuModifier              = 75;

// these 6 options control the appearance of the several additional elements in spaces between words
// if the combined total value exceeds 100, each value will be adjusted to percentages ($val / $sum * 100)
$options->spaceModifierPunctuation = 10;
$options->spaceModifierEmoticon    = 10;
$options->spaceModifierEmoji       = 10;
$options->spaceModifierKaomoji     = 5;
$options->spaceModifierActions     = 5;
$options->spaceModifierStutter     = 10;

// these 3 options control text upper/lowercasing (same adjustment as above)
$options->lowercaseModifier        = 7;
$options->uppercaseModifier        = 7;
$options->mockingcaseModifier      = 7;

$options->mockingModifier          = 60;
$options->exclamationModifier      = 10;
$options->stutterEllipseModifier   = 10;

$options->exclamationMinLength     = 2;
$options->exclamationMaxLength     = 10;


$uwu = new Uwuify($options);

// from: https://github.com/codemasher/dril-archive
$dril = json_decode(file_get_contents('./dril.json'), true);

foreach($dril as $tweet){

	for($i = 0; $i < 10; $i++){
		$str = $uwu->uwuify($tweet);
		var_dump($str);
	}

}

