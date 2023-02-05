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

use InvalidArgumentException;
use function array_rand;
use function is_array;
use function shuffle;
use function sprintf;

/**
 * Trait UwuifyOptionsTrait
 */
trait UwuifyOptionsTrait{

	/**
	 * controls how uwu the string will be [0-100]. -1 disables text uwufication.
	 */
	protected int $uwuModifier              = 75;

	/**
	 * controls lowercasing of whole words [0-100]. -1 disables modification.
	 */
	protected int $lowercaseModifier        = 7;

	/**
	 * controls uppercasing of whole words [0-100]. -1 disables modification.
	 */
	protected int $uppercaseModifier        = 7;

	/**
	 * controls mocking case (spongebob case) of whole words [0-100]. -1 disables modification.
	 */
	protected int $mockingcaseModifier      = 7;

	/**
	 * controls how many characters of a word in mocking case are uppercased [0-100]. -1 disables modification.
	 */
	protected int $mockingModifier          = 60;

	/**
	 * controls the amount of exclamations [0-100]. -1 disables modification.
	 */
	protected int $exclamationModifier      = 100;

	/**
	 * the minimum length of exclamations
	 */
	protected int $exclamationMinLength     = 2;

	/**
	 * the maximum length of exclamations
	 */
	protected int $exclamationMaxLength     = 10;

	/**
	 * controls the amount of additional punctuation [0-100]. -1 disables modification.
	 */
	protected int $spaceModifierPunctuation = 10;

	/**
	 * controls the amount of ascii emoticons [0-100]. -1 disables modification.
	 */
	protected int $spaceModifierEmoticon    = 10;

	/**
	 * controls the amount of emoji [0-100]. -1 disables modification.
	 */
	protected int $spaceModifierEmoji       = 10;

	/**
	 * controls the amount of text kaomoji [0-100]. -1 disables modification.
	 */
	protected int $spaceModifierKaomoji     = 5;

	/**
	 * controls the amount of actions (*denoted by asterisks*) [0-100]. -1 disables modification.
 	 */
	protected int $spaceModifierActions     = 5;

	/**
	 * controls the amount of stuttering [0-100]. -1 disables modification.
	 */
	protected int $spaceModifierStutter     = 10;

	/**
	 * controls the amount of elleises in stuttering (I-I-I- vs I…I…I…) [0-100]. -1 disables modification.
	 */
	protected int $stutterEllipseModifier   = 10;

	/**
	 * UwU regegx replacement patterns
	 *
	 * @var string[]
	 */
	protected array $uwuMap = [
		'/(?:r|l)/'       => 'w',
		'/(?:R|L)/'       => 'W',
		'/you\'?(r|w)e/i' => 'ur', // before/after modification
		'/you/i'          => 'u',
		'/a(r|w)e/i'      => 'r',
		'/n([aeiou])/'    => 'ny$1',
		'/N([aeiou])/'    => 'Ny$1',
		'/N([AEIOU])/'    => 'NY$1',
		'/ove/i'          => 'uv',
		'/their/i'        => 'deyw', // @todo
		'/they/i'         => 'dey',
		'/the/i'          => 'da',
		'/this/i'         => 'dis',
		'/th/i'           => 'd',
		'/ou/'            => 'ow',
		'/Ou/'            => 'Ow',
		'/OU/'            => 'OW',
	];

	/**
	 * punctuation patterns
	 *
	 * @var string[]
	 */
	protected array $punctuation = [
		'~',
		'~~',
		'-',
		'--',
		'..',
		'..,',
		',,,',
		'^^',
		'^^;;',
		'...!',
		'...?',
	];

	/**
	 * ascii/"western" emoticons
	 *
	 * @var string[]
	 */
	protected array $emoticons = [
		'^^',
		'^^;;',
		'OwO',
		'UwU',
		';;w;;',
		'>w<',
		'^w^',
		':3',
		'x3',
		'^-^',
		'._.',
		'*_*',
		'o.O',
		'O.o',
		'-.-',
		'(o_O)',
		'ʘwʘ',
		'XD',
		'nyaa~~',
		'nya~',
		'mya',
		'nani!?',
		'rawr x3',
		'>_<',
		'rawr',
		'ö.ö',
		'ÚwÚ',
		'σωσ',
		'òωó',
		'ò_ó',
		'ó_ò',
		'õ_o',
		'ù_u',
		'o_Ô',
	];

	/**
	 * unicode emoji
	 *
	 * @var string[]
	 */
	protected array $emoji = [
		'😳',
		'😳😳😳',
		'🥺',
		'😇',
		'😯',
		'😊',
		'☺️',
		'😍',
		'🥰',
		'🤩',
		'🥵',
		'😻',
		'👉👈',
		'🌈',
		'✨',
		'💖',
		'💞',
		'🏳️‍🌈',
		'🏳️‍⚧️',
	];

	/**
	 * "eastern"/japanese emoticons
	 *
	 * @var string[]
	 */
	protected array $kaomoji = [
		'(・`ω´・)',
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
		'(°ロ°)',
		'(ーー;)',
		'(⑅˘꒳˘)',
		'(ꈍᴗꈍ)',
		'(˘ω˘)',
		'(U ᵕ U❁)',
		'(U ﹏ U)',
		'( ͡o ω ͡o )',
		'(ˆ ﻌ ˆ)♡',
		'^•ﻌ•^',
		'/(^•ω•^)',
		'(✿oωo)',
		'(◕‿◕✿) ',
		'(=ʘᆽʘ=)∫',
		'(⁄ ⁄•⁄ω⁄•⁄ ⁄)',
		'(=^・^=)',
		'(︶｡︶✽)',
		'(* ^ ω ^)',
		'(o^▽^o)',
		'(≧◡≦)',
		'*:･ﾟ✧*:･ﾟ✧',
		'☆*:・ﾟ',
		'〜☆',
	];

	/**
	 * actions
	 *
	 * @var string[]
	 */
	protected array $actions = [
		'blushes',
		'nuzzles',
		'notices bulge',
		'whispers to self',
		'cries',
		'walks away',
		'sweats',
		'boops your nose',
		'bleps',
		'screams',
		'twerks',
		'runs away',
		'screeches',
		'looks at you',
		'notices bulge',
		'starts twerking',
		'huggles tightly',
		'whispers',
		'zones out',
		'stares',
		'wheezes',
		'pokes you',
	];

	/*
	 * allow adding emoji etc
	 */

	/**
	 * adds a value to the given array property
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function addArrayValue(string $property, string $value):self{

		if(!is_array($this->{$property})){
			throw new InvalidArgumentException(sprintf('"%s" is not an array', $property));
		}

		$this->{$property}[] = $value;

		return $this;
	}


	public function addPunctuation(string $punctuation):self{
		return $this->addArrayValue('punctuation', $punctuation);
	}

	public function addEmoticon(string $emoticon):self{
		return $this->addArrayValue('emoticons', $emoticon);
	}

	public function addEmoji(string $emoji):self{
		return $this->addArrayValue('emoji', $emoji);
	}

	public function addKaomoji(string $kaomoji):self{
		return $this->addArrayValue('kaomoji', $kaomoji);
	}

	public function addAction(string $action):self{
		return $this->addArrayValue('actions', $action);
	}

	/*
	 * looks like array_rand() is not random enough, so we shuffle the array on each call too
	 */

	/**
	 * returns a random string from the given array property
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function getArrayRandom(string $property):string{

		if(!is_array($this->{$property})){
			throw new InvalidArgumentException(sprintf('"%s" is not an array', $property));
		}

		shuffle($this->{$property});

		return $this->{$property}[array_rand($this->{$property})];
	}

	public function getRandomPunctuation():string{
		return $this->getArrayRandom('punctuation');
	}

	public function getRandomEmoticon():string{
		return $this->getArrayRandom('emoticons');
	}

	public function getRandomEmoji():string{
		return $this->getArrayRandom('emoji');
	}

	public function getRandomKaomoji():string{
		return $this->getArrayRandom('kaomoji');
	}

	public function getRandomAction():string{
		return $this->getArrayRandom('actions');
	}

}
