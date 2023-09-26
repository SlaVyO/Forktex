<?php
require_once "./session.php";
//$category = [];
$fst = "";
$sec = "";
$forClass = 1;
$titleHtml = 'ForkTekx';
require_once "head.php";
if ($isActiv){
	echo "<p style = \"text-align: right;\"><a href=\"./signout.php\">Выход</a></p><br><br>";
}

if (!$result = getSelectAll($dbcon, '*', 'group_vorteks')){ 
	strike();
}

//var_dump($result);

foreach ($result as $value) {
	    if (($fst != $value['group']) && ($value['subgroup'] == "0")){
	    	$category[] = array (
            "fst" => $value['group'],
	    	"sec" => $value['group_name'],
	    	"srd" => "",
	    	"url" =>"http://vxwiki.cnetcontentsolutions.com/index.php?title=Category:".ucfirst($value['group_name']),
            "class" => "MainCat");
	    	$fst = $value['group'];
            $forClass = -1;
	    }
	    else {
            $sec  = $value['group'];
            $sec .= $value['subgroup'];
            $category[]= array (
	    	"fst" => "",
	    	"sec" => $sec,
	    	"srd" => $value['group_name'],
	    	"url" =>"http://vxwiki.cnetcontentsolutions.com/?alias=cc.ds.template.".$sec,
             "class" => "Cat".$forClass);
                $forClass *= -1;
	    }
}
	    
//var_dump($category);
///http://vxwiki.cnetcontentsolutions.com/index.php?title=Category:Audio_-_Car_Audio
//http://vxwiki.cnetcontentsolutions.com/?alias=cc.ds.template.KB:cc.ds.attr.3754
?>

<table class="mainboard">
	
<?php
	    foreach ($category as $value) {
	    echo "<tr class='".$value['class']."'>".
	    "<td>".$value['fst']."</td>"
	    	."<td>".$value['sec']."</td>"
	    	."<td><a href='category.php?category=".$value['sec']."'>".$value['srd']."</a></td>"
	    	."<td><a href='".$value['url']."' target='_blank' rel='noopener noreferrer'><img class='helpimg' src='./img/help.png' 
 alt='Help'></a></td>"
	    	."</tr>";
	    }

	    ?>	   

</table>
 
<?= require_once "futter.php";?>