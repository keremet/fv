<?php
	include 'connect.php';
	
	$plan_donation_acc_type_id = $_POST['plan_donation_acc_type_id'];
	$cr_acc_id = $_POST['cr_acc_id'];
	
	$stmtEnt = $db->prepare("INSERT INTO provodki(exec_date, summa, cred_acc_id, deb_acc_id, purpose) 
                          VALUES (to_date(?, 'ddmmyyyy'), ?, ?, ?, ?)");
	
	
	$stmtAcc = $db->prepare(
		"SELECT acc.id
		FROM acc
		WHERE acc.type_id = ?"	
	);
	$stmtAcc->execute(array($plan_donation_acc_type_id));
	while($rowAcc = $stmtAcc->fetch()){
		$summa = $_POST['acc'.$rowAcc['id']];
		if($summa!='')
			if(!$stmtEnt->execute(array($_POST['exec_date_cor']
								 ,$summa
								 ,$cr_acc_id
								 ,$rowAcc['id'] 
								 ,$_POST['purpose']))){
				?> 
				<html>
					<head>
						<meta charset="utf-8">
					</head>
					<body>
						Ошибка <?php print_r($stmt->errorInfo()); ?> 
					</body>
				</html>		
				<?php
			};
    } 
    
    header("Location: fund_plan.php?plan_acc_id=$cr_acc_id&plan_donation_acc_type_id=$plan_donation_acc_type_id");                    
?>          
