<?php
if(file_exists('countries.php')) include_once( 'countries.php' );
$data = array();
$data['name'] = 'all';
$data['label'] = 'Open UN-Habitat  Transparency Initiative';
$data['amount'] = $_COUNTRY_BUDGETS['all'];
$data['children'] = array();
foreach($_COUNTRY_BUDGETS AS $iso=>$budget) {
	if($iso=='all') continue;
	$child['name'] = $iso;
	$child['label'] = $_COUNTRY_ISO_MAP[$iso];
	$child['amount'] = $budget;
	$child['color'] = generateRandomColor();
	$data['children'][] = $child;
}
echo json_encode($data);

function generateRandomColor(){
    $randomcolor = '#' . strtoupper(dechex(rand(0,10000000)));
    if (strlen($randomcolor) != 7){
        $randomcolor = str_pad($randomcolor, 10, '0', STR_PAD_RIGHT);
        $randomcolor = substr($randomcolor,0,7);
    }
return $randomcolor;
}
?>