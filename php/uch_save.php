<?php
	include 'connect.php';
	$stmt = $db->prepare("INSERT INTO ofv_uch (name, type_id, birthday, address) VALUES (?, ?, ?, ?)");
	if(!$stmt->execute(array( $_POST['ofv_uch_name'],1,'1985-05-06','ТРУЪЪЪЪЪЪЪЪЪ')))
	{
?> 
		<html>
		<head>
			<meta charset="utf-8">
		</head>
		<body>
		Ошибка 	<?php print_r($stmt->errorInfo()); 	?> 
		</body>
		</html>		
<?php
	}
	else 
		header( 'Location: index.php' );
?> 
