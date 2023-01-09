<?php
include 'menu.php';
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$res = $mysqli->query("SELECT DATE_FORMAT(DT, '%d.%m.%Y') AS DTF, SCRIPT_NAME, COUNT(DT) as CDT
FROM statistics
GROUP BY DATE_FORMAT(DT, '%d.%m.%Y')
ORDER BY DTF DESC;");
echo '<div class="form-control div-okrugl">';
echo '<div class="div-okrugl"><details><summary>Посещения по дням</summary><table class="table table-bordered table-striped">';
$ar_stat = array();
$ar_stat[] = ['dtf', 'cdt'];
foreach ($res as $row) {
    echo "<tr><td>" . $row['DTF'] . "</td><td>" . $row['CDT'] . "</td></tr>";
    $ar_stat[] = [$row['DTF'], (int) $row['CDT']];
}
echo "</table></details></div>";
$jsonTable = json_encode($ar_stat);
echo '
<div class="flex-container">
	<div class="form-control div-okrugl">
		<div id="myChart"   class="split right" style="width:100%; height:300px;" ></div>
	</div>
	<div class="form-control div-okrugl">
		<div id="myChart3"  class="split right" style="width:100%; height:300px;" ></div>
	</div>
	<div class="form-control div-okrugl">
		<div id="myChart2"  class="split right" style="width:100%; height:300px;" ></div>
	</div>
</div>
';
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<div class="flex-container">';
echo '<div class="div-okrugl">
<h6>Всего с IP</h6>';

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$res = $mysqli->query("
SELECT REMOTE_ADDR, COUNT(statistics.DT) as CDT
FROM statistics		
GROUP BY REMOTE_ADDR
ORDER BY COUNT(statistics.DT) DESC");
		echo '
		<details><summary><b>Всего посещений с IP</b></summary>
		<table class="table table-bordered table-striped">';
		$ip_per = '';
		foreach ($res as $row) {
			if ($ip_per != $row['REMOTE_ADDR']) {
				echo "<tr><td style='text-align: center'><b>" . $row['REMOTE_ADDR'] . " - </b>";
				$ip_per = $row['REMOTE_ADDR'];
			}
			echo $row['CDT'] . "</td></tr>";
		}
		echo "</table></details>";

$res = $mysqli->query("SELECT
statistics.REMOTE_ADDR,
statistics.SCRIPT_NAME,
COUNT(statistics.DT) as CDT
FROM
statistics
GROUP BY
statistics.REMOTE_ADDR,
statistics.SCRIPT_NAME
ORDER BY
statistics.REMOTE_ADDR,COUNT(statistics.DT) DESC ");

$flag = 0;
$ip_per = '';
foreach ($res as $row) {
    if (($ip_per != $row['REMOTE_ADDR'])) {
        if ($flag == 1) {
            $flag = 0;
            echo '</details>';
        }
        echo "<details><summary>" . $row['REMOTE_ADDR'] . "</summary>";
        $ip_per = $row['REMOTE_ADDR'];
        $flag = 1;
    }
    echo "<a href='http://$$$$/" . $row['SCRIPT_NAME'] . "' target='_blank'>" . $row['SCRIPT_NAME'] . "</a>  -  <i>" . $row['CDT'] . "</i><br>";
}
echo "</div>";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$res = $mysqli->query("SELECT
statistics.REMOTE_ADDR,
statistics.SCRIPT_NAME,
COUNT(statistics.DT) as CDT
FROM
statistics
WHERE DATEDIFF (NOW(),statistics.DT)=0
GROUP BY
statistics.REMOTE_ADDR,
statistics.SCRIPT_NAME
ORDER BY
statistics.REMOTE_ADDR DESC ");
echo '<div class="div-okrugl">
<h6>C IP за сегодня</h6>';		
$flag = 0;
$ip_per = '';
foreach ($res as $row) {
    if (($ip_per != $row['REMOTE_ADDR'])) {
        if ($flag == 1) {
            $flag = 0;
            echo '</details>';
        }
        echo "<details><summary>" . $row['REMOTE_ADDR'] . "</summary>";
        $ip_per = $row['REMOTE_ADDR'];
        $flag = 1;
    }
    echo "<a href='http://$$$$/" . $row['SCRIPT_NAME'] . "' target='_blank'>" . $row['SCRIPT_NAME'] . "</a>  -  <i>" . $row['CDT'] . "</i><br>";
}
echo "</div>";







////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$res = $mysqli->query("SELECT
statistics.SCRIPT_NAME,
COUNT(statistics.DT) as CDT
FROM statistics
GROUP BY
statistics.SCRIPT_NAME
ORDER BY
COUNT(statistics.DT) DESC");
echo '<div class="div-okrugl"><h6>Посещения страниц</h6><table class="table table-bordered table-striped">';
$ar_stat = array();
$ar_stat[] = ['sn', 'cdt'];

foreach ($res as $row) {
    echo "<tr><td><a href='http://10.230.4.245/" . $row['SCRIPT_NAME'] . "' target='_blank'>" . $row['SCRIPT_NAME'] . "</a>  -  <i>" . $row['CDT'] . "</i></td></tr>";
    $ar_stat[] = [$row['SCRIPT_NAME'], (int) $row['CDT']];
}
echo '</table></details></div>';
$jsonTable2 = json_encode($ar_stat);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$res = $mysqli->query("SELECT COUNT(DISTINCT REMOTE_ADDR) as CDT,
MONTH (DT) as M,
DAY (DT) as D
FROM statistics
GROUP BY MONTH (DT),DAY (DT)");
$ar_stat = array();
$ar_stat[] = ['md', 'cdt'];
foreach ($res as $row) {
    $ar_stat[] = [$row['D'] . '.' . $row['M'], (int) $row['CDT']];
}
$jsonTable3 = json_encode($ar_stat);

echo "</table></div></div>";
$mysqli->close();
include 'footer.php';
?>



<script>
	google.charts.load('current', {
		packages: ['corechart']
	});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = google.visualization.arrayToDataTable(<?=$jsonTable?>);
		var options = {
			title: 'Посещения по дням',
			curveType: 'function',
			legend: 'none',
			lineWidth: 5,
			colors: ['#e2431e'],
			crosshair: {
				selected: {
					color: '#3bc',
					opacity: 0.8
				}
			},

			chartArea: {
				backgroundColor: {
					fill: '#464646'
				},
			},
			backgroundColor: {
				fill: '#6385A1'
			},
		};
		var chart = new google.visualization.ColumnChart(document.getElementById('myChart'));
		chart.draw(data, options);
	}
</script>

<script>
	google.charts.load('current', {
		packages: ['corechart']
	});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = google.visualization.arrayToDataTable(<?=$jsonTable2?>);
		var options = {
			title: 'Посещения страниц',
			curveType: 'function',
			lineWidth: 5,

			crosshair: {
				selected: {
					color: '#3bc',
					opacity: 0.8
				}
			},

			chartArea: {
				backgroundColor: {
					fill: '#464646'
				},
			},
			backgroundColor: {
				fill: '#6385A1'
			},
		};
		var chart = new google.visualization.PieChart(document.getElementById('myChart2'));
		chart.draw(data, options);
	}
</script>

<script>
	google.charts.load('current', {
		packages: ['corechart']
	});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = google.visualization.arrayToDataTable(<?=$jsonTable3?>);
		var options = {
			title: 'Посещения по дням (уникальных пользователей)',
			curveType: 'function',
			legend: 'none',
			lineWidth: 5,
			colors: ['#e2431e'],
			crosshair: {
				selected: {
					color: '#3bc',
					opacity: 0.8
				}
			},

			chartArea: {
				backgroundColor: {
					fill: '#464646'
				},
			},
			backgroundColor: {
				fill: '#6385A1'
			},
		};
		var chart = new google.visualization.ColumnChart(document.getElementById('myChart3'));
		chart.draw(data, options);
	}
</script>