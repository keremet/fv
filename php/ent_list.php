<?php
	$acc_id = $_GET['acc_id'];
?>
<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Движения по счету</title>
</head>
<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="index.php">Участники</a>
	<td align="left"><a href="ent_add.php?cr_acc=<?=$acc_id?>&acc_id=<?=$acc_id?>">Добавить приход</a>
	<td align="left"><a href="ent_add.php?deb_acc=<?=$acc_id?>&acc_id=<?=$acc_id?>">Добавить расход</a>
</table>
<br/>
<?php
	include "oft_table.php";
	include "connect.php";
	
	$stmt = $db->prepare(
		"SELECT ofv_acc.remark, ofv_uch.id as uch_id
			, ofv_uch.name as uch_name, ofv_acc_type.name as type_name
		 FROM ofv_acc, ofv_uch, ofv_acc_type
		 WHERE ofv_acc.id = ?
			and ofv_acc.uch_id = ofv_uch.id
			and ofv_acc.type_id = ofv_acc_type.id");
	$stmt->execute(array($acc_id));
	$acc = $stmt->fetch();
	
	oftTable::init('Движения по счету <a href="acc_add.php?acc_id='.$acc_id.'">'.$acc_id.'</a>'
		.'<br>('.$acc['remark'].', '.$acc['type_name'].', <a href="uch.php?id='.$acc['uch_id'].'">'.$acc['uch_name'].'</a>)'
	);
	oftTable::header(array('ID','ДАТА','ПРИХОД','РАСХОД','ОСТАТОК','СЧЕТ','УЧАСТНИК','НАЗНАЧЕНИЕ ПЛАТЕЖА'));
	$stmt = $db->prepare(
		"SELECT A.*, DATE_FORMAT(exec_date, '%d-%m-%Y') as exec_date_u, ofv_uch.name, ofv_acc.uch_id
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
		oftTable::row(array('<a href=ent_add.php?id='.$row['id'].'&acc_id='.$_GET['acc_id'].'>'.$row['id'].'</a>', $row['exec_date_u'], $row['cr'], $row['deb']
		   ,$s ,'<a href=acc_add.php?acc_id='.$row['acc_id'].'>'.$row['acc_id'].'</a>'
		   , '<a href=uch.php?id='.$row['uch_id'].'>'.$row['name'].'</a>', $row['purpose']));
	}
        
	oftTable::end();
?> 
</body>
</html>
