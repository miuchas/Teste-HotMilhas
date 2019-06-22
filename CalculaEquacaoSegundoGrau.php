//coeficientes
$a = 1;
$b = 2;
$c = -15;
//outras variaveis
$delta = $r1 = $r2 = $raisdelta = 0;
$tipo = true;

//verifica se a equiação é valida
if($a != 0){
	//calcula o delta
	$delta = pow($b,2) - (4*$a*$c);

	if($delta >= 0){ //raiz é real
		$raisdelta = sqrt($delta);
		$r1 = (-$b + $raisdelta)/(2*$a);
        $r2 = (-$b - $raisdelta)/(2*$a);
	}
		$tipo = false;
		$delta = $delta*-1;
		$raisdelta = sqrt($delta);
		$r1 = (-$b)/(2*$a)+$raisdelta/(2*$a);
		$r2 = (-$b)/(2*$a)-$raisdelta/(2*$a);
	}
	echo "A equação dos coefientes A $a, B $b e C $c possui raizes "
		.($tipo ? "reais.": "complexas.").
		"São elas: ".
		$r1.",".$r2.".";
}
else{
	echo("Coeficiente 'a' inválido. Não é uma equação do 2o grau");
}