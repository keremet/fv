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
             Das hat nicht geklabt 	<?php print_r($stmt->errorInfo()); ?> 
            </body>
</html>		
<?php
		} else {
			header('Location: loan_agr_list.php?uch_id='.$_POST['uch_id']);
		}
	}
	
	if ($_POST['base_debt_acc']!=null) {
		if ($_POST['oper_type'] == 'delete') {
			execStmt("DELETE FROM ofv_acc WHERE base_debt_acc = ?", array($_POST['base_debt_acc']));
		} 
			execStmt("UPDATE ofv_loan_agr SET sum = ?, base_rate = ?, fuflo_rate = ?
                                  WHERE ofv_loan_agr.base_debt_acc = ?",
				array($_POST['ofv_loan_agr_sum']
				     ,$_POST['ofv_loan_agr_base_rate']
                                     ,$_POST['ofv_loan_agr_fuflo_rate'] ));
		}
	 else {execStmt(" INSERT INTO ofv_acc(
				      uch_id, type_id, creat_date, remark)
				SELECT ? , id, ?, ?
				FROM ofv_acc_type
				WHERE name = 'Ссудный'",
		       array ($_POST['uch_id']
                             ,$_POST['ofv_acc_creat_date_cor']
                             ,$_POST['ofv_acc_remark']));
		/*execStmt("INSERT INTO ofv_loan_agr (sum, base_rate, fuflo_rate) 
                          VALUES (?, ?, ?)",
                            array($_POST['ofv_loan_agr_sum']
                            ,$_POST['ofv_loan_agr_base_rate']
                            ,$_POST['ofv_loan_agr_fuflo_rate']));*/
	}
?>
