<?php 

function dbConnect($config){
	try {

		$host_opion = 'mysql:
    					host='    . $config['db']['host'].';
    					dbname='  . $config['db']['db_name'].';
    					charset=' . $config['db']['charset'];

    	$opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    	];

    	$pdo = new PDO(	$host_opion, 
    					$config['db']['username'], 
    					$config['db']['password'], 
    					$opt);


    	return $pdo;
    	
    	//$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
    //echo 'Ошибка: ' . $e->getMessage();
		return false;
	}
}



/*
function createtable ($dbcon, $table_name, array $filds_name, array $prim_key = array() ,array $uniq = array()){
	try {
		$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (";
		foreach ($filds_name as $key => $value) {
			$sql .= "`" . $key . "` " . $value.", ";
		}
		    
		if (!empty ($prim_key)){
			$sql .= "PRIMARY KEY (";
			foreach ($prim_key as $pk) {// переписать с implode
				$sql .= "`" . $pk . "`, ";
			}
			$sql  = substr($sql , 0, -2);
		    $sql .= "), ";
		}
		   
		if (!empty ($uniq)){
		    	
		   	foreach ($uniq as $un) {
		   		$sql .= "UNIQUE (`" . $un . "`), ";
		   	}
		   	//$sql  = substr($sql , 0, -2);
		}

		  	$sql  = substr($sql , 0, -2);
		  	$sql .= ")";
		    echo $sql;
		if ($sql != ""){
			if ($dbcon->exec($sql)){
				return true;		
			} 
		}
		 //   return $sql;
	} catch(PDOException $e) {
	    //echo 'Ошибка: ' . $e->getMessage();
		return false;
		}

}*/

function getSelect($dbcon, $fieldName, $tableName, $whereParam, $xzParam, $opionalParam = ''){
//($dbcon, ['id','password','email'], 'cn_user', 'username', $userInfo['username'], "LIMIT 1");
	try {
		
		$fieldNameStr =  "`" . implode('`,`', $fieldName ) . "`";
		//echo $fieldNameStr ;
		$data = $dbcon->prepare(
			"SELECT $fieldNameStr 
			FROM `$tableName` 
			WHERE `$whereParam` = :xzParam 
			$opionalParam");

		$data->bindParam(':xzParam' , $xzParam);
		$data->execute(); 
		$result = $data->fetchAll();

		//echo $data->rowCount();
		//echo "<br/><pre>";
		//$data->debugDumpParams();
		//echo "</pre><br/><br/>";

		return $result;
		
	} catch(PDOException $e) {
		echo 'Ошибка: ' . $e->getMessage();
		return false;
	}
}

//скрестить с обычной функцией селект
function getSelectAll($dbcon, $fieldName, $tableName, $opionalParam = '', array $whereParam = array() ){
	try {
		
		$execArray = [];
		
		if (!empty($whereParam)){
			$setWhereParam = '';
			foreach ($whereParam as $key => $value) {
				$setWhereParam .= $key." = :".str_replace(".", "_", $key).", ";

				$execArray[":".str_replace(".", "_", $key)] = $value;
			}
			$setWhereParam  = substr($setWhereParam , 0, -2);
		}
		$setWhereParam = isset($setWhereParam) ? "WHERE ".$setWhereParam : "";

		$sql = "SELECT $fieldName 
			FROM $tableName 
			$opionalParam
			$setWhereParam";
			
		$data = $dbcon->prepare($sql);
		$data->execute($execArray); 
		$result = $data->fetchAll();

		//echo "<br/><pre>"; $data->debugDumpParams(); echo "</pre><br/><br/>";
		return $result;
		
	} catch(PDOException $e) {
		echo 'Ошибка: ' . $e->getMessage();
		return false;
	}
}


//$ids = join("','",$galleries);   
//$sql = "SELECT * FROM galleries WHERE id IN ('$ids')";
function getSelectAtrVal($dbcon, $fieldName, $tableName, $whereName, array $whereParam){//array
	try {
		$preparedInValues = array_combine(
	    array_map(function($key) {
	       return ':var_'.$key;
	    }, array_keys($whereParam)),
	    array_values($whereParam)
	   );
		//print_r($preparedInValues);
  		$sql = "SELECT $fieldName FROM `$tableName` WHERE `$whereName` IN (".implode(',', array_keys($preparedInValues)).")";

  		//$data = $dbcon->prepare($sql);
		//$result = $data->execute($preparedInValues); 
		//$result = $data->fetchAll();
		//return $result;
		print_r($whereParam);
		print_r($sql);
		print_r($preparedInValues);
		return $sql;
		
		
	} catch(PDOException $e) {
		echo 'Ошибка: ' . $e->getMessage();
		return false;
	}
		
}


function getSelectAtrVal2($dbcon, $fieldName, $tableName, $whereParam, array $xzParam){
//($dbcon, ['id','password','email'], 'cn_user', 'username', $userInfo['username']);
	try {
		
		$fieldNameStr =  "`" . implode('`,`', $fieldName ) . "`";
		//$xzParamImpl = "`" . implode('`,`', $xzParam ) . "`";
		//echo $fieldNameStr ;
		//$data = $dbcon->prepare(
		//	"SELECT $fieldNameStr 
		//	FROM `$tableName` 
		//	WHERE `$whereParam` 
		//	IN (:xzParam) ");
		//echo $xzParamImpl;

		//$data->bindParam(':xzParam' , $xzParamImpl);
		//$data->execute(); 
		//$result = $data->fetchAll();

		//echo $data->rowCount();
		//echo "<br/><pre>";
		//$data->debugDumpParams();
		//echo "</pre><br/><br/>";
		return true;
		//return $result;
		
	} catch(PDOException $e) {
		echo 'Ошибка: ' . $e->getMessage();
		return false;
	}
}
function getSelectAtrVal3($dbcon, $fieldName, $tableName, $whereName, array $whereParam){
//($dbcon, ['id','password','email'], 'cn_user', 'username', $userInfo['username']);
	try {
		
		$fieldNameStr =  "`" . implode('`,`', $fieldName ) . "`";


		// other parameters that are going into query



$in = "";
$i = 0;// we are using an external counter 
// because the actual array key could be dangerous
foreach ($whereParam as $params)
{
    $key = ":id".$i++;
    $in .= "$key,";
    $in_params[$key] = $params; // collecting values into key-value array
}
$in = rtrim($in,","); // :id0,:id1,:id2

$sql = "SELECT $fieldNameStr FROM $tableName WHERE $whereName IN ($in)";
$stm = $dbcon->prepare($sql);
$stm->execute(array_merge($in_params)); // just merge two arrays
$data = $stm->fetchAll();

		//$xzParamImpl = "`" . implode('`,`', $xzParam ) . "`";
		//echo $fieldNameStr ;
		//$data = $dbcon->prepare(
		//	"SELECT $fieldNameStr 
		//	FROM `$tableName` 
		//	WHERE `$whereParam` 
		//	IN (:xzParam) ");
		//echo $xzParamImpl;

		//$data->bindParam(':xzParam' , $xzParamImpl);
		//$data->execute(); 
		//$result = $data->fetchAll();

		//echo $data->rowCount();
		//echo "<br/><pre>";
		//$data->debugDumpParams();
		//echo "</pre><br/><br/>";
		return $data;
		//return $result;
		
	} catch(PDOException $e) {
		echo 'Ошибка: ' . $e->getMessage();
		return false;
	}
}


/*
function insertInto ($dbcon,  $tableName, $valuesParam, $fieldNames = false, $opionalParam = ''){
	try {
		//разгрести $fieldNames

		if ($fieldNames){
			$fieldNameStr = '('.implode(',', array_keys($valuesParam)).') ';
		}
		$fieldNameStr = $fieldNameStr ?? "";
		$bindStr = ':'.implode(',:', array_keys($valuesParam));
		$sql  = "INSERT INTO 
				`$tableName` 
				$fieldNameStr 
				VALUES ( $bindStr )";
		$data = $dbcon->prepare($sql);
  		$data->execute(array_combine(explode(',',$bindStr), array_values($valuesParam)));
		//echo "<br/><pre>";
		
		//$data->debugDumpParams();
		//echo "</pre><br/><br/>";

		return true;
		
	} catch(PDOException $e) {
		//echo 'Ошибка: ' . $e->getMessage();
		return false;
	}
}*/

function letsUpdate($dbcon, $tableName, $fieldParam, $whereParam = array(), $opionalParam = ''){
	try {
		$execArray = [];
		$setFieldParam =' ';
		$setWhereParam = '';
		foreach ($fieldParam as $key => $value) {
			$setFieldParam .= "`".$key."` = :".$key.", ";
			$execArray[":".$key] = $value;
		}
		$setFieldParam  = substr($setFieldParam , 0, -2);

		if (!empty($whereParam)){
			foreach ($whereParam as $key => $value) {
				$setWhereParam .= "`".$key."` = :".$key.", ";
				$execArray[":".$key] = $value;
			}
			$setWhereParam  = substr($setWhereParam , 0, -2);
		}
		$setWhereParam = isset($setWhereParam) ? "WHERE ".$setWhereParam : "";

		
		$sql = "UPDATE `$tableName`
				SET $setFieldParam 
				$setWhereParam 
				$opionalParam";
		
		$data = $dbcon->prepare($sql);
		$data->execute($execArray);
		
		
		//echo "<br/><pre>";
		//$data->debugDumpParams();
		//echo "</pre><br/><br/>";

		return true;
		
	} catch(PDOException $e) {
		//echo 'Ошибка: ' . $e->getMessage();
		return false;
	}
}

/*
function deliteNow($dbcon, $tableName, $whereParam){
	try {
		$execArray = [];
		$setWhereParam = "";
		foreach ($whereParam as $key => $value) {
			$setWhereParam .= "`".$key."` = :".$key.", ";
			$execArray[":".$key] = $value;
		}
		$setWhereParam  = substr($setWhereParam , 0, -2);

		$sql = "DELETE FROM `$tableName`
				WHERE $setWhereParam";

	
		$data = $dbcon->prepare($sql);
		
		$data->execute($execArray);
		//echo "<br/><pre>";
		//$data->debugDumpParams();
		//echo "</pre><br/><br/>";
		return true;

		
	} catch(PDOException $e) {
		//echo 'Ошибка: ' . $e->getMessage();
		return false;
	}
	
}
*/

function strike(){
	$titleHtml = "Houston, we have a problem";
	require_once "head.php";
	echo '<p style = "text-align: center;">
  		Произошла ошибка ¯\_(ツ)_/¯ <br> <a href="./">Главная</a>';
	require_once "futter.php";	
	exit();
}

/*
function delArticleByUserId($dbcon, $fieldName, $tableName, $whereParam, $id, $option ){

	$result = getSelect($dbcon, $fieldName, $tableName, $whereParam, $id, $option);
	if ($result || empty ($result)) {
		if (count($result)!=0){
			//echo "у пользователя есть ".count($result)." статей";			
			if (deliteNow($dbcon, $tableName, [$whereParam => $id])){
				return true;
			} else { 
				return false; 
			}
		}else{
			return true;
		}	
	} else { 
		return false; 
	}
}
*/
function isSessionActive($dbcon){
	if (isset($_SESSION['auth_user']) && $_SESSION['auth_user']!=''){
		$result = getSelect($dbcon, ['username'], 'cn_user', 'id', $_SESSION['auth_user'], "LIMIT 1");
		if ($result || empty ($result)) {
			if (count($result)!=0){
				//echo "Пользователь с именем ".$result[0]['username']." существует";
				return true;		
			}	
		} else {
			return false;
		}
	}else{
		return false;
	}
}

function sessionExit(){
	//unset($_SESSION['auth_user']); 
	//unset($_SESSION); 
	session_unset();
	session_destroy();
}

function goBack(){
	//header("Location: ./index.php");
	header("Location: ./signin.php");
	
}

?>
