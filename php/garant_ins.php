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
			header('Location: loan_agr_add.php?acc_id='.$_POST['base_debt_acc']);
           }
    }
    
    
   execStmt("INSERT INTO ofv_garant (uch_id, base_debt_acc) 
             VALUES (?, ?)",
             array($_POST['ofv_uch_name']
				  ,$_POST['base_debt_acc']));
?>
