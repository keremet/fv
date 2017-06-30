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
		 FROM uch
		 WHERE id = ?");
	$stmt->execute(array($uch_id));	
	oftTable::init('Счета (<a href="uch.php?id='.$uch_id.'">'.$stmt->fetchColumn().'</a>)');
	oftTable::header(array('ID','ТИП','ДАТА ОТКРЫТИЯ','ДАТА ЗАКРЫТИЯ', 'ПРИМЕЧАНИЕ', ''));
	$stmt = $db->prepare(
		"SELECT acc.id, acc_type.name
			, to_char( acc.creat_date, 'dd-mm-yyyy' ) AS creat_date
			, to_char( acc.clos_date, 'dd-mm-yyyy' ) AS clos_date
                        , acc.remark
		 FROM acc, acc_type
		 WHERE acc.uch_id = ?
			AND acc.type_id = acc_type.id");
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
