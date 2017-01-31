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
			header('Location: acc_list.php?uch_id='.$_POST['uch_id']);
		}
	}
	
	if ($_POST['acc_id']!=null) {
		if ($_POST['oper_type'] == 'delete') {
			execStmt("DELETE FROM ofv_acc WHERE id = ?", array($_POST['acc_id']));
		} else {
			$ofv_acc_clos_date_cor = $_POST['ofv_acc_clos_date_cor'];
			execStmt("UPDATE ofv_acc SET creat_date = ?, clos_date = ?
					  WHERE id = ?",
				array($_POST['ofv_acc_creat_date_cor']
					,($ofv_acc_clos_date_cor != '')?$ofv_acc_clos_date_cor:null
					,$_POST['acc_id']));
		}
	} else {
		execStmt("INSERT INTO ofv_acc (uch_id, type_id, creat_date) 
                          VALUES (?, ?, ?)",
			array($_POST['uch_id']
                             ,$_POST['ofv_acc_type']
                             ,$_POST['ofv_acc_creat_date_cor']));
	}
?>
