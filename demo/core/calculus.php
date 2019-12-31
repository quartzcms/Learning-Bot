<?php

$units = array(
	'chiffres','centimetre','metre','millimetre','kilometre','pied','pouce','once','millilitre','litre','metre-carre','centimetre-carre',
	'millimetre-carre','kilometre-carre','pied-carre','pouce-carre','metre-cubique','centimetre-cubique','millimetre-cubique','kilometre-cubique',
	'pied-cubique','pouce-cubique'
);
function all_units($quantity, $units){
	$results = array();
	if($units == 'pied'){
		$results['pied'] = $quantity;
		$results['pouce'] = $quantity * 12;
		$results['millimetre'] = $quantity * 304.8;
		$results['centimetre'] = $quantity * 30.48;
		$results['metre'] = $quantity / 3.281;
		$results['kilometre'] = $quantity / 3281;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow(($quantity), 2);
		$results['pouce-carre'] = pow(($quantity * 12), 2);
		$results['millimetre-carre'] = pow(($quantity * 304.8), 2);
		$results['centimetre-carre'] = pow(($quantity * 30.48), 2);
		$results['metre-carre'] = pow(($quantity / 3.281), 2);
		$results['kilometre-carre'] = pow(($quantity / 3281), 2);
		$results['pied-cubique'] = pow(($quantity), 3);
		$results['pouce-cubique'] = pow(($quantity * 12), 3);
		$results['millimetre-cubique'] = pow(($quantity * 304.8), 3);
		$results['centimetre-cubique'] = pow(($quantity * 30.48), 3);
		$results['metre-cubique'] = pow(($quantity / 3.281), 3);
		$results['kilometre-cubique'] = pow(($quantity / 3281), 3);
	} elseif($units == 'pouce'){
		$results['pied'] = $quantity / 12;
		$results['pouce'] = $quantity;
		$results['millimetre'] = $quantity * 25.4;
		$results['centimetre'] = $quantity * 2.54;
		$results['metre'] = $quantity / 39.37;
		$results['kilometre'] = $quantity / 39370;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow(($quantity / 12), 2);
		$results['pouce-carre'] = pow(($quantity), 2);
		$results['millimetre-carre'] = pow(($quantity * 25.4), 2);
		$results['centimetre-carre'] = pow(($quantity * 2.54), 2);
		$results['metre-carre'] = pow(($quantity / 39.37), 2);
		$results['kilometre-carre'] = pow(($quantity / 39370), 2);
		$results['pied-cubique'] = pow(($quantity / 12), 3);
		$results['pouce-cubique'] = pow(($quantity), 3);
		$results['millimetre-cubique'] = pow(($quantity * 25.4), 3);
		$results['centimetre-cubique'] = pow(($quantity * 2.54), 3);
		$results['metre-cubique'] = pow(($quantity / 39.37), 3);
		$results['kilometre-cubique'] = pow(($quantity / 39370), 3);
	} elseif($units == 'centimetre'){
		$results['pied'] = $quantity / 30.48;
		$results['pouce'] = $quantity / 2.54;
		$results['millimetre'] = $quantity * 10;
		$results['centimetre'] = $quantity;
		$results['metre'] = $quantity / 100;
		$results['kilometre'] = $quantity / 100000;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow(($quantity / 30.48), 2);
		$results['pouce-carre'] = pow(($quantity / 2.54), 2);
		$results['millimetre-carre'] = pow(($quantity * 10), 2);
		$results['centimetre-carre'] = pow(($quantity), 2);
		$results['metre-carre'] = pow(($quantity / 100), 2);
		$results['kilometre-carre'] = pow(($quantity / 100000), 2);
		$results['pied-cubique'] = pow(($quantity / 30.48), 3);
		$results['pouce-cubique'] = pow(($quantity / 2.54), 3);
		$results['millimetre-cubique'] = pow(($quantity * 10), 3);
		$results['centimetre-cubique'] = pow(($quantity), 3);
		$results['metre-cubique'] = pow(($quantity / 100), 3);
		$results['kilometre-cubique'] = pow(($quantity / 100000), 3);
	} elseif($units == 'metre') {
		$results['pied'] = $quantity * 3.281;
		$results['pouce'] = $quantity / 39.37;
		$results['millimetre'] = $quantity * 1000;
		$results['centimetre'] = $quantity * 100;
		$results['metre'] = $quantity;
		$results['kilometre'] = $quantity / 1000;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow(($quantity * 3.281), 2);
		$results['pouce-carre'] = pow(($quantity / 39.37), 2);
		$results['millimetre-carre'] = pow(($quantity * 1000), 2);
		$results['centimetre-carre'] = pow(($quantity * 100), 2);
		$results['metre-carre'] = pow(($quantity), 2);
		$results['kilometre-carre'] = pow(($quantity / 1000), 2);
		$results['pied-cubique'] = pow(($quantity * 3.281), 3);
		$results['pouce-cubique'] = pow(($quantity / 39.37), 3);
		$results['millimetre-cubique'] = pow(($quantity * 1000), 3);
		$results['centimetre-cubique'] = pow(($quantity * 100), 3);
		$results['metre-cubique'] = pow(($quantity), 3);
		$results['kilometre-cubique'] = pow(($quantity / 1000), 3);
	} elseif($units == 'millimetre') {
		$results['pied'] = $quantity / 304.8;
		$results['pouce'] = $quantity / 25.4;
		$results['millimetre'] = $quantity;
		$results['centimetre'] = $quantity / 10;
		$results['metre'] = $quantity / 1000;
		$results['kilometre'] = $quantity / 1000000;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow(($quantity / 304.8), 2);
		$results['pouce-carre'] = pow(($quantity / 25.4), 2);
		$results['millimetre-carre'] = pow(($quantity), 2);
		$results['centimetre-carre'] = pow((($quantity / 10)), 2);
		$results['metre-carre'] = pow((($quantity / 1000)), 2);
		$results['kilometre-carre'] = pow((($quantity / 1000000)), 2);
		$results['pied-cubique'] = pow(($quantity / 304.8), 3);
		$results['pouce-cubique'] = pow(($quantity / 25.4), 3);
		$results['millimetre-cubique'] = pow((($quantity)), 3);
		$results['centimetre-cubique'] = pow((($quantity / 10)), 3);
		$results['metre-cubique'] = pow((($quantity / 1000)), 3);
		$results['kilometre-cubique'] = pow((($quantity / 1000000)), 3);
	} elseif($units == 'kilometre') {
		$results['pied'] = $quantity * 3280.84;
		$results['pouce'] = $quantity * 39370.079;
		$results['millimetre'] = $quantity * 1000000;
		$results['centimetre'] = $quantity * 100000;
		$results['metre'] = $quantity * 1000;
		$results['kilometre'] = $quantity;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow(($quantity * 3280.84), 2);
		$results['pouce-carre'] = pow(($quantity * 39370.079), 2);
		$results['millimetre-carre'] = pow(($quantity * 1000000), 2);
		$results['centimetre-carre'] = pow((($quantity * 100000)), 2);
		$results['metre-carre'] = pow((($quantity * 1000)), 2);
		$results['kilometre-carre'] = pow((($quantity)), 2);
		$results['pied-cubique'] = pow(($quantity * 3280.84), 3);
		$results['pouce-cubique'] = pow(($quantity * 39370.079), 3);
		$results['millimetre-cubique'] = pow((($quantity * 1000000)), 3);
		$results['centimetre-cubique'] = pow((($quantity * 100000)), 3);
		$results['metre-cubique'] = pow((($quantity * 1000)), 3);
		$results['kilometre-cubique'] = pow((($quantity)), 3);
	} elseif($units == 'once') {
		$results['pied'] = 'NA';
		$results['pouce'] = 'NA';
		$results['millimetre'] = 'NA';
		$results['centimetre'] = 'NA';
		$results['metre'] = 'NA';
		$results['kilometre'] = 'NA';
		$results['once'] = $quantity;
		$results['millilitre'] = $quantity * 29.5735;
		$results['litre'] = $quantity / 33.814;
		$results['pied-carre'] = 'NA';
		$results['pouce-carre'] = 'NA';
		$results['millimetre-carre'] = 'NA';
		$results['centimetre-carre'] = 'NA';
		$results['metre-carre'] = 'NA';
		$results['kilometre-carre'] = 'NA';
		$results['pied-cubique'] = $quantity / 957.506;
		$results['pouce-cubique'] = $quantity * 1.805;
		$results['millimetre-cubique'] = $quantity * 29573.53;
		$results['centimetre-cubique'] = $quantity * 29.5735;
		$results['metre-cubique'] = $quantity / (2.95735 * pow(10, 5));
		$results['kilometre-cubique'] = $quantity / (2.95735 * pow(10, 14));
	} elseif($units == 'millilitre') {
		$results['pied'] = 'NA';
		$results['pouce'] = 'NA';
		$results['millimetre'] = 'NA';
		$results['centimetre'] = 'NA';
		$results['metre'] = 'NA';
		$results['kilometre'] = 'NA';
		$results['once'] = $quantity / 29.5735;
		$results['millilitre'] = $quantity;
		$results['litre'] = $quantity / 1000;
		$results['pied-carre'] = 'NA';
		$results['pouce-carre'] = 'NA';
		$results['millimetre-carre'] = 'NA';
		$results['centimetre-carre'] = 'NA';
		$results['metre-carre'] = 'NA';
		$results['kilometre-carre'] = 'NA';
		$results['pied-cubique'] = $quantity / 28316.847;
		$results['pouce-cubique'] = $quantity / 16.387;
		$results['millimetre-cubique'] = $quantity * 1000;
		$results['centimetre-cubique'] = $quantity;
		$results['metre-cubique'] = $quantity / (1 * pow(10, 6));
		$results['kilometre-cubique'] = $quantity / (1 * pow(10, 15));
	} elseif($units == 'litre') {
		$results['pied'] = 'NA';
		$results['pouce'] = 'NA';
		$results['millimetre'] = 'NA';
		$results['centimetre'] = 'NA';
		$results['metre'] = 'NA';
		$results['kilometre'] = 'NA';
		$results['once'] = $quantity * 33.814;
		$results['millilitre'] = $quantity * 1000;
		$results['litre'] = $quantity;
		$results['pied-carre'] = 'NA';
		$results['pouce-carre'] = 'NA';
		$results['millimetre-carre'] = 'NA';
		$results['centimetre-carre'] = 'NA';
		$results['metre-carre'] = 'NA';
		$results['kilometre-carre'] = 'NA';
		$results['pied-cubique'] = $quantity / 28.317;
		$results['pouce-cubique'] = $quantity * 61.024;
		$results['millimetre-cubique'] = $quantity * (1 * pow(10, 6));
		$results['centimetre-cubique'] = $quantity * 1000;
		$results['metre-cubique'] = $quantity / 1000;
		$results['kilometre-cubique'] = $quantity / (1 * pow(10, 12));
	} elseif($units == 'pied-carre'){
		$results['pied'] = sqrt($quantity);
		$results['pouce'] = sqrt($quantity) * 12;
		$results['millimetre'] = sqrt($quantity) * 304.8;
		$results['centimetre'] = sqrt($quantity) * 30.48;
		$results['metre'] = sqrt($quantity) / 3.281;
		$results['kilometre'] = sqrt($quantity) / 3281;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = $quantity;
		$results['pouce-carre'] = pow((sqrt($quantity) * 12), 2);
		$results['millimetre-carre'] = pow((sqrt($quantity) * 304.8), 2);
		$results['centimetre-carre'] = pow((sqrt($quantity) * 30.48), 2);
		$results['metre-carre'] = pow((sqrt($quantity) / 3.281), 2);
		$results['kilometre-carre'] = pow((sqrt($quantity) / 3281), 2);
		$results['pied-cubique'] = pow((sqrt($quantity)), 3);
		$results['pouce-cubique'] = pow((sqrt($quantity) * 12), 3);
		$results['millimetre-cubique'] = pow((sqrt($quantity) * 304.8), 3);
		$results['centimetre-cubique'] = pow((sqrt($quantity) * 30.48), 3);
		$results['metre-cubique'] = pow((sqrt($quantity) / 3.281), 3);
		$results['kilometre-cubique'] = pow((sqrt($quantity) / 3281), 3);
	} elseif($units == 'pouce-carre'){
		$results['pied'] = sqrt($quantity) / 12;
		$results['pouce'] = sqrt($quantity);
		$results['millimetre'] = sqrt($quantity) * 25.4;
		$results['centimetre'] = sqrt($quantity) * 2.54;
		$results['metre'] = sqrt($quantity) / 39.37;
		$results['kilometre'] = sqrt($quantity) / 39370;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow((sqrt($quantity) / 12), 2);
		$results['pouce-carre'] = $quantity;
		$results['millimetre-carre'] = pow((sqrt($quantity) * 25.4), 2);
		$results['centimetre-carre'] = pow((sqrt($quantity) * 2.54), 2);
		$results['metre-carre'] = pow((sqrt($quantity) / 39.37), 2);
		$results['kilometre-carre'] = pow((sqrt($quantity) / 39370), 2);
		$results['pied-cubique'] = pow((sqrt($quantity) / 12), 3);
		$results['pouce-cubique'] = pow((sqrt($quantity)), 3);
		$results['millimetre-cubique'] = pow((sqrt($quantity) * 25.4), 3);
		$results['centimetre-cubique'] = pow((sqrt($quantity) * 2.54), 3);
		$results['metre-cubique'] = pow((sqrt($quantity) / 39.37), 3);
		$results['kilometre-cubique'] = pow((sqrt($quantity) / 39370), 3);
	} elseif($units == 'metre-carre') {		
		$results['pied'] = sqrt($quantity) / 3.281;
		$results['pouce'] = sqrt($quantity) / 39.37;
		$results['millimetre'] = sqrt($quantity) * 1000;
		$results['centimetre'] = sqrt($quantity) * 100;
		$results['metre'] = sqrt($quantity);
		$results['kilometre'] = sqrt($quantity) / 1000;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow((sqrt($quantity) / 3.281), 2);
		$results['pouce-carre'] = pow((sqrt($quantity) / 39.37), 2);
		$results['millimetre-carre'] = $quantity * (1 * pow(10, 6));
		$results['centimetre-carre'] = $quantity * 10000;
		$results['metre-carre'] = $quantity;
		$results['kilometre-carre'] = $quantity / (1 * pow(10, 6));
		$results['pied-cubique'] = pow((sqrt($quantity) / 3.281), 3);
		$results['pouce-cubique'] = pow((sqrt($quantity) / 39.37), 3);
		$results['millimetre-cubique'] = pow((sqrt($quantity) * 1000), 3);
		$results['centimetre-cubique'] = pow((sqrt($quantity) * 100), 3);
		$results['metre-cubique'] = pow((sqrt($quantity)), 3);
		$results['kilometre-cubique'] = pow((sqrt($quantity) / 1000), 3);
	} elseif($units == 'centimetre-carre') {
		$results['pied'] = sqrt($quantity) / 30.48;
		$results['pouce'] = sqrt($quantity) / 2.54;
		$results['millimetre'] = sqrt($quantity) * 10;
		$results['centimetre'] = sqrt($quantity);
		$results['metre'] = sqrt($quantity) / 100;
		$results['kilometre'] = sqrt($quantity) / 1000000;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow((sqrt($quantity) / 30.48), 2);
		$results['pouce-carre'] = pow((sqrt($quantity) / 2.54), 2);
		$results['millimetre-carre'] = $quantity * 100;
		$results['centimetre-carre'] = $quantity;
		$results['metre-carre'] = $quantity / 10000;
		$results['kilometre-carre'] = $quantity / (1 * pow(10, 9));
		$results['pied-cubique'] = pow((sqrt($quantity) / 30.48), 3);
		$results['pouce-cubique'] = pow((sqrt($quantity) / 2.54), 3);
		$results['millimetre-cubique'] = pow((sqrt($quantity) * 10), 3);
		$results['centimetre-cubique'] = pow((sqrt($quantity)), 3);
		$results['metre-cubique'] = pow((sqrt($quantity) / 100), 3);
		$results['kilometre-cubique'] = pow((sqrt($quantity) / 1000000), 3);
	} elseif($units == 'millimetre-carre') {
		$results['pied'] = sqrt($quantity) / 304.8;
		$results['pouce'] = sqrt($quantity) / 25.4;
		$results['millimetre'] = sqrt($quantity);
		$results['centimetre'] = sqrt($quantity) / 10;
		$results['metre'] = sqrt($quantity) / 1000;
		$results['kilometre'] = sqrt($quantity) / 1000000;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow((sqrt($quantity) / 304.8), 2);
		$results['pouce-carre'] = pow((sqrt($quantity) / 25.4), 2);
		$results['millimetre-carre'] = $quantity;
		$results['centimetre-carre'] = $quantity / 100;
		$results['metre-carre'] = $quantity / 1000000;
		$results['kilometre-carre'] = $quantity / (1 * pow(10, 9));
		$results['pied-cubique'] = pow((sqrt($quantity) / 304.8), 3);
		$results['pouce-cubique'] = pow((sqrt($quantity) / 25.4), 3);
		$results['millimetre-cubique'] = pow((sqrt($quantity)), 3);
		$results['centimetre-cubique'] = pow((sqrt($quantity) / 10), 3);
		$results['metre-cubique'] = pow((sqrt($quantity) / 1000), 3);
		$results['kilometre-cubique'] = pow((sqrt($quantity) / 1000000), 3);
	} elseif($units == 'kilometre-carre') {
		$results['pied'] = sqrt($quantity) * 3280.84;
		$results['pouce'] = sqrt($quantity) * 39370.079;
		$results['millimetre'] = sqrt($quantity) * 1000000;
		$results['centimetre'] = sqrt($quantity) * 100000;
		$results['metre'] = sqrt($quantity) * 1000;
		$results['kilometre'] = sqrt($quantity);
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow((sqrt($quantity) * 3280.84), 2);
		$results['pouce-carre'] = pow((sqrt($quantity) * 39370.079), 2);
		$results['millimetre-carre'] = $quantity * 1000000000000;
		$results['centimetre-carre'] = $quantity * 10000000000;
		$results['metre-carre'] = $quantity * 1000000;
		$results['kilometre-carre'] = $quantity;
		$results['pied-cubique'] = pow((sqrt($quantity) * 3280.84), 3);
		$results['pouce-cubique'] = pow((sqrt($quantity) * 39370.079), 3);
		$results['millimetre-cubique'] = pow((sqrt($quantity) * 1000000), 3);
		$results['centimetre-cubique'] = pow((sqrt($quantity) * 100000), 3);
		$results['metre-cubique'] = pow((sqrt($quantity) * 1000), 3);
		$results['kilometre-cubique'] = pow((sqrt($quantity)), 3);
	} elseif($units == 'pied-cubique'){
		$results['pied'] = pow($quantity, 1/3);
		$results['pouce'] = pow($quantity, 1/3) * 12;
		$results['millimetre'] = pow($quantity, 1/3) * 304.8;
		$results['centimetre'] = pow($quantity, 1/3) * 30.48;
		$results['metre'] = pow($quantity, 1/3) / 3.281;
		$results['kilometre'] = pow($quantity, 1/3) / 3281;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow(pow($quantity, 1/3), 2);
		$results['pouce-carre'] = pow((pow($quantity, 1/3) * 12), 2);
		$results['millimetre-carre'] = pow((pow($quantity, 1/3) * 304.8), 2);
		$results['centimetre-carre'] = pow((pow($quantity, 1/3) * 30.48), 2);
		$results['metre-carre'] = pow((pow($quantity, 1/3) / 3.281), 2);
		$results['kilometre-carre'] = pow((pow($quantity, 1/3) / 3281), 2);
		$results['pied-cubique'] = $quantity;
		$results['pouce-cubique'] = pow((pow($quantity, 1/3) * 12), 3);
		$results['millimetre-cubique'] = pow((pow($quantity, 1/3) * 304.8), 3);
		$results['centimetre-cubique'] = pow((pow($quantity, 1/3) * 30.48), 3);
		$results['metre-cubique'] = pow((pow($quantity, 1/3) / 3.281), 3);
		$results['kilometre-cubique'] = pow((pow($quantity, 1/3) / 3281), 3);
	} elseif($units == 'pouce-cubique'){
		$results['pied'] = pow($quantity, 1/3) / 12;
		$results['pouce'] = pow($quantity, 1/3);
		$results['millimetre'] = pow($quantity, 1/3) * 25.4;
		$results['centimetre'] = pow($quantity, 1/3) * 2.54;
		$results['metre'] = pow($quantity, 1/3) / 39.37;
		$results['kilometre'] = pow($quantity, 1/3) / 39370;
		$results['once'] = 'NA';
		$results['millilitre'] = 'NA';
		$results['litre'] = 'NA';
		$results['pied-carre'] = pow((pow($quantity, 1/3) / 12), 2);
		$results['pouce-carre'] = pow((pow($quantity, 1/3)), 2);
		$results['millimetre-carre'] = pow((pow($quantity, 1/3) * 25.4), 2);
		$results['centimetre-carre'] = pow((pow($quantity, 1/3) * 2.54), 2);
		$results['metre-carre'] = pow((pow($quantity, 1/3) / 39.37), 2);
		$results['kilometre-carre'] = pow((pow($quantity, 1/3) / 39370), 2);
		$results['pied-cubique'] = pow((pow($quantity, 1/3) / 12), 3);
		$results['pouce-cubique'] = $quantity;
		$results['millimetre-cubique'] = pow((pow($quantity, 1/3) * 25.4), 3);
		$results['centimetre-cubique'] = pow((pow($quantity, 1/3) * 2.54), 3);
		$results['metre-cubique'] = pow((pow($quantity, 1/3) / 39.37), 3);
		$results['kilometre-cubique'] = pow((pow($quantity, 1/3) / 39370), 3);
	} elseif($units == 'metre-cubique') {
		$results['pied'] = pow($quantity, 1/3) / 3.281;
		$results['pouce'] = pow($quantity, 1/3) / 39.37;
		$results['millimetre'] = pow($quantity, 1/3) * 1000;
		$results['centimetre'] = pow($quantity, 1/3) * 100;
		$results['metre'] = pow($quantity, 1/3);
		$results['kilometre'] = pow($quantity, 1/3) / 1000;
		$results['once'] = $quantity * 33814.023;
		$results['millilitre'] = $quantity * 1000000;
		$results['litre'] = $quantity * 1000;
		$results['pied-carre'] = pow((pow($quantity, 1/3) / 3.281), 2);
		$results['pouce-carre'] = pow((pow($quantity, 1/3) / 39.37), 2);
		$results['millimetre-carre'] = pow((pow($quantity, 1/3) * 1000), 2);
		$results['centimetre-carre'] = pow((pow($quantity, 1/3) * 100), 2);
		$results['metre-carre'] = pow((pow($quantity, 1/3)), 2);
		$results['kilometre-carre'] = pow((pow($quantity, 1/3) / 1000), 2);
		$results['pied-cubique'] = pow((pow($quantity, 1/3) / 3.281), 3);
		$results['pouce-cubique'] = pow((pow($quantity, 1/3) / 39.37), 3);
		$results['millimetre-cubique'] = pow((pow($quantity, 1/3) * 1000), 3);
		$results['centimetre-cubique'] = pow((pow($quantity, 1/3) * 100), 3);
		$results['metre-cubique'] = $quantity;
		$results['kilometre-cubique'] = pow((pow($quantity, 1/3) / 1000), 3);
	} elseif($units == 'centimetre-cubique') {
		$results['pied'] = pow($quantity, 1/3) / 30.48;
		$results['pouce'] = pow($quantity, 1/3) / 2.54;
		$results['millimetre'] = pow($quantity, 1/3) * 10;
		$results['centimetre'] = pow($quantity, 1/3);
		$results['metre'] = pow($quantity, 1/3) / 100;
		$results['kilometre'] = pow($quantity, 1/3) / 1000000;
		$results['once'] = $quantity / 29.5735;
		$results['millilitre'] = $quantity;
		$results['litre'] = $quantity / 1000;
		$results['pied-carre'] = pow((pow($quantity, 1/3) / 30.48), 2);
		$results['pouce-carre'] = pow((pow($quantity, 1/3) / 2.54), 2);
		$results['millimetre-carre'] = pow((pow($quantity, 1/3) * 10), 2);
		$results['centimetre-carre'] = pow((pow($quantity, 1/3)), 2);
		$results['metre-carre'] = pow((pow($quantity, 1/3) / 100), 2);
		$results['kilometre-carre'] = pow((pow($quantity, 1/3) / 1000000), 2);
		$results['pied-cubique'] = pow((pow($quantity, 1/3) / 30.48), 3);
		$results['pouce-cubique'] = pow((pow($quantity, 1/3) / 2.54), 3);
		$results['millimetre-cubique'] = pow((pow($quantity, 1/3) * 10), 3);
		$results['centimetre-cubique'] = $quantity;
		$results['metre-cubique'] = pow((pow($quantity, 1/3) / 100), 3);
		$results['kilometre-cubique'] = pow((pow($quantity, 1/3) / 1000000), 3);
	} elseif($units == 'millimetre-cubique') {
		$results['pied'] = pow($quantity, 1/3) / 304.8;
		$results['pouce'] = pow($quantity, 1/3) / 25.4;
		$results['millimetre'] = pow($quantity, 1/3);
		$results['centimetre'] = pow($quantity, 1/3) / 10;
		$results['metre'] = pow($quantity, 1/3) / 1000;
		$results['kilometre'] = pow($quantity, 1/3) / 1000000;
		$results['once'] = $quantity / 29573.53;
		$results['millilitre'] = $quantity / 1000;
		$results['litre'] = $quantity / 1000000;
		$results['pied-carre'] = pow((pow($quantity, 1/3) / 304.8), 2);
		$results['pouce-carre'] = pow((pow($quantity, 1/3) / 25.4), 2);
		$results['millimetre-carre'] = pow((pow($quantity, 1/3)), 2);
		$results['centimetre-carre'] = pow((pow($quantity, 1/3) / 10), 2);
		$results['metre-carre'] = pow((pow($quantity, 1/3) / 100), 2);
		$results['kilometre-carre'] = pow((pow($quantity, 1/3) / 1000000), 2);
		$results['pied-cubique'] = pow((pow($quantity, 1/3) / 304.8), 3);
		$results['pouce-cubique'] = pow((pow($quantity, 1/3) / 25.4), 3);
		$results['millimetre-cubique'] = $quantity;
		$results['centimetre-cubique'] = pow((pow($quantity, 1/3) / 10), 3);
		$results['metre-cubique'] = pow((pow($quantity, 1/3) / 100), 3);
		$results['kilometre-cubique'] = pow((pow($quantity, 1/3) / 1000000), 3);
	} elseif($units == 'kilometre-cubique') {
		$results['pied'] = pow($quantity, 1/3) * 3280.84;
		$results['pouce'] = pow($quantity, 1/3) * 39370.079;
		$results['millimetre'] = pow($quantity, 1/3) * 1000000;
		$results['centimetre'] = pow($quantity, 1/3) * 100000;
		$results['metre'] = pow($quantity, 1/3) * 1000;
		$results['kilometre'] = pow($quantity, 1/3);
		$results['once'] = $quantity / 29573.53;
		$results['millilitre'] = $quantity / 1000;
		$results['litre'] = $quantity / 1000000;
		$results['pied-carre'] = pow((pow($quantity, 1/3) * 3280.84), 2);
		$results['pouce-carre'] = pow((pow($quantity, 1/3) * 39370.079), 2);
		$results['millimetre-carre'] = pow((pow($quantity, 1/3) * 1000000), 2);
		$results['centimetre-carre'] = pow((pow($quantity, 1/3) * 100000), 2);
		$results['metre-carre'] = pow((pow($quantity, 1/3) * 1000), 2);
		$results['kilometre-carre'] = pow((pow($quantity, 1/3)), 2);
		$results['pied-cubique'] = pow((pow($quantity, 1/3) * 3280.84), 3);
		$results['pouce-cubique'] = pow((pow($quantity, 1/3) * 39370.079), 3);
		$results['millimetre-cubique'] = pow((pow($quantity, 1/3) * 1000000), 3);
		$results['centimetre-cubique'] = pow((pow($quantity, 1/3) * 100000), 3);
		$results['metre-cubique'] = pow((pow($quantity, 1/3) * 1000), 3);
		$results['kilometre-cubique'] = $quantity;
	}
	return array($units => $results);
}		

$keywords = array(
	'plus', 'additionne', 'incremente',
	'moins', 'reduit', 'soustrait',
	'fois', 'multiplie',
	'divise',
	'au-carre',
	'racine',
	'conversion', 'en', 'pour'
);
$response_equation = '';
$unwanted_array = array(
'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 
'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N',
 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 
'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 
'ÿ'=>'y');
$sentence = strtr($reason, $unwanted_array);
$sentence = explode(' ', $sentence);
foreach($sentence as $key => $value) {
	$value = mb_strtolower($value, 'UTF-8');
	if($value == 'cm'){
		$sentence[$key] = 'centimetre';
	} elseif($value == 'mm') {
		$sentence[$key] = 'millimetre';
	} elseif($value == 'm') {
		$sentence[$key] = 'metre';
	} elseif($value == 'km') {
		$sentence[$key] = 'kilometre';
	} elseif($value == 'cm²'){
		$sentence[$key] = 'centimetre-carre';
	} elseif($value == 'mm²') {
		$sentence[$key] = 'millimetre-carre';
	} elseif($value == 'm²') {
		$sentence[$key] = 'metre-carre';
	} elseif($value == 'km²') {
		$sentence[$key] = 'kilometre-carre';
	} elseif($value == 'po²') {
		$sentence[$key] = 'pouce-carre';
	} elseif($value == 'pi²') {
		$sentence[$key] = 'pied-carre';
	} elseif($value == 'cm³'){
		$sentence[$key] = 'centimetre-cubique';
	} elseif($value == 'mm³') {
		$sentence[$key] = 'millimetre-cubique';
	} elseif($value == 'm³') {
		$sentence[$key] = 'metre-cubique';
	} elseif($value == 'km³') {
		$sentence[$key] = 'kilometre-cubique';
	} elseif($value == 'po³') {
		$sentence[$key] = 'pouce-cubique';
	} elseif($value == 'pi³') {
		$sentence[$key] = 'pied-cubique';
	} elseif($value == 'l') {
		$sentence[$key] = 'litre';
	} elseif($value == 'ml') {
		$sentence[$key] = 'millilitre';
	} elseif($value == 'oz') {
		$sentence[$key] = 'once';
	} elseif($value == 'po') {
		$sentence[$key] = 'pouce';
	} elseif($value == 'pi') {
		$sentence[$key] = 'pied';
	} elseif($value == '*' || $value == 'x') {
		$sentence[$key] = 'fois';
	} elseif($value == '+') {
		$sentence[$key] = 'plus';
	} elseif($value == '-') {
		$sentence[$key] = 'moins';
	} elseif($value == '/') {
		$sentence[$key] = 'divise';
	}
}
$sentence = array_values($sentence);
$sentence = implode(' ', $sentence);

$sentence = str_replace('pouces', 'pouce', $sentence);
$sentence = str_replace('pouce-', 'pouce', $sentence);
$sentence = str_replace('poucecarre', 'pouce-carre', $sentence);
$sentence = str_replace('poucecubique', 'pouce-cubique', $sentence);
$sentence = str_replace('pieds', 'pied', $sentence);
$sentence = str_replace('pied-', 'pied', $sentence);
$sentence = str_replace('piedcarre', 'pied-carre', $sentence);
$sentence = str_replace('piedcubique', 'pied-cubique', $sentence);
$sentence = str_replace('metres', 'metre', $sentence);
$sentence = str_replace('metre-', 'metre', $sentence);
$sentence = str_replace('onces', 'once', $sentence);
$sentence = str_replace('once-', 'once', $sentence);
$sentence = str_replace('litres', 'litre', $sentence);
$sentence = str_replace('litre-', 'litre', $sentence);
$sentence = str_replace('metrecarre', 'metre-carre', $sentence);
$sentence = str_replace('metrecubique', 'metre-cubique', $sentence);
$sentence = str_replace(' carre', '-carre', $sentence);
$sentence = str_replace('-carree', '-carre', $sentence);
$sentence = str_replace('-carres', '-carre', $sentence);
$sentence = str_replace(' cub', '-cub', $sentence);
$sentence = str_replace('-cubes', '-cube', $sentence);
$sentence = str_replace('-cubiques', '-cubique', $sentence);
$sentence = str_replace('-cube', '-cubique', $sentence);
$sentence = explode(' ', $sentence);

function check_before($value, $key, $sentence, $keywords, $units) {
	for($i = ((($key - 1) > -1) ? ($key - 1) : 0); $i > -1; $i--){
		if(in_array(strtolower($sentence[$i]), $keywords)){
			break;
		}
		
		if(is_numeric($sentence[$i])){
			for($j = ((($i + 1) < count($sentence)) ? ($i + 1) : 0); $j < count($sentence); $j++){
				if(in_array(strtolower($sentence[$j]), $keywords)){
					break;
				}
				$unit_found = strtolower($sentence[$j]);
				
				foreach($units as $value2) {
					if($unit_found == $value2){
						return array($j, all_units($sentence[$i], $value2));
					}
				}
			}
		}
	}
}

function check_after($value, $key, $sentence, $keywords, $units) {
	for($j = ((($key + 1) < count($sentence)) ? ($key + 1) : 0); $j < count($sentence); $j++){
		if(in_array(strtolower($sentence[$j]), $keywords)){
			break;
		}
		
		if(is_numeric($sentence[$j])){
			for($k = ((($j + 1) < count($sentence)) ? ($j + 1) : 0); $k < count($sentence); $k++){
				if(in_array(strtolower($sentence[$k]), $keywords)){
					break;
				}
				$unit_found = strtolower($sentence[$k]);
				
				foreach($units as $value2) {
					if($unit_found == $value2){
						return array($k, all_units($sentence[$j], $value2));
					}
				}
			}
		}
	}

	return 'nothing';
}


$units_measure = array();
$equation = array();
foreach($sentence as $key => $value) {
	if(in_array($value, $keywords)){
		$value1 = check_before($value, $key, $sentence, $keywords, $units);
		$value2 = check_after($value, $key, $sentence, $keywords, $units);
		if($value2 == 'nothing'){
			if(!empty($value1)){ $units_measure[$value1[0]] = $value1[1]; }
		} else {
			if(!empty($value1)){ $units_measure[$value1[0]] = $value1[1]; }
			if(!empty($value2)){ $units_measure[$value2[0]] = $value2[1]; }
		}
	}
	
	if(!isset($units_measure[$key])) {
		if(is_numeric($value)){
			$units_measure[$key] = array('chiffres' => array('chiffres' => $value));
		} else {
			$units_measure[$key] = $value;	
		}
	}
}

foreach($sentence as $key => $value) {	
	if(in_array($value, $keywords)){
		$measure_to_do = '';
		$number = array();
		for($i = ((($key - 1) > -1) ? ($key - 1) : 0); $i > -1; $i--){
			if(in_array(strtolower($sentence[$i]), $keywords)){
				break;
			}
					
			if(isset($units_measure[$i]) && is_array($units_measure[$i])){
				foreach($units_measure[$i] as $key2 => $value2) {
					if(isset($units_measure[$i][$key2][$key2])){
						$measure_to_do = $key2;
						$number[] = array($key2, $units_measure[$i][$key2][$key2], $units_measure[$i][$key2]);
						break 2;
					}
				}
			}
		}
		if($value != 'carre') {		
			for($j = ((($key + 1) < count($sentence)) ? ($key + 1) : 0); $j < count($sentence); $j++){
				if(in_array(strtolower($sentence[$j]), $keywords)){
					break;
				}
				
				if(isset($units_measure[$j]) && is_array($units_measure[$j])){
					foreach($units_measure[$j] as $key2 => $value2) {
						if(isset($units_measure[$j][$key2][$measure_to_do]) && !empty($measure_to_do)){
							$number[] = array($measure_to_do, $units_measure[$j][$key2][$measure_to_do], $units_measure[$j][$key2]);
							break 2;
						}
					}
				}
			}
		}
		
		$units_conversion = array(
			'chiffres' => 'unitées',
			'centimetre' => 'centimètres',
			'metre' => 'mètres',
			'millimetre' => 'millimètres',
			'kilometre' => 'kilomètres',
			'pouce' => 'pouces',
			'pied' => 'pieds',
			'once' => 'onces',
			'millilitre' => 'millilitres',
			'litre' => 'litres',
			'metre-carre' => 'mètres carrés',
			'centimetre-carre' => 'centimètres carrés',
			'millimetre-carre' => 'millimètres carrés',
			'kilometre-carre' => 'kilomètres carrés',
			'pouce-carre' => 'pouces carrés',
			'pied-carre' => 'pieds carrés',
			'metre-cubique' => 'mètres cubiques',
			'centimetre-cubique' => 'centimètres cubiques',
			'millimetre-cubique' => 'millimètres cubiques',
			'kilometre-cubique' => 'kilomètres cubiques',
			'pouce-cubique' => 'pouces cubiques',
			'pied-cubique' => 'pieds cubiques'
		);
		
		if(($value == 'plus' || $value == 'additionne' || $value == 'incremente') && isset($number[0][0]) && isset($number[1][0]) &&
		isset($units_conversion[$number[0][0]]) && isset($units_conversion[$number[1][0]])){
			$equation[] = $number[0][1];
			$equation[] = $units_conversion[$number[0][0]];
			$equation[] = '+';
			$equation[] = $number[1][1];
			$equation[] = $units_conversion[$number[1][0]];
			$equation[] = '=';
			$equation[] = $number[0][1] + $number[1][1];
			$equation[] = $units_conversion[$number[0][0]].'.';
		} elseif(($value == 'moins' || $value == 'reduit' || $value == 'soustrait') && isset($number[0][0]) && isset($number[1][0]) &&
		isset($units_conversion[$number[0][0]]) && isset($units_conversion[$number[1][0]])){
			$equation[] = $number[0][1];
			$equation[] = $units_conversion[$number[0][0]];
			$equation[] = '-';
			$equation[] = $number[1][1];
			$equation[] = $units_conversion[$number[1][0]];
			$equation[] = '=';
			$equation[] = $number[0][1] - $number[1][1];
			$equation[] = $units_conversion[$number[0][0]].'.';
		} elseif(($value == 'fois' || $value == 'multiplie') && isset($number[0][0]) && isset($number[1][0]) &&
		isset($units_conversion[$number[0][0]]) && isset($units_conversion[$number[1][0]])){
			$equation[] = $number[0][1];
			$equation[] = $units_conversion[$number[0][0]];
			$equation[] = '*';
			$equation[] = $number[1][1];
			$equation[] = $units_conversion[$number[1][0]];
			$equation[] = '=';
			$equation[] = $number[0][1] * $number[1][1];
			$equation[] = $units_conversion[$number[0][0]].'.';
		} elseif(($value == 'divise') && isset($number[0][0]) && isset($number[1][0]) &&
		isset($units_conversion[$number[0][0]]) && isset($units_conversion[$number[1][0]])){
			$equation[] = $number[0][1];
			$equation[] = $units_conversion[$number[0][0]];
			$equation[] = '/';
			$equation[] = $number[1][1];
			$equation[] = $units_conversion[$number[1][0]];
			$equation[] = '=';
			$equation[] = $number[0][1] / $number[1][1];
			$equation[] = $units_conversion[$number[0][0]].'.';
		} elseif(($value == 'au-carre') && isset($number[0][0]) &&
		isset($units_conversion[$number[0][0]])){
			$equation[] = $number[0][1];
			$equation[] = $units_conversion[$number[0][0]];
			$equation[] = 'au carré';
			$equation[] = '=';
			$equation[] = $number[0][1] * $number[0][1];
			$equation[] = $units_conversion[$number[0][0]].'.';
		} elseif(($value == 'conversion' || $value == 'en' || $value == 'pour') && isset($number[0][0]) &&
		isset($units_conversion[$number[0][0]])){
			$units_no_equation = '';
			for($j = ((($key + 1) < count($sentence)) ? ($key + 1) : 0); $j < count($sentence); $j++){
				if(in_array(strtolower($sentence[$j]), $keywords)){
					break;
				}
				
				$unit_found = strtolower($sentence[$j]);
				
				foreach($units as $value2) {
					if($unit_found == $value2){
						$units_no_equation = $value2;
						break 2; 
					}
				}
			}
			if(!empty($units_no_equation)){
				$equation[] = $number[0][1];
				$equation[] = $units_conversion[$number[0][0]];
				$equation[] = '=';
				$equation[] = $number[0][2][$units_no_equation];
				$equation[] = $units_conversion[$units_no_equation].'.';
			}
		} 
	}			
}
if(!empty($equation)){
	$response_equation = implode(' ', $equation);
}

?>