<?php
	class Tax {
		// тариф компании

		function calc_money($total, $regime) {
			switch ($regime) {
				case 'day':
						if ($total < 1000) {
							return $total * 4;
						} else if ($total < 1600) {
							return $total * 5;
						} else {
							return $total * 5.3;
						}
					break;
				
				case 'night':
						if ($total < 1500) {
							return $total * 3.7;
						} else if ($total < 2000) {
							return $total * 4.5;
						} else {
							return $total * 4.8;
						}
					break;
				
				default:
					return -1;
					break;
			}
		}
	} $CurTax = new Tax; 
?>