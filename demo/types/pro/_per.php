<?php

class pro_per {
	protected $build_memory;
	protected $connexion;
    protected $action;
    protected $response;
	protected $response_temp;
	protected $rand;
	
	public function __construct($variables) {
		$this->build_memory = $variables['build_memory'];
		$this->connexion = $variables['connexion'];
		$this->action = $variables['action'];
		$this->rand = $variables['rand'];
    }
	
	public function loop($response, $response_temp) {
		$this->response = $response;
		$this->response_temp = $response_temp;
		
		if(isset($this->build_memory['PRO:per'])){
			foreach($this->build_memory['PRO:per'] as $word_key => $word_value){
				$this->response['pro_per'][] = $word_value['ortho'];
			}
		}
		
		return array(
			'response' => $this->response,
			'response_temp' => $this->response_temp,
			'build_memory' => $this->build_memory,
		);
	}
}

