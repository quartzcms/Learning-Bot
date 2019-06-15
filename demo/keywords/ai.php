<?php
	ini_set('display_errors', 1);
	include('../../config.php');
	include('../sessions/save_sessions.php');
	include('../sessions/use_sessions.php');
	if(isset($_POST['language'])){
		write_session('language', $_POST['language']);
	}
	
	/* CAPTCHA : if failed no answer */
	if($_SERVER['REMOTE_ADDR'] == $server_ip || (isset($_POST['bot']) && $_POST['bot'] == 1)){ write_session('captcha', '15'); }
	if(!use_session('captcha') || use_session('captcha') != '15'){echo json_encode(['response' => '', 'analyse' => array('', 'words_found' => array(), 'pattern_chosen' => 'No pattern found (empty captcha)', 'none', array(), '', 'empty_', 'will_learn' => '', 'already_said' => 'no')]); exit;}else{write_session('captcha', '15');}
	$type_bot = $_POST['type'];
	if(!use_session('count_response_'.$type_bot)){ write_session('count_response_'.$type_bot, 0); } 
	$count_response = use_session('count_response_'.$type_bot);
	write_session('count_response_'.$type_bot, $count_response + 1);
	if(!use_session('links_'.$type_bot)){
		$accepted = array('other', 'nom', 'ver', 'adj');
		$new_table = array();
		foreach($accepted as $key => $value) {
			$new_table[$value] = array();
		}
		write_session('links_'.$type_bot, $new_table);
	}
	
	if(!use_session('last_question_'.$type_bot)){ write_session('last_question_'.$type_bot, 0); }
	if(!use_session('last_question_sentence_'.$type_bot)){ write_session('last_question_sentence_'.$type_bot, ''); }
	if(!use_session('used_id_'.$type_bot)){ write_session('used_id_'.$type_bot, array()); }
	if(!use_session('note_'.$type_bot)){ write_session('note_'.$type_bot, array()); }
	$already_said = 'no';
	$trigger_verb = '';
	if(use_session('last_question_'.$type_bot) == 1){
		$trigger_verb = 'learn';
		write_session('last_question_'.$type_bot, 0);
	}
	
	if(use_session('count_response_'.$type_bot) > 10){
		write_session('used_id_'.$type_bot, array());
		write_session('note_'.$type_bot, array());
	}
	
	$bot_notes = use_session('note_'.$type_bot);
	$bot_notes[use_session('count_response_'.$type_bot)] = '';
	write_session('note_'.$type_bot, $bot_notes);
	
	if(isset($_POST['question']) && !empty($_POST['question'])){
		$natural_language_question = $_POST['question'];
	}
	
	if(isset($_POST['question']) && !empty($_POST['question']) && use_session('language') && use_session('language') != 'fr' && $google_translate == 1) {
		$post_values = [
			'translate' => $_POST['question'],
			'language' => 'fr',
		];
		
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
	if($google_natural_language == 1 && isset($natural_language_question) && !empty($natural_language_question)) {
		$post_values = [
			'text' => $natural_language_question
		];
		$ch = curl_init($url."demo/google/natural_language/api.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		$wiki_lang = '';
		if(use_session('language')) {
			$wiki_lang = use_session('language').'.';
		} else {
			$wiki_lang = 'fr.';
		}
		
		if(!empty($response)) { 
			$result = json_decode($response, true); 
			
			$description = array();
			
			foreach($result as $key => $value){
				if(strpos($result[$key], ' ') === false) {
					$data = file_get_contents('https://'.$wiki_lang.'wikipedia.org/w/api.php?action=opensearch&search='.$result[$key].'&limit=1&format=json');
					$data = json_decode($data, true);
					if(isset($data[2][0]) && !empty($data[2][0]) && substr($data[2][0], -1) == '.') { 
						$description[] = html_entity_decode($data[2][0], ENT_QUOTES);
						break; 
					}
				}
			}
			if(!empty($description)){
				$append_data = ' '. implode(' ', $description);
				$appendToResponse =  $append_data;
			}
		} else {
			$wiki_later = 1;
		}
	}	
	
	function format_word($al_txt = null) {
        $al_transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ç' => 'c', 'Ç' => 'C', 'Ð' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ë' => 'e', 'Ë' => 'E', 'ƒ' => 'f', 'ƒ' => 'F', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ñ' => 'n', 'Ñ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ö' => 'oe', 'Ö' => 'OE', 'š' => 's', 'Š' => 'S', 'ß' => 'SS', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'û' => 'u', 'Û' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ý' => 'y', 'Ý' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'ž' => 'z', 'Ž' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u');
        $al_txt = str_replace(array_keys($al_transliterationTable), array_values($al_transliterationTable), html_entity_decode($al_txt));
        $al_txt = preg_replace_callback("/[^a-zA-Z0-9]/", function() {
            return "_";
        }, $al_txt);
        return $al_txt;
    }
	
	include('../core/functions.php');
	include('../core/core.php');
	$connexion = mysqli_connect($al_host, $al_user, $al_password, $al_db_name);
	mysqli_set_charset($connexion, 'utf8');
	$build_memory = array();
	$response = array();
	$words_kept = '';
	$words_kept_array = array();
	$freecard = 0;
	if(isset($_POST['question']) && !empty($_POST['question'])) {
		$reason = $_POST['question'];
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
		$_POST['question'] = str_replace('\'', ' ', $_POST['question']);
		$_POST['question'] = str_replace('.', '', $_POST['question']);
		$_POST['question'] = str_replace(',', '', $_POST['question']);
		$_POST['question'] = mb_strtolower($_POST['question'], 'UTF-8');
		$question_array = preg_split("/[\s]/", trim(str_replace('?', '', $_POST['question']), ' '));
		$question_array = array_filter($question_array, function($value) { return $value !== ''; });
		$path_array = array();
		if (($key = array_search('*', $question_array)) !== false) {unset($question_array[$key]); $freecard = 1;}
		
		$question_array = array_values($question_array);
		$test = array();
		
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
		
		foreach($question_array as $key => $value) {
			$lexique_query2 = mysqli_query($connexion, "SELECT * FROM lexique WHERE ortho = '" . addslashes(mb_strtolower($value, 'UTF-8')) . "' COLLATE utf8_bin") or die (mysqli_error($connexion));
			if(mysqli_num_rows($lexique_query2) == 0){
				$utf8 = ' COLLATE utf8_general_ci';
			} else {
				$utf8 = ' COLLATE utf8_bin';
			}
			
			$lexique_query = mysqli_query($connexion, "SELECT * FROM lexique WHERE ortho = '" . addslashes(mb_strtolower($value, 'UTF-8')) . "'".$utf8." ORDER BY FIND_IN_SET(cgram, 'PRO:int,CON,LIA,ART,ART:def,ART:ind,PRE,PRO:pos,PRO:per,PRO:per:con,PRO:ind,PRO:rel,PRO:dem,AUX,VER,VER:inf,VER:past,ADJ,ADJ:ind,ADJ:int,ADJ:num,ADJ:pos,ADV,ONO,NOM')") or die (mysqli_error($connexion));
			if(mysqli_num_rows($lexique_query) > 0){
				while ($row = mysqli_fetch_assoc($lexique_query)) { 
					$data[md5($value)][] = $row; 
				}
			}
		}
		
		foreach($question_array as $key => $value) {
			$types = array();
			$path_array[$key]['ortho'] = $value;
			if(isset($data[md5($value)])){
				foreach($data[md5($value)] as $row) {
					$trigger = 1;
	
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) &&
						isset($question_array[$key - 2]) &&
						isset($words_kept_array[$question_array[$key - 2]]) && (
							(!in_array('ART:def', $words_kept_array[$question_array[$key - 1]]) && in_array('PRO:int', $words_kept_array[$question_array[$key - 2]])) ||
							in_array('ADJ:dem', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADJ:pos', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ART:ind', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('PRO:pos', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'VER' || $row['cgram'] == 'VER:inf' || $row['cgram'] == 'VER:past' || $row['cgram'] == 'AUX')
					){
						$trigger = 0;
					}
					
					if(
						isset($question_array[$key - 1]) &&
						isset($question_array[$key - 2]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && 
						isset($words_kept_array[$question_array[$key - 2]]) && (
							in_array('ADJ', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ART:def', $words_kept_array[$question_array[$key - 2]])
						) && ($row['cgram'] == 'VER')
					){
						$trigger = 0;
					}
					/*
					if(
						isset($question_array[$key - 1]) &&
						isset($question_array[$key - 2]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && 
						isset($words_kept_array[$question_array[$key - 2]]) && 
						(in_array('VER', $words_kept_array[$question_array[$key - 1]]) || 
						in_array('VER:past', $words_kept_array[$question_array[$key - 1]]) || 
						in_array('VER:inf', $words_kept_array[$question_array[$key - 1]]) || 
						in_array('AUX', $words_kept_array[$question_array[$key - 1]])) &&
						(in_array('VER', $words_kept_array[$question_array[$key - 2]]) || 
						in_array('VER:past', $words_kept_array[$question_array[$key - 2]]) || 
						in_array('VER:inf', $words_kept_array[$question_array[$key - 2]]) || 
						in_array('AUX', $words_kept_array[$question_array[$key - 2]])) && 
						($row['cgram'] == 'VER' || $row['cgram'] == 'VER:past' || $row['cgram'] == 'VER:inf' || $row['cgram'] == 'AUX')
					){
						$trigger = 0;
					}*/
					
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('ART:def', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'ONO')
					){
						$trigger = 0;
					}
					
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('ADJ:dem', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADJ:pos', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ART:ind', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('PRO:pos', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'ADJ')
					){
						$trigger = 0;
					}
					
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('AUX', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'VER' || $row['cgram'] == 'VER:inf' || $row['cgram'] == 'VER:past') && (strpos($row['infover'], 'par:pas;') === false)
					){
						$trigger = 0;
					}
					
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('ART:def', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'PRO:ind')
					){
						$trigger = 0;
					}
					
					if(
						isset($question_array[$key + 1]) && 
						strlen($question_array[$key + 1]) == 1 && 
						($row['cgram'] == 'VER')
					){
						$trigger = 0;
					}
					
					if(
						isset($question_array[$key + 1]) &&
						search_multi_array($data, $question_array[$key + 1], 'AUX') && ($row['cgram'] == 'VER' || $row['cgram'] == 'VER:inf' || $row['cgram'] == 'VER:past')
					){
						$trigger = 0;
					}
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
						$path_array[$key]['cgram'] = $row['cgram'];
						$path_array[$key]['genre'] = $row['genre'];
						$path_array[$key]['nombre'] = $row['nombre'];
					}
					if($trigger == 1) { $types[]= format_word($row['ortho']); }
				}
			}
		}
		
		$accepted = array('other', 'nom', 'ver', 'adj');
		$array_= use_session('links_'.$type_bot);
		foreach($accepted as $key => $value) {
			if(isset($build_memory[strtoupper($value)])) {
				foreach($build_memory[strtoupper($value)] as $word_key => $word_value){
					if(isset($array_[$value]) && !in_array($word_value['ortho'], $array_[$value])){
						$array_[$value][] = $word_value['ortho'];
					}
				}
			}
		}
		write_session('links_'.$type_bot, $array_);
		
		$groups = array('adj', 'adv', 'art', 'aux', 'con', 'lia', 'nom', 'ono', 'other', 'pre', 'pro', 'ver', 'adj_dem', 'adj_ind', 'adj_int', 'adj_num', 'adj_pos', 'art_def', 'art_inf', 'pro_dem', 'pro_ind', 'pro_int', 'pro_per', 'pro_pos', 'pro_rel', 'pro_per_con', 'ver_inf', 'ver_past');
		foreach($groups as $value){
			$response[$value] = array();
		}
		
		$response3['pro_per'] = array();
		$response['pro_g'] = array();
		$response['pro_n'] = array();
		
		$action = array('sug', 'rep');
		$rand = rand(0,1);
		$rand = (strpos($_POST['question'], '?') !== false) ? 0 : $rand;
		$verbs = [];
		$memory_insert = 0;
		
		foreach($question_array as $key => $value){
			if(strpos($words_kept, format_word($question_array[$key])) !== false){} else {
				$build_memory['OTHER'][] = array('ortho' => $value);
			}
		}
		
		if($wiki_later == 1){
			if(isset($build_memory['NOM'][0])){
				$data = file_get_contents('https://'.$wiki_lang.'wikipedia.org/w/api.php?action=opensearch&search='.$build_memory['NOM'][0]['ortho'].'&limit=1&format=json');
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
		
		if(!isset($build_memory['PRO:int'])){
			$appendToResponse = '';
		}
		
		$response_temp = array();
		foreach($groups as $value){
			$response_temp[$value] = array();
		}
		
		include('../core/includes.php');
		include('../core/loops.php');
		
		/* VERBS */
		$verbs_and_pronouns = renderVerbs($verbs, $response, $response_temp, $path_array, $build_memory, $connexion);
		extract($verbs_and_pronouns, EXTR_OVERWRITE);
		/* CLEAN PRONOUNS */
		unset($response['pro_g']);
		unset($response['pro_n']);
		/* CORE */
		$core = new core($variables);
		
		if(isset($build_memory['ADJ:pos']) || $trigger_verb == 'learn' || $freecard == 1){
			$order_pro_ver = array();
			$core->createPatterns($response);
			$core->prepareDataInsert($response);
			$inserted_data = $core->dataInsert($response, $memory_insert, $append_data);
			extract($inserted_data, EXTR_OVERWRITE);
		}
		
		foreach($groups as $value){
			$response[$value] = !empty($response[$value]) ? array_values($response[$value]) : array();
		}
		foreach($groups as $value){
			$response_temp[$value] = !empty($response_temp[$value]) ? array_values($response_temp[$value]) : array();
		}
		
		$new_sentence = '';
		$new_sentence .= (is_array($response['ver']) ? implode($response['ver']) : '');
		$new_sentence .= (is_array($response['nom']) ? implode($response['nom']) : '');
		
		$data = '';
		$response2 = array();
		$query4 = array();
		
		$accepted = array('nom', 'other', 'adj', 'ver');		
		if(isset($build_memory) && !empty($build_memory) && $memory_insert != 1){
			foreach($build_memory as $key => $value){
				
				if(!empty($build_memory[$key]) && isset($build_memory[$key])){
					$build_conditions4 = array();
					$new = array();
					$index = str_replace(':', '_', mb_strtolower($key, 'UTF-8'));
					
					foreach($value as $word_key => $word_value){
						if(isset($response[$index])){
							if(in_array($index, $accepted)){
								$build_conditions4[] = 'keywords LIKE \'%"'.addslashes($word_value['ortho']).'"%\'';
							}
						}
					}
					if(!empty($build_conditions4)){
						if(!empty($build_conditions4)){
							$query4[] = '('.implode(' COLLATE utf8_bin OR ', $build_conditions4).' COLLATE utf8_bin)';
						}
					}
				}
			}
			if(!empty($query4)){
				$query_used = '';
				$query_links = '';
				if(use_session('used_id_'.$type_bot)){
					$query_used .= ' AND id NOT IN ('.implode(',', use_session('used_id_'.$type_bot)).')';
				}
				
				$links = array();
				$accepted = array('other', 'nom', 'ver', 'adj');
				foreach($accepted as $key1 => $value1) {
					$array_ = use_session('links_'.$type_bot);
					if(isset($array_[$value1]) && !empty($array_[$value1])){
						foreach($array_[$value1] as $key => $value){
							$links[] = $value1.' LIKE \'%'.addslashes($array_[$value1][$key]).'%\'';
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
				
				$query = 'WHERE (('.implode(') OR (', $first_query).')'.$query_links.') AND (pattern LIKE \'%{%\' AND pattern LIKE \'%}%\')'.$query_used;
			} else {
				$query = 'WHERE (id = 0)';
			}
			
			$memory_query = mysqli_query($connexion, "SELECT * FROM ai_memory_".$type_bot." ".$query." ORDER BY id DESC LIMIT 1") or die (mysqli_error($connexion));
			$data = mysqli_fetch_assoc($memory_query);
			
			if(mysqli_num_rows($memory_query) > 0){
				write_session('used_id_'.$type_bot, array($data['id']));
				$randWords = rand(1, 5);
				
				if(!empty($data)){
					$data['pattern'] = str_replace(' {', '{', $data['pattern']);
					$data['pattern'] = str_replace('} ', '}', $data['pattern']);
					$pattern = preg_split('/[\{,\}]/', $data['pattern']);
					$pattern_chosen = implode(',', array_filter($pattern, function($value) { return $value !== ''; }));
				}
				
				if(!empty($data)){
					$new_sentence = '';
					$ver = json_decode($data['ver'], true);
					$nom = json_decode($data['nom'], true);
					$new_sentence .= (is_array($ver) ? implode($ver) : '');
					$new_sentence .= (is_array($nom) ? implode($nom) : '');
					$pattern = $data['human'];
				}
			}
		}
		
		if(in_array($new_sentence, use_session('note_'.$type_bot))){
			$already_said = 'yes';
		} else {
			$bot_notes = use_session('note_'.$type_bot);
			$bot_notes[use_session('count_response_'.$type_bot)] = $new_sentence;
			write_session('note_'.$type_bot, $bot_notes);
		}
		
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
							if($response_temp[$group_tag2][$kept_ones2[$group_tag2]]['ortho'] == $response_temp[$tag][$kept_ones2[$tag]]['ortho']){
								return 1;
							}
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
							
							
							if(substr_count($full_tag, '*') > 1){
								if($response_temp[$group_tag][$kept_ones2[$group_tag]]['genre'] != $response_temp[$tag][$kept_ones2[$tag]]['genre']){
									return 1;
								}
							}
							
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
		
		if(!isset($pattern)){
			$build_sentence = array();
			$build_container = array();
			$reorder_array = array();
			$sentence_order = array();
			$modele = array();
			
			/* INFINITIF VERBS */
			$modele[] = 'other,ver_inf,art_def|adj_num|adj_pos,nom|other**1,adj+**1,aux,pro_per*1,art_def,nom**1,adj+**1,question';
			$modele[] = 'other,ver_inf,art_def|adj_num|adj_pos,nom|other**1,adj+**1,aux,pro_per*1,art_def,adj+**1,question';
			$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver,ver_inf,art_def,art_def+,nom**2,question';
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver,ver_inf,art_def,art_def+,nom**2,question';
			$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver,ver_inf,art_ind,nom**1,adj**1,question';
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver,ver_inf,art_def,art_def+,nom**2,adj+**1,question';
			/* END OF INFINITIF VERBS */
			
			/* PAST PARTICIPLE */
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux,pro_per_con+,pro_per*2,ver_past**1,adv+,art_def,art_def+,nom**2,adj+**1,question';
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux,pro_per_con+,pro_per*2,ver_past**1,art_ind,nom**1,adj+**1,question';
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux,pro_per_con+,pro_per*2,ver_past**1,art_def,art_def+,nom**2,question';
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux,ver_past**3,art_def,art_def+,nom**2,adj+**1,dot';
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux*2,pro_per_con+,pro_per*2,ver_past**1,question';
			$modele[] = 'art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,other,adv+,aux*4,pro_per_con+,pro_per*2,ver_past**1,question';
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux,ver_past**3,art_ind,nom**1,adj+**1,dot';
			$modele[] = 'adv+,aux,pro_per_con+,pro_per*2,ver_past**1,art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,question';
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux,ver_past**3,art_def,art_def+,nom**2,dot';
			$modele[] = 'art_def,art_def+,nom|other**1,adj+**1,other,adv+,aux*4,pro_per_con+,pro_per*2,ver_past**1,question';
			$modele[] = 'adv+,aux,pro_per_con+,pro_per*2,ver_past**1,art_def,art_def+,nom|other**2,adj+**1,question';
			$modele[] = 'aux,pro_per_con+,pro_per*2,adv+,ver_past**2,art_def,art_def+,nom|other**6,adj**1,question';
			$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux*2,ver_past**3,dot';
			$modele[] = 'art_ind|art_def|adj_num|adj_pos,nom|other**1,adj**1,other,aux*3,ver_past**4,dot';
			/* END OF PAST PARTICIPLE */
			
			if ($action[$rand] == 'rep') {
				$modele[] = 'art_def|adj_num|adj_pos,other,adj+**2,adv+,ver**4,pro_per**1,art_def,art_def+,nom|other**2,question';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver|aux*2,pro_per_con+,pro_per*2,adj**1,art_def,art_def+,nom**2,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux|ver*2,pro_per_con+,pro_per*2,art_def,art_def+,nom**2,question';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver|aux*2,pro_per_con+,pro_per*2,adj**1,art_ind,nom**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux*2,pro_per_con+,pro_per*2,art_def,art_def+,nom**2,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,ver**1,pro_per_con+,pro_per*2,ver_inf,art_def,art_def+,nom**2,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1,pro_per_con+,pro_per*2,art_ind,nom**1,adj**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux*1,pro_per_con+,pro_per*2,art_def,art_def+,nom**2,adj**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,pro_per_con+,pro_per**2,art_ind,adj,nom**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,pro_per_con+,pro_per**2,art_def,art_def+,adj+,nom**2,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux|ver*2,pro_per_con+,pro_per*2,art_ind,nom**1,question';
			} elseif ($action[$rand] == 'sug') {
				$modele[] = 'pro_per,ver**1,art_def|adj_num|adj_pos,other,adj+,art_def,art_def+,nom|other**2,dot';
				$modele[] = 'pro_per,adv+,aux*2,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj**2,art_def,art_def+,nom**2,dot';
				$modele[] = 'pro_per,adv+,aux*2,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj**2,art_ind,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_ind,nom**1,adj+**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_def,art_def+,nom**2,adj+**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_ind,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_def,art_def+,nom**2,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_ind,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_def,art_def+,nom**2,dot';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver|aux*2,adj+**3,art_def,art_def+,nom**2,dot';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver|aux*2,adj+**3,art_ind,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux*2,adj+**3,dot';
			}
			
			
			/* PAST PARTICIPLE */
			$modele[] = 'pro_per,aux*1,ver_past**2,art_def,art_def+,nom|other**2,adj+**1,dot';
			$modele[] = 'adv+,aux,pro_per*1,ver_past**2,art_ind,nom|other**1,adj+**1,question';
			$modele[] = 'art_def,art_def+,nom|other**2,adv+,aux*2,ver_past**3,dot';
			$modele[] = 'pro_per,aux*1,ver_past**2,art_ind,nom|other**1,adj+**1,dot';
			$modele[] = 'pro_per|pro_dem|pro_ind|nom,aux*1,ver_past**2,dot';
			$modele[] = 'adv+,aux,pro_per_con+,pro_per*2,ver_past**1,question';
			/* END OF PAST PARTICIPLE */
			
			/* INFINITIF VERBS */
			$modele[] = 'ver,ver_inf,art_def|adj_num|adj_pos,nom|other**1,adj+**1,question';
			$modele[] = 'ver,ver_inf,art_def|adj_num|adj_pos,adv+,nom|other**2,question';
			$modele[] = 'ver_inf,art_def|adj_num|adj_pos,nom|other**1,adj+**1,question';
			$modele[] = 'ver,ver_inf,adv,art_def,nom|other**1,adj+**1,question';
			$modele[] = 'pro_per|pro_dem|pro_ind,adv+,ver,ver_inf,adj+**1,question';
			$modele[] = 'ver_inf,art_def,art_def+,nom|other**2,question';
			$modele[] = 'ver_inf,art_def,nom|other**1,question';
			$modele[] = 'ver_inf,question';
			/* END OF INFINITIF VERBS */
			
			if ($action[$rand] == 'rep') {
				$modele[] = 'aux*1,pro_per_con+,pro_per,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
				$modele[] = 'pro_per,aux*1,adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
				$modele[] = 'pro_per|pro_dem|pro_ind,adv+,aux*2,adj+**1,question';
				$modele[] = 'aux,pro_per*1,art_def,art_def+,nom|other**2,adj+**1,question';
				$modele[] = 'aux,pro_per*1,art_ind,nom|other**1,adj+**1,question';
				$modele[] = 'pro_per|pro_dem|pro_ind,adv+,aux|ver*2,question';
				$modele[] = 'pro_int,aux|ver,pro_per_con+,pro_per*2,question';
				$modele[] = 'nom|other,question';
				$modele[] = 'ono,question';
			} elseif ($action[$rand] == 'sug') {
				$modele[] = 'pro_per,aux|ver*1,art_def,art_def+,nom|other**2,adj+**1,dot';
				$modele[] = 'ver,pro_per_con+,pro_per*1,ver_inf,art_def,art_def+,nom|other**2,adj+**1,dot';
				$modele[] = 'pro_per,aux*1,art_def,art_def+,nom|other**2,adj+**1,dot';
				$modele[] = 'pro_per,aux|ver*1,art_ind,nom|other**1,adj+**1,dot';
				$modele[] = 'pro_per,aux|ver**1,art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,dot';
				$modele[] = 'pro_per,aux*1,art_ind,nom|other**1,adj+**1,dot';
				$modele[] = 'art_def,art_def+,nom|other**2,adv+,aux*2,adj+**3,dot';
				$modele[] = 'adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux*2,adj+**3,dot';
				$modele[] = 'pro_per|pro_dem|pro_ind,adv+,aux*2,adj+,dot';
				$modele[] = 'pro_per|pro_dem|pro_ind,aux|ver*1,dot';
				$modele[] = 'nom|other,dot';
				$modele[] = 'ono,dot';
			}
			
			
			$sentence_order = array();
			//This foreach test if the modele match the number of response type values or if response has any value for the modele value
			foreach($modele as $modele_key => $modele_value){
				//$bad_connection = 0;
				$kept_ones = array();
				$recreate = array();
				$tags_new = explode(',', $modele_value);
				//$i = 0;
				$not = 0;				
				foreach($tags_new as $key => $tag){
					if($tag != 'question' && $tag != 'dot'){
						$tag_split2 = explode('*', $tag);
						$tag_split = explode('|', $tag_split2[0]);
						if(count($tag_split) > 1){
							$tag_split_build = array();
							foreach($tag_split as $tag_split_key => $tag_split_value){
								if(!empty($response[$tag_split_value])){
									if(!isset($kept_ones[$tag_split_value])) { 
										$kept_ones[$tag_split_value] = 0; 
									} else {
										$kept_ones[$tag_split_value]++;
									}
									if(isset($response[$tag_split_value][$kept_ones[$tag_split_value]])){
										foreach($tag_split as $key00 => $value00){
											if($key00 == $tag_split_key){
												$tag_split_build[] = $tag_split[$key00];
											}
										}
										break;
									}
								}
							}
							if(empty($tag_split_build)){
								$not = 1;
								break;
							}
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
							if(strpos($tag_split2[0], '+') === false){
								if(!empty($response[$tag_split2[0]])){
									if(!isset($kept_ones[$tag_split2[0]])) { 
										$kept_ones[$tag_split2[0]] = 0; 
									} else {
										$kept_ones[$tag_split2[0]]++;
									}
									
									if(!isset($response[$tag_split2[0]][$kept_ones[$tag_split2[0]]])){
										$not = 1;
										break;
									} else {
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
									$not = 1;
									break;
								}
							} else {
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
				
				if($not ==  0){					
					$kept_ones2 = array();
					$toggle = 0;
					$bad_connection = 0;
					
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
						
						$bad_connection = verify_grammar($tag_split6, $kept_ones2, $key7, $tag_split2[0], end($tag_split2), $recreate, $response_temp);
						
						if($bad_connection == 1) {
							$toggle = 1;
						}
					}
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
				foreach($sentence_order2 as $tag_key => $tag_value){
					if($tag_value != 'question' && $tag_value != 'dot'){
						$tag_split = explode('|', $tag_value);
						if(count($tag_split) > 1){
							foreach($tag_split as $tag_split_value){
								if(!empty($response[$tag_split_value])){
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
									
									if(in_array($tag_split_value, $last_check)){
										if(isset($response[$tag_split_value][0])){
											unset($response[$tag_split_value][0]);
										}
									}
									
									$response[$tag_split_value] = array_values($response[$tag_split_value]);
									
									if(!empty($response[$tag_split_value])){
										$reorder_array[$storing] = array();
										$reorder_array[$storing] = $response[$tag_split_value];
									}
									
									$last_check[] = $tag_split_value;
									
									break;
								}
							}
						} else {
							$tag_value = str_replace('+', '', $tag_value);
							if(!empty($response[$tag_value])){
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
								
								if(in_array($tag_value, $last_check)){
									if(isset($response[$tag_value][0])){
										unset($response[$tag_value][0]);
									}
								}
								
								$response[$tag_value] = array_values($response[$tag_value]);
								
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
				
				$reorder_array = array_values($reorder_array);
				
				//This for loop iterate the modele with each value => array()
				//It will store an array each sentence and get each response type value for each new modele value
				for($k = 0; $k < 11; $k++){
					$build_sentence[$k] = array();
					foreach($reorder_array as $i => $value){
						if(is_array($value)){
							if(!empty($reorder_array[$i])){
								if(isset($reorder_array[$i][$k])) {
									$build_sentence[$k][$i] = $reorder_array[$i][$k];
								}
							}
						} else {
							$build_sentence[$k][$i] = $reorder_array[$i];
						}
					}
					
					if(!empty($build_sentence[$k]) && count($build_sentence[$k]) > 1){
						if(count($reorder_array) == count($build_sentence[$k])){
							$build_container[] = implode(' ', $build_sentence[$k]);
						}
					}
				}
				
				$data = $response;
				$response = (!empty($build_container)) ? implode(' ', $build_container) : '';
				$response = str_replace('\' ', '\'', $response);
				$response = str_replace(' .', '.', $response);
						
				if(!empty($response)){
					$response = explode(' ', $response);
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
					
					/* if this response is a question */
					if(strpos($response, '?') !== false){
						write_session('last_question_'.$type_bot, 1);
						write_session('last_question_sentence_'.$type_bot, ucfirst($response));
					}
					
					if(!empty($appendToResponse)) {
						$response = $appendToResponse;
					}
					
					if($_POST['nojson'] == 1){
						echo $response;
					} else {
						echo json_encode(['response' => $response, 'temp memory '.use_session('count_response_'.$type_bot), 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => implode(',', $sentence_order), $reorder_array, $action[$rand], $question_array, $words_kept, 'not_pattern', 'already_said' => $already_said, 'will_learn' => use_session('last_question_sentence_'.$type_bot), 'new_sentence' => $new_sentence)]);
					}
				} else {
					//if a pattern is matched but no response : output nothing
					
					if($_POST['nojson'] == 1){
						
					} else {
						echo json_encode(['response' => '', 'temp memory '.use_session('count_response_'.$type_bot), 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => implode(',', $sentence_order), $reorder_array, $action[$rand], $question_array, $words_kept, 'not_pattern empty_response', 'already_said' => $already_said, 'will_learn' => use_session('last_question_sentence_'.$type_bot), 'new_sentence' => $new_sentence)]);
					}
				}
			} else {
				//if no sentence pattern matched : output nothing
				
				if($_POST['nojson'] == 1){
						
				} else {
					echo json_encode(['response' => '', 'temp memory '.use_session('count_response_'.$type_bot), 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => 'No pattern found', $reorder_array, $action[$rand], $question_array, $words_kept, 'not_pattern no_sentence_order', 'already_said' => $already_said, 'will_learn' => use_session('last_question_sentence_'.$type_bot), 'new_sentence' => $new_sentence)]);
				}
			}
		} else {
			$pattern = str_replace(' .', '.', $pattern);
			
			if(!empty($pattern)){
				$pattern = explode(' ', $pattern);
				
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
				
				/* if this response is a question */
				if(strpos($pattern, '?') !== false){
					write_session('last_question_'.$type_bot, 1);
					write_session('last_question_sentence_'.$type_bot, $pattern);
				}
				
				if($_POST['nojson'] == 1){
					echo $pattern;	
				} else {
					echo json_encode(['response' => $pattern, 'temp memory '.use_session('count_response_'.$type_bot), 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => $pattern_chosen, $action[$rand], $question_array, $words_kept, 'pattern', 'will_learn' => use_session('last_question_sentence_'.$type_bot), 'already_said' => $already_said, 'new_sentence' => $new_sentence)]);
				}				
			} else {
				//if pattern is empty : output nothing
				
				if($_POST['nojson'] == 1){
					
				} else {
					echo json_encode(['response' => '', 'temp memory '.use_session('count_response_'.$type_bot), 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => 'No pattern found', $action[$rand], $question_array, $words_kept, 'pattern empty_pattern', 'will_learn' => use_session('last_question_sentence_'.$type_bot), 'already_said' => $already_said, 'new_sentence' => $new_sentence)]);
				}
			}
		}
	}
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