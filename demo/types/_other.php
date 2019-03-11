<?php

class other {
	protected $build_memory;
	protected $connexion;
    protected $action;
	protected $rand;
    protected $response;
	protected $response_temp;
	
	public function __construct($variables) {
		$this->build_memory = $variables['build_memory'];
		$this->connexion = $variables['connexion'];
		$this->action = $variables['action'];
		$this->rand = $variables['rand'];
    }
	
	public function loop($response, $response_temp) {
		$this->response = $response;
		$this->response_temp = $response_temp;
		
		if(isset($this->build_memory['OTHER'])){
			foreach($this->build_memory['OTHER'] as $word_key => $word_value){
				$this->response['other'][] = $word_value['ortho'];
			}
		}
		return array(
			'response' => $this->response,
			'response_temp' => $this->response_temp
		);
	}
}