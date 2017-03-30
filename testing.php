<?php

require __DIR__.'/vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_url' => 'http://localhost:8000',
    'defaults' => [
        'exceptions' => false
    ]
]);

$title = 'ObjectOrienter'.rand(0, 999);
$data = array(
    'title' => $title,
    'author' => 'a test dev!'
);
$response = $client->post('/api/books', [
    'body' => json_encode($data)
]);

echo $response;
echo "\n\n";

