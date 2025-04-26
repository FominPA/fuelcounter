<?php	
	class MonthViewer implements iScreen {
		function __construct (
			private $login,
			private $pdo,
		) {}

		private function get_all () {
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

		private function echo_styles() {
			echo <<< END
				<style>
					.day {
						display: inline-block;
						text-align: center;
						padding: 3px 2px;
						width: 93px;
					}

					.day:hover {
						padding: 2px 1px;
						border: 1px solid grey;
						cursor: pointer;
					}

					.dayHeader {
						width: 30px;
						text-align: center;
						vertical-align: middle;
						padding: 6px 0;
						background-color: #4CAF50;
						color: white;
						// border-radius: 50% 50% 0 0;
						border-radius: 50%;
						margin: auto;
					}

					.dayValue {
						// border: 1px solid #4CAF50;
						// border-radius: 50px;
						padding: 2px 5px;
					}

					.firstWeek { text-align: right; }

					.month { display: inline-block; }

				</style>
			END;
		}

		private function echo_month ($month) {
			$monthStart = [0, 3, 6, 6, 2, 4, 7, 2, 5, 1, 3, 6, 1]; // День недели с которго начинается N месяц 2024 года
			$monthMax = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]; // Количество дней в N месяце 2024 года
			$this->echo_styles(); // Стили для календаря
			$money = $this->get_all(); // Все отработанные дни

			echo "<div class='month'><div class='firstWeek week'>";
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

		function view() { $this->echo_month(4); }
	}
?>