<?php

// this file accepts all the AJAX requests and processes them through the enigma class

namespace Enigma;

if (empty($_POST)) {
	http_response_code(404);
	exit;
}

$response = array( );

$E = new Machine($_POST);

echo json_encode($response);
exit;
