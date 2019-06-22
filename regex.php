<?php

$teste = '
Melhor preço sem escalas R$ 1.367(1)
Melhor preço com escalas R$ 994,50 (1)
Melhor preço com escalas R$ 991234 (2adfdfsd01)
Melhor preço com escalas R$ 10,40 (123456)
Melhor preço com escalas R$ 9 (e12)
Melhor preço com escalas R$ 19,50 (1frf346ghk)';

$regex = '/[A-Za-z $ç.]*|\([^\)]*\)/';
$teste = preg_replace($regex, "", $teste);
$precos = explode("\n", trim($teste));
$menor_valor = 999999999;

foreach ($precos as $preco) {
	$preco = floatval(preg_replace("/,/", ".", $preco));
	if($menor_valor > $preco){ $menor_valor = $preco; }
}

echo 'menor valor R$ '. money_format('%i',$menor_valor);
