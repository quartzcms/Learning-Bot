<?php
ini_set('display_errors', 1);
putenv('GOOGLE_APPLICATION_CREDENTIALS=../credentials/google_credentials.json');
require '../../../config.php';

# Includes the autoloader for libraries installed with composer
require '../../../../../vendor/autoload.php';

# Imports the Google Cloud client library
use Google\Cloud\Speech\SpeechClient;

# Instantiates a client
$speech = new SpeechClient([
    'projectId' => $google_project_id,
    'languageCode' => 'fr-FR',
]);

exec('ffmpeg -y -i '.$_FILES['file']['tmp_name'].' -f s16le -acodec pcm_s16le -ar 16000 '.__DIR__.'/tmp/'.md5($_SERVER['REMOTE_ADDR']).'.raw');

# The name of the audio file to transcribe
$fileName = __DIR__.'/tmp/'.md5($_SERVER['REMOTE_ADDR']).'.raw';

# The audio file's encoding and sample rate
$options = [
    'encoding' => 'LINEAR16',
    'sampleRateHertz' => 16000,
];

# Detects speech in the audio file
$results = $speech->recognize(fopen($fileName, 'r'), $options);
if(isset($results[0])){
	$data = $results[0]->info();
	if(isset($data["alternatives"][0]['transcript'])){
		echo json_encode(array('voice_text' => $data["alternatives"][0]['transcript']));
	}	
}

?>