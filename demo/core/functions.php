<?php
	function renderVerbs($reason, $verbs, $response, $response_temp, $path_array, $build_memory, $connexion){
		$last2 = array();
		if(!empty($verbs)){
			$target_before = 0;
			$verb_array_count = array();
			$pronouns = array();
			foreach($verbs as $key => $value){
				$tenses = $value['infover'];
				$after_verb = '';
				$pre_verb = '';
				$middle_verb = '';
				$middle_current_verb = '';	
				$pre_verb_artDef_genre = '';
				$pre_verb_artDef_nombre = '';
				$plural = 0;
				$new_question = $path_array;
				$id_pronouns = 0;
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
							if(isset($new_question[$i]['cgram']) && 
							($new_question[$i]['cgram'] == 'VER' || 
							$new_question[$i]['cgram'] == 'VER:past' || 
							$new_question[$i]['cgram'] == 'AUX')){
								break;
							}
							
							if(isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'NOM'){
								if(
									isset($new_question[$i - 1]['cgram']) &&
									($new_question[$i - 1]['cgram'] == 'ART:def' || 
									$new_question[$i - 1]['cgram'] == 'ART:ind' || 
									$new_question[$i - 1]['cgram'] == 'ADJ:pos') &&
									($new_question[$i - 1]['ortho'] != 'aux' && $new_question[$i - 1]['ortho'] != 'au')
								) {
									if(isset($new_question[$i - 2]['cgram']) &&	
									$new_question[$i - 2]['cgram'] == 'ART:def' &&
									 $new_question[$i - 1]['cgram'] != 'ART:ind'){
										continue;
									}
									
									$pre_verb = $new_question[$i]['ortho'];
									break;
								}
							}
						}
						
						if(!empty($pre_verb)) {
							$collect_pronouns = array();
							for($i = ((($index - 1) > -1) ? ($index - 1) : 0); $i > -1; $i--){
								if($new_question[$i]['ortho'] == $pre_verb){
									if(isset($new_question[$i]['cgram']) &&
									 ($new_question[$i]['cgram'] == 'VER' || 
									 $new_question[$i]['cgram'] == 'VER:past' || 
									 $new_question[$i]['cgram'] == 'AUX')){
										break;
									}
																
									if(isset($new_question[$i]['cgram']) && 
									($new_question[$i]['cgram'] == 'ART:def' || 
									$new_question[$i]['cgram'] == 'ART:ind' || 
									$new_question[$i]['cgram'] == 'ADJ:pos')){
										if(
											(
												isset($new_question[$i - 1]['ortho']) &&
												($new_question[$i - 1]['ortho'] == 'et' || 
												//$new_question[$i - 1]['ortho'] == 'ou' || 
												$new_question[$i - 1]['ortho'] == 'ainsi' || 
												$new_question[$i - 1]['ortho'] == 'comme')
											) || 
											(
												isset($new_question[$i - 2]['ortho']) && 
												($new_question[$i - 2]['ortho'] == 'et' ||  
												//$new_question[$i - 2]['ortho'] == 'ou' ||  
												$new_question[$i - 2]['ortho'] == 'ainsi' || 
												$new_question[$i - 2]['ortho'] == 'comme')
											) || 
											(
												isset($new_question[$i - 3]['ortho']) && 
												($new_question[$i - 3]['ortho'] == 'et' ||  
												//$new_question[$i - 2]['ortho'] == 'ou' ||  
												$new_question[$i - 3]['ortho'] == 'ainsi' || 
												$new_question[$i - 3]['ortho'] == 'comme')
											) 
										) {
											for($j = ((($i - 1) > -1) ? ($i - 1) : 0); $j > -1; $j--){
												if(isset($new_question[$j - 1]['cgram']) &&
												($new_question[$j - 1]['cgram'] == 'PRO:per')){
													$pre_verb_artDef_genre = $new_question[$i]['genre'];
													$pre_verb_artDef_nombre = $new_question[$i]['nombre'];
													break 2;
												}
											}
											
											/* IF CANT FIND ANY PRONOUNS */
											$collect_pronouns[] = $new_question[$i];
											for($j = ((($i - 1) > -1) ? ($i - 1) : 0); $j > -1; $j--){
												if(isset($new_question[$j]['cgram']) && 
												($new_question[$j]['cgram'] == 'ART:def' ||
												 $new_question[$j]['cgram'] == 'ADJ:pos')){
													$collect_pronouns[] = $new_question[$j];
												}
											}
										} else {
											$pre_verb_artDef_genre = $new_question[$i]['genre'];
											$pre_verb_artDef_nombre = $new_question[$i]['nombre'];
											break;
										}
									}
								}
							}
							
							if(!empty($collect_pronouns)){
								$f = 0;
								$m = 0;
								foreach($collect_pronouns as $key8 => $value8){
									if($value8['genre'] == 'f'){
										$f++;
									} else {
										$m++;
									}
								}
								$pre_verb_artDef_genre = 'm';
								
								if(($m != 0) && ($f != 0)){
									$pre_verb_artDef_genre = 'm';
								}
								if(($m == 0) && ($f != 0)){
									$pre_verb_artDef_genre = 'f';
								}
								
								$pre_verb_artDef_nombre = 'p';
							}
						}
						
						$j = 0;
						for($i = ((($index - 1) > -1) ? ($index - 1) : 0); $i > -1; $i--){
							if(isset($new_question[$i]['cgram']) && 
							($new_question[$i]['cgram'] == 'VER' || 
							$new_question[$i]['cgram'] == 'VER:past' || 
							$new_question[$i]['cgram'] == 'AUX')){
								break;
							}
							
							if($j == 0 && isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'NOM'){
								$j = 1;
							}
							
							if($j == 1 && isset($new_question[$i]['cgram']) && 
								(
									($new_question[$i]['ortho'] == 'et' || 
									//$new_question[$i]['ortho'] == 'ou' || 
									$new_question[$i]['ortho'] == 'ainsi' || 
									$new_question[$i]['ortho'] == 'comme')
								)
							){
								$j = 2;
							}
							
							if($j == 2 && isset($new_question[$i]['cgram']) && $new_question[$i]['cgram'] == 'NOM'){
								$j = 3;
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
							if(isset($new_question[$i]['cgram']) && 
							($new_question[$i]['cgram'] == 'VER' || 
							$new_question[$i]['cgram'] == 'VER:past' || 
							$new_question[$i]['cgram'] == 'AUX')){
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
								$pronoun_verb == 'soi' || 
								$pronoun_verb == 'soi-même' || 
								$pronoun_verb == 'toi-même' || 
								$pronoun_verb == 'moi-même' || 
								$pronoun_verb == 'nous-même' || 
								$pronoun_verb == 'vous-même' || 
								$pronoun_verb == 'celle' || 
								$pronoun_verb == 'ceux' || 
								$pronoun_verb == 'celles' || 
								$pronoun_verb == 'celui' || 
								$pronoun_verb == 'ceci' || 
								$pronoun_verb == 'cela' || 
								$pronoun_verb == 'c' || 
								$pronoun_verb == 'm' || 
								$pronoun_verb == 's' || 
								$pronoun_verb == 't'
								){
									$id_pronouns = $i;
									$after_verb = $new_question[$i]['ortho'];
									break;
								}
							}
						}
					
						if(empty($after_verb)){
							$j = 0;
							for($i = ((($index + 1) < count($new_question)) ? ($index + 1) : 0); $i < count($new_question); $i++){
								if(isset($new_question[$i]['cgram']) && ($new_question[$i]['cgram'] == 'VER' || $new_question[$i]['cgram'] == 'VER:past' || $new_question[$i]['cgram'] == 'AUX' || $new_question[$i]['cgram'] == 'VER:inf')){
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
									$pronoun_verb == 'soi' ||  
									$pronoun_verb == 'soi-même' || 
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
										$id_pronouns = $i;
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
				
				$pro_genre = 'm';
				$rightTense = array('ind', 'pre');
				if ($pro == 'il' || $pro == 'elle' || 
					$pro == 'celle' || $pro == 'cela' || 
					$pro == 'ceci' || $pro == 'celui'){
					$person = '3s';
					$pro_nombre = 's';
					$pro_per = $pro;
					
					if($pro == 'elle' || $pro == 'celle'){
						$pro_genre = 'f';
					}
				} elseif ($pro == 'ils' || $pro == 'elles' || 
						  $pro == 'celles' || $pro == 'ceux'){
					$person = '3p';
					$pro_nombre = 'p';
					$pro_per = $pro;
					
					if($pro == 'elles' || $pro == 'celles'){
						$pro_genre = 'f';
					}
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
				} elseif ($pro == 'toi' || $pro == 'soi'){
					$person = '1s';
					$pro_nombre = 's';
					$pro_per = 'moi';
				} elseif ($pro == 'toi-même' || $pro == 'soi-même'){
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
					
					if(isset($build_memory['PRO:per']) && !empty($build_memory['PRO:per'])){
						foreach($build_memory['PRO:per'] as $key2 => $value2){
							if($build_memory['PRO:per'][$key2]['ortho'] == $pro){
								if($build_memory['PRO:per'][$key2]['genre'] == 'f'){
									$pro_per = 'elle';
									$pro_genre = 'f';
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
					
					if(!empty($after_verb)){
						if($new_question[$id_pronouns]['cgram'] == 'PRO:dem') {
							$pro_per = '';
						}
					}
				}
				
				/*if ($pro != 'il' && $pro != 'elle' && 
					$pro != 'ils' && $pro != 'elles'){
					if(isset($response['pro_per_con'][$key])){
						unset($response['pro_per_con'][$key]);
					}
				}*/
				if(!empty($pro_per)){
					/*Store in response temp variable*/
					$cgram = str_replace(':', '_', mb_strtolower($value['cgram'], 'UTF-8'));				
					$response_temp['pro_per'][$key]['ortho'] = $pro_per;
					$response_temp['pro_per'][$key]['nombre'] = $pro_nombre;
					$response_temp['pro_per'][$key]['genre'] = $pro_genre;
					/////////////////////////////////////
				}
				if(strpos($tenses, 'inf;') !== false){
					$lexique_query = mysqli_query($connexion, "SELECT ortho,genre,nombre,cgram FROM lexique WHERE infover LIKE '%inf;%' AND lemme = '".addslashes($value['lemme'])."' COLLATE utf8_bin AND cgram = '".$value['cgram']."' AND ortho = '".$value['ortho']."' LIMIT 1") or die (mysqli_error($connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
						$cgram = str_replace(':', '_', mb_strtolower($row['cgram'], 'UTF-8'));
						$response[$cgram][] = $row['ortho'];
						
						/* Store in response temp variable */
						if(!isset($verb_array_count[$cgram])){ $verb_array_count[$cgram] = 0;  } else { $verb_array_count[$cgram]++; }
						$response_temp[$cgram][$verb_array_count[$cgram]]['ortho'] = $row['ortho'];
						$response_temp[$cgram][$verb_array_count[$cgram]]['genre'] = !empty($row['genre']) ? $row['genre'] : 'm';
						$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = !empty($row['nombre']) ? $row['nombre'] : 's';
						////////////////////////////////
					}
				} elseif (strpos($tenses, 'par:pas;') !== false){
					$lexique_query = mysqli_query($connexion, "SELECT ortho,genre,nombre,cgram FROM lexique WHERE infover LIKE '%par:pas;%' AND lemme = '".addslashes($value['lemme'])."' COLLATE utf8_bin AND cgram = '".$value['cgram']."' AND ortho = '".$value['ortho']."' LIMIT 1") or die (mysqli_error($connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
						$cgram = str_replace(':', '_', mb_strtolower($row['cgram'], 'UTF-8'));
						$response[$cgram][] = $row['ortho'];
						
						/* Store in response temp variable */
						if(!isset($verb_array_count[$cgram])){ $verb_array_count[$cgram] = 0;  } else { $verb_array_count[$cgram]++; }
						$response_temp[$cgram][$verb_array_count[$cgram]]['ortho'] = $row['ortho'];
						$response_temp[$cgram][$verb_array_count[$cgram]]['genre'] = !empty($row['genre']) ? $row['genre'] : 'm';
						$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = !empty($row['nombre']) ? $row['nombre'] : 's';
						////////////////////////////////
					}
				} else {
					$lexique_query = mysqli_query($connexion, "SELECT ortho,genre,nombre,infover,cgram FROM lexique WHERE infover LIKE '%".$rightTense[0].":".$rightTense[1].":".$person."%' AND lemme = '".addslashes($value['lemme'])."' COLLATE utf8_bin AND cgram = '".$value['cgram']."' LIMIT 1") or die (mysqli_error($connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
						$response[mb_strtolower($row['cgram'], 'UTF-8')][] = $row['ortho'];
						/* Store in response temp variable */
						$cgram = str_replace(':', '_', mb_strtolower($row['cgram'], 'UTF-8'));
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
						if(!empty($pro_per)){
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
						}
						if(!empty($pro_per)){
							if(!empty($after_verb)){
								$pronouns[$id_pronouns][$after_verb] = $pro_per;
							} else {
								if(isset($value['ortho'])){
									$pronouns[$value['id']][$value['ortho']] = $pro_per;
								}
							}
						}
						
						$response['pro_per'] = array_values($response['pro_per']);
					} else {
						$response[mb_strtolower($value['cgram'], 'UTF-8')][] = $value['ortho'];
						
						/* Store in response temp variable */
						$cgram = str_replace(':', '_', mb_strtolower($value['cgram'], 'UTF-8'));
						if(!isset($verb_array_count[$cgram])){ $verb_array_count[$cgram] = 0;  } else { $verb_array_count[$cgram]++; }
						$response_temp[$cgram][$verb_array_count[$cgram]]['ortho'] = $value['ortho'];
						$response_temp[$cgram][$verb_array_count[$cgram]]['genre'] = !empty($value['genre']) ? $value['genre'] : 'm';
						$response_temp[$cgram][$verb_array_count[$cgram]]['nombre'] = !empty($value['nombre']) ? $value['nombre'] : 's';
						////////////////////////////////
					}
				}
			}
			
			$last = array();
			$last_dem = array();
			$pro_per3 = $response['pro_per'];
			$pro_dem3 = $response['pro_dem'];
			
			foreach($new_question as $index3 => $word3){
				if(isset($pronouns[$index3]) && isset($pronouns[$index3][$new_question[$index3]['ortho']])){
					if($new_question[$index3]['cgram'] != 'VER' && $new_question[$index3]['cgram'] != 'AUX'){
						$last[] = $pronouns[$index3][$new_question[$index3]['ortho']];
					}
					$last2[] = $pronouns[$index3][$new_question[$index3]['ortho']];
					//unset($pronouns[$index3]);
				} elseif(isset($new_question[$index3]['cgram']) && 
				($new_question[$index3]['cgram'] == 'PRO:per' || $new_question[$index3]['cgram'] == 'PRO:dem') && 
				(in_array($new_question[$index3]['ortho'], $pro_per3) || in_array($new_question[$index3]['ortho'], $pro_dem3))){
					$pro3 = $new_question[$index3]['ortho'];
					$pro_per2 = $pro3;
					if ($pro3 == 'j' || $pro3 == 'je'){
						$pro_per2 = str_replace('je', 'tu', $pro_per2);
						$pro_per2 = str_replace('j', 'tu', $pro_per2);
					} elseif ($pro3 == 'tu'){
						$pro_per2 = str_replace('tu', 'je', $pro_per2);
					} elseif ($pro3 == 'nous'){
						$pro_per2 = str_replace('nous', 'vous', $pro_per2);
					} elseif ($pro3 == 'vous'){
						$pro_per2 = str_replace('vous', 'nous', $pro_per2);
					} elseif ($pro3 == 'moi'){
						$pro_per2 = str_replace('moi', 'toi', $pro_per2);
					} elseif ($pro3 == 'toi' || $pro3 == 'soi'){
						$pro_per2 = str_replace('toi', 'moi', $pro_per2);
						$pro_per2 = str_replace('soi', 'moi', $pro_per2);
					} elseif ($pro3 == 'toi-même' || $pro3 == 'soi-même'){
						$pro_per2 = str_replace('toi-même', 'moi-même', $pro_per2);
						$pro_per2 = str_replace('soi-même', 'moi-même', $pro_per2);
					} elseif ($pro3 == 'moi-même'){
						$pro_per2 = str_replace('moi-même', 'toi-même', $pro_per2);
					} elseif ($pro3 == 'nous-même'){
						$pro_per2 = str_replace('nous-même', 'vous-même', $pro_per2);
					} elseif ($pro3 == 'vous-même'){
						$pro_per2 = str_replace('vous-même', 'nous-même', $pro_per2);
					}

					if(in_array($new_question[$index3]['ortho'], $pro_per3)){
						$key8 = array_search($new_question[$index3]['ortho'], $pro_per3);
						unset($pro_per3[$key8]);
						$last[] = $pro_per2;
						$last2[] = $pro_per2;
					} elseif(in_array($new_question[$index3]['ortho'], $pro_dem3)){
						$key8 = array_search($new_question[$index3]['ortho'], $pro_dem3);
						unset($pro_dem3[$key8]);
						$last_dem[] = $pro_per2;
					}
				}
			}
			
			$response['pro_per'] = $last;
			$response['pro_dem'] = $last_dem;
		}
		return array(
			'response' => $response,
			'response_temp' => $response_temp,
			'last2' => $last2
		);
	}