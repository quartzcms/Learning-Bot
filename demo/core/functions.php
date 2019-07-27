<?php
	function renderVerbs($reason, $path_array, $connexion){
		$pronouns = array();
		$insert_before = array();
		foreach($path_array as $key => $value){
			if(isset($value['cgram']) && ($value['cgram'] == 'AUX' || $value['cgram'] == 'VER:inf' || $value['cgram'] == 'VER:past' || $value['cgram'] == 'VER')) {
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
												$new_question[$i - 1]['ortho'] == 'ainsi' || 
												$new_question[$i - 1]['ortho'] == 'comme')
											) || 
											(
												isset($new_question[$i - 2]['ortho']) && 
												($new_question[$i - 2]['ortho'] == 'et' ||  
												$new_question[$i - 2]['ortho'] == 'ainsi' || 
												$new_question[$i - 2]['ortho'] == 'comme')
											) || 
											(
												isset($new_question[$i - 3]['ortho']) && 
												($new_question[$i - 3]['ortho'] == 'et' ||  
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
								$pronoun_verb == 't' ||
								$pronoun_verb == 'on'
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
									$pronoun_verb == 'cela' ||
									$pronoun_verb == 'on'
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
				$detect = 0;
				foreach($path_array as $key4 => $value4){
					if(isset($value4['cgram']) && isset($value4['ortho']) && ($value4['cgram'] == 'PRO:per' || $value4['cgram'] == 'PRO:dem') && $after_verb == $value4['ortho']){
						$detect = 1;
						break;
					}
				}
				
				if($detect == 0){
					foreach($path_array as $key4 => $value4){	
						if($value4['ortho'] == $pre_verb && isset($value4['genre'])) {
							if($pre_verb_artDef_genre == 'm' || $value4['genre'] == 'm'){
								$pro = 'il';
							} 
							if($pre_verb_artDef_genre == 'f' || $value4['genre'] == 'f'){
								$pro = 'elle';
							}
							if($pre_verb_artDef_nombre == 's' || $value4['nombre'] == 's'){
								$pro .= '';
							}
							if($plural == 1 || $pre_verb_artDef_nombre == 'p' || $value4['nombre'] == 'p'){
								$pro .= 's';
							}
							break;
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
					
					foreach($path_array as $key4 => $value4){	
						if(isset($value4['cgram']) && $value4['cgram'] == "PRO:per" && $value4['ortho'] == $pro) {
							if($value4['genre'] == 'f'){
								$pro_per = 'elle';
								$pro_genre = 'f';
							} else {
								$pro_per = 'il';
							}
						
							if($value4['nombre'] == 'p'){
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
					if(!empty($after_verb)){
						if($new_question[$id_pronouns]['cgram'] == 'PRO:dem') {
							$pro_per = '';
						}
					}
				}

				if(strpos($tenses, 'inf;') !== false){
					$lexique_query = mysqli_query($connexion, "SELECT ortho,genre,nombre,cgram FROM lexique WHERE infover LIKE '%inf;%' AND lemme = '".addslashes($value['lemme'])."' COLLATE utf8_bin AND cgram = '".$value['cgram']."' AND ortho = '".$value['ortho']."' LIMIT 1") or die (mysqli_error($connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
					}
				} elseif (strpos($tenses, 'par:pas;') !== false){
					$lexique_query = mysqli_query($connexion, "SELECT ortho,genre,nombre,cgram FROM lexique WHERE infover LIKE '%par:pas;%' AND lemme = '".addslashes($value['lemme'])."' COLLATE utf8_bin AND cgram = '".$value['cgram']."' AND ortho = '".$value['ortho']."' LIMIT 1") or die (mysqli_error($connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
					}
				} else {
					$lexique_query = mysqli_query($connexion, "SELECT ortho,genre,nombre,infover,cgram FROM lexique WHERE infover LIKE '%".$rightTense[0].":".$rightTense[1].":".$person."%' AND lemme = '".addslashes($value['lemme'])."' COLLATE utf8_bin AND cgram = '".$value['cgram']."' LIMIT 1") or die (mysqli_error($connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
						$path_array[$key]['new'] = $row['ortho'];
						
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
								$path_array[$id_pronouns]['new'] = $pro_per;
								$path_array[$id_pronouns]['existing'] = 1;
							} else {
								$path_array[$key]['insert_before'] = 1;
								$inserted = array();
								$inserted['ortho'] = $pro_per;
								$inserted['cgram'] = 'PRO:per';
								$inserted['nombre'] = $pro_nombre;
								$inserted['genre'] = $pro_genre;
								$inserted['added'] = '1';
								$insert_before[] = array($inserted);
							}
						}
					}
				}
			}
		}
		
		$path_array2 = $path_array;
		$i = 0;
		foreach($path_array as $key => $value){
			if(isset($path_array[$key + 1]) && isset($path_array[$key + 1]['insert_before'])){
				array_splice($path_array2, $key, 0, $insert_before[$i]);
				$i++;
			}
		}
		
		foreach($path_array2 as $key => $value){
			if(!isset($path_array2[$key]['added']) && !isset($path_array2[$key]['existing']) && isset($path_array2[$key]['cgram']) && $path_array2[$key]['cgram'] == 'PRO:per'){
				$pro_per2 = $path_array2[$key]['ortho'];
				$pro3 = $path_array2[$key]['ortho'];
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
				} elseif ($pro3 == 'me'){
					$pro_per2 = str_replace('me', 'te', $pro_per2);
				} elseif ($pro3 == 'te'){
					$pro_per2 = str_replace('te', 'me', $pro_per2);
				} elseif ($pro3 == 'm'){
					$pro_per2 = str_replace('m', 't', $pro_per2);
				} elseif ($pro3 == 't'){
					$pro_per2 = str_replace('t', 'm', $pro_per2);
				}
				$path_array2[$key]['new'] = $pro_per2;
			}
		}
		return array(
			'path_array' => $path_array2
		);
	}