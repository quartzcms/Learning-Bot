<?php

class ver_past {
	protected $build_memory;
	protected $connexion;
    protected $action;
    protected $rand;
    protected $response;
	protected $response_temp;
    protected $question_array;
    protected $verbs;
	
	public function __construct($variables) {
		$this->build_memory = $variables['build_memory'];
		$this->connexion = $variables['connexion'];
		$this->action = $variables['action'];
		$this->rand = $variables['rand'];
    }
	
	public function loop($response, $question_array, $verbs, $response_temp) {
		$this->response = $response;
		$this->response_temp = $response_temp;
		$this->question_array = $question_array;
		$this->verbs = $verbs;
		
		if(isset($this->build_memory['VER:past'])){
			foreach($this->build_memory['VER:past'] as $word_key => $word_value){
				$this->verbs[] = $this->build_memory['VER:past'][$word_key];
			}
		}
		
		return array(
			'response' => $this->response,
			'response_temp' => $this->response_temp,
			'verbs' => $this->verbs
		);
			
	}
}