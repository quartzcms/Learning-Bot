<?php
/* If last response from the bot is a question and not defined set it to 0 */
if(!use_session('last_question_'.$botType)){ 
	write_session('last_question_'.$botType, 0); 
}
/* if the last response sentence from the bot is not defined then set it empty. */
if(!use_session('last_question_sentence_'.$botType)){ 
	write_session('last_question_sentence_'.$botType, ''); 
}
/* Defining the used memory rows ID session if not defined */
if(!use_session('used_id_'.$botType)){ 
	write_session('used_id_'.$botType, array()); 
}

/* Defining an array to remember which sentence was said from the bot */
if(!use_session('note_'.$botType)){ 
	write_session('note_'.$botType, array()); 
}
/* Defining an array of responses from the bot which is supposed to be storing each response into an array */
if(!use_session('last_response_'.$botType)){ 
	write_session('last_response_'.$botType, array()); 
}
/* Defining the variable already said */
$alreadySaidFlag = 'no';
/* Defining the learning variable */
$learnTrigger = '';
/* If last bot response is a question set the learn variable on */
if(use_session('last_question_'.$botType) == 1){
	$learnTrigger = 'learn';
	write_session('last_question_'.$botType, 0);
	write_session('last_question_sentence_'.$botType, '');
}

/* Each 10 response clean the the memory used IDs and the stored responses */
if(use_session('count_response_'.$botType) > 40){
	write_session('used_id_'.$botType, array());
	write_session('note_'.$botType, array());
	write_session('last_response_'.$botType, array());
	write_session('stored_syntax_'.$botType, array());
}
/* Set a new array element (index of the response count) to the note session */
$botNotes = use_session('note_'.$botType);
$botNotes[use_session('count_response_'.$botType)] = '';
write_session('note_'.$botType, $botNotes);
