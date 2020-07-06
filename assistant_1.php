<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("tags", "Prototype 1");
$APPLICATION->SetPageProperty("keywords", "Prototype 1");
$APPLICATION->SetPageProperty("description", "Prototype 1");
$APPLICATION->SetPageProperty("title", "Prototype 1");
$APPLICATION->SetTitle("Prototype 1"); 
?>

<!-- ////////////////////////////////////////////////////////////////////////////////////////// -->

<style>
table.report-table, th.report-th, td.report-td {
  border: 1px solid black;
  border-collapse: collapse;
  margin: 4px;
  padding: 4px;
  text-align: left;
}
th.report-th {
	background-color: grey;
	color: white;
}
p.fio-p {
	font-size: 20px;
	color: #008B8B;
}
p.warning-p {
	font-size: 16px;
	color: red;
}
</style>

<?

$currentUserId = CUser::GetID();

$sqltext = "
	SELECT 
	CONCAT(b_user.LAST_NAME,' ', b_user.NAME,' ', b_user.SECOND_NAME) as 'ФИО'
	FROM
	b_user
	WHERE
	b_user.ID = '".$currentUserId."'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		echo '<p class="fio-p">'.$row['ФИО'].'<hr>';
	}

$sqltext = "
	SELECT
	b_user.ID as 'ID',
	COUNT(DISTINCT b_crm_act.OWNER_ID) as 'Кол-во забытых клиентов по направлениию - Мониторинг'
	FROM
	b_crm_act, b_crm_deal, b_user
	WHERE
	b_crm_act.COMPLETED = 'N'
	AND
	b_crm_act.DEADLINE BETWEEN '2000-01-01 00-00-00' and NOW()
	AND
	b_crm_act.RESPONSIBLE_ID = '".$currentUserId."'
	AND
	b_user.ID = b_crm_act.RESPONSIBLE_ID
	AND
	b_crm_act.OWNER_ID = b_crm_deal.ID
	AND
	b_crm_deal.CATEGORY_ID = '20'
	AND
	b_crm_deal.STAGE_SEMANTIC_ID = 'P'
	GROUP BY
	b_crm_act.RESPONSIBLE_ID
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		echo '<p class="warning-p">Всего забытых клиентов - '.$row['Кол-во забытых клиентов по направлениию - Мониторинг'].'</p>';
	}

echo '<table class="report-table"><tr class="report-tr">';
echo '<th class="report-th">ID сделки</th>';
echo '<th class="report-th">Клиент / ссылка на сделку</th></tr>';

$sqltext = "
	SELECT DISTINCT 
	b_crm_act.OWNER_ID as 'ID сделки',
	b_crm_deal.TITLE as 'Клиент'
	FROM
	b_crm_act, b_crm_deal, b_user
	WHERE
	b_crm_act.COMPLETED = 'N'
	AND
	b_crm_act.DEADLINE BETWEEN '2000-01-01 00-00-00' and NOW()
	AND
	b_crm_act.RESPONSIBLE_ID = '".$currentUserId."'
	AND
	b_user.ID = b_crm_act.RESPONSIBLE_ID
	AND
	b_crm_act.OWNER_ID = b_crm_deal.ID
	AND
	b_crm_deal.CATEGORY_ID = '20'
	AND
	b_crm_deal.STAGE_SEMANTIC_ID = 'P'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		echo '<tr class="report-tr"><td class="report-td">'.$row['ID сделки'].'</td>';
		echo '<td class="report-td"><a href="https://bitrix.ved-ug.ru/crm/deal/details/'.$row['ID сделки'].'/" target="_blank" title="откроется в новом окне">'.$row['Клиент'].'</a></td></tr>';
	}

echo '</table><hr>';

$sqltext = "
	SELECT
	b_user.ID as 'ID',
	COUNT(DISTINCT b_crm_act.OWNER_ID) as 'Кол-во дел без крайнего срока по направлению - Мониторинг'
	FROM
	b_crm_act, b_crm_deal, b_user
	WHERE
	b_crm_act.COMPLETED = 'N'
	AND
	b_crm_act.DEADLINE='9999-12-31 00-00-00'
	AND
	b_crm_act.RESPONSIBLE_ID = '.$currentUserId.'
	AND
	b_user.ID = b_crm_act.RESPONSIBLE_ID
	AND
	b_crm_act.OWNER_ID = b_crm_deal.ID
	AND
	b_crm_deal.CATEGORY_ID = '20'
	AND
	b_crm_deal.STAGE_SEMANTIC_ID = 'P'
	GROUP BY
	b_crm_act.RESPONSIBLE_ID
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		echo '<p class="warning-p">Всего сделок, с делами в которых не установлен крайний срок - '.$row['Кол-во дел без крайнего срока по направлению - Мониторинг'].'</p>';
	}

echo '<table class="report-table"><tr class="report-tr">';
echo '<th class="report-th">ID сделки</th>';
echo '<th class="report-th">Клиент / ссылка на сделку</th></tr>';

$sqltext = "
	SELECT DISTINCT 
	b_crm_act.OWNER_ID as 'ID сделки',
	b_crm_deal.TITLE as 'Клиент'
	FROM
	b_crm_act, b_crm_deal, b_user
	WHERE
	b_crm_act.COMPLETED = 'N'
	AND
	b_crm_act.DEADLINE='9999-12-31 00-00-00'
	AND
	b_crm_act.RESPONSIBLE_ID = '.$currentUserId.'
	AND
	b_user.ID = b_crm_act.RESPONSIBLE_ID
	AND
	b_crm_act.OWNER_ID = b_crm_deal.ID
	AND
	b_crm_deal.CATEGORY_ID = '20'
	AND
	b_crm_deal.STAGE_SEMANTIC_ID = 'P'
	";

	$results = $DB->Query($sqltext);
	while ($row = $results->Fetch())
	{
		echo '<tr class="report-tr"><td class="report-td">'.$row['ID сделки'].'</td>';
		echo '<td class="report-td"><a href="https://bitrix.ved-ug.ru/crm/deal/details/'.$row['ID сделки'].'/" target="_blank" title="откроется в новом окне">'.$row['Клиент'].'</a></td></tr>';
	}

echo '</table>';

?>

<!-- ////////////////////////////////////////////////////////////////////////////////////////// -->

<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>