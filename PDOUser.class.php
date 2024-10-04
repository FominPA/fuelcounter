<?php
	class PDOUser {
		public $attr, $pdo;
		public $chrs = 'utf8mb4';
		public $opts = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false
		];

		function __construct (
			public $host,
			public $data,
			public $user,
			public $pass,
		) { 
			$this->attr = "mysql:host=$host;dbname=$data;charset=$this->chrs"; 
			try { $this->pdo = new PDO($this->attr, $this->user, $this->pass, $this->opts); }
			catch (PDOException $e) { throw new PDOException($e->getMessage(), (int)$e->getCode()); }
		}
	}
?>