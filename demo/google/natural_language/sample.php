<?php
namespace Google\Cloud\Samples\Language;

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
function analyze_entities($text, $projectId = null)
{
    // Create the Natural Language client
    $language = new LanguageClient([
        'projectId' => $projectId,
    ]);

    // Call the analyzeEntities function
    $annotation = $language->analyzeEntities($text);

    // Print out information about each entity
    $entities = $annotation->entities();
    foreach ($entities as $entity) {
        printf('Name: %s' . PHP_EOL, $entity['name']);
        printf('Type: %s' . PHP_EOL, $entity['type']);
        printf('Salience: %s' . PHP_EOL, $entity['salience']);
        if (array_key_exists('wikipedia_url', $entity['metadata'])) {
            printf('Wikipedia URL: %s' . PHP_EOL, $entity['metadata']['wikipedia_url']);
        }
        if (array_key_exists('mid', $entity['metadata'])) {
            printf('Knowledge Graph MID: %s' . PHP_EOL, $entity['metadata']['mid']);
        }
        printf(PHP_EOL);
    }
}
?>