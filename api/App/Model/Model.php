<?php
namespace App\Model;
use PDO;
class Model{
	use Builder;

	protected  	$class;
	public 	$mode 		= array(
		"driver" 	=> "mysql",
		"host"		=> "138.97.220.62",
		"dbname"	=> "aetub",
		"user"		=> "root",
		"pass"		=> "Supereng"
		);

	public function save(){
		$this->loadTable();
		$sql  = Builder::makeInsert($this->class);
		$con  = new ConnectionFactory($this->mode);
		$db   = $con->getInstance();
		$stmt = $db->prepare(Builder::$sql);
		return $stmt->execute();
	}	

	/**
	*	Cria select da conforme o objeto da classe que chamou o metodo;
	*	EX: se o Objeto tem um campo nome e este campo esta com valor João ele vai fazer um select 
	*	na coluna nome = João caso vazio $arr ele tras um SELECT * FROM $tabela
	*/
	public function select($arr = null){
		$this->loadTable();
		Builder::$condition = $arr;
		Builder::makeSelect($this->class);
		$con                = new ConnectionFactory($this->mode);
		$db                 = $con->getInstance();
		$linha              = $db->query(Builder::$sql);
		$r                  = $linha->fetch(PDO::FETCH_ASSOC);
		return $r;

	}

	/**
	*	Atualiza informação conforme o objeto que chamou está função
	*/
	public function update(){
		$this->loadTable();
		$this->class->updateAt = $_SERVER["REQUEST_TIME"];
		Builder::makeUpdate($this->class);
		$con                   = new ConnectionFactory($this->mode);
		$db                    = $con->getInstance();
		$linha                 = $db->execute(Builder::$sql);
		$r                     = $linha->fetch(PDO::FETCH_ASSOC);
		return $r;
	}

	/**
	*	Carrega a Tabela na variavel dentro da trait Builder::$table 
	*/
	public function loadTable(){
		Builder::$table = str_replace("\\", "/", strtolower(get_called_class()));
		Builder::$table = explode('/', Builder::$table);
		Builder::$table = Builder::$table[3];	
	}
}