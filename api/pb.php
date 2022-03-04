<?php
# delay
sleep(1);

# var
$method = $_SERVER['REQUEST_METHOD'];

$formData = file_get_contents('php://input');
$data = json_decode($formData, TRUE);
if ($data)
	extract($data);

# includes
include_once "../lib/mysqli.php";
include_once "routers/PB_$method.php";
?>