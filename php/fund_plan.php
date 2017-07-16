<?php
//session_start();
include 'connect.php';
$plan_acc_id = $_GET['plan_acc_id'];
$plan_donation_acc_type_id = $_GET['plan_donation_acc_type_id'];
?>
<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>План по взносам</title>
</head>
<table style="page-break-before: always;" width="650" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="ent_list.php?acc_id=<?=$plan_acc_id?>">Движение по счету</a>
	<td align="left"><a href="fund_plan_add.php?plan_acc_id=<?=$plan_acc_id?>&plan_donation_acc_type_id=<?=$plan_donation_acc_type_id?>">Добавить план на дату</a>
	<td align="left"><a href="fund_list.php">Общаки</a>
</table>
<br/>
<?php
	include "oft_table.php";
	oftTable::init('План по взносам');
	$stmt = $db->prepare(
		"SELECT distinct provodki.exec_date
		 FROM acc
		   JOIN provodki ON provodki.deb_acc_id = acc.id
		 WHERE acc.type_id = ?
		 ORDER BY 1 DESC"
	);
	$stmt->execute(array($plan_donation_acc_type_id));
	$dates = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
	$header[-1] = '';
	$header += $dates;
	oftTable::header($header);
	
	$stmt = $db->prepare(
		"SELECT uch.id, uch.name, acc.id deb_acc_id
		 FROM acc
		   JOIN uch ON acc.uch_id = uch.id
		 WHERE acc.type_id = ?
		 ORDER BY uch.name"
	);
	$stmt->execute(array($plan_donation_acc_type_id));
	$stmtSum = $db->prepare(
		"SELECT exec_date, summa
		 FROM provodki
		 WHERE cred_acc_id = ? and deb_acc_id = ?"
	);		
	while($row = $stmt->fetch()){
		$stmtSum->execute(array($plan_acc_id, $row['deb_acc_id']));
		$sums = array();
		while($rowSum = $stmtSum->fetch()){
			$sums[$rowSum['exec_date']] = $rowSum['summa'];
		}
		$planRow = array();
		$planRow[] = '<a href=uch.php?id='.$row['id'].'>'.$row['name'].'</a>';
		foreach($dates as $v){
			$planRow[] = array_key_exists($v, $sums)?('<p align="right">'.$sums[$v].'</p>'):'';
		}
		oftTable::row($planRow);
	}
	oftTable::end();
?> 
</body>
</html>
