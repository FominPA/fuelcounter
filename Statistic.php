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
			$money = sprintf('%.2f руб.', $row['_money']);
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

			$max = 0;

			foreach ($result as $row) {if ($row['_money'] > $max) $max = $row['_money']; }

			foreach ($result as $row) $this->echo_progress_bar($row, $max);
		}
 	} $CurStatistic = new Statistic($CurUserSet, $SQLLoader);
?>