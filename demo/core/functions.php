<?php
	function renderVerbs($verbs, $response, $response_temp, $path_array, $build_memory, $connexion){
		if(!empty($verbs)){
			$target_before = 0;
			$verb_array_count = array();
			foreach($verbs as $key => $value){
				$tenses = $value['infover'];
				$after_verb = '';
				$pre_verb = '';
				$middle_verb = '';
				$middle_current_verb = '';	
				$pre_verb_artDef_genre = '';
				$pre_verb_artDef_nombre = '';
				
				$new_question = $path_array;
				
				// For complement sentence (remove temporarly complement to get subject number and kind
				foreach($new_question as $index => $word){
					if(isset($word['cgram']) && $word['cgram'] == 'VER'){
						for($i = $index; $i > -1; $i--){
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'PRO:int'){
								unset($new_question[$i]);
							}
							
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'PRO:per'){
								break;
							}
						}
						
						for($i = $index; $i < count($new_question); $i++){
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'PRO:int'){
								unset($new_question[$i]);
							}
							
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'PRO:per'){
								break;
							}
						}
					}
				}
				
				$new_question = array_values($new_question);
				
				foreach($new_question as $index => $word){
					if($word['ortho'] == $value['ortho']){
						for($i = ((($index - 1) > -1) ? ($index - 1) : 0); $i > -1; $i--){							
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'VER' && $new_question[$i]['cgram'] == 'AUX'){
								break;
							}
							
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'NOM'){
								if(
								((isset($new_question[$i - 3]['cgram']) && 
								($new_question[$i - 3]['cgram'] != 'NOM' && $new_question[$i - 3]['cgram'] != 'ART:def' && $new_question[$i - 3]['cgram'] != 'ADJ:pos')) || !isset($new_question[$i - 3]['cgram'])) &&
								((isset($new_question[$i - 2]['cgram']) && 
								($new_question[$i - 2]['cgram'] != 'NOM' && $new_question[$i - 2]['cgram'] != 'ART:def' && $new_question[$i - 2]['cgram'] != 'ADJ:pos')) || !isset($new_question[$i - 2]['cgram'])) 
								) {
									$pre_verb = $new_question[$i]['ortho'];
									break;
								}
							}
						}
						
						if(!empty($pre_verb)) {
							for($i = ((($index - 1) > -1) ? ($index - 1) : 0); $i > -1; $i--){
								if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'VER' && $new_question[$i]['cgram'] == 'AUX'){
									break;
								}
															
								if(isset($new_question[$i]['cgram']) && ($new_question[$i]['cgram'] == 'ART:def' || $new_question[$i]['cgram'] == 'ADJ:pos')){
									if(
									((isset($new_question[$i - 2]['cgram']) && 
									($new_question[$i - 2]['cgram'] != 'NOM' && $new_question[$i - 2]['cgram'] != 'ART:def' && $new_question[$i - 2]['cgram'] != 'ADJ:pos')) || !isset($new_question[$i - 2]['cgram'])) &&
									((isset($new_question[$i - 1]['cgram']) && 
									($new_question[$i - 1]['cgram'] != 'NOM' && $new_question[$i - 1]['cgram'] != 'ART:def' && $new_question[$i - 1]['cgram'] != 'ADJ:pos')) || !isset($new_question[$i - 1]['cgram'])) 
									) {
										$pre_verb_artDef_genre = $new_question[$i]['genre'];
										$pre_verb_artDef_nombre = $new_question[$i]['nombre'];
										break;
									}
								}
							}
						}
						
						$j = 0;
						$plural = 0;
						for($i = ((($index - 1) > -1) ? ($index - 1) : 0); $i > -1; $i--){
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'VER' && $new_question[$i]['cgram'] == 'AUX'){
								break;
							}
							
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'NOM'){
								$j++;
							}
							
							if(isset($new_question[$i]['cgram']) && 
								(
									$new_question[$i]['ortho'] == 'et' || 
									$new_question[$i]['ortho'] == 'ou' || 
									$new_question[$i]['ortho'] == 'ainsi' || 
									$new_question[$i]['ortho'] == 'comme'
								)
							){
								$j++;
							}
							
							if($j == 3){
								$plural = 1;
								break;
							}
						}
								
						if(isset($new_question[$index - 1]) && isset($new_question[$index - 1]['cgram']) && $new_question[$index - 1]['cgram'] == 'PRO:per' && strlen($new_question[$index - 1]['ortho']) == 1){
							$middle_current_verb = $new_question[$index - 1]['ortho'];
							
							if($new_question[$index - 1]['ortho'] == 'm'){
								$middle_verb = 't';
							} elseif($new_question[$index - 1]['ortho'] == 't') {
								$middle_verb = 'm';
							} elseif($new_question[$index - 1]['ortho'] == 's') {
								$middle_verb = 's';
							} elseif($new_question[$index - 1]['ortho'] == 'y') {
								$middle_verb = 'y';
							}
						}
						
						for($i = ((($index - 1) > -1) ? ($index - 1) : 0); $i > -1; $i--){
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'VER' && $new_question[$i]['cgram'] == 'AUX'){
								break;
							}
														
							if(isset($new_question[$i]['cgram']) && ($new_question[$i]['cgram'] == 'PRO:per' || $new_question[$i]['cgram'] == 'PRO:dem') && $new_question[$i]['ortho'] != $middle_verb){
								$pronoun_verb = $new_question[$i]['ortho'];
								
								if (
								$pronoun_verb == 'il' || $pronoun_verb == 'elle' || 
								$pronoun_verb == 'ils' || $pronoun_verb == 'elles' || 
								$pronoun_verb == 'j' || $pronoun_verb == 'je' || 
								$pronoun_verb == 'tu' || 
								$pronoun_verb == 'nous' || 
								$pronoun_verb == 'vous' || 
								$pronoun_verb == 'moi' || 
								$pronoun_verb == 'toi' || 
								$pronoun_verb == 'toi-même' || 
								$pronoun_verb == 'moi-même' || 
								$pronoun_verb == 'nous-même' || 
								$pronoun_verb == 'vous-même' || 
								$pronoun_verb == 'celle' || 
								$pronoun_verb == 'ceux' || 
								$pronoun_verb == 'celles' || 
								$pronoun_verb == 'celui' || 
								$pronoun_verb == 'ceci' || 
								$pronoun_verb == 'cela'
								){
									$after_verb = $new_question[$i]['ortho'];
									break;
								}
							}
						}
					
						if(empty($after_verb)){
							$j = 0;
							for($i = ((($index + 1) < count($new_question)) ? ($index + 1) : 0); $i < count($new_question); $i++){
								if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'VER' && $new_question[$i]['cgram'] == 'AUX'){
									break;
								}
								
								if(isset($new_question[$i]['cgram']) && ($new_question[$i]['cgram'] == 'PRO:per' || $new_question[$i]['cgram'] == 'PRO:dem')){
									$pronoun_verb = $new_question[$i]['ortho'];
									if (
									$pronoun_verb == 'il' || $pronoun_verb == 'elle' || 
									$pronoun_verb == 'ils' || $pronoun_verb == 'elles' || 
									$pronoun_verb == 'j' || $pronoun_verb == 'je' || 
									$pronoun_verb == 'tu' || 
									$pronoun_verb == 'nous' || 
									$pronoun_verb == 'vous' || 
									$pronoun_verb == 'moi' || 
									$pronoun_verb == 'toi' || 
									$pronoun_verb == 'toi-même' || 
									$pronoun_verb == 'moi-même' || 
									$pronoun_verb == 'nous-même' || 
									$pronoun_verb == 'vous-même' ||
									$pronoun_verb == 'celle' || 
									$pronoun_verb == 'ceux' || 
									$pronoun_verb == 'celles' || 
									$pronoun_verb == 'celui' || 
									$pronoun_verb == 'ceci' || 
									$pronoun_verb == 'cela'
									){
										$after_verb = $new_question[$i]['ortho'];
										break;
									}
								}
								$j++;
								
								if($j == 1){
									break;
								}
							}
						}
					}
				}
				$pro = 'il';
				if(!in_array($after_verb, $response['pro_per']) && !in_array($after_verb, $response['pro_dem'])) {	
					foreach($build_memory as $cgram => $value1){
						foreach($build_memory[$cgram] as $word_key => $word_value){								
							if($word_value['ortho'] == $pre_verb && isset($word_value['genre'])) {
								if($pre_verb_artDef_genre == 'm' || $word_value['genre'] == 'm'){
									$pro = 'il';
								} 
								if($pre_verb_artDef_genre == 'f' || $word_value['genre'] == 'f'){
									$pro = 'elle';
								}
								if($pre_verb_artDef_nombre == 's' || $word_value['nombre'] == 's'){
									$pro .= '';
								}
								if($plural == 1 || $pre_verb_artDef_nombre == 'p' || $word_value['nombre'] == 'p'){
									$pro .= 's';
								}
								break 2;
							}
						}
					}
				} else {
					$pro = $after_verb;
				}
				$rightTense = array('ind', 'pre');
				if ($pro == 'il' || $pro == 'elle' || 
					$pro == 'celle' || $pro == 'cela' || 
					$pro == 'ceci' || $pro == 'celui'){
					$person = '3s';
					$pro_nombre = 's';
					$pro_per = $pro;
				} elseif ($pro == 'ils' || $pro == 'elles' || 
						  $pro == 'celles' || $pro == 'ceux'){
					$person = '3p';
					$pro_nombre = 'p';
					$pro_per = $pro;
				} elseif ($pro == 'j' || $pro == 'je'){
					$person = '2s';
					$pro_nombre = 's';
					$pro_per = 'tu';
				} elseif ($pro == 'tu'){
					$person = '1s';
					$pro_nombre = 's';
					$pro_per = 'je';
				} elseif ($pro == 'nous'){
					$person = '2p';
					$pro_nombre = 'p';
					$pro_per = 'vous';
				} elseif ($pro == 'vous'){
					$person = '1p';
					$pro_nombre = 'p';
					$pro_per = 'nous';
				} elseif ($pro == 'moi'){
					$person = '2s';
					$pro_nombre = 's';
					$pro_per = 'toi';
				} elseif ($pro == 'toi'){
					$person = '1s';
					$pro_nombre = 's';
					$pro_per = 'moi';
				} elseif ($pro == 'toi-même'){
					$person = '1s';
					$pro_nombre = 's';
					$pro_per = 'moi-même';
				} elseif ($pro == 'moi-même'){
					$person = '2s';
					$pro_nombre = 's';
					$pro_per = 'toi-même';
				} elseif ($pro == 'nous-même'){
					$person = '2p';
					$pro_nombre = 'p';
					$pro_per = 'vous-même';
				} elseif ($pro == 'vous-même'){
					$person = '1p';
					$pro_nombre = 'p';
					$pro_per = 'nous-même';
				} else {
					$person = '3';
					$pro_nombre = 's';
					$pro_per = 'il';
					
					foreach($build_memory['PRO:per'] as $key2 => $value2){
						if($build_memory['PRO:per'][$key2]['ortho'] == $pro){
							if($build_memory['PRO:per'][$key2]['genre'] == 'f'){
								$pro_per = 'elle';
							} else {
								$pro_per = 'il';
							}
						
							if($build_memory['PRO:per'][$key2]['nombre'] == 'p'){
								$pro_per .= 's';
								$person .= 'p';
								$pro_nombre = 'p';
							} else {
								$pro_per .= '';
								$person .= 's';
								$pro_nombre = 's';
							}
						}
					}
				}
				
				/*Store in response temp variable*/
				$cgram = str_replace(':', '_', strtolower($value['cgram']));				
				$response_temp['pro_per'][$key]['ortho'] = $pro_per;
				$response_temp['pro_per'][$key]['nombre'] = $pro_nombre;
				$response_temp['pro_per'][$key]['genre'] = 'm';
				/////////////////////////////////////
				
				if(
					(strpos($tenses, 'inf;') !== false) &&
					(strpos($tenses, '3s') === false) &&
					(strpos($tenses, '3p') === false) &&
					(strpos($tenses, '1s') === false) &&
					(strpos($tenses, '2s') === false)
				){
					$lexique_query = mysqli_query($connexion, "SELECT ortho,genre,nombre,cgram FROM lexique WHERE infover LIKE '%inf;%' AND lemme = '".addslashes($value['lemme'])."' COLLATE utf8_bin AND cgram = '".$value['cgram']."' AND ortho = '".$value['ortho']."' LIMIT 1") or die (mysqli_error($connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
						$response[strtolower($row['cgram'])][] = $row['ortho'];
						
						/* Store in response temp variable */
						$cgram = str_replace(':', '_', strtolower($row['cgram']));
						if(!isset($verb_array_count[$cgram])){ $verb_array_count[$cgram] = 0;  } else { $verb_array_count[$cgram]++; }
						$response_temp[$cgram][$verb_array_count[$cgram]]['ortho'] = $row['ortho'];
						$response_temp[$cgram][$verb_array_count[$cgram]]['genre'] = !empty($row['genre']) ? $row['genre'] : 'm';
						$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = !empty($row['nombre']) ? $row['nombre'] : 's';
						////////////////////////////////
					}
				} elseif (
					(strpos($tenses, 'par:pas;') !== false) &&
					(strpos($tenses, '3s') === false) &&
					(strpos($tenses, '3p') === false) &&
					(strpos($tenses, '1s') === false) &&
					(strpos($tenses, '2s') === false)
				){
					$lexique_query = mysqli_query($connexion, "SELECT ortho,genre,nombre,cgram FROM lexique WHERE infover LIKE '%par:pas;%' AND lemme = '".addslashes($value['lemme'])."' COLLATE utf8_bin AND cgram = '".$value['cgram']."' AND ortho = '".$value['ortho']."' LIMIT 1") or die (mysqli_error($connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
						$response[strtolower($row['cgram'])][] = $row['ortho'];
						
						/* Store in response temp variable */
						$cgram = str_replace(':', '_', strtolower($row['cgram']));
						if(!isset($verb_array_count[$cgram])){ $verb_array_count[$cgram] = 0;  } else { $verb_array_count[$cgram]++; }
						$response_temp[$cgram][$verb_array_count[$cgram]]['ortho'] = $row['ortho'];
						$response_temp[$cgram][$verb_array_count[$cgram]]['genre'] = !empty($row['genre']) ? $row['genre'] : 'm';
						$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = !empty($row['nombre']) ? $row['nombre'] : 's';
						////////////////////////////////
						
						write_session('part',true);
					}
				} else {
					$lexique_query = mysqli_query($connexion, "SELECT ortho,genre,nombre,infover,cgram FROM lexique WHERE infover LIKE '%".$rightTense[0].":".$rightTense[1].":".$person."%' AND lemme = '".addslashes($value['lemme'])."' COLLATE utf8_bin AND cgram = '".$value['cgram']."' LIMIT 1") or die (mysqli_error($connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
						$response[strtolower($row['cgram'])][] = $row['ortho'];
						
						/* Store in response temp variable */
						$cgram = str_replace(':', '_', strtolower($row['cgram']));
						if(!isset($verb_array_count[$cgram])){ $verb_array_count[$cgram] = 0;  } else { $verb_array_count[$cgram]++; }
						$response_temp[$cgram][$verb_array_count[$cgram]]['ortho'] = $row['ortho'];
						$response_temp[$cgram][$verb_array_count[$cgram]]['genre'] = !empty($row['genre']) ? $row['genre'] : 'm';
						$array_tenses = explode(';', $row['infover']);
						if(empty($row['nombre'])){
							foreach($array_tenses as $values) {
								if(strpos($values, 'ind:pre') !== false){
									$present = explode(':', $values);
									if(strpos($present[2], 's') !== false){
										$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = 's';
										break;
									}
									if(strpos($present[2], 'p') !== false){
										$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = 'p';
										break;
									}
									$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = 's';
									break;
								}
							}
						} else {
							$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = $row['nombre'];
						}
						////////////////////////////////
						
						if(
							substr($row['ortho'], 0, 1) == 'a' ||
							substr($row['ortho'], 0, 1) == 'e' ||
							substr($row['ortho'], 0, 1) == 'i' ||
							substr($row['ortho'], 0, 1) == 'o' ||
							substr($row['ortho'], 0, 1) == 'u' 
						) {
							if($person == '1s'){
								$pro_per = 'j';
							}
						}
						/*if(!in_array($after_verb, $response['pro_per'])) {*/
							$response['pro_per'][$key] = $pro_per;
						/*} else {
							if(isset($response['pro_per']) && !empty($response['pro_per'])){
								if(isset($response['pro_per'][$key + $target_before]) && $response['pro_per'][$key + $target_before] == $pro){
									$response['pro_per'][$key + $target_before] = $pro_per;
								}
								
								if(isset($response['pro_per'][$key + $target_before + 1]) && $middle_current_verb == $response['pro_per'][$key + $target_before + 1]) {
									$response['pro_per'][$key + $target_before + 1] = $middle_verb;
								}
								
								if(!empty($middle_current_verb)){
									$target_before++;
								}
							}
						}*/
						$response['pro_per'] = array_values($response['pro_per']);
					} else {
						$response[strtolower($value['cgram'])][] = $value['ortho'];
						
						/* Store in response temp variable */
						$cgram = str_replace(':', '_', strtolower($value['cgram']));
						if(!isset($verb_array_count[$cgram])){ $verb_array_count[$cgram] = 0;  } else { $verb_array_count[$cgram]++; }
						$response_temp[$cgram][$verb_array_count[$cgram]]['ortho'] = $value['ortho'];
						$response_temp[$cgram][$verb_array_count[$cgram]]['genre'] = !empty($value['genre']) ? $value['genre'] : 'm';
						$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = !empty($value['nombre']) ? $value['nombre'] : 's';
						////////////////////////////////
					}
				}
			}
		}
		return array(
			'response' => $response,
			'response_temp' => $response_temp
		);
	}