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
			execStmt("UPDATE ofv_uch SET name = ?, address = ?, birthday = ?
						, pasp_ser = ?, pasp_num = ?, pasp_date = ?, pasp_who = ? 
					  WHERE id = ?",
				array($_POST['ofv_uch_name'],$_POST['ofv_uch_address'],$_POST['ofv_uch_birthday_cor']
						,$_POST['ofv_uch_pasp_ser'],$_POST['ofv_uch_pasp_num']
                                                ,$_POST['ofv_uch_pasp_date_cor'],$_POST['ofv_uch_pasp_who']
						,$_POST['id']));
		}
	} else {
		execStmt("INSERT INTO ofv_uch (name, birthday
					, address, pasp_ser
					, pasp_num, pasp_date
					, pasp_who) 
				  VALUES (?, ?, ?, ?, ?, ?, ?)",
			array( $_POST['ofv_uch_name'],$_POST['ofv_uch_birthday_cor']
				  ,$_POST['ofv_uch_address'],$_POST['ofv_uch_pasp_ser']
				  ,$_POST['ofv_uch_pasp_num'],$_POST['ofv_uch_pasp_date_cor']
				  ,$_POST['ofv_uch_pasp_who']));
	}
?>
