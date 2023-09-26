<?php
require_once "./session.php";
$titleHtml = 'Forktex';
require_once "head.php";
if ($isActiv){
	echo "<p style = \"text-align: right;\"><a href=\"./\">Main Page</a></p>";
		echo "<p style = \"text-align: right;\"><a href=\"./signout.php\">LogOut</a></p>";
}
//echo $_GET['category'];

$attributeName='';
$search  = array('A0000', 'A000', 'A00', 'A0', 'A');
$replace = '';
if (isset($_GET["category"]) && ($_GET["category"]!='')){
	$categoryGetId = trim($_GET["category"]);
	if (!$result = getSelect(
				$dbcon, 
				["attribute_id","attribute_mainname","attribute_subname","color", "display"], 
				'attributes_vorteks', 
				'category', 
				$categoryGetId,
				'ORDER BY `attribute_position` ASC'
				)){
		//strike();
	}
}else{
		strike();
}

//var_dump($result);

foreach ($result as $value) {
	    if (($attributeName != $value['attribute_mainname']) &&($value['attribute_mainname'] != Null)){
	    	$attributeName = $value['attribute_mainname'];
	    	//$color = ($value['color']=='grey')?:"";
	    	$category[] = array (
            "attributeName" => $attributeName,
	    	"attributeID" => "B000000",
	    	"attributeColour" => ($value['color']=='grey')?"grey":"none",
	    	);
	    }
	    if ($value['attribute_mainname'] != Null){
		    $category[] = array (
		    "attributeName" => $value['attribute_subname'],
		    "attributeID" => $value['attribute_id'],
		    "attributeColour" => $value['color'],
		    );
		   $categoryAtribut []  = $value['attribute_id'];
		    //array_push($categoryAtribut,$value['attribute_id']  => "");
		    //$categoryAtribut[] = $value['attribute_id'];
	    }
}
  
/*if (!$result = getSelectAtrVal(
				$dbcon, 
				'`attribute_id`,`attribute_value`', 
				'attributes_values',  
				'attribute_id', 
				$categoryAtribut
				))	{
		strike();
	}*/


if (!$result = getSelectAtrVal3(
	$dbcon, 
				['attribute_id', 'attribute_value'], 
				'attributes_values',  
				'attribute_id', 
				$categoryAtribut
				))	{
		strike();
	}
//print_r($result);
foreach ($result as $value) {
     $atrValue[$value['attribute_id']][] = $value['attribute_value'];
    
    //$res1[$key]=$value;
    //print_r($value);
    //foreach ($preValue as $key => $value) {
    //$res1[$key]=$value;
        
        
    //}
}
//print_r($atrValue);
//echo "----------------\n";
//echo $key;
//}//
?>
<button onclick="hide_g()">Hide grey</button>
<button onclick="hide_c()">Show colour</button>
<button onclick="show()">Show all</button>
<script>
function hide_g() {
    var myClasses = document.querySelectorAll('.colour-grey'),
        i = 0,
        l = myClasses.length;

    for (i; i < l; i++) {
        myClasses[i].style.display = 'none';
    }
}
function show() {
    var myClasses = document.querySelectorAll('.colour-grey'),
        i = 0,
        l = myClasses.length;

    for (i; i < l; i++) {
        myClasses[i].style.display = 'table-row';
       // alert("1");
    }
}
</script>
<table class="catTabl">
	

<?php
        $j=0;
        $ind=-1;
	    foreach ($category as $value) {
	    	if ($value['attributeID'] != "B000000"){
	 		$attributeID = str_replace($search, $replace, $value['attributeID']);
	    //echo "<tr class='".$value['class']."'>".
	    $i=0; $j++;$ind *=-1;
	    	echo "<tr class = 'trchng".$ind." colour-".$value['attributeColour']."'>"
    	        ."<td class = 'atrname'>".$value['attributeName']."</td><td>";
    	    if (isset($atrValue[$value['attributeID']])) {	
    	            echo "<select class = 'atrselect custom-select' name='select".$j."'>";
    	    
    	    	foreach ($atrValue[$value['attributeID']] as $aValue) {
    	    	    $i++;
    	    	    echo "<option value='value".$i."'>".$aValue."</option>";
    	    	}
    	    echo "</select></td>";}
    	    	echo "</td><td class = 'smalltext'>".$value['attributeID']."</td>"
    	    	."<td><a href='http://vxwiki.cnetcontentsolutions.com/?alias=cc.ds.template."
    	    	    .$categoryGetId.":cc.ds.attr."
    	    	    .$attributeID."' target='_blank' rel='noopener noreferrer'><img class='helpimg' src='./img/help.png' alt='Help'></a></td>"
	    	."</tr>";} 
	    	else {
	    		echo "<tr class = 'MainCat'><td>".$value['attributeName']."</td></tr>";
	    	}

	    }

	    ?>	 
</table>

<br><br><br>
 
<? require_once "futter.php";?>
