<?php
	include 'connect.php';
	function execStmt($qry, $arr) {
		global $db;
		$stmt = $db->prepare($qry);
		if (!$stmt->execute($arr)) {
?> 
			<html>
			<head>
				<meta charset="utf-8">
			</head>
			<body>
			Ошибка 	<?php print_r($stmt->errorInfo()); ?> 
			</body>
			</html>		
<?php
		} else {
			header('Location: index.php');
		}
	}
	
	if ($_POST['id'] != '') {
		if ($_POST['oper_type'] == 'delete') {
			execStmt("DELETE FROM ofv_uch WHERE id = ?", array($_POST['id']));
		} else {
			execStmt("UPDATE ofv_uch SET name = ?, address = ?
						, birthday = ?, pasp_ser = ?
						, pasp_num = ?, pasp_date = ?, pasp_who = ? 
					  WHERE id = ?",
				array( doNull($_POST['ofv_uch_name']), doNull($_POST['ofv_uch_address'])
					  ,doNull($_POST['ofv_uch_birthday_cor']), doNull($_POST['ofv_uch_pasp_ser'])
					  ,doNull($_POST['ofv_uch_pasp_num']), doNull($_POST['ofv_uch_pasp_date_cor'])
					  ,doNull($_POST['ofv_uch_pasp_who']), $_POST['id']));
		}
	} else {
		execStmt("INSERT INTO ofv_uch (name, address
					, birthday, pasp_ser
					, pasp_num, pasp_date
					, pasp_who) 
				  VALUES (?, ?, ?, ?, ?, ?, ?)",
			array( doNull($_POST['ofv_uch_name']), doNull($_POST['ofv_uch_address'])
				  ,doNull($_POST['ofv_uch_birthday_cor']), doNull($_POST['ofv_uch_pasp_ser'])
				  ,doNull($_POST['ofv_uch_pasp_num']), doNull($_POST['ofv_uch_pasp_date_cor'])
				  ,doNull($_POST['ofv_uch_pasp_who'])));
	}
?>
