<?php
/* If already said set variable or if not increment the sentences storing variable with the new accepted value founds in the pattern */
if(in_array($alreadySaidSignature, use_session('note_'.$botType))){
	$alreadySaidFlag = 'yes';
} else {
	$botNotes = use_session('note_'.$botType);
	$botNotes[use_session('count_response_'.$botType)] = $alreadySaidSignature;
	write_session('note_'.$botType, $botNotes);
}
