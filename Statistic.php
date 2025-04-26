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

		function echo_tabs() {
			echo <<< END
			<style>
				.statTabs {
					border-bottom: 2px solid #4CAF50;
					margin-bottom: 5px;
					display: inline-block;
				}

				.statTab {
					display: inline-block;
					font-family: sans-serif;
					margin: 0;
					margin-right: 5px;
					padding: 5px 20px;
					cursor: pointer;
					background-color: #E8F5E9;
					font-size: 80%;
					color: #000;
				}

				.statTab.last {
					margin-right: 0;
				}

				.statTab.active {
					color: #E8F5E9;
					background-color: #4CAF50;
				}
			</style>


			<div class="statTabs">
				<a href="?statstate=graph"><div class="statTab">График</div></a><!-- 
				 --><a href="?statstate=month"><div class="statTab last">Календарь</div></a>
			</div><br>
			END;
		}

		function echo_screen() {
		    $this->echo_controller();
			// echo '<a href="?statstate=graph">График</a> | <a href="?statstate=month">Календарь</a> <br/>';
			$this->echo_tabs();
			switch ($this->get_state()) {
				case 'graph': $StatViewer = new GraphViewer($this->login, $this->pdo); break;
				case 'month': $StatViewer = new MonthViewer($this->login, $this->pdo); break; 
				default: $this->set_state('graph'); break;
			} $StatViewer->view();
		}
	} $CurStat = new StatController($CurUserSet->login, $SQLLoader->pdo);
?>