<?php
	ini_set('display_errors', 1);
	include('../../config.php');
	include('../sessions/save_sessions.php');
	include('../sessions/use_sessions.php');
	
	/* If language is defined set new language */
	if(isset($_POST['language'])){
		write_session('language', $_POST['language']);
	}
	
	/* Set captcha if bot variable is true or if the AI script is read from internally by the server */
	if($_SERVER['REMOTE_ADDR'] == $server_ip || (isset($_POST['bot']) && $_POST['bot'] == 1)){ 
		write_session('captcha', '15'); 
	}
	/* If captcha is not set */
	if(!use_session('captcha') || use_session('captcha') != '15'){
		/* Return an empty JSON object */
		echo json_encode(['response' => '', 'analyse' => array('', 'words_found' => array(), 'pattern_chosen' => 'No pattern found (empty captcha)', 'none', array(), '', 'empty_', 'will_learn' => '', 'already_said' => 'no')]); 
		exit;
	}
	
	/* Get the channel. If not found use the default one. */
	if(!use_session('channels')){ 
		write_session('channels', '255.255.255.255');
		$ip_user = '255.255.255.255';
	} else {
		$ip_user = use_session('channels');
	}
	
	$type_bot = $_POST['type'];
	/* If response counter is not defined, set it to 0 */
	if(!use_session('count_response_'.$type_bot)){ 
		write_session('count_response_'.$type_bot, 0); 
	} 
	/* Incrementing the response counter +1 */
	$count_response = use_session('count_response_'.$type_bot);
	write_session('count_response_'.$type_bot, $count_response + 1);
	
	/* If the short term memory is not defined */
	if(!use_session('links_'.$type_bot)){
		/* Define new array for each accepted word type */
		$accepted = array('other', 'nom', 'ver', 'adj');
		$new_table = array();
		foreach($accepted as $key => $value) {
			$new_table[$value] = array();
		}
		write_session('links_'.$type_bot, $new_table);
	}
	
	/* Retake last human question if current human question is a simple yes or no */
	if(isset($_POST['question']) && !empty($_POST['question']) && use_session('last_human_question_'.$type_bot)){
		if((strpos($_POST['question'], 'oui') !== false || strpos($_POST['question'], 'non') !== false)){
			$_POST['question'] = use_session('last_human_question_'.$type_bot);
		}
	}
	write_session('last_human_question_'.$type_bot, $_POST['question']);
	
	/* If last response from the bot is a question and not defined set it to 0 */
	if(!use_session('last_question_'.$type_bot)){ 
		write_session('last_question_'.$type_bot, 0); 
	}
	/* if the last response sentence from the bot is not defined then set it empty. */
	if(!use_session('last_question_sentence_'.$type_bot)){ 
		write_session('last_question_sentence_'.$type_bot, ''); 
	}
	/* Defining the used memory rows ID session if not defined */
	if(!use_session('used_id_'.$type_bot)){ 
		write_session('used_id_'.$type_bot, array()); 
	}
	
	/* Defining an array to remember which sentence was said from the bot */
	if(!use_session('note_'.$type_bot)){ 
		write_session('note_'.$type_bot, array()); 
	}
	/* Defining an array of responses from the bot which is supposed to be storing each response into an array */
	if(!use_session('last_response_'.$type_bot)){ 
		write_session('last_response_'.$type_bot, array()); 
	}
	/* Defining the variable already said */
	$already_said = 'no';
	/* Defining the learning variable */
	$trigger_verb = '';
	/* If last bot response is a question set the learn variable on */
	if(use_session('last_question_'.$type_bot) == 1){
		$trigger_verb = 'learn';
		write_session('last_question_'.$type_bot, 0);
		write_session('last_question_sentence_'.$type_bot, '');
	}
	
	/* Each 10 response clean the the memory used IDs and the stored responses */
	if(use_session('count_response_'.$type_bot) > 10){
		write_session('used_id_'.$type_bot, array());
		write_session('note_'.$type_bot, array());
		write_session('last_response_'.$type_bot, array());
	}
	/* Set a new array element (index of the response count) to the note session */
	$bot_notes = use_session('note_'.$type_bot);
	$bot_notes[use_session('count_response_'.$type_bot)] = '';
	write_session('note_'.$type_bot, $bot_notes);
	
	/* Set the human question for Google natural language */	
	if(isset($_POST['question']) && !empty($_POST['question'])){
		$natural_language_question = $_POST['question'];
	}
	
	/* If language is set and Google translate is activated */
	if(isset($_POST['question']) && !empty($_POST['question']) && use_session('language') && use_session('language') != 'fr' && $google_translate == 1) {
		$post_values = [
			'translate' => $_POST['question'],
			'language' => 'fr',
		];
		/* Translate all the words to french which is the bot language */
		$ch = curl_init($url."demo/google/translate/api.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		if(!empty($response)) { $result = json_decode($response, true); $_POST['question'] = html_entity_decode($result['translated_text'], ENT_QUOTES); }
	}
	$append_data = '';
	$appendToResponse = '';
	$wiki_later = 0;
	/* If google natural language is activated */
	if($google_natural_language == 1 && isset($natural_language_question) && !empty($natural_language_question)) {
		$post_values = [
			'text' => $natural_language_question
		];
		
		/* Getting all the meaningfull word from the human sentence */
		$ch = curl_init($url."demo/google/natural_language/api.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		$wiki_lang = '';
		/* Setting the language for Wikipedia query */
		if(use_session('language')) {
			$wiki_lang = use_session('language').'.';
		} else {
			$wiki_lang = 'fr.';
		}
		
		if(!empty($response)) { 
			$result = json_decode($response, true); 
			$description = array();
			
			/* Loop through all the google natural language filtered words */
			foreach($result as $key => $value){
				/* Making sure its only one word */
				if(strpos($result[$key], ' ') === false) {
					/* Query wikipedia for the word description */
					$data = file_get_contents('https://'.$wiki_lang.'wikipedia.org/w/api.php?action=opensearch&search='.$result[$key].'&limit=1&format=json');
					$data = json_decode($data, true);
					if(isset($data[2][0]) && !empty($data[2][0]) && substr($data[2][0], -1) == '.') { 
						/* Storing first description in an array */
						$description[] = html_entity_decode($data[2][0], ENT_QUOTES);
						break; 
					}
				}
			}
			if(!empty($description)){
				/* Description to be appended to the response later if the appropriate human question pronouns are found */
				$append_data = ' '. implode(' ', $description);
				$appendToResponse =  $append_data;
			}
		} else {
			/* If no result is found from translate skip this part and register a variable for later */
			$wiki_later = 1;
		}
	}	
	
	/* Function to format word and remove and convert all special characters to _ */
	function format_word($al_txt = null) {
        $al_transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ç' => 'c', 'Ç' => 'C', 'Ð' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ë' => 'e', 'Ë' => 'E', 'ƒ' => 'f', 'ƒ' => 'F', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ñ' => 'n', 'Ñ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ö' => 'oe', 'Ö' => 'OE', 'š' => 's', 'Š' => 'S', 'ß' => 'SS', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'û' => 'u', 'Û' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ý' => 'y', 'Ý' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'ž' => 'z', 'Ž' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u');
        $al_txt = str_replace(array_keys($al_transliterationTable), array_values($al_transliterationTable), html_entity_decode($al_txt));
        $al_txt = preg_replace_callback("/[^a-zA-Z0-9]/", function() {
            return "_";
        }, $al_txt);
        return $al_txt;
    }
	
	/* Including the verbs function */
	include('../core/functions.php');
	/* Including the AI learning functions */
	include('../core/core.php');
	/* Defining a new MySQL connection for the AI */
	$connexion = mysqli_connect($al_host, $al_user, $al_password, $al_db_name);
	mysqli_set_charset($connexion, 'utf8');
	/* Defining the necessary variable for the storing of database words */
	$build_memory = array();
	$response = array();
	$words_kept = '';
	$words_kept_array = array();
	$words_kept_array2 = array();
	$freecard = 0;
	/* If the human sentence is not empty */
	if(isset($_POST['question']) && !empty($_POST['question'])) {
		$reason = $_POST['question'];
		/* Separate all the human sentence pronouns from their verbs */
		$_POST['question'] = trim($_POST['question'], ' ');
		$_POST['question'] = str_replace('-t', ' t', $_POST['question']);
		$_POST['question'] = str_replace('-il', ' il', $_POST['question']);
		$_POST['question'] = str_replace('-elle', ' elle', $_POST['question']);
		$_POST['question'] = str_replace('-tu', ' tu', $_POST['question']);
		$_POST['question'] = str_replace('-je', ' je', $_POST['question']);
		$_POST['question'] = str_replace('-nous', ' nous', $_POST['question']);
		$_POST['question'] = str_replace('-vous', ' vous', $_POST['question']);
		$_POST['question'] = str_replace('-on', ' on', $_POST['question']);
		$_POST['question'] = str_replace('-toi', ' toi', $_POST['question']);
		$_POST['question'] = str_replace('-moi', ' moi', $_POST['question']);
		$_POST['question'] = str_replace('-le', ' le', $_POST['question']);
		$_POST['question'] = str_replace('-la', ' la', $_POST['question']);
		$_POST['question'] = str_replace('-les', ' les', $_POST['question']);
		/* Clean unecessary characters */
		$_POST['question'] = str_replace('\'', ' ', $_POST['question']);
		$_POST['question'] = str_replace('.', '', $_POST['question']);
		$_POST['question'] = str_replace(':', '', $_POST['question']);
		$_POST['question'] = str_replace(';', '', $_POST['question']);
		$_POST['question'] = str_replace(',', '', $_POST['question']);
		$_POST['question'] = mb_strtolower($_POST['question'], 'UTF-8');
		/* Make the sentence into an array */
		$question_array = preg_split("/[\s]/", trim(str_replace('?', '', $_POST['question']), ' '));
		$question_array = array_filter($question_array, function($value) { return $value !== ''; });
		$path_array = array();
		/* Free card to make the AI learn if * character is used */
		if (($key = array_search('*', $question_array)) !== false) {unset($question_array[$key]); $freecard = 1;}
		
		$question_array = array_values($question_array);
		$test = array();
		
		/* Function to search in an array multidimensional */
		function search_multi_array($data, $word, $type){
			if(isset($data[md5($word)])){
				foreach($data[md5($word)] as $key => $value){
					if($value['cgram'] == $type && $value['ortho'] == $word){
						return true;
					}
				}
			}
			return false;
		}
		
		$data = array();
		/* For each question word */
		foreach($question_array as $key => $value) {
			/* Query in the dictionnary */			
			$lexique_query = mysqli_query($connexion, "SELECT * FROM lexique WHERE ortho = '" . addslashes(mb_strtolower($value, 'UTF-8')) . "' ORDER BY FIND_IN_SET(cgram, 'PRO:int,CON,LIA,ART,ART:def,ART:ind,PRE,PRO:pos,PRO:per,PRO:per:con,PRO:ind,PRO:rel,PRO:dem,AUX,VER,VER:inf,VER:past,ADJ,ADJ:ind,ADJ:int,ADJ:num,ADJ:pos,ADV,ONO,NOM')") or die (mysqli_error($connexion));
			/* If results are found create a new array with index key the question word and all the word possibilities from database */
			if(mysqli_num_rows($lexique_query) > 0){
				while ($row = mysqli_fetch_assoc($lexique_query)) { 
					$data[md5($value)][] = $row; 
				}
			} else {
				/* If no result it means its not a word form dictionnary and stored inside an OTHER array */
				$build_memory['OTHER'][] = array('ortho' => $value);
			}
		}
		/* Looping through all the question words again */
		foreach($question_array as $key => $value) {
			/* If the word of the question is found in the OTHER array, store it inside the human question data array */
			if(isset($build_memory['OTHER']) && !empty($build_memory['OTHER'])){
				$test = 0;
				foreach($build_memory['OTHER'] as $key2 => $value2){
					if($value2['ortho'] == $value){
						$path_array[$key]['ortho'] = $value;
						$path_array[$key]['cgram'] = 'OTHER';
						$path_array[$key]['genre'] = '';
						$path_array[$key]['nombre'] = '';
						$test = 1;
					}
				}
				
				if($test == 1){
					/* Continue to next word to avoid storing it again */
					continue;
				}
			}
			
			$types = array();
			if(isset($data[md5($value)])){
				foreach($data[md5($value)] as $row) {
					$trigger = 1;
					
					/* If last word is a determinant, pronouns, of possesive adjective and the current word is a verb or auxiliary */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) &&
						(
							in_array('ART:def', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADJ:dem', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADJ:pos', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ART:ind', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('PRO:pos', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'VER' || $row['cgram'] == 'VER:past' || $row['cgram'] == 'AUX')
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					
					/* If last word is a determinant and current word is a infinitive verb */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('ART:ind', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'VER:inf')
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					
					/* If last word is a one character association letter and current word is a verb */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('PRE', $words_kept_array[$question_array[$key - 1]])
						) && strlen($question_array[$key - 1]) == 1 && ($row['cgram'] == 'VER')
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					
					/* If last word is a verb or an infinitive verb or a past participle and current word is an auxiliary */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) &&
						(
							in_array('VER', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('VER:inf', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('VER:past', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'AUX') &&
						(strpos($row['infover'], 'inf;') === false)
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					
					/* If last word is an auxiliary and current word is a verb or infinitive verb */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('AUX', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'VER' || $row['cgram'] == 'VER:inf')
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					
					/* If there is two verbs side by side */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) &&
						in_array('VER', $words_kept_array[$question_array[$key - 1]]) &&
						($row['cgram'] == 'VER') && (strpos($row['infover'], 'par:pre;') === false)
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					/* If there is two past participle verbs side by side  */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) &&
						in_array('VER:past', $words_kept_array[$question_array[$key - 1]]) && 
						($row['cgram'] == 'VER:past')
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					
					/* If last word is an infinitive verb and current word is a verb */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) &&
						in_array('VER:inf', $words_kept_array[$question_array[$key - 1]]) && 
						($row['cgram'] == 'VER')
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					
					/* If last word is a determinant and current word is an introduction word */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('ART:def', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'ONO')
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					
					/* If last word is an adjective, determinant, pronouns or adverb and current word is an adjective */
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('ADJ:dem', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADJ:pos', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ART:ind', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('PRO:pos', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ART:def', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADV', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'ADJ')
					){
						/* Exclude this match from database for the current word */
						$trigger = 0;
					}
					
					/* If the word has passed all the validations and if is not already stored */
					if($trigger == 1 && !in_array(format_word($row['ortho']), $types)){
						$build_memory[$row['cgram']][] = array(
							'ortho' => mb_strtolower(($row['ortho']), 'UTF-8'),
							'lemme' => $row['lemme'],
							'cgram' => $row['cgram'],
							'genre' => $row['genre'],
							'nombre' => $row['nombre'],
							'infover' => $row['infover']
						);
						$words_kept .= format_word(mb_strtolower($row['ortho'], 'UTF-8'));
						$words_kept_array[$row['ortho']][$row['id']] = $row['cgram'];
						$words_kept_array2[$row['ortho']][$row['id']] = $row['infover'];
						/* Store the word in the human question data array */
						$path_array[$key]['ortho'] = $row['ortho'];
						$path_array[$key]['cgram'] = $row['cgram'];
						$path_array[$key]['lemme'] = $row['lemme'];
						$path_array[$key]['infover'] = $row['infover'];
						$path_array[$key]['genre'] = !empty($row['genre']) ? $row['genre'] : 'm';
						$path_array[$key]['nombre'] = !empty($row['nombre']) ? $row['nombre'] : 's';
					}
					/* If the word has passed the validation store it in an array to avoid storing it again. */
					if($trigger == 1) { $types[]= format_word($row['ortho']); }
				}
			}
		}
		/* For each posessive adjective word replace by opposite for example : your -> my */
		foreach($path_array as $key => $value){
			if(isset($value['cgram']) && isset($value['ortho']) && ($value['cgram'] == 'ADJ:pos' || $value['cgram'] == 'PRO:pos')){
				$reverse = mysqli_query($connexion, "SELECT * FROM adj_pos WHERE keyword = '".addslashes($value['ortho'])."' COLLATE utf8_bin LIMIT 1") or die (mysqli_error($connexion));
				if(mysqli_num_rows($reverse) > 0){
					$row = mysqli_fetch_assoc($reverse);
					$path_array[$key]['new'] = $row['match'];
				}
			} 
		}
		/* Store the new human questions words in the short term memory session */
		$accepted = array('other', 'nom', 'ver', 'adj');
		$array_= use_session('links_'.$type_bot);
		foreach($accepted as $key => $value) {
			foreach($path_array as $word_key => $word_value){
				$cgram = str_replace(':', '_', mb_strtolower($word_value['cgram'], 'UTF-8'));
				if(isset($array_[$value]) && $value == $cgram && !in_array($word_value['ortho'], $array_[$value])){
					$array_[$value][] = $word_value['ortho'];
				}
			}
		}
		write_session('links_'.$type_bot, $array_);
		
		/* Deciding randomly if the response will be a suggestion or a repetition */
		$action = array('sug', 'rep');
		$rand = rand(0,1);
		$rand = (strpos($_POST['question'], '?') !== false) ? 0 : $rand;
		/* Defining a new array for verbs */
		$verbs = [];
		$memory_insert = 0;
		
		/* From earlier, if no translation for the human question */
		if($wiki_later == 1){
			$name = '';
			/* Detect nouns in the human question data array */
			foreach($path_array as $key => $value){
				if(isset($value['cgram']) && $value['cgram'] == 'NOM'){
					$name = $value['ortho'];
					break;
				}
			}
			/* If a word is found query Wikipedia for a description to be used if the appropriate pronouns are found in human sentence */
			if(!empty($name)){
				$data = file_get_contents('https://'.$wiki_lang.'wikipedia.org/w/api.php?action=opensearch&search='.$name.'&limit=1&format=json');
				$data = json_decode($data, true);
				$description = array();
				if(isset($data[2][0]) && !empty($data[2][0]) && substr($data[2][0], -1) == '.') { 
					$description[] = html_entity_decode($data[2][0], ENT_QUOTES); 
				}
				if(!empty($description)){
					$append_data = ' '. implode(' ', $description);
					$appendToResponse = $append_data;
				}
			}
		}
		
		/* If no special pronoun are found the chatbot will not output Wikipedia description to the human */
		$detect = 0;
		foreach($path_array as $key => $value){
			if(isset($value['cgram']) && $value['cgram'] == 'PRO:int'){
				$detect = 1;
				break;
			}
		}
		if($detect == 0){
			$appendToResponse = '';
		}
		
		/* VERBS function */
		$verbs_and_pronouns = renderVerbs($reason, $path_array, $connexion);
		extract($verbs_and_pronouns, EXTR_OVERWRITE);
		
		/* CORE */
		$variables = array(
			'connexion' => $connexion,
			'action' => $action,
			'rand' => $rand,
			'type_bot' => $type_bot,
			'question_array' => $path_array
		);
		/* Defining a new core class for the learning functions */
		$core = new core($variables);
		
		/* If the sentence contains a posessive adjective or if the last bot response is a question or if the * character is found the chatbot will learn */
		if(isset($build_memory['ADJ:pos']) || isset($build_memory['PRO:pos']) || $trigger_verb == 'learn' || $freecard == 1){
			$order_pro_ver = array();
			/* Create the sentence pattern with each word types */
			$core->createPatterns();
			/* Create the array which will be inserted in the database */
			$core->prepareDataInsert();
			/* Insert data inside the database and return values of the inserted arrays */
			$inserted_data = $core->dataInsert($memory_insert, $append_data, $ip_user);
			extract($inserted_data, EXTR_OVERWRITE);
		}
		
		/* Add the verbs and nouns values to the already said detection variable */
		$new_sentence = '';
		foreach($path_array as $key => $value){
			if(isset($value['cgram']) && $value['cgram'] == 'VER'){
				$new_sentence .= $value['ortho'];
			}
		}
		foreach($path_array as $key => $value){
			if(isset($value['cgram']) && $value['cgram'] == 'NOM'){
				$new_sentence .= $value['ortho'];
			}
		}
		
		$data = '';
		$response2 = array();
		$query4 = array();
		/* If the chatbot is not learning, loop through all human question data array values and add appropriate conditions */
		$accepted = array('nom', 'other', 'adj', 'ver');		
		if($memory_insert != 1){
			$build_conditions4 = array();
			foreach($path_array as $key => $value){
				$new = array();
				$index = str_replace(':', '_', mb_strtolower($value['cgram'], 'UTF-8'));
				if(in_array($index, $accepted)){
					$build_conditions4[$index][] = 'keywords LIKE \'%"'.addslashes($value['ortho']).'"%\'';
				}
			}
			foreach($build_conditions4 as $key => $value){
				if(!empty($value)){
					$query4[] = '('.implode(' COLLATE utf8_bin AND ', $value).' COLLATE utf8_bin)';
				}
			}
			
			if(!empty($query4)){
				$query_used = '';
				$query_links = '';
				if(use_session('used_id_'.$type_bot)){
					$query_used .= ' AND id NOT IN ('.implode(',', use_session('used_id_'.$type_bot)).')';
				}
				
				$links = array();
				/* Adding old short term memory values to the database memory query */
				foreach($accepted as $key1 => $value1) {
					$array_ = use_session('links_'.$type_bot);
					if(isset($array_[$value1]) && !empty($array_[$value1])){
						foreach($array_[$value1] as $key => $value){
							$links[] = 'keywords LIKE \'%"'.addslashes($array_[$value1][$key]).'"%\'';
						}
					}
				}
				
				if(!empty($links)){
					$query_links .= ' OR ('.implode(' COLLATE utf8_bin AND ', $links).' COLLATE utf8_bin)';
				}
				
				$first_query = array();
				
				if(!empty($query4)){
					$first_query[] = '('.implode(') AND (', $query4).')';
				}
				
				$query = 'WHERE (('.implode(') OR (', $first_query).')'.$query_links.') AND (pattern LIKE \'%{%\' AND pattern LIKE \'%}%\') AND ip = "'.$ip_user.'"'.$query_used;
			} else {
				$query = 'WHERE (id = 0) AND ip = "'.$ip_user.'"';
			}
			/* Querying the database for a match */
			$memory_query = mysqli_query($connexion, "SELECT * FROM ai_memory_".$type_bot." ".$query." ORDER BY id DESC LIMIT 1") or die (mysqli_error($connexion));
			$data = mysqli_fetch_assoc($memory_query);
			
			if(mysqli_num_rows($memory_query) > 0){
				/* If a match is found store the ID in the used IDs array session */
				$used_id = use_session('used_id_'.$type_bot);
				$used_id[] = $data['id'];
				write_session('used_id_'.$type_bot, $used_id);
				$randWords = rand(1, 5);
				
				if(!empty($data)){
					/* Getting the pattern outputed for side block data */
					$data['pattern'] = str_replace(' {', '{', $data['pattern']);
					$data['pattern'] = str_replace('} ', '}', $data['pattern']);
					$pattern = preg_split('/[\{,\}]/', $data['pattern']);
					$pattern_chosen = implode(',', array_filter($pattern, function($value) { return $value !== ''; }));
				}
				
				if(!empty($data)){
					/* Add new verbs and nouns to the already said detection variable */
					$new_sentence = '';
					$ver = json_decode($data['ver'], true);
					$nom = json_decode($data['nom'], true);
					$new_sentence .= (is_array($ver) ? implode($ver) : '');
					$new_sentence .= (is_array($nom) ? implode($nom) : '');
					/* Store the bot response in the pattern variable */
					$pattern = $data['human'];
				}
			}
		}
		
		/* If already said set variable or if not increment the sentences storing variable with the new accepted value founds in the pattern */
		if(in_array($new_sentence, use_session('note_'.$type_bot))){
			$already_said = 'yes';
		} else {
			$bot_notes = use_session('note_'.$type_bot);
			$bot_notes[use_session('count_response_'.$type_bot)] = $new_sentence;
			write_session('note_'.$type_bot, $bot_notes);
		}
		
		/* Function to verify granting in the sentence */
		function verify_grammar($full_tag, $kept_ones2, $key, $tag, $end, $recreate, $response_temp) {
			if($tag != 'other'){
				$operator2 = $key - 1;
				if(isset($recreate[$operator2])){
					$group_tag2 = $recreate[$operator2];
					$group_tag2 = preg_replace('/[0-9]+/', '', $group_tag2);
					$group_tag2 = preg_replace('/[\*]+/', '', $group_tag2);
					
					if($group_tag2 != 'other'){
						if(
							!empty($response_temp[$group_tag2]) &&
							!empty($response_temp[$tag]) &&
							isset($kept_ones2[$group_tag2]) &&
							isset($kept_ones2[$tag]) &&
							isset($response_temp[$group_tag2][$kept_ones2[$group_tag2]]['ortho']) &&
							isset($response_temp[$tag][$kept_ones2[$tag]]['ortho'])
						){
							/* Not accepting 2 words with same wording side by side */
							if($response_temp[$group_tag2][$kept_ones2[$group_tag2]]['ortho'] == $response_temp[$tag][$kept_ones2[$tag]]['ortho']){
								return 1;
							}
							/* Not accepting a one letter pronoun next to a determinant */
							if(strlen($response_temp[$group_tag2][$kept_ones2[$group_tag2]]['ortho']) == 1 && $tag == 'art_def'){
								return 1;
							}
						}
					}
				}
			}
			
			if($tag != 'other' && strlen($end) == 1){
				$operator = $key - $end;
				if(isset($recreate[$operator])){
					$group_tag = $recreate[$operator];
					$group_tag = preg_replace('/[0-9]+/', '', $group_tag);
					$group_tag = preg_replace('/[\*]+/', '', $group_tag);
					
					if($group_tag != 'other'){
						if(
							!empty($response_temp[$group_tag]) &&
							!empty($response_temp[$tag]) &&
							isset($kept_ones2[$group_tag]) &&
							isset($kept_ones2[$tag]) &&
							isset($response_temp[$group_tag][$kept_ones2[$group_tag]]['genre']) &&
							isset($response_temp[$tag][$kept_ones2[$tag]]['genre']) &&
							isset($response_temp[$group_tag][$kept_ones2[$group_tag]]['nombre']) &&
							isset($response_temp[$tag][$kept_ones2[$tag]]['nombre'])
						){
							
							/* More than 1 star in the syntax pattern means it will grant the kind */
							if(substr_count($full_tag, '*') > 1){
								/* If word kind is granting with the one targeted */
								if($response_temp[$group_tag][$kept_ones2[$group_tag]]['genre'] != $response_temp[$tag][$kept_ones2[$tag]]['genre']){
									return 1;
								}
							}
							/* If word number is granted with the one targeted */
							if($response_temp[$group_tag][$kept_ones2[$group_tag]]['nombre'] != $response_temp[$tag][$kept_ones2[$tag]]['nombre']){
								return 1;
							}
						}
					}
				} else {
					return 1;
				}
			}
			
			return 0;
		}
		
		/* Recreate a response array with the human question data array */
		$response = array();
		foreach($path_array as $key => $value){
			if(isset($value['cgram'])){
				$value2 = str_replace(':', '_', mb_strtolower($value['cgram'], 'UTF-8'));
				if(isset($value['new'])){
					$response[$value2][] = $value['new'];
				} else {
					$response[$value2][] = $value['ortho'];
				}
			}
		}
		/* Recreate a response array with the human question data array */
		$response_temp = array();
		foreach($path_array as $key => $value){
			if(isset($value['cgram'])){
				$value2 = str_replace(':', '_', mb_strtolower($value['cgram'], 'UTF-8'));
				$response_temp[$value2][] = $value;
			}
		}
		
		$result_array = ['temp memory '.use_session('count_response_'.$type_bot), 'analyse' => array('data' => $data, 'words_found' => $build_memory, 'links' => use_session('links_'.$type_bot), 'counter' => use_session('count_response_'.$type_bot), 'used_id' => implode(', ', use_session('used_id_'.$type_bot)), 'action' => $action[$rand], 'question_array' => $question_array, 'words_kept' => $words_kept, 'already_said' => $already_said, 'new_sentence' => $new_sentence)];
		
		if(!isset($pattern)){
			/* The syntax pattern in case no pattern is found. They are used to reorder sentences in new sentences */
			
			$build_sentence = array();
			$build_container = array();
			$reorder_array = array();
			$sentence_order = array();
			$modele = array();
			
			/* INFINITIF VERBS */
			$modele[] = 'other,ver_inf,art_def|adj_num|adj_pos,nom|other**1,adj+**1,aux,pro_per*1,art_def,nom**1,adj+**1,question';
			$modele[] = 'ver_inf,lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver,art_def,art_def+,nom**2,question';
			$modele[] = 'ver_inf,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver,pro_per_con+,pro_per**2,art_def,art_def+,nom**2,question';
			$modele[] = 'ver_inf,lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux**1,adv+,art_ind,nom**1,adj**1,question';
			$modele[] = 'pro_per,ver,adv+,ver_inf,lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
			/* END OF INFINITIF VERBS */
			
			/* PAST PARTICIPLE */
			/* If no verb in the sentence */
			if(empty($response['ver'])){
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux,pro_per_con+,pro_per*2,adv+,ver_past**2,art_def,art_def+,nom**2,adj+**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux,pro_per_con+,pro_per*2,adv+,ver_past**2,art_ind,nom**1,adj+**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux,pro_per_con+,pro_per*2,adv+,ver_past**2,art_def,art_def+,nom**2,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux,adv+,ver_past**3,art_def,art_def+,nom**2,adj+**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*2,pro_per_con+,pro_per*2,adv+,ver_past**2,question';
				$modele[] = 'art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,aux*2,pro_per_con+,pro_per*2,adv+,ver_past**2,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1,adv+,ver_past**3,art_ind,nom**1,adj+**1,dot';
				$modele[] = 'aux,pro_per_con+,pro_per*2,adv+,ver_past**2,art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux,adv+,ver_past**3,art_def,art_def+,nom**2,dot';
				$modele[] = 'art_def,art_def+,nom|other**2,adj+**1,other,aux*3,pro_per_con+,pro_per*2,adv+,ver_past**2,question';
				$modele[] = 'aux,pro_per_con+,pro_per*2,adv+,ver_past**2,art_def,art_def+,nom|other**2,adj+**1,question';
				$modele[] = 'aux,pro_per_con+,pro_per*2,adv+,ver_past**2,art_def,art_def+,nom|other**2,adj**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*2,adv+,ver_past**3,dot';
				$modele[] = 'art_ind|art_def|adj_num|adj_pos,nom|other**1,adj**1,other,aux*3,adv+,ver_past**5,dot';
			}
			/* END OF PAST PARTICIPLE */
			
			if ($action[$rand] == 'rep') {
				$modele[] = 'art_def|adj_num|adj_pos,other,adj+**2,ver**3,pro_per**1,adv+,art_def,art_def+,nom|other**2,question';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj**1,ver|aux*2,pro_per_con+,pro_per*2,adv+,art_def,art_def+,nom**2,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver|aux*2,pro_per_con+,pro_per*2,adv+,art_def,art_def+,nom**2,question';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver|aux*1,pro_per_con+,pro_per*2,adv+,art_ind,nom**1,adj**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,pro_per_con+,pro_per*2,ver_inf,art_def,art_def+,nom**2,question';
				/* If no verb in the sentence */
				if(empty($response['ver'])){
					$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1,pro_per_con+,pro_per*2,adv+,art_def,art_def+,nom**2,question';
					$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1,pro_per_con+,pro_per*2,art_ind,nom**1,adj**1,question';
					$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1,pro_per_con+,pro_per*2,art_def,art_def+,nom**2,adj**1,question';
				}
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**2,pro_per_con+,pro_per**2,adv+,art_ind,nom**1,adj,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,pro_per_con+,pro_per**2,adv+,art_def,art_def+,nom**2,adj+,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver|aux*1,pro_per_con+,pro_per*2,adv+,art_ind,nom**1,question';
			} elseif ($action[$rand] == 'sug') {
				/* If no verb in the sentence */
				if(empty($response['ver'])){
					$modele[] = 'pro_per,aux*1,adv+,ver_past,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj**2,art_def,art_def+,nom**2,dot';
					$modele[] = 'pro_per,aux*1,ver_past,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,adj**2,dot';
				}
				$modele[] = 'pro_per,ver**1,art_def|adj_num|adj_pos,other,adj+,art_def,art_def+,nom|other**2,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,adv+,art_ind,nom**1,adj+**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,adv+,art_def,art_def+,nom**2,adj+**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,adv+,art_ind,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,adv+,art_def,art_def+,nom**2,dot';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver|aux*1,adv+,adj+**3,art_def,art_def+,nom**2,dot';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver|aux*1,adv+,adj+**3,art_ind,nom**1,dot';
			}
			
			
			/* PAST PARTICIPLE */
			/* If no verb in the sentence */
			if(empty($response['ver'])){
				$modele[] = 'pro_per,aux*1,ver_past**2,art_def,art_def+,nom|other**2,adj+**1,dot';
				$modele[] = 'aux,pro_per*1,adv+,ver_past**2,art_ind,nom|other**1,adj+**1,question';
				$modele[] = 'art_def,art_def+,nom|other**2,aux*1,adv+,ver_past**3,dot';
				$modele[] = 'pro_per,aux*1,ver_past**2,art_ind,nom|other**1,adj+**1,dot';
				$modele[] = 'pro_per|pro_dem|pro_ind,aux*1,ver_past**2,dot';
				$modele[] = 'aux,pro_per_con+,pro_per*2,adv+,ver_past**2,question';
			}
			/* END OF PAST PARTICIPLE */
			
			/* INFINITIF VERBS */
			$modele[] = 'ver_inf,ver_inf+,art_def|adj_num|adj_pos,nom|other**1,adj+**1,question';
			$modele[] = 'adv+,ver_inf,ver_inf+,art_def|adj_num|adj_pos,nom|other**2,question';
			$modele[] = 'adv,ver_inf,ver_inf+,art_def,nom|other**1,adj+**1,question';
			$modele[] = 'pro_per|pro_dem|pro_ind,ver,adv+,lia,ver_inf,adj+,question';
			$modele[] = 'ver_inf,art_def,art_def+,nom|other**2,question';
			$modele[] = 'ver_inf,art_def,nom|other**1,question';
			$modele[] = 'ver_inf,question';
			/* END OF INFINITIF VERBS */
			
			if ($action[$rand] == 'rep') {
				/* If no verb in the sentence */
				if(empty($response['ver'])){
					$modele[] = 'aux,pro_per_con+,pro_per*2,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
					$modele[] = 'pro_per,aux*1,adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
					$modele[] = 'pro_per|pro_dem|pro_ind,aux*1,adv+,ver_past,question';
					$modele[] = 'aux,pro_per*1,art_def,art_def+,nom|other**2,adj+**1,question';
					$modele[] = 'aux,pro_per*1,art_ind,nom|other**1,adj+**1,question';
				}
				$modele[] = 'pro_per|pro_dem|pro_ind,ver|aux*1,adv+,question';
				$modele[] = 'pro_int,ver|aux,pro_per_con+,pro_per*2,question';
				$modele[] = 'nom|other,question';
				$modele[] = 'ono,question';
			} elseif ($action[$rand] == 'sug') {
				/* If no verb in the sentence */
				if(empty($response['ver'])){
					$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1,adv+,other,dot';
					$modele[] = 'pro_per,aux*1,adv+,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**3,dot';
				}
				$modele[] = 'pro_per,ver|aux*1,art_def,art_def+,nom|other**2,adj+**1,dot';
				$modele[] = 'ver,pro_per_con+,pro_per*1,lia,ver_inf,art_def,art_def+,nom|other**2,adj+**1,dot';
				$modele[] = 'pro_per,ver|aux*1,art_ind,nom|other**1,adj+**1,dot';
				$modele[] = 'pro_per,ver|aux*1,art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,dot';
				/* If no verb in the sentence */
				if(empty($response['ver'])){
					$modele[] = 'pro_per,aux*1,art_ind,nom|other**1,adj+**1,dot';
					$modele[] = 'art_def,art_def+,nom|other**2,aux*1,adv+,adj+**3,dot';
					$modele[] = 'adj_num|adj_pos|pro_ind,nom|other**1,aux*1,adv+,adj+**3,dot';
					$modele[] = 'pro_per|pro_dem|pro_ind,aux*1,adv+,adj+,dot';
				}
				$modele[] = 'pro_per|pro_dem|pro_ind,ver|aux*1,dot';
				$modele[] = 'nom|other,dot';
				$modele[] = 'ono,dot';
			}
			
			
			$sentence_order = array();
			//This foreach test if the modele match the number of response type values or if response has any value for the modele value
			foreach($modele as $modele_key => $modele_value){
				$kept_ones = array();
				$recreate = array();
				$tags_new = explode(',', $modele_value);
				$not = 0;				
				foreach($tags_new as $key => $tag){
					if($tag != 'question' && $tag != 'dot'){
						$tag_split2 = explode('*', $tag);
						$tag_split = explode('|', $tag_split2[0]);
						if(count($tag_split) > 1){
							$tag_split_build = array();
							/* If the tag is a multiple choice split the choices and loop in them. */
							foreach($tag_split as $tag_split_key => $tag_split_value){
								if(!empty($response[$tag_split_value])){
									if(!isset($kept_ones[$tag_split_value])) { 
										$kept_ones[$tag_split_value] = 0; 
									} else {
										$kept_ones[$tag_split_value]++;
									}
									/* If the value in the response array is found for first index of the type */
									if(isset($response[$tag_split_value][$kept_ones[$tag_split_value]])){
										foreach($tag_split as $key00 => $value00){
											if($key00 == $tag_split_key){
												$tag_split_build[] = $tag_split[$key00];
											}
										}
										/* Store the word and get out of the multiple choice loop */
										break;
									}
								}
							}
							/* If no word are found in the multiple choice get out of the tag loop and set variable to skip this modele */
							if(empty($tag_split_build)){
								$not = 1;
								break;
							}
							/* If no break, store the tag chosen and found in a new more simple modele array */
							if(substr_count($tag, '*') > 0){
								if(substr_count($tag, '*') == 1) {
									$recreate[] = $tag_split_build[0]."*".end($tag_split2);
								} else {
									$recreate[] = $tag_split_build[0]."**".end($tag_split2);
								} 
							} else {
								$recreate[] = $tag_split_build[0];
							}
						} else {
							/* If the tag is not a multiple choice and is a single word */
							/* If the tag don't contain an optional filter */
							if(strpos($tag_split2[0], '+') === false){
								if(!empty($response[$tag_split2[0]])){
									if(!isset($kept_ones[$tag_split2[0]])) { 
										$kept_ones[$tag_split2[0]] = 0; 
									} else {
										$kept_ones[$tag_split2[0]]++;
									}
									
									/* If no value is found in the response array for this type of word break of the tag loop and set variable to skip this modele */
									if(!isset($response[$tag_split2[0]][$kept_ones[$tag_split2[0]]])){
										$not = 1;
										break;
									} else {
										/* If a word is found, store it in a new more simple modele array */
										if(substr_count($tag, '*') > 0){
											if(substr_count($tag, '*') == 1) {
												$recreate[] = $tag_split[0]."*".end($tag_split2);
											} else {
												$recreate[] = $tag_split[0]."**".end($tag_split2);
											} 
										} else {
											$recreate[] = $tag_split[0];
										}
									}
								} else {
									/* If the type is not found in the response array simply break and set variable to skip this modele */
									$not = 1;
									break;
								}
							} else {
								/* If tag is optional no validation is needed and simply storing it in the new more simple modele array  */
								if(substr_count($tag, '*') > 0){
									if(substr_count($tag, '*') == 1) {
										$recreate[] = $tag_split2[0]."*".end($tag_split2);
									} else {
										$recreate[] = $tag_split2[0]."**".end($tag_split2);
									}
								} else {
									$recreate[] = $tag_split2[0];
								}
							}
						}
					}
				}
				/* Is validation is still good while creating the new simple modele array */
				if($not ==  0){					
					$kept_ones2 = array();
					$toggle = 0;
					$bad_connection = 0;
					
					/*
						-> Example of recreate array
						$modele[] = 'pro_per,aux*1,adv+,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**3,dot';
						$recreate = array(
							'pro_per',
							'aux*1',
							'adv+',
							'art_def', <- chosen word
							'nom**1', <- chosen word
							'adj+**3',
							'dot'
						);
					*/
					
					foreach($recreate as $key7 => $value7){
						$tag_split6 = str_replace('+', '', $value7);
						$tag_split2 = explode('*', $tag_split6);
						if(!isset($kept_ones2[$tag_split2[0]])) { 
							$kept_ones2[$tag_split2[0]] = 0; 
						} else {
							$kept_ones2[$tag_split2[0]]++;
							if(!isset($response_temp[$tag_split2[0]][$kept_ones2[$tag_split2[0]]]) && strpos($value7, '+') !== false){
								$kept_ones2[$tag_split2[0]]--;
							}
						}
						/* Verify granting for each word of the new simple modele array */
						$bad_connection = verify_grammar($tag_split6, $kept_ones2, $key7, $tag_split2[0], end($tag_split2), $recreate, $response_temp);
						
						if($bad_connection == 1) {
							$toggle = 1;
						}
					}
					/* If validation has passed with all the granting and response array testing, choose this pattern */
					if($toggle == 0){
						$sentence_order = $tags_new;
						break;
					}
				}
			}
			if(!empty($sentence_order)){
				//This foreach gets each response type array in each modele value
				$sentence_order2 = preg_replace('/[0-9]+/', '', $sentence_order);
				$sentence_order2 = preg_replace('/[\*]+/', '', $sentence_order2);
				$o = array();
				$storing = 0;
				$last_check = array();
				/* 
					-> Example of the array (original pattern array)
					$modele[] = 'pro_per,aux*1,adv+,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**3,dot';
					$recreate = array(
						'pro_per',
						'aux*1',
						'adv+',
						'lia|art_ind|art_def|adj_num|adj_pos|pro_ind',
						'nom|other**1',
						'adj+**3',
						'dot'
					);
				*/
				/* Loop through the old syntax pattern tags */
				foreach($sentence_order2 as $tag_key => $tag_value){
					if($tag_value != 'question' && $tag_value != 'dot'){
						$tag_split = explode('|', $tag_value);
						if(count($tag_split) > 1){
							/* For multiple choices */
							foreach($tag_split as $tag_split_value){
								if(!empty($response[$tag_split_value])){
									/* If next word is a pronoun and current word is a verb update the response appropriate index to correct grammar */
									if(
										(isset($sentence_order2[$tag_key + 1]) && 
										(($tag_split_value == 'ver' && $sentence_order2[$tag_key + 1] == 'pro_per') ||
										($tag_split_value == 'aux' && $sentence_order2[$tag_key + 1] == 'pro_per')))
									){
										foreach($response['pro_per'] as $word_key => $word_value){
											if($word_value == 'j'){
												$response['pro_per'][$word_key] = 'je';
												break;
											}
										}
									}
									
									/* If response type is already stored delete first value of response type. */
									if(in_array($tag_split_value, $last_check)){
										if(isset($response[$tag_split_value][0])){
											unset($response[$tag_split_value][0]);
										}
									}
									
									/* Reorder response type array */
									$response[$tag_split_value] = array_values($response[$tag_split_value]);
									
									/* If there is a value for the response type store it in a new array */
									if(!empty($response[$tag_split_value])){
										$reorder_array[$storing] = array();
										$reorder_array[$storing] = $response[$tag_split_value];
									}
									
									$last_check[] = $tag_split_value;
									
									break;
								}
							}
						} else {
							/* Remove optional filter from tag */
							$tag_value = str_replace('+', '', $tag_value);
							if(!empty($response[$tag_value])){
								/* If next word is a pronoun and current word is a verb update the response appropriate index to correct grammar */
								if(
									(isset($sentence_order[$tag_key + 1]) && 
									(($tag_value == 'ver' && $sentence_order2[$tag_key + 1] == 'pro_per') ||
									($tag_value == 'aux' && $sentence_order2[$tag_key + 1] == 'pro_per')))
								){
									foreach($response['pro_per'] as $word_key => $word_value){
										if($word_value == 'j'){
											$response['pro_per'][$word_key] = 'je';
											break;
										}
									}
								}
								
								/* If response type is already stored delete first value of response type. */
								if(in_array($tag_value, $last_check)){
									if(isset($response[$tag_value][0])){
										unset($response[$tag_value][0]);
									}
								}
								
								/* Reorder response type array */
								$response[$tag_value] = array_values($response[$tag_value]);
								
								/* If there is a value for the response type store it in a new array */
								if(!empty($response[$tag_value])){
									$reorder_array[$storing] = array();
									$reorder_array[$storing] = $response[$tag_value];
								}
								
								$last_check[] = $tag_value;
							}
						}
					} else {
						if($tag_value == 'dot'){
							$reorder_array[$storing] = '.';
						} else {
							$reorder_array[$storing] = '?';
						}
					}
					$storing++;
				}
				
				/* Array of the final reordered response arrays using pattern modele is ready 
				$reorder_array = array(
					0 => array(
						'tomatoes', <- First element
						'lettuce',
						'onions',
						'carrots',
						'pepper',
						'apple'
					),
					1 => array(
						'are',
						'is',
						'were'
					),
					2 => array(
						'lettuce', <- First new element
						'onions',
						'carrots',
						'pepper',
						'apple'
					), 
					3 => array(
						'and',
						'plus',
						'or'
					), 
					4 => array(
						'onions', <- First new element
						'carrots',
						'pepper',
						'apple'
					), 
					5 => array(
						'is',
						'were'
					), 
					6 => array(
						'carrots', <- First new element
						'pepper',
						'apple'
					),
					7 => '.' 
				)
				*/
				$reorder_array = array_values($reorder_array);
				
				//This for loop iterate the modele with each value => array()
				//It will store an array each sentence and get each response type value for each new modele value
				for($k = 0; $k < 11; $k++){
					$build_sentence[$k] = array();
					foreach($reorder_array as $i => $value){
						if(is_array($value)){
							if(!empty($reorder_array[$i])){
								if(isset($reorder_array[$i][$k])) {
									/* Reorganize element to get them in order and class them by sentence */
									$build_sentence[$k][$i] = $reorder_array[$i][$k];
								}
							}
						} else {
							/* For dots and question mark */
							$build_sentence[$k][$i] = $reorder_array[$i];
						}
					}
					
					if(!empty($build_sentence[$k]) && count($build_sentence[$k]) > 1){
						if(count($reorder_array) == count($build_sentence[$k])){
							/* Store this sentence in a container array */
							$build_container[] = implode(' ', $build_sentence[$k]);
						}
					}
				}
				/*
					-> Example of build_container
					$build_container = array(
						0 => array(
							'tomatoes',
							'are',
							'lettuce',
							'and',
							'onions',
							'is',
							'carrots'
						),
						1 => array(
							'pepper',
							'were',
							'apple',
							'plus'
						)		
					);
				*/
				
				$result_array['analyse']['pattern_chosen'] = implode(', ', $sentence_order);
				$result_array['analyse']['reorder_array'] = $reorder_array;
				$result_array['analyse']['detect'] = 'Syntax Patterns';
				
				$data = $response;
				$response = (!empty($build_container)) ? implode(' ', $build_container) : '';
				$response = str_replace('\' ', '\'', $response);
				$response = str_replace(' .', '.', $response);
				
				/* If this response from the bot is already store in the last response, set response to empty */
				if(in_array($response, use_session('last_response_'.$type_bot))){
					$response = '';
				} else {
				/* Else add this response to the last response array */
					$array = use_session('last_response_'.$type_bot);
					$array[] = $response;
					write_session('last_response_'.$type_bot, $array);
				}
						
				if(!empty($response)){
					$response = explode(' ', $response);
					/* Glue each one letter word to the next word with an apostrophe */
					foreach($response as $key => $value) {
						if(
							$value == 'j' ||
							$value == 'l' ||
							$value == 't' ||
							$value == 's' ||
							$value == 'm' ||
							$value == 'c' ||
							$value == 'd' ||
							$value == 'n'
						) {
							$response[$key] = $value.'\'';
						}
					}
					
					$response = str_replace('\' ','\'', implode(' ', $response));
					
					/* Translate back the sentence to the human language is Google translate is enabled */
					if(use_session('language') && use_session('language') != 'fr' && $google_translate == 1) {
						$post_values = [
							'translate' => $response,
							'language' => use_session('language')
						];
						
						$ch = curl_init($url."demo/google/translate/api.php");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
						
						$data_curl = curl_exec($ch);
						curl_close($ch);
						
						if(!empty($data_curl)) { 
							$result = json_decode($data_curl, true); 
							$response = html_entity_decode($result['translated_text'], ENT_QUOTES); 
						}
					}
					
					$response = ucfirst($response);
					
					$result_array['analyse']['will_learn'] = use_session('last_question_sentence_'.$type_bot);
					
					/* If this response is a question add it to the last question sentence session */
					if(strpos($response, '?') !== false){
						write_session('last_question_'.$type_bot, 1);
						write_session('last_question_sentence_'.$type_bot, ucfirst($response));
					}
					
					/* If the chatbot found a special pronouns and a Wikipedia description set it to be shown to the human */
					if(!empty($appendToResponse)) {
						$response = $appendToResponse;
					}
					
					/* Variable to indicate if the response is an object of a simple text */
					if($_POST['nojson'] == 1){
						/* Used to store the chatbot response inside the chatbox messages (when having a multiple convo with the chatbot) */
						if(isset($_POST['chatbox']) && $_POST['chatbox'] == 'save'){
							$ids = $_POST['ids'];
							$ids = explode('-', $ids);
							session_start();
							mysqli_query($connexion, "INSERT INTO cms_messages (message, date, time, username, group_id, box_id, timestamp_key) VALUES ('".addslashes($response)."', '".date('Y-m-d')."', '".date('H:i:s')."', 'Julie', '".$ids[0]."', '".$ids[1]."', '".round(microtime(true) * 1000)."')") or die (mysqli_error());
						}
						/* Echo response */
						echo $response;
					} else {
						/* Otherwise echo a JSON object */
						$result_array['response'] = $response;
						$result_array['analyse']['detect'] = 'Syntax Patterns';
					}
				} else {
					// If a pattern is matched but no response : output nothing
					
					if($_POST['nojson'] == 1){
						
					} else {
						$result_array['response'] = '';
						$result_array['analyse']['pattern_chosen'] = 'Pattern is empty';
						$result_array['analyse']['detect'] = 'Syntax Patterns';
					}
				}
			} else {
				// If no sentence pattern matched : output nothing
				
				if($_POST['nojson'] == 1){
						
				} else {
					$result_array['response'] = '';
					$result_array['analyse']['pattern_chosen'] = 'No pattern matched';
					$result_array['analyse']['detect'] = 'Syntax Patterns';
				}
			}
		} else {
			/* If pattern is found */
			$pattern = str_replace(' .', '.', $pattern);
			
			if(!empty($pattern)){
				$pattern = explode(' ', $pattern);
				/* Glue each one letter word to the next word with an apostrophe */
				foreach($pattern as $key => $value) {
					if(
						$value == 'j' ||
						$value == 'l' ||
						$value == 't' ||
						$value == 's' ||
						$value == 'm' ||
						$value == 'c' ||
						$value == 'd' ||
						$value == 'n'
					) {
						$pattern[$key] = $value.'\'';
					}
				}
				
				$pattern = str_replace('\' ','\'', implode(' ', $pattern));
				/* Translate back the sentence to the human language is Google translate is enabled */
				if(use_session('language') && use_session('language') != 'fr' && $google_translate == 1) {
					$post_values = [
						'translate' => $pattern,
						'language' => use_session('language')
					];
					
					$ch = curl_init($url."demo/google/translate/api.php");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
					
					$data_curl = curl_exec($ch);
					curl_close($ch);
					
					if(!empty($data_curl)) { 
						$result = json_decode($data_curl, true); 
						$pattern = html_entity_decode($result['translated_text'], ENT_QUOTES); 
					}
				}
				
				$pattern = ucfirst($pattern);
				
				$result_array['analyse']['will_learn'] = use_session('last_question_sentence_'.$type_bot);
				
				/* If this response is a question */
				if(strpos($pattern, '?') !== false){
					write_session('last_question_'.$type_bot, 1);
					write_session('last_question_sentence_'.$type_bot, $pattern);
				}
				
				/* Variable to indicate if the response is an object of a simple text */
				if($_POST['nojson'] == 1){
					/* Used to store the chatbot response inside the chatbox messages (when having a multiple convo with the chatbot) */
					if(isset($_POST['chatbox']) && $_POST['chatbox'] == 'save'){
						$ids = $_POST['ids'];
						$ids = explode('-', $ids);
						session_start();
						mysqli_query($connexion, "INSERT INTO cms_messages (message, date, time, username, group_id, box_id, timestamp_key) VALUES ('".addslashes($pattern)."', '".date('Y-m-d')."', '".date('H:i:s')."', 'Julie', '".$ids[0]."', '".$ids[1]."', '".round(microtime(true) * 1000)."')") or die (mysqli_error());
					}
					
					echo $pattern;	
				} else {
					/* Echo full JSON object */
					$result_array['response'] = $pattern;
					$result_array['analyse']['pattern_chosen'] = str_replace(',', ', ', $pattern_chosen);
					$result_array['analyse']['detect'] = 'Database pattern';
				}				
			} else {
				// If pattern is empty : output nothing
				
				if($_POST['nojson'] == 1){
					
				} else {
					$result_array['response'] = '';
					$result_array['analyse']['pattern_chosen'] = 'Pattern is empty';
					$result_array['analyse']['detect'] = 'Database pattern';
				}
			}
		}
	}
	
	echo json_encode($result_array);
	/* Each 10 response from bot the short term memory is flushed and the counter is resetted */
	if(use_session('count_response_'.$type_bot) > 10){
		$accepted = array('other', 'nom', 'ver', 'adj');
		$new_table = array();
		foreach($accepted as $key => $value) {
			 $new_table[$value] = array();
		}
		write_session('links_'.$type_bot, $new_table);
		write_session('count_response_'.$type_bot, 0);
	}
?>