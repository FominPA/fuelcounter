<?php
	include_once 'UserSet.php';
	include_once 'SQLPublicRoots.php';
	include_once 'GraphViewer.php';
	include_once 'MonthViewer.php';

	class StatController {

		function __construct (
			private $login,
			private $pdo,
		) {}

		function set_state($state) { 
			$this->pdo->query('UPDATE FCUsers SET statstate = "' . $state . '" WHERE login = "' . $this->login . '"');
		}

		function get_state() { return $this->pdo->query("SELECT * FROM FCUsers WHERE login = '" . $this->login . "'")->fetch()['statstate']; }
		function echo_controller() { if (isset($_GET['statstate'])) { $this->set_state($_GET['statstate']); } }

		function echo_screen() {
		    $this->echo_controller();
			echo '<a href="?statstate=graph">График</a> | <a href="?statstate=month">Календарь</a> <br/>';
			switch ($this->get_state()) {
				case 'graph': $StatViewer = new GraphViewer($this->login, $this->pdo); break;
				case 'month': $StatViewer = new MonthViewer($this->login, $this->pdo); break; 
				default: $this->set_state('graph'); break;
			} $StatViewer->view();
		}
	} $CurStat = new StatController($CurUserSet->login, $SQLLoader->pdo);
?>