<?php
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
use function array_map;
use function array_sum;
use function explode;
use function floor;
use function implode;
use function mb_str_split;
use function mb_strtolower;
use function mb_strtoupper;
use function mt_rand;
use function preg_match;
use function preg_replace;
use function rtrim;
use function sprintf;
use function str_repeat;

class Uwuify{

	protected const spaceModifierKeys = [
		'punctuation' => 'spaceModifierPunctuation',
		'emoticon'    => 'spaceModifierEmoticon',
		'emoji'       => 'spaceModifierEmoji',
		'kaomoji'     => 'spaceModifierKaomoji',
		'actions'     => 'spaceModifierActions',
		'stutter'     => 'spaceModifierStutter',
	];

	protected const caseModifierKeys = [
		'lower'       => 'lowercaseModifier',
		'upper'       => 'uppercaseModifier',
		'mocking'     => 'mockingcaseModifier',
	];

	protected UwuifyOptions|SettingsContainerInterface $options;
	protected array                                    $spaceModifiers;
	protected array                                    $caseModifiers;

	/**
	 * Uwuify Constwuctow òωó
	 */
	public function __construct(UwuifyOptions|SettingsContainerInterface $options = null){
		$this->options        = $options ?? new UwuifyOptions;
		$this->spaceModifiers = $this->setupModifierThresholds($this::spaceModifierKeys);
		$this->caseModifiers  = $this->setupModifierThresholds($this::caseModifierKeys);
	}

	/**
	 * Set up the modifier threshold values
	 */
	protected function setupModifierThresholds(array $modifierOptionKeys):array{
		$thresholds = [];

		foreach($modifierOptionKeys as $key => $option){
			$thresholds[$key] = $this->options->{$option};
		}

		$sum = array_sum($thresholds);

		// total is over 100, we'll adjust to proper percentages to reduce scatter
		if($sum > 100){
			$thresholds = array_map(fn(int $val):int => (int)floor($val / $sum * 100), $thresholds);
			$sum        = array_sum($thresholds);
		}

		$thresholds['nothing'] = 100 - $sum;

		return $thresholds;
	}

	/**
	 * Translate some words to uwu from a sentence.
	 */
	public function uwuifyWords(string $sentence):string{
		$words = explode(' ', $sentence);

		foreach($words as &$word){

			// skip URLs, @-names and hashtags
			if(preg_match('~^(https?://|[@#])~i', $word)){
				continue;
			}

			foreach($this->options->uwuMap as $regex => $replacement){

				if($this->rand() <= $this->options->uwuModifier){
					$word = preg_replace($regex, $replacement, $word);
				}

				// randomly uppercase/lower/spongebobcase words
				if($this->rand() <= $this->caseModifiers['nothing']){
					continue;
				}
				elseif($this->rand() <= $this->caseModifiers['lower']){
					$word = mb_strtolower($word);
				}
				elseif($this->rand() <= $this->caseModifiers['upper']){
					$word = mb_strtoupper($word);
				}
				elseif($this->rand() <= $this->caseModifiers['mocking']){
					$word = $this->mOCkiNgCaSe($word);
				}
			}
		}

		return implode(' ', $words);
	}

	/**
	 * spongebob mocking case
	 */
	protected function mOCkiNgCaSe(string $word):string{
		$chars = mb_str_split(mb_strtolower($word));

		foreach($chars as &$char){

			if(preg_match('/\W/', $char)){
				continue;
			}

			if($this->rand() <= $this->options->mockingModifier){
				$char = mb_strtoupper($char);
			}

		}

		return implode('', $chars);
	}

	/**
	 * Translate some exclamations to uwu from a sentence.
	 */
	public function uwuifyExclamations(string $sentence):string{
		$words = explode(' ', $sentence);

		foreach($words as &$word){

			if($this->rand() > $this->options->exclamationModifier){
				continue;
			}

			$replacedWord = rtrim($word, '!?');

			if($word === $replacedWord){
				continue;
			}

			$exclamation = '';
			$len         = mt_rand($this->options->exclamationMinLength, $this->options->exclamationMaxLength);

			for($i = 0; $i < $len; $i++){
				$exclamation .= ['!', '¡', '?', '¿', '1'][mt_rand(0, 4)];
			}

			$word = $replacedWord.$exclamation;
		}

		return implode(' ', $words);
	}

	/**
	 * Translate some spaces to faces, action or stutters from a sentence.
	 */
	public function uwuifySpaces(string $sentence):string{
		$words = explode(' ', $sentence);

		foreach($words as &$word){

			if($this->rand() <= $this->spaceModifiers['nothing']){
				continue;
			}
			elseif($this->rand() <= $this->spaceModifiers['punctuation']){
				$word .= $this->options->getRandomPunctuation();
			}
			elseif($this->rand() <= $this->spaceModifiers['emoticon']){
				$word .= ' '.$this->options->getRandomEmoticon();
			}
			elseif($this->rand() <= $this->spaceModifiers['emoji']){
				$word .= ' '.$this->options->getRandomEmoji();
			}
			elseif($this->rand() <= $this->spaceModifiers['kaomoji']){
				$word .= ' '.$this->options->getRandomKaomoji();
			}
			elseif($this->rand() <= $this->spaceModifiers['actions']){
				$word .= sprintf(' *%s*', $this->options->getRandomAction());
			}
			elseif($this->rand() <= $this->spaceModifiers['stutter']){
				// skip non-word characters and URLs
				if(preg_match('/^(\W|http)/i', $word)){
					continue;
				}
				// sprinkle in some ellipses for a change
				$fill = ['-', '…'][(int)($this->rand() <= $this->options->stutterEllipseModifier)];
				$word = str_repeat(mb_substr($word, 0, 1).$fill, mt_rand(1, 3)).$word;
			}

		}

		return implode(' ', $words);
	}

	/**
	 * Uwuify text.
	 */
	public function uwuify(string $text):string{
		$text = $this->uwuifyWords($text);
		$text = $this->uwuifyExclamations($text);

		return $this->uwuifySpaces($text);
	}

	/**
	 * Get a random int between 0 and 100.
	 */
	protected function rand(int $max = null):int{
		return mt_rand(0, $max ?? 100);
	}

}
