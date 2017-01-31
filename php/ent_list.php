<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Движение по счету</title>
</head>
<table style="page-break-before: always;" width="650" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="index.php">Участники</a>
	<td align="left"><a href="acc_add.php?acc_id=<?=$_GET['acc_id']?>">Счёт</a>
</table>
<br/>
<?php
	include "oft_table.php";
	include "connect.php";
	oftTable::init('Движение по счету');
	oftTable::header(array('ID','ДАТА','ПРИХОД','РАСХОД','ОСТАТОК','СЧЕТ','УЧАСТНИК','НАЗНАЧЕНИЕ ПЛАТЕЖА'));
	$stmt = $db->prepare(
		"SELECT A.*, ofv_uch.name
		 FROM (
			 SELECT id, exec_date, summa as cr, null as deb, deb_acc_id as acc_id, purpose
			 FROM ofv_provodki
			 WHERE cred_acc_id = ?
			 UNION ALL
			 SELECT id, exec_date, null as cr, summa as deb, cred_acc_id as acc_id, purpose
			 FROM ofv_provodki
			 WHERE deb_acc_id = ?
		 ) A, ofv_acc, ofv_uch
		 WHERE A.acc_id = ofv_acc.id
		   AND ofv_acc.uch_id = ofv_uch.id
		 ORDER BY A.exec_date, A.id
		 ");
	$stmt->execute(array($_GET['acc_id'], $_GET['acc_id']));
	$s = 0;
	while ($row = $stmt->fetch()) {
		if ($row['cr'] != null)
			$s += $row['cr'];
		else
			$s -= $row['deb'];
		oftTable::row(array($row['id'], $row['exec_date'], $row['cr'], $row['deb']
			,$s ,$row['acc_id'], $row['name'], $row['purpose']));
	}

	oftTable::end();
?> 
</body>
</html>
