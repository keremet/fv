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
			header('Location: ent_list.php?acc_id='.$_POST['acc_id']);
		}
	}
	
	if ($_POST['ent_id']!=null) {
		if ($_POST['oper_type'] == 'delete') {
			execStmt("DELETE FROM provodki WHERE id = ?", array($_POST['ent_id']));
		} else {
            execStmt("UPDATE provodki SET exec_date = ?, summa = ?, cred_acc_id = ?, 
                             deb_acc_id = ?, purpose = ?
					  WHERE id = ?",
                            array($_POST['ofv_provodki_exec_date_cor']
                                 ,$_POST['ofv_provodki_summa']
								 ,$_POST['ofv_provodki_cred_acc_id']
                                 ,$_POST['ofv_provodki_deb_acc_id']
                                 ,$_POST['ofv_provodki_purpose']
                                 ,$_POST['ent_id'])); 
		}
	} else {
		execStmt("INSERT INTO provodki(exec_date, summa, cred_acc_id, deb_acc_id, purpose) 
                          VALUES (?, ?, ?, ?, ?)",
			array($_POST['ofv_provodki_exec_date_cor']
                             ,$_POST['ofv_provodki_summa']
                             ,$_POST['ofv_provodki_cred_acc_id']
                             ,$_POST['ofv_provodki_deb_acc_id']
                             ,$_POST['ofv_provodki_purpose']));                       
	}
?>

                       
