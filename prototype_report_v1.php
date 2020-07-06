<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("tags", "Prototype 1");
$APPLICATION->SetPageProperty("keywords", "Prototype 1");
$APPLICATION->SetPageProperty("description", "Prototype 1");
$APPLICATION->SetPageProperty("title", "Prototype 1");
$APPLICATION->SetTitle("Prototype 1"); 
?>

<!-- ////////////////////////////////////////////////////////////////////////////////////////// -->

<script src="//www.amcharts.com/lib/4/core.js"></script>
<script src="//www.amcharts.com/lib/4/charts.js"></script>

<style>
table.report{
	  border: 1px solid black;
	  border-collapse: collapse;
	  text-align: center;
}
tr.report-row{
	
}
th.report-title-one{
	width: 200px;
	border: 1px solid black;
	border-collapse: collapse;
	padding: 4px;
}
td.normal-number{
	width: 100px;
	height: 24px;
	background-color: #99ccff;
	border: 1px solid black;
	border-collapse: collapse;
	font-size: 16px;
	padding: 4px;
}
td.success-number{
	width: 100px;
	height: 24px;
	background-color: #ccffcc;
	border: 1px solid black;
	border-collapse: collapse;
	font-size: 16px;
	padding: 4px;
}
td.failure-number{
	width: 100px;
	height: 24px;
	background-color: #ff9999;
	border: 1px solid black;
	border-collapse: collapse;
	font-size: 16px;
	padding: 4px;
}
td.error{
	color: red;
}
td.chart-1, td.chart-2, td.chart-3, td.chart-4{
	width: 700px;
	heigth: 600px;
}
td.space-td, tr.space-tr{
	width: 50px;
	heigth: 50px;
}


</style>

<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
	<table>
		<tr>
			<td>
				<label for="date_1">Дата начала: </label>
				<input type="date" name="date_1" value="<? echo $date_1 ?>"/>
			</td>
			<td>
				<label for="date_2">Дата окончания: </label>
				<input type="date" name="date_2" value="<? echo $date_2 ?>"/>
			</td>
			<td>
				<button type="submit">Сформировать отчет</button>
			</td>

<?

if(!isset($date_1)){$date_1 = date('Y-m-d');}
if(!isset($date_2)){$date_2 = date('Y-m-d');}

$time_1 = ' 00-00-00';
$time_2 = ' 23-59-59';

$manager_1 = 619;
$manager_2 = 1021;
$manager_3 = 1210;
$manager_4 = 1281;
$manager_5 = 1283;
$manager_6 = 1296;
$manager_7 = 1480;

$date_1_seconds = strtotime($date_1);
$date_2_seconds = strtotime($date_2);

$total_row_count = ($date_2_seconds-$date_1_seconds)/60/60/24+1;
$number_of_days = $total_row_count;

if ($date_1_seconds > $date_2_seconds){
		echo '<td class="error">'.'Неверно указаны даты отбора для отчета'.'</td>'.'</tr>'.'</table>'.'</form>'; 
		die();
	} else {
		echo '</tr>'.'</table>'.'</form>';
	}

?>
<hr>
<table class="charts">
	<tr class="row-1">
		<td class="chart-1">ЛИДы<div id="chart-1"></div></td>
		<td class="space-td"></td>
		<td class="chart-2">Сделки "Новые клиенты"<div id="chart-2"></div></td>
	</tr>
	<tr class="space-tr"></td>
	<tr class="row-2">
		<td class="chart-3">Сделки "Телемарктеинг"<div id="chart-3"></div></td>
		<td class="space-td"></td>
		<td class="chart-4">Сделки "Производство"<div id="chart-4"></div></td>
	</tr>
</table>
<hr>

<table class="report">
	<tr class="report-title">
		<th class="report-title-one">№</th>
		<th class="report-title-one">Дата</th>
		<th class="report-title-one">Создано ЛИДов</th>
		<th class="report-title-one">Целевые ЛИДы</th>
		<th class="report-title-one">Забракованные ЛИДы</th>
		<th class="report-title-one">Успех "Телемаркетинг"</th>
		<th class="report-title-one">Брак "Телемаркетинг"</th>
		<th class="report-title-one">Создано "Новые клиенты"</th>
		<th class="report-title-one">Успех "Новые клиенты"</th>
		<th class="report-title-one">Брак "Новые клиенты"</th>
		<th class="report-title-one">Конверсия: создано"НК" / успех "НК"</th>
		<th class="report-title-one">Создано "Производство"</th>
		<th class="report-title-one">Успех "Производство"</th>
		<th class="report-title-one">Сумма успех "Производство"</th>
	</tr>
<?

$count_1 = 0;
$count_2 = 0;
$count_3 = 0;
$count_4 = 0;
$count_5 = 0;
$count_6 = 0;
$count_7 = 0;
$count_8 = 0;
$count_9 = 0;
$count_10 = 0;
$count_11 = 0;
$count_12 = 0;

$total_count_1 = 0;
$total_count_2 = 0;
$total_count_3 = 0;
$total_count_4 = 0;
$total_count_5 = 0;
$total_count_6 = 0;
$total_count_7 = 0;
$total_count_8 = 0;
$total_count_9 = 0;
$total_count_10 = 0;
$total_count_11 = 0;
$total_count_12 = 0;

$order_number = 1;

while ($total_row_count > 0){
	echo '<tr class="report-row">'.'<td class="normal-number">'.$order_number.'</td>'.'<td class="normal-number">';
	$date_number = date('Y.m.d', $date_1_seconds);
	echo $date_number.'</td>'.'<td class="normal-number">';



	$sqltext = "
	select ID from b_crm_lead where 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_1."' and DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_2."' and DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_3."' and DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_4."' and DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_5."' and DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_6."' and DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_7."' and DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_1 = $count_1 + 1;
	}
	echo $count_1.'</td><td class="success-number">';
	$total_count_1 = $total_count_1 + $count_1;



	$sqltext = "
	select ID from b_crm_lead where 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_1."' and STATUS_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_2."' and STATUS_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_3."' and STATUS_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_4."' and STATUS_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_5."' and STATUS_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_6."' and STATUS_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_7."' and STATUS_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_2 = $count_2 + 1;
	}
	echo $count_2.'</td><td class="failure-number">';
	$total_count_2 = $total_count_2 + $count_2;



	$sqltext = "
	select ID from b_crm_lead where 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_1."' and STATUS_SEMANTIC_ID='F' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_2."' and STATUS_SEMANTIC_ID='F' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_3."' and STATUS_SEMANTIC_ID='F' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_4."' and STATUS_SEMANTIC_ID='F' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_5."' and STATUS_SEMANTIC_ID='F' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_6."' and STATUS_SEMANTIC_ID='F' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_7."' and STATUS_SEMANTIC_ID='F' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_3 = $count_3 + 1;
	}
	echo $count_3.'</td><td class="success-number">';
	$total_count_3 = $total_count_3 + $count_3;



	$sqltext = "
	select ID from b_crm_deal where 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_1."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_2."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_3."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_4."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_5."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_6."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_7."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_4 = $count_4 + 1;
	}
	echo $count_4.'</td><td class="failure-number">';
	$total_count_4 = $total_count_4 + $count_4;



	$sqltext = "
	select ID from b_crm_deal where 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_1."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_2."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_3."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_4."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_5."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_6."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_7."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='19' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_5 = $count_5 + 1;
	}
	echo $count_5.'</td><td class="normal-number">';
	$total_count_5 = $total_count_5 + $count_5;



	$sqltext = "
	select ID from b_crm_deal where 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_1."' and CATEGORY_ID='0' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_2."' and CATEGORY_ID='0' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_3."' and CATEGORY_ID='0' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_4."' and CATEGORY_ID='0' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_5."' and CATEGORY_ID='0' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_6."' and CATEGORY_ID='0' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_7."' and CATEGORY_ID='0' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_6 = $count_6 + 1;
	}
	echo $count_6.'</td><td class="success-number">';
	$total_count_6 = $total_count_6 + $count_6;



	$sqltext = "
	select ID from b_crm_deal where 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_1."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_2."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_3."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_4."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_5."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_6."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_7."' and STAGE_SEMANTIC_ID='S' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_7 = $count_7 + 1;
	}
	echo $count_7.'</td><td class="failure-number">';
	$total_count_7 = $total_count_7 + $count_7;



	$sqltext = "
	select ID from b_crm_deal where 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_1."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_2."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_3."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_4."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_5."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_6."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	SOURCE_ID!=12 and SOURCE_ID!=4 and ASSIGNED_BY_ID='".$manager_7."' and STAGE_SEMANTIC_ID='F' and CATEGORY_ID='0' and CLOSED='Y' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_8 = $count_8 + 1;
	}
	echo $count_8.'</td><td class="normal-number">';
	$total_count_8 = $total_count_8 + $count_8;
	


	$count_9 = ($count_7 / $count_6) * 100;
	$count_9 = round($count_9, 0);
	if ($count_7 == 0 or $count_6 == 0)
	{
		echo '0'.' %'.'</td><td class="normal-number">';
	} else {
	echo $count_9.' %'.'</td><td class="normal-number">';
	}

	$total_count_9 = ($total_count_7 / $total_count_6) * 100;
	$total_count_9 = round($total_count_9, 0);
	


	$sqltext = "
	select ID from b_crm_deal where 
	CREATED_BY_ID='".$manager_1."' and CATEGORY_ID='15' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_2."' and CATEGORY_ID='15' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_3."' and CATEGORY_ID='15' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_4."' and CATEGORY_ID='15' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_5."' and CATEGORY_ID='15' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_6."' and CATEGORY_ID='15' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_7."' and CATEGORY_ID='15' and 
	DATE_CREATE between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_10 = $count_10 + 1;
	}
	echo $count_10.'</td><td class="success-number">';
	$total_count_10 = $total_count_10 + $count_10;



	$sqltext = "
	select ID from b_crm_deal where 
	CREATED_BY_ID='".$manager_1."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_2."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_3."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_4."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_5."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_6."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_7."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_11 = $count_11 + 1;
	}
	echo $count_11.'</td><td class="success-number">';
	$total_count_11 = $total_count_11 + $count_11;



	$sqltext = "
	select OPPORTUNITY as money from b_crm_deal where 
	CREATED_BY_ID='".$manager_1."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_2."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_3."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_4."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_5."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_6."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."' or 
	CREATED_BY_ID='".$manager_7."' and CATEGORY_ID='15' and STAGE_SEMANTIC_ID='S' and 
	DATE_MODIFY between '".$date_number.$time_1."' and '".$date_number.$time_2."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		$count_12 = $count_12 + $row[money];
	}
	echo number_format($count_12,0,' ',' ').'</td>';
	$total_count_12 = $total_count_12 + $count_12;



	$count_1 = 0;
	$count_2 = 0;
	$count_3 = 0;
	$count_4 = 0;
	$count_5 = 0;
	$count_6 = 0;
	$count_7 = 0;
	$count_8 = 0;
	$count_9 = 0;
	$count_10 = 0;
	$count_11 = 0;
	$count_12 = 0;

	$order_number = $order_number + 1;
	$total_row_count = $total_row_count - 1;
	$date_1_seconds = $date_1_seconds + 86400;
}

echo 
'</tr>'.'<tr class="report-row">'.
'<th class="report-title-one">'.'ИТОГО ВСЕГО'.'</th>'.
'<th class="report-title-one">'.'Отчет за '.$number_of_days.' дней'.'</th>'.
'<th class="report-title-one">'.$total_count_1.'</th>'.
'<th class="report-title-one">'.$total_count_2.'</th>'.
'<th class="report-title-one">'.$total_count_3.'</th>'.
'<th class="report-title-one">'.$total_count_4.'</th>'.
'<th class="report-title-one">'.$total_count_5.'</th>'.
'<th class="report-title-one">'.$total_count_6.'</th>'.
'<th class="report-title-one">'.$total_count_7.'</th>'.
'<th class="report-title-one">'.$total_count_8.'</th>'.
'<th class="report-title-one">'.$total_count_9.' %'.'</th>'.
'<th class="report-title-one">'.$total_count_10.'</th>'.
'<th class="report-title-one">'.$total_count_11.'</th>'.
'<th class="report-title-one">'.number_format($total_count_12,0,' ',' ').' рублей'.'</th>'.
'</tr>'.'</table>';

?>

<script>
// chart-1
var chart = am4core.create("chart-1", am4charts.PieChart);

chart.data = [{
  "country": "<?echo 'Создано'?>",
  "litres": <?echo $total_count_1?>,
  "color": am4core.color("#99ccff")
}, {
  "country": "<?echo 'Успех'?>",
  "litres": <?echo $total_count_2?>,
  "color": am4core.color("#ccffcc")
}, {
  "country": "<?echo 'Брак'?>",
  "litres": <?echo $total_count_3?>,
  "color": am4core.color("#ff9999")
}];

var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "litres";
pieSeries.dataFields.category = "country";
pieSeries.slices.template.propertyFields.fill = "color";

chart.innerRadius = am4core.percent(50);


// chart-2
var chart = am4core.create("chart-2", am4charts.PieChart);

chart.data = [{
  "country": "<?echo 'Создано'?>",
  "litres": <?echo $total_count_6?>,
  "color": am4core.color("#99ccff")
}, {
  "country": "<?echo 'Успех'?>",
  "litres": <?echo $total_count_7?>,
  "color": am4core.color("#ccffcc")
}, {
  "country": "<?echo 'Брак'?>",
  "litres": <?echo $total_count_8?>,
  "color": am4core.color("#ff9999")
}];

var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "litres";
pieSeries.dataFields.category = "country";
pieSeries.slices.template.propertyFields.fill = "color";

chart.innerRadius = am4core.percent(50);


// chart-3
var chart = am4core.create("chart-3", am4charts.PieChart);

chart.data = [{
  "country": "<?echo 'Создано'?>",
  "litres": <?echo $total_count_2?>,
  "color": am4core.color("#99ccff")
}, {
  "country": "<?echo 'Успех'?>",
  "litres": <?echo $total_count_4?>,
  "color": am4core.color("#ccffcc")
}, {
  "country": "<?echo 'Брак'?>",
  "litres": <?echo $total_count_5?>,
  "color": am4core.color("#ff9999")
}];

var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "litres";
pieSeries.dataFields.category = "country";
pieSeries.slices.template.propertyFields.fill = "color";

chart.innerRadius = am4core.percent(50);


// chart-4
var chart = am4core.create("chart-4", am4charts.PieChart);

chart.data = [{
  "country": "<?echo 'Создано'?>",
  "litres": <?echo $total_count_10?>,
  "color": am4core.color("#99ccff")
}, {
  "country": "<?echo 'Успех'?>",
  "litres": <?echo $total_count_11?>,
  "color": am4core.color("#ccffcc")
}, {
  "country": "<?echo 'Брак'?>",
  "litres": <?echo ($total_count_10 - $total_count_11)?>,
  "color": am4core.color("#ff9999")
}];

var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "litres";
pieSeries.dataFields.category = "country";
pieSeries.slices.template.propertyFields.fill = "color";

chart.innerRadius = am4core.percent(50);
</script>

<!-- ////////////////////////////////////////////////////////////////////////////////////////// -->

<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>