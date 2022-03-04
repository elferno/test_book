<?php
# var
$item_id = $mySQL -> safeString($item_id);

# remove item
if ($item_id)
	$mySQL -> query("DELETE FROM items WHERE item_id = $item_id;");

# answer to client
echo json_encode([
	'action' => 'delete',
	'payload' => [
		'item_id' => $item_id
	]
]);
?>