<?php

require 'vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create() // (2)
->build(); // (3)


$q = $_REQUEST['q'];



//-- Change Here -->
$query = $client->search([
	'body' => [
		'track_total_hits' => true,
		'query' => [ // (5)
			// 'bool' => [
			// 	'should' => [
			// 		'match' => [
						
			// 			'description' => $q
			// 		],
			// 	]
			// ]
			'multi_match' => [
				'query' => $q,
				'fields' => ['description', 'patentID'],
				'fuzziness' => 'AUTO',
			], 
			// 'match' => [
			// 	'description' => $q,
			// 	// 'fuzziness' => 'AUTO',
			// ]
			
		],
		'size' => 100,
		// 'from' => $from,
		'highlight' => [
			'fields' => [
				'description' => (object)[],
				'patentID' => (object)[],
				'pid' => (object)[],
				'aspect' => (object)[],
			]
		]
	]
]);


header('Content-type: application/json');
echo json_encode( $query );