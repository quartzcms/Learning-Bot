<?php
/* If the word has passed all the validations and if is not already stored */
if($isWordValid == 1 && !in_array(format_word($dictionaryEntry['ortho']), $storedWordForms)){
	$wordsFoundByType[$dictionaryEntry['cgram']][] = array(
		'ortho' => mb_strtolower(($dictionaryEntry['ortho']), 'UTF-8'),
		'lemme' => $dictionaryEntry['lemme'],
		'cgram' => $dictionaryEntry['cgram'],
		'genre' => $dictionaryEntry['genre'],
		'nombre' => $dictionaryEntry['nombre'],
		'infover' => $dictionaryEntry['infover']
	);
	$keptWordsSignature .= format_word(mb_strtolower($dictionaryEntry['ortho'], 'UTF-8'));
	$keptWordTypes[$dictionaryEntry['ortho']][$dictionaryEntry['id']] = $dictionaryEntry['cgram'];
	$keptWordInflections[$dictionaryEntry['ortho']][$dictionaryEntry['id']] = $dictionaryEntry['infover'];
	/* Store the word in the human question data array */
	$sentenceWords[$wordIndex]['ortho'] = $dictionaryEntry['ortho'];
	$sentenceWords[$wordIndex]['cgram'] = $dictionaryEntry['cgram'];
	$sentenceWords[$wordIndex]['lemme'] = $dictionaryEntry['lemme'];
	$sentenceWords[$wordIndex]['infover'] = $dictionaryEntry['infover'];
	$sentenceWords[$wordIndex]['genre'] = !empty($dictionaryEntry['genre']) ? $dictionaryEntry['genre'] : 'm';
	$sentenceWords[$wordIndex]['nombre'] = !empty($dictionaryEntry['nombre']) ? $dictionaryEntry['nombre'] : 's';
}
/* If the word has passed the validation store it in an array to avoid storing it again. */
if($isWordValid == 1) { $storedWordForms[]= format_word($dictionaryEntry['ortho']); }
