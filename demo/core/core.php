<?php

class core {
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
		$this->connexion = $variables['connexion'];
		$this->action = $variables['action'];
		$this->rand = $variables['rand'];
		$this->type_bot = $variables['type_bot'];
		$this->question_array = $variables['question_array'];
    }
	
	public function createPatterns() {
		/* Create pattern to store */
		$this->pattern_learn = array();
		foreach($this->question_array as $key => $words){
			if(!isset($this->question_array[$key]['added'])){
				$label = str_replace(':', '_', mb_strtolower($words['cgram'], 'UTF-8'));
				$this->pattern_learn[] = "{".$label."}";
			}
		}
		$this->pattern_learn = implode(' ', $this->pattern_learn);
		
		/* Create sentence to store from pattern with new response array */
		$unique = array();
		foreach($this->question_array as $key => $value){
			if(!isset($this->question_array[$key]['added'])){
				if(isset($this->question_array[$key]['new'])){
					$unique[] = $this->question_array[$key]['new'];
				} else {
					$unique[] = $this->question_array[$key]['ortho'];
				}
			}
		}
		foreach($unique as $key => $value) {
			if(
				$value == 'j' ||
				$value == 'l' ||
				$value == 't' ||
				$value == 's' ||
				$value == 'm' ||
				$value == 'c' ||
				$value == 'd' ||
				$value == 'n' ||
				$value == 'qu' ||
				$value == 'puisqu' ||
				$value == 'lorsqu' ||
				$value == 'quelqu' ||
				$value == 'jusqu'
			) {
				$unique[$key] = $value.'\'';
			}
		}
		
		$pattern = str_replace('\' ','\'',  html_entity_decode(implode(' ', $unique), ENT_QUOTES | ENT_XML1, 'UTF-8'));
		$this->pattern_sentence = $pattern;
	}
	
	public function prepareDataInsert() {
		$this->data_to_insert = array();
		$this->data_to_verify = array();
		
		foreach($this->question_array as $key => $value){
			$index = str_replace(':', '_', mb_strtolower($value['cgram'], 'UTF-8'));
			$this->data_to_verify[$index][] = html_entity_decode($value['ortho'], ENT_QUOTES | ENT_XML1, 'UTF-8');
			$this->data_to_insert[$index][] = html_entity_decode($value['ortho'], ENT_QUOTES | ENT_XML1, 'UTF-8');
		}
		foreach($this->data_to_insert as $key => $value){
			$this->data_to_insert[$key] = addslashes(json_encode($value, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE));
		}
	}
	
	public function dataInsert($memory_insert, $append_data, $ip_user) {
		$query = array();
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
			$query = 'WHERE '.implode(' AND ', $query). ' AND ip = "'.$ip_user.'"';
		} else {
			$query = 'WHERE ip = "'.$ip_user.'"';
		}
				
		$verify_query = mysqli_query($this->connexion, "SELECT * FROM ai_memory_".$this->type_bot." ".$query." LIMIT 1") or die (mysqli_error($this->connexion));
		
		if(
			!empty($this->data_to_insert) && 
			(mysqli_num_rows($verify_query) == 0) && 
			!empty($this->pattern_learn)
		){
			$memory_insert = 1;
			$insert = mysqli_query($this->connexion, "INSERT INTO ai_memory_".$this->type_bot." (".mb_strtolower(implode(',', array_keys($this->data_to_insert)), 'UTF-8').", human, pattern, question, keywords, wikipedia, ip) VALUES ('".mb_strtolower(implode('\',\'', array_values($this->data_to_insert)), 'UTF-8')."', '".addslashes($this->pattern_sentence)."', '".addslashes($this->pattern_learn)."', '".addslashes(use_session('last_question_sentence_'.$this->type_bot))."', '". json_encode(use_session('links_'.$this->type_bot), JSON_UNESCAPED_UNICODE)."', '".addslashes($append_data)."', '".$ip_user."')") or die (mysqli_error($this->connexion));
			write_session('last_question_sentence_'.$this->type_bot, '');
			$used_id = use_session('used_id_'.$this->type_bot);
			$used_id[] = mysqli_insert_id($this->connexion);
			write_session('used_id_'.$this->type_bot, $used_id);
			write_session('last_inserted_id_'.$this->type_bot, mysqli_insert_id($this->connexion));
		}
		
		return array(
			'memory_insert' => $memory_insert,
			'data_to_insert' => $this->data_to_insert,
			'data_to_verify' => $this->data_to_verify,
			'pattern_learn' => $this->pattern_learn
		);
	}
}