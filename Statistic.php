<?php
	include_once 'UserSet.php';
	include_once 'SQLPublicRoots.php';

	class Statistic {
		function __construct (
			private $user,
			private $pdouser,
		) {}

		function get_rows () {
			$query = "SELECT _money, _finish FROM " . $this->user->login . "DailyData ORDER BY _finish DESC";
			$result = $this->pdouser->pdo->query($query)->fetchall();
			return $result;
		}

		function echo_all () {
			$result = $this->get_rows();
			foreach ($result as $row) {
				$month = substr($row['_finish'], 5, 2);
				$day = substr($row['_finish'], 8, 2);
				echo sprintf('%s.%s числа - %.2f руб. <br/>', $day, $month, $row['_money']);
			}
		}

		function echo_pr_Styles() {
			echo <<< _END
			<style>
				.prbar {
					background-color: #66ff00;
					border: 1px solid #66ff00;
					height: 100px;
					width: 7px;
					margin: auto;
				}

				.pr {
					background-color: #fff;
					width: 7px;
					margin: 0;
					padding: 0;
				}

				.day {
					display: inline-block;
					text-align: center;
				}
			</style>
			_END;
		}

		function echo_progress_bar($row, $max) {
			$month = substr($row['_finish'], 5, 2);
			$day = substr($row['_finish'], 8, 2);
			$date = sprintf('%s.%s', $day, $month);
			$money = sprintf('%.2f ₽', $row['_money']);
			$percent = 100 - (int) ($row['_money'] / $max * 100.00);

			echo <<< _END
				<div class="day">
				<div class="prbar"><div class="pr" style="height: $percent%;"></div></div>

				<p>$money<br/>$date</p>
			</div>
			_END;
		}

		function echo_last_seven () {
			$query = "SELECT _money, _finish FROM " . $this->user->login . "DailyData ORDER BY _finish DESC LIMIT 7";
			$result = $this->pdouser->pdo->query($query)->fetchall();
			
			$this->echo_pr_Styles();

			$max = 0;

			foreach ($result as $row) {if ($row['_money'] > $max) $max = $row['_money']; }

			foreach ($result as $row) $this->echo_progress_bar($row, $max);
		}
 	} $CurStatistic = new Statistic($CurUserSet, $SQLLoader);

 	#####################################################################################################################

	class MonthViewer {
		function __construct (
			private $login,
			private $pdo,
		) {}

		function get_all () {
			$result = $this->pdo->query('SELECT * FROM ' . $this->login . 'DailyData ORDER BY _finish')->fetchall();
			$money = null;
			foreach ($result as $row) {
				$month = (int) substr($row['_finish'], 5, 2);
				$day = (int) substr($row['_finish'], 8, 2);
				$value = sprintf('%.2f ₽', $row['_money']);
				$money[$month][$day] = $value;
			}

			return $money;
		}

		function echo_styles() {
			echo <<< END
				<style>
					.day {
						display: inline-block;
						text-align: center;
						padding: 3px 2px;
						width: 93px;
					}

					.day:hover {
						border: 1px solid grey;
						padding: 2px 1px;
						cursor: pointer;
					}

					.dayHeader {
						width: 30px;
						text-align: center;
						vertical-align: middle;
						padding: 6px 0;
						background-color: #66ff00;
						color: white;
						border-radius: 50% 50% 0 0;
						margin: auto;
					}

					.dayValue {
						border: 1px solid #66ff00;
						border-radius: 50px;
						padding: 2px 5px;
					}

					.firstWeek { text-align: right; }

					.month { display: inline-block; }

				</style>
			END;
		}

		function echo_month ($month) {
			$monthStart = [0, 3, 6, 6, 2, 4, 7, 2, 5, 1, 3, 6, 1]; // День недели с которго начинается N месяц
			$monthMax = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]; // Количество дней в N месяце
			$this->echo_styles(); // Стили для календаря
			$money = $this->get_all(); // Все отработанные дни

			echo "<div class='month'><div class='firstWeek'>";
			for (
				$day = 1, $dotw = $monthStart[$month];
				$day <= $monthMax[$month];
				$day++, $dotw++
			) {
			    $value = $money[$month][$day];
			    $value = ($value === null) ? 0 : $value;
				echo <<< END
					<div class='day'>
						<div class='dayHeader'>$day</div>
						<div class='dayValue'>$value</div>
					</div>
				END;
				if ($dotw == 7) {
					$dotw = 0;
					echo "</div><div class='week'>";
				} 
			}
			echo "</div></div>";
		}

		function echo_view() { $this->echo_month(4); }
	}

 	#####################################################################################################################

 	class GraphViewer {
 		function __construct (
 			private $login,
 			private $pdo,
 		) {}

 		function echo_styles() {
			echo <<< _END
			<style>
				.prbar {
					background-color: #66ff00;
					border: 1px solid #66ff00;
					height: 100px;
					width: 7px;
					margin: auto;
				}

				.pr {
					background-color: #fff;
					width: 7px;
					margin: 0;
					padding: 0;
				}

				.prday {
					display: inline-block;
					text-align: center;
				}
			</style>
			_END;
		}

 		function echo_progress_bar($row, $max) {
			$month = substr($row['_finish'], 5, 2);
			$day = substr($row['_finish'], 8, 2);
			$date = sprintf('%s.%s', $day, $month);
			$money = sprintf('%.2f ₽', $row['_money']);
			$percent = 100 - (int) ($row['_money'] / $max * 100.00);

			echo <<< _END
				<div class="prday">
				<div class="prbar"><div class="pr" style="height: $percent%;"></div></div>

				<p>$money<br/>$date</p>
			</div>
			_END;
		}

 		function echo_last_six () {
 			$query = "SELECT _money, _finish FROM " . $this->login . "DailyData ORDER BY _finish DESC LIMIT 6";
			$result = $this->pdo->query($query)->fetchall();
			$max = 0;
			foreach ($result as $row) {if ($row['_money'] > $max) $max = $row['_money']; }
			$this->echo_styles();
			foreach ($result as $row) $this->echo_progress_bar($row, $max);
 		}

 		function echo_view() { $this->echo_last_six(); }
 	}

 	#####################################################################################################################


 	include_once 'UserSet.php';
 	include_once 'SQLPublicRoots.php';
 	// include_once 'GraphViewer.php';
 	// include_once 'MonthViewer.php';

 	class StatController {

 		function __construct (
 			private $login,
 			private $pdo,
 		) {}

 		function set_state($state) { 
 			$this->pdo->query('UPDATE FCUsers SET statstate = "' . $state . '" WHERE login = "' . $this->login . '"'); 
            # echo("<meta http-equiv='refresh' content='1'>");
 		}

 		function get_state() { return $this->pdo->query("SELECT * FROM FCUsers WHERE login = '" . $this->login . "'")->fetch()['statstate']; }
 		function echo_controller() { if (isset($_GET['statstate'])) { $this->set_state($_GET['statstate']); } }

 		function echo_screen() {
 		    $this->echo_controller();
 			echo '<a href="?statstate=graph">График</a> | <a href="?statstate=month">Календарь</a> <br/>';
 			switch ($this->get_state()) {
 				case 'graph':
 					$StatViewer = new GraphViewer($this->login, $this->pdo);
 					$StatViewer->echo_view();
 					break;

 				case 'month':
 					$StatViewer = new MonthViewer($this->login, $this->pdo);
 					$StatViewer->echo_view();
 					break;
 				
 				default:
 					$this->set_state('graph');
 					break;
 			}

 			$StatViewer->echo_screen();
 		}
 	} $CurStat = new StatController($CurUserSet->login, $SQLLoader->pdo);
?>