<?php

class core {
	protected $build_memory;
	protected $connexion;
    protected $action;
	protected $rand;
    protected $response;
    protected $data_to_insert;
    protected $data_to_verify;
    protected $type_bot;
    protected $pattern_learn;
    protected $pattern_sentence;
    protected $question_array;
	
	public function __construct($variables) {
		$this->build_memory = $variables['build_memory'];
		$this->connexion = $variables['connexion'];
		$this->action = $variables['action'];
		$this->rand = $variables['rand'];
		$this->type_bot = $variables['type_bot'];
		$this->question_array = $variables['question_array'];
    }
	
	public function createPatterns($response) {
		$this->response = $response;
		
		/* Create pattern to store */
		
		$this->pattern_learn = mb_strtolower((implode(' ', $this->question_array)), 'UTF-8');
		$this->pattern_learn = str_replace('\'', ' ', $this->pattern_learn);
		$this->pattern_learn = str_replace(' ', '} {', '{'.$this->pattern_learn.'}');
		foreach($this->build_memory as $key => $words){
			foreach($words as $word_key => $word_value){
				$tag = '('.str_replace(':', '_', mb_strtolower($key, 'UTF-8')).')';
				$label = str_replace(':', '_', mb_strtolower($key, 'UTF-8'));
				if(isset($word_value['ortho'])){
					if(in_array($word_value['ortho'], $this->question_array) && isset($this->response[$label]) && !empty($this->response[$label])){
						$this->pattern_learn = str_replace('{'.$word_value['ortho'].'}', $tag, $this->pattern_learn);
					}
				}
			}
		}
		
		$this->pattern_learn = str_replace('} {', ' ', $this->pattern_learn);
		$this->pattern_learn = str_replace('{', '', $this->pattern_learn);
		$this->pattern_learn = str_replace('}', '', $this->pattern_learn);
		$this->pattern_learn = str_replace('(', '{', $this->pattern_learn);
		$this->pattern_learn = str_replace(')', '}', $this->pattern_learn);
		
		/* Create sentence to store from pattern with new response array */
		
		$pattern = str_replace(' {', '{', $this->pattern_learn);
		$pattern = str_replace('} ', '}', $pattern);
		$pattern = preg_split('/[\{,\}]/', $pattern);
		$pattern_chosen = implode(',', array_filter($pattern, function($value) { return $value !== ''; }));
		
		$unique = array();
		foreach($pattern as $key => $value){
			if(isset($response[$value]) && !empty($response[$value])){
				if(!isset($unique[$value])){
					$unique[$value] = 0;
				}
				if(isset($response[$value][$unique[$value]])){
					$pattern[$key] = $response[$value][$unique[$value]];
					$unique[$value]++;
				}
			}
		}
		$pattern = array_filter($pattern, function($value) { return $value !== ''; });
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
		
		$pattern = str_replace('\' ','\'',  html_entity_decode(implode(' ', $pattern), ENT_QUOTES | ENT_XML1, 'UTF-8'));
		$this->pattern_sentence = $pattern;
	}
	
	public function prepareDataInsert($response) {
		$this->response = $response;
		$this->data_to_insert = array();
		$this->data_to_verify = array();
		
		foreach($this->build_memory as $key => $value){
			if(is_array($this->build_memory[$key])){
				$index = str_replace(':', '_', mb_strtolower($key, 'UTF-8'));
				if(!empty($index)){
					if(!empty($this->response[$index]) && isset($this->response[$index])){
						$this->data_to_verify[$index] = $this->response[$index];
						$add = array(); 
						foreach($this->response[$index] as $word_key => $word_value){
							$add[] = html_entity_decode($word_value, ENT_QUOTES | ENT_XML1, 'UTF-8');
						}
						$this->data_to_insert[$index] = addslashes(json_encode($add, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE));
					}
				}
			}
		}
	}
	
	public function dataInsert($response, $memory_insert, $append_data) {
		$query = array();
		$this->response = $response;
		foreach($this->data_to_verify as $key => $value){
			if(!empty($this->data_to_verify[$key]) && isset($this->data_to_verify[$key])){
				$build_conditions = array();
				foreach($value as $word_key => $word_value){
					$build_conditions[] = $key.' LIKE \'%'.addslashes($word_value).'%\'';
				}
				if(!empty($build_conditions)){
					$query[] = '('.implode(' COLLATE utf8_bin AND ', $build_conditions).' COLLATE utf8_bin)';
				}
			}
		}
		if(!empty($query)){
			//$query = 'WHERE '.implode(' AND ', $query).' AND ip = \''.$_SERVER['REMOTE_ADDR'].'\'';
			$query = 'WHERE '.implode(' AND ', $query);
		} else {
			//$query = 'WHERE ip = \''.$_SERVER['REMOTE_ADDR'].'\'';
			$query = '';
		}
				
		$verify_query = mysqli_query($this->connexion, "SELECT * FROM ai_memory_".$this->type_bot." ".$query." LIMIT 1") or die (mysqli_error($this->connexion));
		
		if(
			!empty($this->data_to_insert) && 
			(mysqli_num_rows($verify_query) == 0) && 
			!empty($this->pattern_learn)
		){
			$memory_insert = 1;
			$insert = mysqli_query($this->connexion, "INSERT INTO ai_memory_".$this->type_bot." (".mb_strtolower(implode(',', array_keys($this->data_to_insert)), 'UTF-8').", human, pattern, question, keywords, wikipedia, ip) VALUES ('".mb_strtolower(implode('\',\'', array_values($this->data_to_insert)), 'UTF-8')."', '".addslashes($this->pattern_sentence)."', '".addslashes($this->pattern_learn)."', '".addslashes(use_session('last_question_sentence_'.$this->type_bot))."', '". json_encode(use_session('links_'.$this->type_bot), JSON_UNESCAPED_UNICODE)."', '".addslashes($append_data)."', '255.255.255.255')") or die (mysqli_error($this->connexion));
			write_session('last_question_sentence_'.$this->type_bot, '');
		}
		
		return array(
			'memory_insert' => $memory_insert,
			'data_to_insert' => $this->data_to_insert,
			'data_to_verify' => $this->data_to_verify,
			'pattern_learn' => $this->pattern_learn
		);
	}
}