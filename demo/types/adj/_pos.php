<?php

class adj_pos {
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
		
		if(isset($this->build_memory['ADJ:pos'])){
			foreach($this->build_memory['ADJ:pos'] as $word_key => $word_value){
				$cgram = str_replace(':', '_', strtolower($word_value['cgram']));
				$reverse = mysqli_query($this->connexion, "SELECT * FROM adj_pos WHERE keyword = '".addslashes($word_value['ortho'])."' COLLATE utf8_bin LIMIT 1") or die (mysqli_error($this->connexion));
				if(mysqli_num_rows($reverse) > 0){
					$row = mysqli_fetch_assoc($reverse);
					$this->response['adj_pos'][] = $row['match'];
					$this->response_temp[$cgram][$word_key]['ortho'] = $row['match'];
				} else {
					$this->response['adj_pos'][] = $word_value['ortho'];
					$this->response_temp[$cgram][$word_key]['ortho'] = $word_value['ortho'];
				}
				
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