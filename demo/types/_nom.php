<?php

class nom {
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
		
		if(isset($this->build_memory['NOM'])){
			/*if($this->action[$this->rand] == 'sug'){
				foreach($this->build_memory['NOM'] as $word_key => $word_value){
					$lexique_query = mysqli_query($this->connexion, "SELECT ortho, matches FROM lexique INNER JOIN synonymes ON lexique.ortho = synonymes.keyword WHERE keyword = '".addslashes($word_value['ortho'])."' COLLATE utf8_bin AND cgram = 'NOM' LIMIT 1") or die (mysqli_error($this->connexion));
					if(mysqli_num_rows($lexique_query) > 0){
						$row = mysqli_fetch_assoc($lexique_query);
						$matches = explode('|', $row['matches']);
						$index = (count($matches) == 0) ? 0 : rand(0, count($matches) - 1);
						$this->response['nom'][] = $matches[$index];
					} else {
						$this->response['nom'][] = $word_value['ortho'];
					}
				}
			}
						
			if($this->action[$this->rand] == 'rep'){*/
				foreach($this->build_memory['NOM'] as $word_key => $word_value){
					$this->response['nom'][] = $word_value['ortho'];
					$cgram = str_replace(':', '_', strtolower($word_value['cgram']));				
					$this->response_temp[$cgram][$word_key]['ortho'] = isset($word_value['ortho']) ? $word_value['ortho'] : '';
					$this->response_temp[$cgram][$word_key]['nombre'] = isset($word_value['nombre']) ? $word_value['nombre'] : '';
					$this->response_temp[$cgram][$word_key]['genre'] = isset($word_value['genre']) ? $word_value['genre'] : '';
				}
			/*}*/
		}
		
		return array(
			'response' => $this->response,
			'response_temp' => $this->response_temp
		);
	}
}