<?php
foreach($sentenceWords as $verbKey => $verbWord){
	if(isset($verbWord['cgram']) && in_array($verbWord['cgram'], $verbTypes, true)) {
		$verbTenses = $verbWord['infover'];
		$existingSubjectPronoun = '';
		$subjectNoun = '';
		$subjectGender = '';
		$subjectNumber = '';
		$forcePlural = 0;
		$existingSubjectPronounIndex = 0;

		$reindexedWords = array_values($sentenceWords);
		foreach($reindexedWords as $verbPosition => $sentenceWord){
			if($sentenceWord['ortho'] == $verbWord['ortho']){
				require __DIR__ . '/context/init_word_context.php';
				require __DIR__ . '/context/preceding_scan.php';
				require __DIR__ . '/context/find_pre_verb.php';
				require __DIR__ . '/context/plural_subjects.php';
				require __DIR__ . '/context/default_pre_verb.php';
				require __DIR__ . '/context/find_after_verb.php';
				require __DIR__ . '/context/default_after_verb.php';
			}
		}
		require __DIR__ . '/conjugation/subject_pronoun.php';
		require __DIR__ . '/conjugation/pronoun_forms.php';
		require __DIR__ . '/conjugation/tense_forms.php';
	}
}
