<?php
putenv('GOOGLE_APPLICATION_CREDENTIALS=../credentials/google_credentials.json');
require '../../../config.php';

# Includes the autoloader for libraries installed with composer
require '../../../../../vendor/autoload.php';

use Google\Cloud\Language\LanguageClient;

/**
 * Find the entities in text.
 * ```
 * analyze_entities('Do you know the way to San Jose?');
 * ```
 *
 * @param string $text The text to analyze.
 * @param string $projectId (optional) Your Google Cloud Project ID
 *
 */
function analyze_entities($text)
{
    // Create the Natural Language client
    $language = new LanguageClient([
        'projectId' => $google_project_id,
    ]);

    // Call the analyzeEntities function
    $annotation = $language->analyzeEntities($text);

    // Print out information about each entity
    $entities = $annotation->entities();
	$names = array();
	
    foreach ($entities as $entity) {
        $names[] = $entity['name'];
    }
	
	return $names;
}

$text = isset($_POST['text']) ? $_POST['text'] : '';
$result = analyze_entities($text);

if(!empty($result)){
	echo json_encode($result);
}

?>