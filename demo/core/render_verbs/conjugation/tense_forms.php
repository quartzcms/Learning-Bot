<?php
/* Infinitives and past participles are not conjugated: only other tenses need a lookup */
if(strpos($verbTenses, 'inf;') === false && strpos($verbTenses, 'par:pas;') === false){
	$lexiconResult = mysqli_query($databaseConnection, "SELECT ortho,genre,nombre,infover,cgram FROM lexique WHERE infover LIKE '%".$moodAndTense[0].":".$moodAndTense[1].":".$grammaticalPerson."%' AND lemme = '".addslashes($verbWord['lemme'])."' COLLATE utf8_bin AND cgram = '".$verbWord['cgram']."' LIMIT 1") or die (mysqli_error($databaseConnection));
	$lexiconRow = mysqli_fetch_assoc($lexiconResult);
	if($lexiconRow){
		$sentenceWords[$verbKey]['new'] = $lexiconRow['ortho'];

		$sentenceWords[$verbKey]['genre'] = !empty($sentenceWords[$verbKey]['genre']) ? $sentenceWords[$verbKey]['genre'] : 'm';
		$sentenceWords[$verbKey]['nombre'] = !empty($sentenceWords[$verbKey]['nombre']) ? $sentenceWords[$verbKey]['nombre'] : ($pronounAfterVerbFound == 0 ? $subjectNumber : $pronounNumber);

		/* Elide "je" before a vowel */
		if(!empty($personalPronoun) && $grammaticalPerson == '1s' && in_array(mb_substr($lexiconRow['ortho'], 0, 1, 'UTF-8'), array('a', 'e', "\u{e9}", 'i', 'o', 'u'), true)){
			$personalPronoun = 'j';

			if(isset($sentenceWords[$verbKey - 1]['ortho']) && strlen($sentenceWords[$verbKey - 1]['ortho']) == 1){
				$personalPronoun = 'je';
			}
		}

		if(!empty($grammaticalPerson)){
			$sentenceWords[$verbKey]['person'] = $grammaticalPerson;
		}

		if(!empty($personalPronoun)){
			if(!empty($existingSubjectPronoun)){
				$sentenceWords[$existingSubjectPronounIndex]['new'] = $personalPronoun;
				$sentenceWords[$existingSubjectPronounIndex]['person'] = $grammaticalPerson;
				$sentenceWords[$existingSubjectPronounIndex]['existing'] = 1;
			} else {
				$sentenceWords[$verbKey]['insert_before'] = 1;
				$pronounsToInsert[] = array(array(
					'ortho' => $personalPronoun,
					'cgram' => 'PRO:per',
					'nombre' => $pronounNumber,
					'genre' => $pronounGender,
					'person' => $grammaticalPerson,
					'added' => '1'
				));
			}
		}
	}
}
