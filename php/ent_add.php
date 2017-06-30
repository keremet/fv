<?php
//session_start();
include 'connect.php';
$id = null;
if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$stmt = $db->prepare("
		SELECT to_char(exec_date, 'ddmmyyyy') as exec_date, deb_acc_id, cred_acc_id, summa, purpose
		FROM provodki
		WHERE id = ?");
	$stmt->execute(array($id));
	$ent = $stmt->fetch();
} else {
    $cr_acc = isset($_GET['cr_acc'])?$_GET['cr_acc']:null;
    $deb_acc = isset($_GET['deb_acc'])?$_GET['deb_acc']:null;
    
}
$acc_id = isset($_GET['acc_id'])?$_GET['acc_id']:null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Проводка</title>
</head>
<body>
<script src="dates.js"></script>	
<script>
function checkDelEnt(){
	if(confirm('Действительно удалить проводку?')){
		document.getElementById('oper_type').value='delete';
		return true;
	}
	return false;
}

function saveEnt(){
	var arr_err = [];
	
	document.getElementById("ofv_provodki_exec_date_cor").value = 
		form_and_check_std_dat(document.getElementById("ofv_provodki_exec_date").value, arr_err);
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
value="<?=(($id!=null)?$ent['exec_date']:'')?>" maxlength="8" 
onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
        
<tr><td>Сумма<td><input id="ofv_provodki_summa"  name="ofv_provodki_summa" size="30" type="text" 
value="<?=(($id!=null)?$ent['summa']:'')?>">             

<tr><td>Счёт списания<td><input type=text <?=(isset($_GET['deb_acc']))?'readonly':''?> 
id="ofv_provodki_deb_acc_id" name="ofv_provodki_deb_acc_id" value="<?=(($id!=null)?$ent['deb_acc_id']:$deb_acc)?>">
               
<tr><td>Счёт поступления<td><input type=text <?=(isset($_GET['cr_acc']))?'readonly':''?> 
id="ofv_provodki_deb_acc_id" name="ofv_provodki_cred_acc_id" value="<?=(($id!=null)?$ent['cred_acc_id']:$cr_acc)?>">
      
<tr><td>Назначение<td><input id="ofv_provodki_purpose"  name="ofv_provodki_purpose" size="30" type="text" 
value="<?=(($id!=null)?$ent['purpose']:'')?>">  
      	           
</table>

<br><input value="Сохранить" type="submit"  onclick="return saveEnt();">

<?php if ($id!=null) { ?>
<input value="Удалить" type="submit" onclick="return checkDelEnt();">
<?php }?>

<input type="hidden" id="oper_type" name="oper_type" value="update">
<input type="hidden" id="acc_id" name="acc_id" value="<?=$acc_id?>">
<input type="hidden" id="ent_id" name="ent_id" value="<?=$id?>">
<input type="hidden" id="ofv_provodki_exec_date_cor" name="ofv_provodki_exec_date_cor">

</form>
</body>
</html> 
