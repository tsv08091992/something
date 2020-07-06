<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("tags", "Отчет по CRM");
$APPLICATION->SetPageProperty("keywords", "Отчет по CRM");
$APPLICATION->SetPageProperty("description", "Отчет по CRM");
$APPLICATION->SetPageProperty("title", "Отчет по CRM");
$APPLICATION->SetTitle("Отчет по CRM"); 
?>

<!-- ////////////////////////////////////////////////////////////////////////////////////////// -->

<style>

td.main-report-td-form{
  vertical-align: top
}
td.main-report-td-chart{
  width: 100%;
  height: 600px;
}
#chartdiv{
    width: 100%;
    height: 500px;
    max-width: 100%;
}

</style>

<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<?

// create input form for report - START
$sqltext = "
SELECT 
LAST_NAME as 'Фамилия', NAME as 'Имя', SECOND_NAME as 'Отчество', ID as 'ID'
FROM 
b_user
WHERE 
ACTIVE='Y' 
AND 
LID='s1'
ORDER BY LAST_NAME
";

echo '
<table class="main-report-table">
<tr class="main-report-tr">
<td class="main-report-td-form">
<form action="" method="POST">
<table class="form-report-table">
<tr class="form-report-tr">
<td class="form-report-td">
<label for="users">Выберите пользователей:</label>
<p>
<select size="10" multiple="yes" name="selected-users[]">
';

$results = $DB->Query($sqltext);
while ($row = $results->Fetch())
{
  echo '<option value="'.$row['ID'].'">'.$row['Фамилия'].' '.$row['Имя'].' '.$row['Отчество'].'</option>';
}

echo '
</select>
<hr>
</p>
</td>
</tr>
<tr class="form-report-tr">
<td class="form-report-td">
<label for="users">Выберите метрику: </label>
<p>
<select size="5" multiple="yes" name="selected-counters[]">
<option value="0">Уникальные посещения</option>
<option value="1">Создано ЛИДов</option>
<option value="2">Целевые ЛИДы</option>
<option value="3">Забраковано ЛИДов</option>
<option value="4">Успех - Телемаркетинг</option>
<option value="5">Брак - Телемаркетинг</option>
<option value="6">Создано - Новые клиенты</option>
<option value="7">Успех - Новые клиенты</option>
<option value="8">Брак - Новые клиенты</option>
<option value="9">Создано - Производство</option>
<option value="10">Успех - Производство</option>
<option value="11">Сумма успехов - Производство</option>
</p>
</select>
<hr>
</td>
</tr>
<tr class="form-report-tr">
<td class="form-report-td">
<label for="users">Выберите период:</label>
</td>
</tr>
<tr class="form-report-tr">
<td class="form-report-td">
<input type="date" name="date_1" value="'.$date_1.'"/>
<label for="date_1">Дата начала</label>
</td>
</tr>
<tr class="form-report-tr">
<td class="form-report-td">
<input type="date" name="date_2" value="'.$date_2.'"/>
<label for="date_2">Дата окончания</label>
</td>
</tr>
<tr class="form-report-tr">
<td class="form-report-td">
<br>
<label for="users">Не учитывать источники: </label>
</td>
</tr>
<tr class="form-report-tr">
<td class="form-report-td">
<input type="checkbox" name="sources[]" value="Активные продажи">Активные продажи</p>
</td>
</tr>
<tr class="form-report-tr">
<td class="form-report-td">
<br>
<label for="users">Учитывать только: </label>
</td>
</tr>
<tr class="form-report-tr">
<td class="form-report-td">
<input type="checkbox" name="sources[]" value="SEO">SEO</p>
</td>
</tr>
<tr class="form-report-tr">
<td class="form-report-td">
<hr>
<button type="submit" name="select">Сформировать отчет</button>
</td>
</tr>
</table>
</form>
</td>
';
// create input form for report - FINISH

// days counter - every date put in array - START
$dates = array();

if(!isset($date_1)){$date_1 = date('Y-m-d');}
if(!isset($date_2)){$date_2 = date('Y-m-d');}

$date_1_seconds = strtotime($date_1);
$date_2_seconds = strtotime($date_2);

$time_1 = ' 00-00-00';
$time_2 = ' 23-59-59';

$days_quantity = ($date_2_seconds-$date_1_seconds)/60/60/24+1;

while ($days_quantity > 0){
  $current_date = date('Y.m.d', $date_1_seconds);
  $date_1_seconds = $date_1_seconds + 86400;
  array_push($dates, $current_date);
  $days_quantity = $days_quantity - 1;
}
// days counter - every date put in array - FINISH

// put selected user ids in array_1, put selected counters in array_2, put no need sources in array_3 - START 
$selectedUsersIdsSet       = array();
$selectedCountersSet       = array();
$selectedSourcesNeedSet    = array();

if(isset($_POST["select"])){
  $selectedUsers = $_POST['selected-users'];
    foreach($selectedUsers as $key => $value)
    {
        array_push($selectedUsersIdsSet, $value); // put selected user id in array
    }

  $selectedCounters = $_POST['selected-counters'];
    foreach($selectedCounters as $key => $value)
    {
        array_push($selectedCountersSet, $value); // put selected counter in array
    }
  $selectedSourcesNeed = $_POST['sources'];
    foreach ($selectedSourcesNeed as $key => $value) {
        array_push($selectedSourcesNeedSet, $value);
    }
}
// put selected user ids in array_1, put selected counters in array_2, put no need sources in array_3 - FINISH

// create pressets - START
if (!(in_array('Активные продажи', $selectedSourcesNeedSet))){
  $source_1 = "";
} else {
  $source_1 = "AND SOURCE_ID != '4'";
}

if (!(in_array('SEO', $selectedSourcesNeedSet))){
  $source_2 = "";
} else {
  $source_2 = "&filters=ym:s:trafficSource=='organic'";;
}
// create pressets - FINISH

// get yandex.metrika values - START
$ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api-metrika.yandex.ru/stat/v1/data?ids=52559167,26207739&metrics=ym%3As%3Ausers&dimensions=ym%3As%3Adate&date1=".$date_1."&date2=".$date_2.$source_2."&sort=ym%3As%3Adate&limit=100000&accuracy=full&pretty=true");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, 
        Array('Content-Type: text/xml','Authorization: OAuth {INPUT YOU METRIKA ID}}'));

    $data = curl_exec($ch);

    $json = json_decode($data, true);
// get yandex.metrika values - FINISH

// create array and result class - START
$result = array();

class CurrentResultDate {
  public $date;

  public function __construct($date) {
    $this->date = $date;
  }
}
// create array and result class - FINISH

// create total_counters variables - START
$total_count_1  = 0;
$total_count_2  = 0;
$total_count_3  = 0;
$total_count_4  = 0;
$total_count_5  = 0;
$total_count_6  = 0;
$total_count_7  = 0;
$total_count_8  = 0;
$total_count_9  = 0;
$total_count_10 = 0;
$total_count_11 = 0;
// create total_counters variables - FINISH

// collect data in objects and put them in array - START
$count_metrika = 0;

foreach ($dates as $key_date => $value_date) {

  $count = 0;

  $current_object = new CurrentResultDate($value_date); 

  // find $total_count_0 for current date - START
  if (in_array('0', $selectedCountersSet)) {
      $current_object->count_0 = $json["data"][$count_metrika]["metrics"][0];
      $count_metrika++;
  }
  // find $total_count_0 for current date - FINISH

  // find $total_count_1 for current date - START
  if (in_array('1', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_lead
    WHERE
    SOURCE_ID != '12'
    ".$source_1."
    AND
    ASSIGNED_BY_ID = '".$value_id."'
    AND
    DATE_CREATE
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_1 = $count;
  $total_count_1 = $total_count_1 + $count;
  $count = 0;
  }
  // find $total_count_1 for current date - FINISH

  // find $total_count_2 for current date - START
  if (in_array('2', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_deal
    WHERE
    SOURCE_ID != '12'
    ".$source_1."
    AND
    CREATED_BY_ID = '".$value_id."'
    AND
    CATEGORY_ID = '19'
    AND
    DATE_CREATE
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_2 = $count;
  $total_count_2 = $total_count_2 + $count;
  $count = 0;
  }
  // find $total_count_2 for current date - FINISH

  // find $total_count_3 for current date - START
  if (in_array('3', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_lead
    WHERE
    SOURCE_ID != '12'
    ".$source_1."
    AND
    ASSIGNED_BY_ID = '".$value_id."'
    AND
    STATUS_SEMANTIC_ID = 'F'
    AND
    DATE_CLOSED
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_3 = $count;
  $total_count_3 = $total_count_3 + $count;
  $count = 0;
  }
  // find $total_count_3 for current date - FINISH

  // find $total_count_4 for current date - START
  if (in_array('4', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_deal
    WHERE
    SOURCE_ID != '12'
    ".$source_1."
    AND
    ASSIGNED_BY_ID = '".$value_id."'
    AND
    STAGE_SEMANTIC_ID = 'S'
    AND
    CLOSED = 'Y'
    AND
    CATEGORY_ID = '19'
    AND
    DATE_MODIFY
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_4 = $count;
  $total_count_4 = $total_count_4 + $count;
  $count = 0;
  }
  // find $total_count_4 for current date - FINISH

  // find $total_count_5 for current date - START
  if (in_array('5', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_deal
    WHERE
    SOURCE_ID != '12'
    ".$source_1."
    AND
    ASSIGNED_BY_ID = '".$value_id."'
    AND
    STAGE_SEMANTIC_ID = 'F'
    AND
    CLOSED = 'Y'
    AND
    CATEGORY_ID = '19'
    AND
    DATE_MODIFY
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_5 = $count;
  $total_count_5 = $total_count_5 + $count;
  $count = 0;
  }
  // find $total_count_5 for current date - FINISH

  // find $total_count_6 for current date - START
  if (in_array('6', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_deal
    WHERE
    SOURCE_ID != '12'
    ".$source_1."
    AND
    ASSIGNED_BY_ID = '".$value_id."'
    AND
    CATEGORY_ID = '0'
    AND
    DATE_CREATE
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_6 = $count;
  $total_count_6 = $total_count_6 + $count;
  $count = 0;
  }
  // find $total_count_6 for current date - FINISH

  // find $total_count_7 for current date - START
  if (in_array('7', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_deal
    WHERE
    SOURCE_ID != '12'
    ".$source_1."
    AND
    ASSIGNED_BY_ID = '".$value_id."'
    AND
    STAGE_SEMANTIC_ID = 'S'
    AND
    CLOSED = 'Y'
    AND
    CATEGORY_ID = '0'
    AND
    DATE_MODIFY
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_7 = $count;
  $total_count_7 = $total_count_7 + $count;
  $count = 0;
  }
  // find $total_count_7 for current date - FINISH

  // find $total_count_8 for current date - START
  if (in_array('8', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_deal
    WHERE
    SOURCE_ID != '12'
    ".$source_1."
    AND
    ASSIGNED_BY_ID = '".$value_id."'
    AND
    STAGE_SEMANTIC_ID = 'F'
    AND
    CLOSED = 'Y'
    AND
    CATEGORY_ID = '0'
    AND
    DATE_MODIFY
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_8 = $count;
  $total_count_8 = $total_count_8 + $count;
  $count = 0;
  }
  // find $total_count_8 for current date - FINISH

  // find $total_count_9 for current date - START
  if (in_array('9', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_deal
    WHERE
    CREATED_BY_ID = '".$value_id."'
    AND
    CATEGORY_ID = '15'
    AND
    DATE_CREATE
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_9 = $count;
  $total_count_9 = $total_count_9 + $count;
  $count = 0;
  }
  // find $total_count_9 for current date - FINISH

  // find $total_count_10 for current date - START
  if (in_array('10', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    ID
    FROM
    b_crm_deal
    WHERE
    CREATED_BY_ID = '".$value_id."'
    AND
    STAGE_SEMANTIC_ID = 'S'
    AND
    CATEGORY_ID = '15'
    AND
    DATE_MODIFY
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + 1;
    }
  }
  $current_object->count_10 = $count;
  $total_count_10 = $total_count_10 + $count;
  $count = 0;
  }
  // find $total_count_10 for current date - FINISH

  // find $total_count_11 for current date - START
  if (in_array('11', $selectedCountersSet)) {
      foreach ($selectedUsersIdsSet as $key_id => $value_id) {
    $sqltext = "
    SELECT
    OPPORTUNITY as 'money'
    FROM
    b_crm_deal
    WHERE
    CREATED_BY_ID = '".$value_id."'
    AND
    STAGE_SEMANTIC_ID = 'S'
    AND
    CATEGORY_ID = '15'
    AND
    DATE_MODIFY
    BETWEEN
    '".$value_date.$time_1."' and '".$value_date.$time_2."'
    ";

    $results = $DB->Query($sqltext);
    while ($row = $results->Fetch())
    {
      $count = $count + $row['money'];
      number_format($count,0,'','');
    }
  }
  $current_object->count_11 = $count;
  $total_count_11 = $total_count_11 + $count;
  $count = 0;
  }
  // find $total_count_11 for current date - FINISH

  array_push($result, $current_object);

  $date_1_seconds = $date_1_seconds + 86400;

}


$json_result = json_encode($result);
// collect data in objects and put them in array - FINISH

echo '<td class="main-report-td-chart"><div id="chartdiv">'; // INPUT RIGHT CHART - START

?>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

// Add data
chart.data = <?echo $json_result?>;

// Set input format for the dates
chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

<?
if (in_array('0', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Уникальных посещений / '.$json["totals"][0].'";
  series.dataFields.valueY = "count_0";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_0}";
  series.fill = am4core.color("#8A2BE2");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#8A2BE2");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}

if (in_array('1', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Создано ЛИДов / '.$total_count_1.'";
  series.dataFields.valueY = "count_1";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_1}";
  series.fill = am4core.color("#99ccff");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#99ccff");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('2', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Целевые ЛИДы / '.$total_count_2.'";
  series.dataFields.valueY = "count_2";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_2}";
  series.fill = am4core.color("#006400");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#006400");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('3', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Забраковано ЛИДов / '.$total_count_3.'";
  series.dataFields.valueY = "count_3";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_3}";
  series.fill = am4core.color("#ff9999");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#ff9999");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('4', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Успех - Телемаркетинг / '.$total_count_4.'";
  series.dataFields.valueY = "count_4";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_4}";
  series.fill = am4core.color("#006400");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#006400");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('5', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Брак - Телемаркетинг / '.$total_count_5.'";
  series.dataFields.valueY = "count_5";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_5}";
  series.fill = am4core.color("#ff9999");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#ff9999");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('6', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Создано - Новые клиенты / '.$total_count_6.'";
  series.dataFields.valueY = "count_6";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_6}";
  series.fill = am4core.color("#99ccff");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#99ccff");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('7', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Успех - Новые клиенты / '.$total_count_7.'";
  series.dataFields.valueY = "count_7";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_7}";
  series.fill = am4core.color("#006400");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#006400");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('8', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Брак - Новые клиенты / '.$total_count_8.'";
  series.dataFields.valueY = "count_8";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_8}";
  series.fill = am4core.color("#ff9999");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#ff9999");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('9', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Создано - Производство / '.$total_count_9.'";
  series.dataFields.valueY = "count_9";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_9}";
  series.fill = am4core.color("#99ccff");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#99ccff");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('10', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Успех - Производство / '.$total_count_10.'";
  series.dataFields.valueY = "count_10";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_10}";
  series.stroke = am4core.color("#006400");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#006400");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
if (in_array('11', $selectedCountersSet)){
  echo '
  var series = chart.series.push(new am4charts.LineSeries());
  series.name = "Сумма успехов - Производство / '.$total_count_11.'";
  series.dataFields.valueY = "count_11";
  series.dataFields.dateX = "date";
  series.tooltipText = "{count_11}";
  series.stroke = am4core.color("#DAA520");
  series.strokeWidth = 2;
  series.stroke = am4core.color("#DAA520");
  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.strokeWidth = 2;
  bullet.circle.radius = 4;
  bullet.circle.fill = am4core.color("#fff");
  ';
}
?>

// Make a panning cursor
chart.cursor = new am4charts.XYCursor();
chart.cursor.behavior = "panXY";
chart.cursor.xAxis = dateAxis;
chart.cursor.snapToSeries = series;

// Create vertical scrollbar and place it before the value axis
chart.scrollbarY = new am4core.Scrollbar();
chart.scrollbarY.parent = chart.leftAxesContainer;
chart.scrollbarY.toBack();

// Create legend for chart
chart.legend = new am4charts.Legend();
chart.legend.position = "bottom";
chart.legend.scrollable = true;
chart.legend.itemContainers.template.events.on("over", function(event) {
  processOver(event.target.dataItem.dataContext);
})

chart.legend.itemContainers.template.events.on("out", function(event) {
  processOut(event.target.dataItem.dataContext);
})

function processOver(hoveredSeries) {
  hoveredSeries.toFront();

  hoveredSeries.segments.each(function(segment) {
    segment.setState("hover");
  })

  chart.series.each(function(series) {
    if (series != hoveredSeries) {
      series.segments.each(function(segment) {
        segment.setState("dimmed");
      })
      series.bulletsContainer.setState("dimmed");
    }
  });
}

function processOut(hoveredSeries) {
  chart.series.each(function(series) {
    series.segments.each(function(segment) {
      segment.setState("default");
    })
    series.bulletsContainer.setState("default");
  });
}

}); // end am4core.ready()
</script>

<?

echo '</div></td></tr><table>'; // INPUT RIGHT CHART - FINISH

//////////////// test //////////////////
//echo '<pre>';
//echo var_dump($selectedUsersIdsSet);
//echo '<hr>';
//echo var_dump($selectedCountersSet);
//echo '<hr>';
//echo var_dump($json_result);
//echo '</pre>';
//////////////// test //////////////////

?>

<!-- ////////////////////////////////////////////////////////////////////////////////////////// -->

<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>