<?php
	ini_set('display_errors', 1);
	session_start();
	include('../../config.php');
	
	/* CAPTCHA : if failed no answer */
	if($_SERVER['REMOTE_ADDR'] == $server_ip || (isset($_POST['bot']) && $_POST['bot'] == 1)){ $_SESSION['captcha'] = '15'; }
	if(!isset($_SESSION['captcha']) || $_SESSION['captcha'] != '15'){echo json_encode(['response' => '', 'analyse' => array('', 'words_found' => array(), 'pattern_chosen' => 'No pattern found (empty captcha)', 'none', array(), '', 'empty_', 'will_learn' => '', 'already_said' => 'no')]); exit;}else{$_SESSION['captcha'] = '15';}
	$type_bot = $_POST['type'];
	if(!isset($_SESSION['count_response_'.$type_bot])){ $_SESSION['count_response_'.$type_bot] = 0; } $_SESSION['count_response_'.$type_bot]++;
	if(!isset($_SESSION['links_'.$type_bot])){
		$accepted = array('other', 'nom', 'ver', 'adj');
		foreach($accepted as $key => $value) {
			$_SESSION['links_'.$type_bot][$value] = []; 
		}
	}
	
	if(!isset($_SESSION['last_question_'.$type_bot])){ $_SESSION['last_question_'.$type_bot] = 0; }
	if(!isset($_SESSION['last_question_sentence_'.$type_bot])){ $_SESSION['last_question_sentence_'.$type_bot] = ''; }
	if(!isset($_SESSION['used_id_'.$type_bot])){ $_SESSION['used_id_'.$type_bot] = []; }
	if(!isset($_SESSION['note_'.$type_bot])){ $_SESSION['note_'.$type_bot] = []; }
	$already_said = 'no';
	$trigger_verb = '';
	if($_SESSION['last_question_'.$type_bot] == 1){
		$trigger_verb = 'learn';
		$_SESSION['last_question_'.$type_bot] = 0;
	}
	
	if($_SESSION['count_response_'.$type_bot] > 20){
		$_SESSION['used_id_'.$type_bot] = [];
		$_SESSION['note_'.$type_bot] = [];
	}
	
	$_SESSION['note_'.$type_bot][$_SESSION['count_response_'.$type_bot]] = '';

	if(isset($_POST['question']) && isset($_SESSION['language']) && $_SESSION['language'] != 'fr' && $google_translate == 1) {
		$post_values = [
			'translate' => $_POST['question'],
			'language' => 'fr',
		];
		
		$ch = curl_init($url."demo/google/translate/api.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
		
		$response = curl_exec($ch);
		curl_close($ch);
		if(!empty($response)) { $result = json_decode($response, true); $_POST['question'] = $result['translated_text']; }
	}
	
	$append_data = '';
	$wiki_later = 0;
	if($google_natural_language == 1) {
		$post_values = [
			'text' => $_POST['question']
		];
		
		$ch = curl_init($url."demo/google/natural_language/api.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		$wiki_lang = '';
		if(isset($_SESSION['language'])) {
			$wiki_lang = $_SESSION['language'].'.';
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
					if(isset($data[2][0]) && !empty($data[2][0])) { $description[] = $data[2][0]; }
				}
			}
			
			if(!empty($description)){
				$append_data = ' '. implode(' ', $description); 
			}
		} else {
			$wiki_later = 1;
		}
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
	if(isset($_POST['question'])) {
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
		$_POST['question'] = strtolower($_POST['question']);
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
			$lexique_query = mysqli_query($connexion, "SELECT * FROM lexique WHERE ortho = '" . addslashes($value) . "' COLLATE utf8_bin ORDER BY FIND_IN_SET(cgram, 'CON,LIA,ART,ART:def,ART:ind,PRE,PRO:pos,PRO:per,PRO:int,PRO:ind,PRO:rel,PRO:dem,AUX,VER,ADJ,ADJ:ind,ADJ:int,ADJ:num,ADJ:pos,ADV,ONO,NOM')") or die (mysqli_error($connexion));
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
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('ART:def', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADJ:dem', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADJ:pos', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('PRO:pos', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'VER' || $row['cgram'] == 'AUX')
					){
						$trigger = 0;
					}
					
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('ART:def', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADJ:dem', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('ADJ:pos', $words_kept_array[$question_array[$key - 1]]) ||
							in_array('PRO:pos', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'ADJ')
					){
						$trigger = 0;
					}
					
					if(
						isset($question_array[$key - 1]) &&
						isset($words_kept_array[$question_array[$key - 1]]) && (
							in_array('AUX', $words_kept_array[$question_array[$key - 1]])
						) && ($row['cgram'] == 'VER') && (strpos($row['infover'], 'par:pas;') === false)
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
						search_multi_array($data, $question_array[$key + 1], 'AUX') && ($row['cgram'] == 'VER')
					){
						$trigger = 0;
					}
					
					if($trigger == 1 && !in_array($row['ortho'], $types)){
						$build_memory[$row['cgram']][] = array(
							'ortho' => strtolower($row['ortho']),
							'lemme' => $row['lemme'],
							'cgram' => $row['cgram'],
							'genre' => $row['genre'],
							'nombre' => $row['nombre'],
							'infover' => $row['infover']
						);
						$words_kept .= strtolower($row['ortho']);
						$words_kept_array[$row['ortho']][$row['id']] = $row['cgram'];
						$path_array[$key]['cgram'] = $row['cgram'];
						$path_array[$key]['genre'] = $row['genre'];
						$path_array[$key]['nombre'] = $row['nombre'];
					}
					if($trigger == 1) { $types[]= $row['ortho']; }
				}
			}
		}
		
		$accepted = array('other', 'nom', 'ver', 'adj');
		foreach($accepted as $key => $value) {
			if(isset($build_memory[strtoupper($value)])) {
				foreach($build_memory[strtoupper($value)] as $word_key => $word_value){
					if(!in_array($word_value['ortho'], $_SESSION['links_'.$type_bot][$value])){
						$_SESSION['links_'.$type_bot][$value][] = $word_value['ortho'];
					}
				}
			}
		}
		
		$groups = array('adj', 'adv', 'art', 'aux', 'con', 'lia', 'nom', 'ono', 'other', 'pre', 'pro', 'ver', 'adj_dem', 'adj_ind', 'adj_int', 'adj_num', 'adj_pos', 'art_def', 'art_inf', 'pro_dem', 'pro_ind', 'pro_int', 'pro_per', 'pro_pos', 'pro_rel');
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
			if(strpos($words_kept, $question_array[$key]) !== false){} else {
				$build_memory['OTHER'][] = array('ortho' => $value);
			}
		}
		
		if($wiki_later == 1){
			if(isset($build_memory['NOM'][0])){
				$data = file_get_contents('https://'.$wiki_lang.'wikipedia.org/w/api.php?action=opensearch&search='.$build_memory['NOM'][0]['ortho'].'&limit=1&format=json');
				$data = json_decode($data, true);
				$description = array();
				if(isset($data[2][0]) && !empty($data[2][0])) { $description[] = $data[2][0]; }
				
				if(!empty($description)){
					$append_data = ' '. implode(' ', $description); 
				}
			}
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
					$index = str_replace(':', '_', strtolower($key));
					
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
				if(!empty($_SESSION['used_id_'.$type_bot])){
					$query_used .= ' AND id NOT IN ('.implode(',', $_SESSION['used_id_'.$type_bot]).')';
				}
				
				$links = array();
				$accepted = array('other', 'nom', 'ver', 'adj');
				foreach($accepted as $key1 => $value1) {
					if(isset($_SESSION['links_'.$type_bot][$value1]) && !empty($_SESSION['links_'.$type_bot][$value1])){
						foreach($_SESSION['links_'.$type_bot][$value1] as $key => $value){
							$links[] = $value1.' LIKE \'%'.addslashes($_SESSION['links_'.$type_bot][$value1][$key]).'%\'';
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
				$_SESSION['used_id_'.$type_bot][] = $data['id'];
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
		
		if(in_array($new_sentence, $_SESSION['note_'.$type_bot])){
			$already_said = 'yes';
		} else {
			$_SESSION['note_'.$type_bot][$_SESSION['count_response_'.$type_bot]] = $new_sentence;	
		}
		/*
		function verify_grammar($kept_ones, $tag_split_value, $tag_split2, $tags, $key, $response_temp) {
			$tag_clean = preg_replace('/[0-9]+/', '', $tag_split_value);
			$tag_clean = preg_replace('/[\*]+/', '', $tag_clean);
			$tag_clean = preg_replace('/[\+]+/', '', $tag_clean);
			
			if($tag_clean != 'other'){
				$position = end($tag_split2);
				$operator = $key - $position;
				if(isset($tags[$operator])){
					$group_tag = $tags[$operator];
					$group_tag = preg_replace('/[0-9]+/', '', $group_tag);
					$group_tag = preg_replace('/[\*]+/', '', $group_tag);
					$group_tag = preg_replace('/[\+]+/', '', $group_tag);
					$group_tag2 = explode('|', $group_tag);
					if(count($group_tag2) > 1){
						foreach($group_tag2 as $index9 => $value9){
							if($value9 != 'other'){
								if(
									!empty($response_temp[$value9]) &&
									!empty($response_temp[$tag_clean]) &&
									isset($kept_ones[$value9]) &&
									isset($kept_ones[$tag_clean]) &&
									isset($response_temp[$value9][$kept_ones[$value9]]['genre']) &&
									isset($response_temp[$tag_clean][$kept_ones[$tag_clean]]['genre']) &&
									isset($response_temp[$value9][$kept_ones[$value9]]['nombre']) &&
									isset($response_temp[$tag_clean][$kept_ones[$tag_clean]]['nombre'])
								){
									if($response_temp[$value9][$kept_ones[$value9]]['genre'] != $response_temp[$tag_clean][$kept_ones[$tag_clean]]['genre']){
										return 1;
									}
									if($response_temp[$value9][$kept_ones[$value9]]['nombre'] != $response_temp[$tag_clean][$kept_ones[$tag_clean]]['nombre']){
										return 1;
									}
								}
							}
						}
					} else {
						if($group_tag != 'other'){
							if(
								!empty($response_temp[$group_tag]) &&
								!empty($response_temp[$tag_clean]) &&
								isset($kept_ones[$group_tag]) &&
								isset($kept_ones[$tag_clean]) &&
								isset($response_temp[$group_tag][$kept_ones[$group_tag]]['genre']) &&
								isset($response_temp[$tag_clean][$kept_ones[$tag_clean]]['genre']) &&
								isset($response_temp[$group_tag][$kept_ones[$group_tag]]['nombre']) &&
								isset($response_temp[$tag_clean][$kept_ones[$tag_clean]]['nombre'])
							){
								if($response_temp[$group_tag][$kept_ones[$group_tag]]['genre'] != $response_temp[$tag_clean][$kept_ones[$tag_clean]]['genre']){
									return 1;
								}
								
								if($response_temp[$group_tag][$kept_ones[$group_tag]]['nombre'] != $response_temp[$tag_clean][$kept_ones[$tag_clean]]['nombre']){
									return 1;
								}
							}
						}
					}
				} else {
					return 1;
				}
			}
			
			return 0;
		}*/
		function verify_grammar($kept_ones2, $key, $tag, $end, $recreate, $response_temp) {
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
							if($response_temp[$group_tag][$kept_ones2[$group_tag]]['genre'] != $response_temp[$tag][$kept_ones2[$tag]]['genre']){
								return 1;
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
			if(isset($_SESSION['part']) && $_SESSION['part'] == true) {
				/////// MAKE SURE ALL THE TAGS HAVE DIFFERENT NAMES
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other,aux,pro_per**1,ver**1,adv+,art_def,art_def+,nom**1,adj+**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other,adv+,aux,pro_per**1,ver**1,art_ind,nom**1,adj+**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other,adv+,aux,pro_per**1,ver**1,art_def,art_def+,nom**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other,adv+,aux**2,ver**3,art_def,art_def+,nom**1,adj+**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other,adv+,aux**2,ver**3,art_ind,nom**1,adj+**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other,adv+,aux**2,ver**3,art_def,art_def+,nom**1,dot';				
				$modele[] = 'aux,pro_per,adv+,ver,art_def,art_def+,nom|other**6,adj**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux,pro_per**1,ver**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux,pro_per**1,ver**1,question';
				$modele[] = 'art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,other,adv+,aux,pro_per**1,ver**1,question';
				$modele[] = 'art_ind|art_def|adj_num|adj_pos,nom|other**1,adj**1,other,aux**3,ver**4,dot';
				$modele[] = 'art_def,art_def+,nom|other**1,adv+,aux**2,ver**3,dot';
				$modele[] = 'art_def,art_def+,nom|other**1,adj+**1,other,adv+,aux,pro_per**1,ver**1,question';
				$modele[] = 'art_def,art_def+,nom|other**1,adv+,aux**2,ver**3,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux**2,ver**3,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux**2,ver**3,dot';
				$modele[] = 'pro_per,aux**1,ver**2,art_def,art_def+,nom|other**1,adj+**1,dot';
				$modele[] = 'adv+,aux,pro_per**1,ver**2,art_ind,nom|other**1,adj+**1,question';
				$modele[] = 'pro_per,aux**1,ver**2,art_ind,nom|other**1,adj+**1,dot';
				$modele[] = 'adv+,aux,pro_per**1,ver**2,art_def,art_def+,nom|other**1,adj+**1,question';
				$modele[] = 'adv+,aux,pro_per**1,ver**2,art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,question';
				$modele[] = 'pro_per|pro_dem|pro_ind|nom,aux**1,ver**2,dot';
				$modele[] = 'adv+,aux,pro_per**1,ver**1,question';
				
				$_SESSION['part'] = false;
			} elseif ($action[$rand] == 'rep') {
				$modele[] = 'art_def|adj_num|adj_pos,other,adj+,adv+,ver**4,pro_per**1,art_def,art_def+,nom|other**1,question';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver|aux**2,pro_per,adj,art_def,art_def+,nom**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux|ver,pro_per**1,art_def,art_def+,nom**1,question';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver|aux,pro_per**1,adj**4,art_ind,nom**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux,pro_per**1,art_def,art_def+,nom**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux,pro_per**1,art_ind,nom**1,adj**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,aux**1,pro_per,art_def,art_def+,nom**1,adj**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver,pro_per**1,art_ind,adj,nom**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver,pro_per**1,art_def,art_def+,adj+,nom**1,question';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux|ver,pro_per**1,art_ind,nom**1,question';
				$modele[] = 'pro_per,aux,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
				$modele[] = 'pro_per,aux,adj_num|adj_pos|pro_ind,nom|other**1,adj+**1,question';
				$modele[] = 'pro_per|pro_dem|pro_ind,adv+,aux**1,adj+**1,question';
				$modele[] = 'aux,pro_per**1,art_def,art_def+,nom|other**1,adj+**1,question';
				$modele[] = 'aux,pro_per**1,art_ind,nom|other**1,adj+**1,question';
				$modele[] = 'pro_per|pro_dem|pro_ind,adv+,aux|ver**2,question';
				$modele[] = 'pro_int,aux|ver,pro_per**1,question';
				$modele[] = 'nom|other,question';
				$modele[] = 'ono,question';
			} elseif ($action[$rand] == 'sug') {
				$modele[] = 'pro_per,ver**1,art_def|adj_num|adj_pos,other,adj+,art_def,art_def+,nom|other**1,dot';
				$modele[] = 'pro_per,adv+,aux**2,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj**2,art_def,art_def+,nom**1,dot';
				$modele[] = 'pro_per,adv+,aux**2,lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adj**2,art_ind,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_ind,nom**1,adj+**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_def,art_def+,nom**1,adj+**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_ind,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_def,art_def+,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_ind,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver**2,art_def,art_def+,nom**1,dot';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver|aux**2,adj+**3,art_def,art_def+,nom**1,dot';
				$modele[] = 'lia|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,ver|aux**2,adj+**3,art_ind,nom**1,dot';
				$modele[] = 'lia|art_ind|art_def|adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux**2,adj+**3,dot';
				$modele[] = 'pro_per,aux|ver**1,art_def,art_def+,nom|other**1,adj+**1,dot';
				$modele[] = 'pro_per,aux|ver**1,art_def,art_def+,nom|other**1,adj+**1,dot';
				$modele[] = 'pro_per,aux**1,art_def,art_def+,nom|other**1,adj+**1,dot';
				$modele[] = 'pro_per,aux|ver**1,art_ind,nom|other**1,adj+**1,dot';
				$modele[] = 'pro_per,aux|ver**1,art_ind|art_def|adj_num|adj_pos,nom|other**1,adj+**1,dot';
				$modele[] = 'pro_per,aux**1,art_ind,nom|other**1,adj+**1,dot';
				$modele[] = 'art_def,art_def+,nom|other**1,adv+,aux**2,adj+**3,dot';
				$modele[] = 'adj_num|adj_pos|pro_ind,nom|other**1,adv+,aux**2,adj+**3,dot';
				$modele[] = 'pro_per|pro_dem|pro_ind,adv+,aux**2,adj+,dot';
				$modele[] = 'pro_per|pro_dem|pro_ind,aux|ver**1,dot';
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
							
							if(strlen(end($tag_split2)) == 1){
								$recreate[] = $tag_split_build[0]."**".end($tag_split2);
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
										if(strlen(end($tag_split2)) == 1){
											$recreate[] = $tag_split[0]."**".end($tag_split2);
										} else {
											$recreate[] = $tag_split[0];
										}
									}
								} else {
									$not = 1;
									break;
								}
							} else {
								$tag_split2[0] = str_replace('+', '', $tag_split2[0]);
								if(strlen(end($tag_split2)) == 1){
									$recreate[] = $tag_split[0]."**".end($tag_split2);
								} else {
									$recreate[] = $tag_split[0];
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
						$tag_split2 = explode('*', $value7);
						if(!isset($kept_ones2[$tag_split2[0]])) { 
							$kept_ones2[$tag_split2[0]] = 0; 
						} else {
							$kept_ones2[$tag_split2[0]]++;
						}
						$bad_connection = verify_grammar($kept_ones2, $key7, $tag_split2[0], end($tag_split2), $recreate, $response_temp);
						
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
					
					if(isset($_SESSION['language']) && $_SESSION['language'] != 'fr' && $google_translate == 1) {
						$post_values = [
							'translate' => $response,
							'language' => $_SESSION['language'],
						];
						
						$ch = curl_init($url."demo/google/translate/api.php");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
						
						$data_curl = curl_exec($ch);
						curl_close($ch);
						
						if(!empty($data_curl)) { 
							$result = json_decode($data_curl, true); 
							$response = $result['translated_text']; 
						}
					}
					
					$response = ucfirst($response);
					
					/* if this response is a question */
					if(strpos($response, '?') !== false){
						$_SESSION['last_question_'.$type_bot] = 1;
						$_SESSION['last_question_sentence_'.$type_bot] = ucfirst($response);
					}
					
					if($_POST['nojson'] == 1){
						echo $response;
					} else {
						echo json_encode(['response' => $response, 'temp memory '.$_SESSION['count_response_'.$type_bot], 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => implode(',', $sentence_order), $reorder_array, $action[$rand], $question_array, $words_kept, 'not_pattern', 'already_said' => $already_said, 'will_learn' => $_SESSION['last_question_sentence_'.$type_bot], 'new_sentence' => $new_sentence)]);
					}
				} else {
					if($_POST['nojson'] == 1){
						
					} else {
						echo json_encode(['response' => '', 'temp memory '.$_SESSION['count_response_'.$type_bot], 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => implode(',', $sentence_order), $reorder_array, $action[$rand], $question_array, $words_kept, 'not_pattern empty_response', 'already_said' => $already_said, 'will_learn' => $_SESSION['last_question_sentence_'.$type_bot], 'new_sentence' => $new_sentence)]);
					}
				}
			} else {
				if($_POST['nojson'] == 1){
						
				} else {
					echo json_encode(['response' => '', 'temp memory '.$_SESSION['count_response_'.$type_bot], 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => 'No pattern found', $reorder_array, $action[$rand], $question_array, $words_kept, 'not_pattern no_sentence_order', 'already_said' => $already_said, 'will_learn' => $_SESSION['last_question_sentence_'.$type_bot], 'new_sentence' => $new_sentence)]);
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
				
				if(isset($_SESSION['language']) && $_SESSION['language'] != 'fr' && $google_translate == 1) {
					$post_values = [
						'translate' => $pattern,
						'language' => $_SESSION['language'],
					];
					
					$ch = curl_init($url."demo/google/translate/api.php");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
					
					$data_curl = curl_exec($ch);
					curl_close($ch);
					
					if(!empty($data_curl)) { 
						$result = json_decode($data_curl, true); 
						$pattern = $result['translated_text']; 
					}
				}
				
				$pattern = ucfirst($pattern);
				
				/* if this response is a question */
				if(strpos($pattern, '?') !== false){
					$_SESSION['last_question_'.$type_bot] = 1;
					$_SESSION['last_question_sentence_'.$type_bot] = $pattern;
				}
				
				if($_POST['nojson'] == 1){
					echo $pattern;	
				} else {
					echo json_encode(['response' => $pattern, 'temp memory '.$_SESSION['count_response_'.$type_bot], 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => $pattern_chosen, $action[$rand], $question_array, $words_kept, 'pattern', 'will_learn' => $_SESSION['last_question_sentence_'.$type_bot], 'already_said' => $already_said, 'new_sentence' => $new_sentence)]);
				}				
			} else {
				if($_POST['nojson'] == 1){
					
				} else {
					echo json_encode(['response' => '', 'temp memory '.$_SESSION['count_response_'.$type_bot], 'analyse' => array($data, 'words_found' => $build_memory, 'pattern_chosen' => 'No pattern found', $action[$rand], $question_array, $words_kept, 'pattern empty_pattern', 'will_learn' => $_SESSION['last_question_sentence_'.$type_bot], 'already_said' => $already_said, 'new_sentence' => $new_sentence)]);
				}
			}
		}
	}
	
	if($_SESSION['count_response_'.$type_bot] > 20){
		$accepted = array('other', 'nom', 'ver', 'adj');
		foreach($accepted as $key => $value) {
			$_SESSION['links_'.$type_bot][$value] = []; 
		}
		$_SESSION['count_response_'.$type_bot] = 0;
	}
?>