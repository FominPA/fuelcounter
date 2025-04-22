<?php
	include_once 'UserSet.php';
	include_once 'SQLPublicRoots.php';

	class Statistic {
		function __construct (
			private $user,
			private $pdouser,
		) {}

		function get_rows () {
			$query = "SELECT _money, _finish FROM " . $this->user->login . "DailyData ORDER BY _finishDESC";
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
 	} $CurStatistic = new Statistic($CurUserSet, $SQLLoader);
?>