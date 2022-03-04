<?php
# var
$fio = $mySQL -> safeString($fio);
$who = $mySQL -> safeString($who);
$phone = $mySQL -> safeString($phone);

# add new item
$mySQL -> query("INSERT INTO items (fio, phone, who) VALUES('$fio', '$phone', '$who');");

# get item id
$item_id = $mySQL -> select("SELECT LAST_INSERT_ID() as item_id;")['item_id'];

# answer to client
echo json_encode([
	'action' => 'add',
	'payload' => [
		'item_id' => $item_id,
		'fio' => $fio,
		'phone' => $phone,
		'who' => $who
	]
]);
?>