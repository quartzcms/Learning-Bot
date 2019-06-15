<?php

class pro_per_con {
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
		
		if(isset($this->build_memory['PRO:per:con'])){
			foreach($this->build_memory['PRO:per:con'] as $word_key => $word_value){
				$this->response['pro_per_con'][] = $word_value['ortho'];
				$cgram = str_replace(':', '_', mb_strtolower($word_value['cgram'], 'UTF-8'));				
				$this->response_temp[$cgram][$word_key]['ortho'] = !empty($word_value['ortho']) ? $word_value['ortho'] : '';
				$this->response_temp[$cgram][$word_key]['nombre'] = !empty($word_value['nombre']) ? $word_value['nombre'] : 's';
				$this->response_temp[$cgram][$word_key]['genre'] = !empty($word_value['genre']) ? $word_value['genre'] : 'm';
			}
		}
		
		return array(
			'response' => $this->response,
			'response_temp' => $this->response_temp
		);
	}
}