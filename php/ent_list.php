<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Нормальные двежения по счету</title>
</head>
<table style="page-break-before: always;" width="1000" border="1" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="index.php">Участники</a>
	<td align="left"><a href="acc_add.php?acc_id=<?=$_GET['acc_id']?>">Счёт</a>
        <td align="left"><a href="ent_add.php?cr_acc=<?=$_GET['acc_id']?>&acc_id=<?=$_GET['acc_id']?>">Добавить приход</a>
        <td align="left"><a href="ent_add.php?deb_acc=<?=$_GET['acc_id']?>&acc_id=<?=$_GET['acc_id']?>">Добавить расход</a>
</table>
<br/>
<?php
	include "oft_table.php";
	include "connect.php";
	oftTable::init('Нормальные движения по счёту');
	oftTable::header(array('ID','ДАТА','ПРИХОД','РАСХОД','ОСТАТОК','СЧЕТ','УЧАСТНИК','НАЗНАЧЕНИЕ ПЛАТЕЖА'
                              ,'НАВЕСТИ НОРМАЛЬНОЕ ДВИЖЕНИЕ'));
	$stmt = $db->prepare(
		"SELECT A.*, DATE_FORMAT(exec_date, '%d-%m-%Y') as exec_date_u, ofv_uch.name
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
		oftTable::row(array($row['id'], $row['exec_date_u'], $row['cr'], $row['deb']
		   ,$s ,$row['acc_id'], $row['name'], $row['purpose'], 
                            '<a href=ent_add.php?id='.$row['id'].'&acc_id='.$_GET['acc_id'].'>Переделать движение</a>'));
	}
        
	oftTable::end();
?> 
</body>
</html>
