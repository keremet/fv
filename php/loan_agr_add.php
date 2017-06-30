<?php
//session_start();
include 'connect.php';
if (isset($_GET['acc_id'])) {
	$acc_id = $_GET['acc_id'];
	$stmt = $db->prepare(
		"SELECT uch_id, to_char(creat_date, 'ddmmyyyy') as creat_date
                ,to_char(clos_date, 'ddmmyyyy') as clos_date, remark, sum, base_rate, fuflo_rate
		 FROM acc 
                   JOIN loan_agr ON loan_agr.base_debt_acc = acc.id
		 WHERE acc.id = ?");
	$stmt->execute(array($acc_id));
	$acc = $stmt->fetch();
	$uch_id = $acc['uch_id'];
        
} else {
	$acc_id = null;
	$uch_id = isset($_GET['uch_id'])?$_GET['uch_id']:null;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Счёт</title>
</head>
<body>
<script src="dates.js"></script>	
<script>
function checkDelLoanAgr(){
	if(confirm('Действительно удалить договор?')){
		document.getElementById('oper_type').value='delete';
		return true;
	}
	return false;
}

function saveLoanAgr(){
	var arr_err = [];
	
	document.getElementById("ofv_acc_creat_date_cor").value = 
		form_and_check_std_dat(document.getElementById("ofv_acc_creat_date").value, arr_err);
    if(arr_err.length>0){
		alert("Ошибка в дате создания: "+arr_err[0]);
		document.getElementById("ofv_acc_creat_date").focus();
		document.getElementById("ofv_acc_creat_date").select();
		return false;
    }
<?php if ($acc_id!=null) { ?>  
	var v_ofv_acc_clos_date = document.getElementById("ofv_acc_clos_date").value;
	if ( v_ofv_acc_clos_date != '' ) {
		document.getElementById("ofv_acc_clos_date_cor").value = 
			form_and_check_std_dat(v_ofv_acc_clos_date, arr_err);
		if(arr_err.length>0){
			alert("Ошибка в дате закрытия: "+arr_err[0]);
			document.getElementById("ofv_acc_clos_date").focus();
			document.getElementById("ofv_acc_clos_date").select();
			return false;
		}
	}
<?php } ?>  
    return true;
}

</script>	
<table style="page-break-before: always;" width="600" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="index.php">Участники</a>
	<td align="left"><a href="acc_list.php?uch_id=<?=$uch_id?>">Счета</a>
<?php if ($acc_id!=null) { ?>
	<td align="left"><a href="ent_list.php?acc_id=<?=$acc_id?>">Движение по счету</a>
        <td align="left"><a href="garant_add.php?acc_id=<?=$acc_id?>">Добавить поручителя</a>   
<?php } ?>
</table>
<br/>
<form id="main_form" action="loan_agr_save.php" method="post">
<table border="0" cellpadding="0" cellspacing="2">
 
<tr><td>Базовая ставка<td><input id="ofv_loan_agr_base_rate"  name="ofv_loan_agr_base_rate" size="30" type="text" 
value="<?=(($acc_id!=null)?$acc['base_rate']:'')?>"> 

<tr><td>Фуфлыжная ставка<td><input id="ofv_loan_agr_fuflo_rate"  name="ofv_loan_agr_fuflo_rate" size="30" type="text" 
value="<?=(($acc_id!=null)?$acc['fuflo_rate']:'')?>">   

<tr><td>Сумма<td><input id="ofv_loan_agr_sum"  name="ofv_loan_agr_sum" size="30" type="text" 
value="<?=(($acc_id!=null)?$acc['sum']:'')?>">     

<tr><td>Примечание<td><input id="ofv_acc_remark"  name="ofv_acc_remark" size="30" type="text" 
value="<?=(($acc_id!=null)?$acc['remark']:'')?>">    
        
<tr><td>Дата открытия<td><input id="ofv_acc_creat_date"  name="ofv_acc_creat_date" size="8" type="text" 
value="<?=(($acc_id!=null)?$acc['creat_date']:'')?>" maxlength="8" 
onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">

<?php if ($acc_id!=null){?>     
	
<tr><td>Дата закрытия<td><input id="ofv_acc_clos_date"  name="ofv_acc_clos_date" size="8" type="text" 
value="<?=(($acc_id!=null)?$acc['clos_date']:'')?>" maxlength="8" 
onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
                   
<?php } ?>
</table>  
<br><input value="<?=(($acc_id==null)?"Создать договор":"Сохранить")?>" type="submit"  onclick="return saveLoanAgr();">

<?php if ($acc_id!=null) { ?>
<input value="Удалить договор" type="submit" onclick="return checkDelLoanAgr();">
<input type="hidden" id="ofv_acc_clos_date_cor" name="ofv_acc_clos_date_cor">
<?php } ?>

<input type="hidden" id="oper_type" name="oper_type" value="update">
<input type="hidden" id="uch_id" name="uch_id" value="<?=$uch_id?>">
<input type="hidden" id="acc_id" name="acc_id" value="<?=$acc_id?>">
<input type="hidden" id="ofv_acc_creat_date_cor" name="ofv_acc_creat_date_cor">

</form>

<?php
	include "oft_table.php";
	$stmt = $db->prepare(
		"SELECT base_debt_acc
		 FROM loan_agr
		 WHERE base_debt_acc = ?");
	$stmt->execute(array($acc_id));	
	oftTable::init('Поручители по договору');
        oftTable::header(array('ФИО','АДРЕС'
			,'ДАТА РОЖДЕНИЯ','ПАСПОРТ: СЕРИЯ','НОМЕР','ДАТА ВЫДАЧИ'
			,'КЕМ ВЫДАН', ''));
	$stmt = $db->prepare(
			"SELECT id, name, address, to_char(birthday, 'dd-mm-yyyy') as birthday
				, pasp_ser, pasp_num
				, to_char(pasp_date, 'dd-mm-yyyy') as pasp_date, pasp_who 
			 FROM garant
                         JOIN uch ON garant.uch_id = uch.id
                            WHERE garant.base_debt_acc = ?
			 ORDER BY name");
        $stmt->execute(array($acc_id));	
			while ($row = $stmt->fetch()){
	oftTable::row(array('<a href=uch.php?id='.$row['id'].'>'.$row['name'].'</a>',$row['address']
		,$row['birthday'],$row['pasp_ser'],$row['pasp_num'],$row['pasp_date']
		,$row['pasp_who']
		,'<form action="garant_del.php" method="post">
		<input type="hidden" name="uch_id" value="'.$row['id'].'">
		<input type="hidden" name="acc_id" value="'.$acc_id.'">
		<button>Удалить</button></form>'));
	}
	oftTable::end();


	oftTable::init('График платежей');
        oftTable::header(array('ДАТА', 'В ОСНОВНОЙ ДОЛГ', 'ПРОЦЕНТЫ', 'ОСТАТОК', 'ПЛАТЁЖ'));
	$stmt = $db->prepare("SELECT date, base_debt, `int`, remainder, base_debt+`int` payment
			      FROM sched_line
		              WHERE sched_id = (
				      SELECT MAX(`id`) 
			      	      FROM sched
			              WHERE date = (
				     	     SELECT MAX(`date`)
	                                     FROM sched
	                                     WHERE base_debt_acc = ?)
                              AND base_debt_acc = ?)
                              ORDER BY date");
        $stmt->execute(array($acc_id, $acc_id));	
	while ($row = $stmt->fetch()){
		oftTable::row(array($row['date'], $row['base_debt'], $row['int'], $row['remainder'], $row['payment']));
	}
	oftTable::end();
?>
</body>
</html> 
