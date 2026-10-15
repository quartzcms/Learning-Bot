<?php
$ai_module_dir = __DIR__ . '/ai';

require $ai_module_dir . '/bootstrap/environment.php';
require $ai_module_dir . '/bootstrap/access_control.php';
require $ai_module_dir . '/session/initialize.php';
require $ai_module_dir . '/session/bot_state.php';
require $ai_module_dir . '/external/translate_question.php';
require $ai_module_dir . '/external/natural_language.php';
require $ai_module_dir . '/helpers/format_word.php';
require $ai_module_dir . '/helpers/response_helpers.php';
require $ai_module_dir . '/question/init_state.php';
/* If the human sentence is not empty */
if(isset($_POST['question']) && !empty($_POST['question'])) {
	require $ai_module_dir . '/question/normalize.php';
	require $ai_module_dir . '/helpers/search_multi_array.php';
	require $ai_module_dir . '/dictionary/load_words.php';
	require $ai_module_dir . '/grammar/syntax_rules.php';
	require $ai_module_dir . '/analysis/analyze_words.php';
	require $ai_module_dir . '/question/finalize_analysis.php';
	require $ai_module_dir . '/external/wikipedia_fallback.php';
	require $ai_module_dir . '/memory/update_short_term_links.php';
	require $ai_module_dir . '/learning/verbs_and_core.php';
	require $ai_module_dir . '/learning/forget_on_negative.php';
	require $ai_module_dir . '/learning/learn_from_question.php';
	require $ai_module_dir . '/memory/already_said_signature.php';
	require $ai_module_dir . '/memory/lookup.php';
	require $ai_module_dir . '/memory/remember_sentence.php';
	require $ai_module_dir . '/helpers/verify_grammar.php';
	require $ai_module_dir . '/response/recreate_arrays.php';
	if(!isset($memoryResponse) && empty($response_equation)){
		require $ai_module_dir . '/response/syntax_pattern.php';
	} elseif(!empty($response_equation)) {
		require $ai_module_dir . '/response/equation_response.php';
	} else {
		require $ai_module_dir . '/response/database_pattern.php';
	}
}
require $ai_module_dir . '/response/finish.php';
