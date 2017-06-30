
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
			header('Location: loan_agr_add.php?acc_id='.$_POST['acc_id']);
           }
    }
	
	execStmt("DELETE FROM garant WHERE uch_id = ? AND base_debt_acc = ?", 
             array($_POST['uch_id']
                  ,$_POST['acc_id']));  
?>
