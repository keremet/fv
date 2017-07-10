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
			execStmt("DELETE FROM uch WHERE id = ?", array($_POST['id']));
		} else {
			execStmt("UPDATE uch SET name = ?, address = ?, address_fact = ?
						, pol_m = ?, birthday = to_date(?, 'ddmmyyyy'), pasp_ser = ?
						, pasp_num = ?, pasp_date = to_date(?, 'ddmmyyyy'), pasp_who = ?, remark = ? 
					  WHERE id = ?",
				array( doNull($_POST['ofv_uch_name']), doNull($_POST['ofv_uch_address']), doNull($_POST['ofv_uch_address_fact'])
					  ,doNull($_POST['ofv_uch_pol']), doNull($_POST['ofv_uch_birthday_cor']), doNull($_POST['ofv_uch_pasp_ser'])
					  ,doNull($_POST['ofv_uch_pasp_num']), doNull($_POST['ofv_uch_pasp_date_cor'])
					  ,doNull($_POST['ofv_uch_pasp_who']), doNull($_POST['ofv_uch_remark']), $_POST['id']));
		}
	} else {
		execStmt("INSERT INTO uch (name, address, address_fact
					, pol_m, birthday, pasp_ser
					, pasp_num, pasp_date
					, pasp_who, remark) 
				  VALUES (?, ?, ?, ?, to_date(?, 'ddmmyyyy'), ?, ?, to_date(?, 'ddmmyyyy'), ?, ?)",
			array( doNull($_POST['ofv_uch_name']), doNull($_POST['ofv_uch_address']), doNull($_POST['ofv_uch_address_fact'])
				  ,doNull($_POST['ofv_uch_pol']), doNull($_POST['ofv_uch_birthday_cor']), doNull($_POST['ofv_uch_pasp_ser'])
				  ,doNull($_POST['ofv_uch_pasp_num']), doNull($_POST['ofv_uch_pasp_date_cor'])
				  ,doNull($_POST['ofv_uch_pasp_who']), doNull($_POST['ofv_uch_remark'])));
	}
?>
