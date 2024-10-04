<?php 
	class UserSet {
		// настройки пользователя
		function __construct (
			public $name,
			public $regime,
			public $login
		) {}
	} $CurUserSet = new UserSet('Pavel Fomin', 'night', 'PFomin');

	class Tax {
		// тариф компании

		function calc_money($total, $regime) {
			switch ($regime) {
				case 'day':
						if ($total < 1000) {
							return $total * 3.5;
						} else if ($total < 1500) {
							return $total * 4;
						} else if ($total < 1800) {
							return $total * 4.5;
						} else {
							return $total * 4.7;
						}
					break;
				
				case 'night':
						if ($total < 1500) {
							return $total * 3.1;
						} else if ($total < 2000) {
							return $total * 3.6;
						} else if ($total < 2000) {
							return $total * 4.1;
						} else {
							return $total * 4.4;
						}
					break;
				
				default:
					return -1;
					break;
			}
		}
	} $CurTax = new Tax; 
?>