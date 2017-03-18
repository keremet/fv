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
	
	if ($_POST['acc_id']!=null) {
		if ($_POST['oper_type'] == 'delete') {
			execStmt("CALL del_loan_agr (?)",
			     array($_POST['acc_id']));
		} else {
			$ofv_acc_clos_date_cor = $_POST['ofv_acc_clos_date_cor'];
			execStmt("CALL upd_loan_agr (?, ?, ?, ?, ?, ?, ?)",
				array($_POST['acc_id']
					 ,$_POST['ofv_acc_creat_date_cor']
                     ,($ofv_acc_clos_date_cor != '')?$ofv_acc_clos_date_cor:null
                     ,$_POST['ofv_acc_remark']
					 ,$_POST['ofv_loan_agr_sum']
				     ,$_POST['ofv_loan_agr_base_rate']
                     ,$_POST['ofv_loan_agr_fuflo_rate']));
        }
     }    
	 else {execStmt("CALL ins_loan_agr (?, ?, ?, ?, ?, ?)",
					  array ($_POST['uch_id']
                            ,$_POST['ofv_acc_creat_date_cor']
                            ,$_POST['ofv_acc_remark']
                            ,$_POST['ofv_loan_agr_sum']
                            ,$_POST['ofv_loan_agr_base_rate']
                            ,$_POST['ofv_loan_agr_fuflo_rate']));
	}
?>
