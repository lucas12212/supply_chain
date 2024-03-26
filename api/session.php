<?php

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
    	$session_id = session_create_id();
    	echo json_encode([
            'session_id' => $session_id
        ]);
    break;
}