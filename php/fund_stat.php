<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Статистика</title>
</head>
<table style="page-break-before: always;" width="650" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="fund_list.php">Общаки</a>
</table>
<br/>
<?php
	include "oft_table.php";
	include "connect.php";
	oftTable::init('Статистика');
	oftTable::header(array('УЧАСТНИК', 'УПЛАЧЕНО', 'ПО ПЛАНУ', 'ДОЛГ', 'ДАТА ПОСЛЕДНЕГО ПЛАТЕЖА ПО ПЛАНУ'));
	$stmt = $db->prepare(
		"SELECT a.*, (a.planed - a.payed) debt
		 FROM (
			SELECT uch.id uch_id, uch.name, (
				SELECT sum(summa)
				FROM provodki
				WHERE cred_acc_id = fund.acc_id and deb_acc_id = acc.id
			) payed, 
			(
				SELECT sum(summa)
				FROM provodki
				WHERE cred_acc_id = fund.plan_acc_id and deb_acc_id = plan_acc.id
			) planed, 
			(
				SELECT to_char(max(exec_date), 'dd-mm-yyyy')
				FROM provodki
				WHERE cred_acc_id = fund.plan_acc_id and deb_acc_id = plan_acc.id
			) last_plan_d
			FROM fund
			  JOIN acc ON fund.donation_acc_type_id = acc.type_id
			  JOIN uch ON acc.uch_id = uch.id
				LEFT JOIN acc plan_acc ON uch.id = plan_acc.uch_id 
						and fund.plan_donation_acc_type_id = plan_acc.type_id
			WHERE fund.acc_id = ?
		) a
		ORDER BY a.name"
	);
	$stmt->execute(array($_GET['acc_id']));
	while($row = $stmt->fetch()){
		oftTable::row(array('<a href=uch.php?id='.$row['uch_id'].'>'.$row['name'].'</a>'
			, $row['payed'], '<p align="right">'.$row['planed'].'</p>', $row['debt'], $row['last_plan_d']));
	}
	oftTable::end();
?> 
</body>
</html>
