<?php
# var
$fio = $mySQL -> safeString($fio);
$who = $mySQL -> safeString($who);
$phone = $mySQL -> safeString($phone);
$item_id = $mySQL -> safeString($item_id);

# update item
$mySQL -> query("UPDATE items SET fio = '$fio', phone = '$phone', who = '$who' WHERE item_id = $item_id;");

# answer to client
echo json_encode([
	'action' => 'update',
	'payload' => [
		'item_id' => $item_id,
		'fio' => $fio,
		'phone' => $phone,
		'who' => $who
	]
]);
?>