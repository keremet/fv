<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Общаки</title>
</head>
<table style="page-break-before: always;" width="650" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="fund_add.php">Добавить общак</a>
</table>
<br/>
<?php
	include "oft_table.php";
	include "connect.php";
	oftTable::init('Общаки');
	oftTable::header(array('СЧЕТ', 'УЧАСТНИК', 'ПРИМЕЧАНИЕ', 'ТИП СЧЕТА ДОХОДОВ', 'ТИП СЧЕТА РАСХОДОВ', ''));
	foreach($db->query(
			"SELECT uch.id uch_id, uch.name, acc.remark
				, type_in.name type_in_name, type_out.name type_out_name
				, fund.acc_id
			 FROM fund
			   LEFT JOIN acc_type type_in ON fund.donation_acc_type_id = type_in.id
			   LEFT JOIN acc_type type_out ON fund.expenditure_acc_type_id = type_out.id
			   JOIN acc ON fund.acc_id = acc.id
			   JOIN uch ON acc.uch_id = uch.id
			 ORDER BY name"
			) as $row){
	oftTable::row(array('<a href=acc_add.php?acc_id='.$row['acc_id'].'>'.$row['acc_id'].'</a>'
		, '<a href=uch.php?id='.$row['uch_id'].'>'.$row['name'].'</a>',$row['remark']
		, $row['type_in_name'], $row['type_out_name']
		, '<a href=fund_ent_add.php?cr_acc='.$row['acc_id'].'>Приход</a>
		   <a href=fund_ent_add.php?deb_acc='.$row['acc_id'].'>Расход</a>
		   <a href=fund_stat.php?acc_id='.$row['acc_id'].'>Статистика</a>
		   <a href=ent_list.php?acc_id='.$row['acc_id'].'>Движение</a>'
		));
	}

	oftTable::end();
?> 
</body>
</html>
