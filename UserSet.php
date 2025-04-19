<?php 
	include_once 'Tax.php';

	class UserSet {
		// настройки пользователя
		function __construct (
			public $name,
			public $regime,
			public $login
		) {}
	} $CurUserSet = new UserSet('Pavel Fomin', 'night', 'PFomin');
?>