<?php
	include_once 'iScreen.php';

	class GraphViewer implements iScreen {
		function __construct (
			private $login,
			private $pdo,
		) {}

		private function echo_styles() {
			echo <<< _END
			<style>
				.prbar {
					background-color: #4CAF50;
					border: 1px solid #4CAF50;
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

		private function echo_progress_bar($row, $max) {
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

		private function echo_last_N ($N) {
			$query = "SELECT _money, _finish FROM " . $this->login . "DailyData ORDER BY _finish DESC LIMIT " . $N;
			$result = $this->pdo->query($query)->fetchall();
			$max = 0;
			foreach ($result as $row) {if ($row['_money'] > $max) $max = $row['_money']; }
			$this->echo_styles();
			foreach ($result as $row) $this->echo_progress_bar($row, $max);
		}

		function view() { $this->echo_last_N(8); }
	}
?>