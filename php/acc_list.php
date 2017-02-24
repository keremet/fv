<?php
	$uch_id = $_GET['uch_id'];
?>
<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Счета</title>
</head>
<table style="page-break-before: always;" width="400" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="acc_add.php?uch_id=<?=$uch_id?>">Добавить счёт</a>
	<td align="left"><a href="index.php">Участники</a>
</table>
<br/>
<?php
	include "oft_table.php";
	include "connect.php";
	
	$stmt = $db->prepare(
		"SELECT name
		 FROM ofv_uch
		 WHERE id = ?");
	$stmt->execute(array($uch_id));	
	oftTable::init('Счета (<a href="uch.php?id='.$uch_id.'">'.$stmt->fetchColumn().'</a>)');
	oftTable::header(array('ID','ТИП','ДАТА ОТКРЫТИЯ','ДАТА ЗАКРЫТИЯ', 'ПРИМЕЧАНИЕ', ''));
	$stmt = $db->prepare(
		"SELECT ofv_acc.id, ofv_acc_type.name
			, DATE_FORMAT( ofv_acc.creat_date, '%d-%m-%Y' ) AS creat_date
			, DATE_FORMAT( ofv_acc.clos_date, '%d-%m-%Y' ) AS clos_date
                        , ofv_acc.remark
		 FROM ofv_acc, ofv_acc_type
		 WHERE ofv_acc.uch_id = ?
			AND ofv_acc.type_id = ofv_acc_type.id");
	$stmt->execute(array($_GET['uch_id']));
	while ($row = $stmt->fetch()) {
		oftTable::row(array('<a href=acc_add.php?acc_id='.$row['id'].'>'.$row['id'].'</a>'
			, $row['name'], $row['creat_date'], $row['clos_date'], $row['remark']
			, '<a href=ent_add.php?cr_acc='.$row['id'].'&acc_id='.$row['id'].'>Добавить приход</a>'.
			  '<br><a href=ent_add.php?deb_acc='.$row['id'].'&acc_id='.$row['id'].'>Добавить расход</a>'.
			  '<br><a href=ent_list.php?acc_id='.$row['id'].'>Движение</a>'));
	}

	oftTable::end();
?> 
</body>
</html>
