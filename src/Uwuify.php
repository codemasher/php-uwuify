<?php /**  */

/**
 * Class Uwuify
 *
 * @created      02.10.2022
 * @author       Ji Yong, Kim
 * @author       smiley
 * @copyright    2022 Ji Yong, Kim
 * @license      MIT
 *
 * @noinspection SpellCheckingInspection
 */

namespace codemasher\Uwuify;

use chillerlan\Settings\SettingsContainerInterface;
use function array_rand;
use function explode;
use function filter_var;
use function implode;
use function lcfirst;
use function mt_getrandmax;
use function mt_rand;
use function preg_replace;
use function rand;
use function rtrim;
use function str_repeat;
use function substr;
use const FILTER_VALIDATE_URL;

class Uwuify{

	protected UwuifyOptions|SettingsContainerInterface $options;

	/**
	 * Uwuify Constructor.
	 */
	public function __construct(UwuifyOptions|SettingsContainerInterface $options = null){
		$this->options = $options ?? new UwuifyOptions;
	}

	/**
	 * Translate some words to uwu from a sentence.
	 */
	public function uwuifyWords(string $sentence):string{
		$words = explode(' ', $sentence);

		foreach($words as &$word){

			if(filter_var($word, FILTER_VALIDATE_URL) !== false){
				continue;
			}

			if(str_starts_with($word, '@')){
				continue;
			}

			foreach($this->options->regexMaps as $regex => $replacement){

				if($this->getRandomFloat() <= $this->options->regexModifier){
					$word = preg_replace($regex, $replacement, $word);
				}

				if($this->getUppercaseProportion($word) < 0.5){
					$word = lcfirst($word);
				}
			}
		}

		return implode(' ', $words);
	}

	/**
	 * Translate some exclamations to uwu from a sentence.
	 */
	public function uwuifyExclamations(string $sentence):string{
		$words = explode(' ', $sentence);

		foreach($words as &$word){

			if($this->getRandomFloat() > $this->options->exclamationModifier){
				continue;
			}

			$replacedWord = rtrim($word, '!?');

			if($word === $replacedWord){
				continue;
			}

			$word = $replacedWord.$this->options->exclamations[array_rand($this->options->exclamations)];
		}

		return implode(' ', $words);
	}

	/**
	 * Translate some spaces to faces, action or stutters from a sentence.
	 */
	public function uwuifySpaces(string $sentence):string{
		$words = explode(' ', $sentence);

		$faceThreshold    = $this->options->spaceModifierFaces;
		$actionThreshold  = $this->options->spaceModifierActions + $faceThreshold;
		$stutterThreshold = $this->options->spaceModifierStutters + $actionThreshold;

		foreach($words as &$word){
			$firstCharacter = substr($word, 0, 1);

			if($this->getRandomFloat() <= $faceThreshold){
				$word .= ' '.$this->options->faces[array_rand($this->options->faces)];
			}
			elseif($this->getRandomFloat() <= $actionThreshold){
				$word .= ' '.$this->options->actions[array_rand($this->options->actions)];
			}
			elseif($this->getRandomFloat() <= $stutterThreshold){
				$word = str_repeat($firstCharacter.'-', rand(1, 3)).$word;
			}
		}

		return implode(' ', $words);
	}

	/**
	 * Uwuify sentences.
	 */
	public function uwuify(string $sentence):string{
		$sentence = $this->uwuifyWords($sentence);
		$sentence = $this->uwuifyExclamations($sentence);

		return $this->uwuifySpaces($sentence);
	}

	/**
	 * Get a random float between 0 and 1.
	 */
	protected function getRandomFloat():float{
		return mt_rand() / mt_getrandmax();
	}

	/**
	 * Get a proportion of uppercase characters in a string.
	 */
	protected function getUppercaseProportion(string $word):float{
		$totalCharacters = mb_strlen($word);
		$upperCharacters = 0;

		foreach(mb_str_split($word) as $character){
			if(mb_strtoupper($character) === $character){
				$upperCharacters++;
			}
		}

		return $upperCharacters / $totalCharacters;
	}

}
