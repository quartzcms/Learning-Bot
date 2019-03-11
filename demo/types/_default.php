<?php

class _default {
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
		return array(
			'response' => $this->response,
			'response_temp' => $this->response_temp
		);
	}
}