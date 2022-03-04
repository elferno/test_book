<?php
# requirements
include_once "lib/mysqli.php";

# read DB items
$items = $mySQL -> select_multiple("SELECT * FROM items;")['items'];

# create HTMl items
$bookItems = [];
foreach($items as $item)
	array_push($bookItems, "
		<div class='book-item tabled' id='book-item-" . $item['item_id'] . "'>
			<div class='book-item-fio'>" . $item['fio'] . "</div>
			<div class='book-item-phone'>" . $item['phone'] . "</div>
			<div class='book-item-who'>" . $item['who'] . "</div>
			<div class='book-item-buttons'>
				<div class='button center' onClick='Items.edit(" . $item['item_id'] . ");'>🖉</div>
				<div class='button center' onClick='Items.delete(" . $item['item_id'] . ");'>×</div>
			</div>
		</div>
	");

if (empty($bookItems))
    $bookItems = ["<div id='no-items'>Записей не найдено</div>"];
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PhoneBook</title>
	<link href="css/app.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="header">
		<div class="container">
			<div class="header-label">Phone book</div>
			<div class="header-buttons">
				<div id="add-button" class="center">+</div>
			</div>
		</div>
	</div>

	<div id="content">
		<div id="book-labels">
			<div class="container">
				<div id="book-labels-content" class="tabled">
					<div>ФИО</div>
					<div>Телефон</div>
					<div>Кем приходится</div>
					<div>Кнопки действий</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div id="book-items">
				<?php echo implode('', $bookItems); ?>
			</div>
		</div>
	</div>

	<div class="popup center" id="popup-add">
		<div class="popup-body">
			<div class="popup-close center" id="popup-add-close">×</div>
			<div class="popup-label">Введите данные</div>
			<div class="popup-content">
				<div class="i-label"><input type="text" id="fio" value="" autocomplete="off"><i>ФИО</i></div>
				<div class="i-label"><input type="text" id="phone" value="" autocomplete="off"><i>Телефон</i></div>
				<div class="i-label"><input type="text" id="who" value="" autocomplete="off"><i>Кем приходится</i></div>
				<input type="hidden" value="" id="item-id">
			</div>
			<div class="popup-buttons">
				<div class="button button-g" id="popup-add-cancel">отмена</div>
				<div class="button button-r" id="popup-add-save">сохранить</div>
			</div>
		</div>
	</div>

	<script src="js/app.js" type="text/javascript"></script>
</body>
</html>