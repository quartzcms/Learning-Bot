<?php
ini_set('display_errors', 1);
putenv('GOOGLE_APPLICATION_CREDENTIALS=../credentials/google_credentials.json');
require '../../../config.php';

# Includes the autoloader for libraries installed with composer
require '../../../../../vendor/autoload.php';

# Imports the Google Cloud client library
use Google\Cloud\Translate\TranslateClient;

# Instantiates a client
$translate = new TranslateClient([
    'projectId' => $google_project_id
]);

# The text to translate
$text = isset($_POST['translate']) ? $_POST['translate'] : '';

# The target language
$target = isset($_POST['language']) ? $_POST['language'] : 'fr';

# Translates some text
$translation = $translate->translate($text, [
    'target' => $target
]);

if(isset($translation['text'])){
	echo json_encode(array('translated_text' => $translation['text']));
}

?>