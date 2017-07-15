<?php
//session_start();
include 'connect.php';
$cr_acc = isset($_GET['cr_acc'])?$_GET['cr_acc']:null;
$deb_acc = isset($_GET['deb_acc'])?$_GET['deb_acc']:null;
if(is_null($cr_acc)){
	$acc_id = $deb_acc;
	$uch_acc_id = "ofv_provodki_cred_acc_id";
	$title = "Расход";
}else{
	$acc_id = $cr_acc;
	$uch_acc_id = "ofv_provodki_deb_acc_id";
	$title = "Приход";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title><?=$title?></title>
</head>
<body>
<script src="dates.js"></script>	
<script>

function saveEnt(){
	var arr_err = [];
	
	document.getElementById("ofv_provodki_exec_date_cor").value = 
		form_and_check_dat(document.getElementById("ofv_provodki_exec_date").value, arr_err);
    if(arr_err.length>0){
		alert("Ошибка в дате: "+arr_err[0]);
		document.getElementById("ofv_provodki_exec_date").focus();
		document.getElementById("ofv_provodki_exec_date").select();
		return false;
    }
    return true;
}

</script>	
<table style="page-break-before: always;" width="1000" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="index.php">Участники</a>
	<!--td align="left"><a href="acc_list.php?uch_id=<//?=$uch_id?//>">Счета</a!-->
<?php if ($acc_id!=null) { ?>
	<td align="left"><a href="ent_list.php?acc_id=<?=$acc_id?>">Движения по счету</a>
<?php } ?>
</table>
<br/>
<form id="main_form" action="ent_save.php" method="post">
<table border="0" cellpadding="0" cellspacing="2">
            
<tr><td>Дата<td><input id="ofv_provodki_exec_date"  name="ofv_provodki_exec_date" size="8" type="text" 
 maxlength="8" 
onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
        
<tr><td>Сумма<td><input id="ofv_provodki_summa"  name="ofv_provodki_summa" size="30" type="text">             

<tr><td>Участник<td><select name="<?=$uch_acc_id?>" id="<?=$uch_acc_id?>">
            
<?php
	$stmt = $db->prepare(
		is_null($cr_acc)?
		"SELECT acc.id, uch.name
		FROM fund
		  JOIN acc ON fund.expenditure_acc_type_id = acc.type_id
		  JOIN uch ON acc.uch_id = uch.id
		WHERE fund.acc_id = ?
		ORDER BY uch.name"	
		:
		"SELECT acc.id, uch.name
		FROM fund
		  JOIN acc ON fund.donation_acc_type_id = acc.type_id
		  JOIN uch ON acc.uch_id = uch.id
		WHERE fund.acc_id = ?
		ORDER BY uch.name"
	);
	$stmt->execute(array($acc_id));
	while($row = $stmt->fetch()){
         echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }             
?>
      
<tr><td>Назначение<td><input id="ofv_provodki_purpose"  name="ofv_provodki_purpose" size="30" type="text">  
      	           
</table>

<br><input value="Сохранить" type="submit"  onclick="return saveEnt();">

<input type="hidden" id="oper_type" name="oper_type" value="update">
<input type="hidden" id="acc_id" name="acc_id" value="<?=$acc_id?>">

<?php if (is_null($cr_acc)) { ?>
<input type="hidden" id="ofv_provodki_deb_acc_id" name="ofv_provodki_deb_acc_id" value="<?=$deb_acc?>">
<?php } else { ?>
<input type="hidden" id="ofv_provodki_cred_acc_id" name="ofv_provodki_cred_acc_id" value="<?=$cr_acc?>">
<?php } ?>

<input type="hidden" id="ofv_provodki_exec_date_cor" name="ofv_provodki_exec_date_cor">

</form>
</body>
</html> 
