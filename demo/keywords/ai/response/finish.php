<?php
if($_POST['nojson'] == 1){

} else {
	echo json_encode($jsonResult);
}
/* Each 10 response from bot the short term memory is flushed and the counter is resetted */
if(use_session('count_response_'.$botType) > 40){
	write_session('count_response_'.$botType, 0);
}
