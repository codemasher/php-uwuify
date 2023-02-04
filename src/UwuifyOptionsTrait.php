<?php
/**
 * UwuifyOptionsTrait.php
 *
 * @created      04.02.2023
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2023 smiley
 * @license      MIT
 */

namespace codemasher\Uwuify;

use function shuffle;

/**
 * Trait UwuifyOptionsTrait
 */
trait UwuifyOptionsTrait{

	protected float $regexModifier         = 1.0;
	protected float $exclamationModifier   = 0.75;
	protected float $spaceModifierFaces    = 0.25;
	protected float $spaceModifierActions  = 0.02;
	protected float $spaceModifierStutters = 0.025;

	protected array $regexMaps = [
		'/(?:r|l)/i'    => 'w',
		'/n([aeiou])/i' => 'ny$1',
		'/ove/i'        => 'uv',
		'/the/i'        => 'da',
		'/this/i'       => 'dis',
		'/th/i'         => 'd',
	];

	protected array $faces = [
		'😳',
		'😳😳😳',
		'🥺',
		'👉👈',
		'☺️',
		'😍',
		'🥰',
		'🤩',
		'🥵',
		'😻',
		'🌈',
		'✨',
		'(・`ω´・)',
		';;w;;',
		'OwO',
		'UwU',
		'ÚwÚ',
		'^-^',
		'._.',
		'*_*',
		'>w<',
		'^w^',
		':3',
		'x3',
		'( ^ _ ^)∠☆',
		'(U __ U)',
		'(*^*)',
		'(+_+)',
		'(/_;)',
		'(^.^)',
		'(♥_♥)',
		'*(^O^)*',
		'*(^o^)*',
		'ʕ•ᴥ•ʔ',
		'ʕ •ᴥ•ʔ',
		'(*^.^*)',
		'(｡♥‿♥｡)',
		'o.O',
		'ö.ö',
		'-.-',
		'(⑅˘꒳˘)',
		'(ꈍᴗꈍ)',
		'(˘ω˘)',
		'(U ᵕ U❁)',
		'σωσ',
		'òωó',
		'(U ﹏ U)',
		'( ͡o ω ͡o )',
		'ʘwʘ',
		'XD',
		'nyaa~~',
		'nya~',
		'mya',
		'nani!?',
		'rawr x3',
		'>_<',
		'rawr',
		'^^',
		'^^;;',
		'(ˆ ﻌ ˆ)♡',
		'^•ﻌ•^',
		'/(^•ω•^)',
		'(✿oωo)',
		'(◕‿◕✿) ',
		'(=ʘᆽʘ=)∫',
		'(⁄ ⁄•⁄ω⁄•⁄ ⁄)',
		'(=^・^=)',
		'(︶｡︶✽)',
	];

	protected array $actions = [
		'*blushes*',
		'*nuzzles*',
		'*notices bulge*',
		'*whispers to self*',
		'*cries*',
		'*walks away*',
		'*sweats*',
		'*boops your nose*',
		'*bleps*',
		'*screams*',
		'*twerks*',
		'*runs away*',
		'*screeches*',
		'*looks at you*',
		'*notices bulge*',
		'*starts twerking*',
		'*huggles tightly*',
	];

	protected array $exclamations = [
		'!?',
		'?!!',
		'?!?1',
		'!!11',
		'?!1?',
	];

	/**
	 * looks like array_rand() is not random enough, so we shuffle the array on each call
	 */
	protected function get_faces():array{
		shuffle($this->faces);

		return $this->faces;
	}

	protected function get_actions():array{
		shuffle($this->actions);

		return $this->actions;
	}

}
