<?php
function renderVerbs($reason, $sentenceWords, $databaseConnection){
	require __DIR__ . '/render_verbs/init_state.php';
	require __DIR__ . '/render_verbs/scan_verbs.php';
	require __DIR__ . '/render_verbs/post/insert_before_pronouns.php';
	require __DIR__ . '/render_verbs/post/convert_pronouns.php';
	require __DIR__ . '/render_verbs/post/convert_elided_pronouns.php';
	require __DIR__ . '/render_verbs/post/count_verbs.php';
	require __DIR__ . '/render_verbs/post/add_missing_pronoun.php';
	return array(
		'path_array' => $rewrittenWords
	);
}
