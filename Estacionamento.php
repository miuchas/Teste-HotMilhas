<?php
class veiculo{
	public $hora_entrada;
	public $placa;
	public $numero_visitas = 0;
	public $cupom = false;
	public $valor_estadia = 0;
	//computa entrada desse veiculo no estacionamento
	public function daEntrada(){
		$this->hora_entrada = date('d/m/y - H:i:s');
		$this->numero_visitas = $this->numero_visitas + 1;
	}
}

class estacionamento{
	public $veiculos = [];
	public $clientes = [];
	public $vagas_totais = 5;
	public $vagas_atuais = 5;
	public $promocao_numero_visitas = 10;
	
	public function selecionaVeiculo($placa, $tipo){
		if($tipo == 'veiculo'){
			if(getType($placa) == 'string'){
				return array_search($placa, array_column($this->veiculos, 'placa'));
			}
			else{
				return array_search($placa->placa, array_column($this->veiculos, 'placa'));	
			}
		}
		else{
			return array_search($placa->placa, array_column($this->clientes, 'placa'));
		}
	}
	
	public function entradaVeiculo($veiculo){
		$this->vagas_atuais = $this->vagas_atuais -1;
		//verifica se o veiculo ja não esta no estacionamento
		$id_existencia = $this->selecionaVeiculo($veiculo, "veiculo");
		if($id_existencia == ""){
			$this->veiculos[] = $veiculo;
			echo "O veiculo de placa $veiculo->placa entrou no estacionamento.<br>";
		}
		else{
			echo "O veiculo de placa $veiculo->placa já está estacionado no estabelecimento atualmente.<br>";
		}

		//verifica se existe esse cliente na lista de clientes
		$id_existencia = $this->selecionaVeiculo($veiculo, "cliente");
		if($id_existencia != ""){
			$this->clientes[$id_existencia]->numero_visitas = $this->clientes[$id_existencia]->numero_visitas + 1;
		}
		else{
			$this->clientes[] = $veiculo;
		}
	}
	
	public function saidaVeiculo($placa){
		//seleciona o veiculo que vai sair
		$id = $this->selecionaVeiculo($placa, "veiculo");
		if( $id != ""){
			$veiculo = $this->veiculos[$id];
			$veiculo->valor_estadia = $this->calculaValor($veiculo->hora_entrada);
			echo "<br> O valor do estacionamento para o veiculo de placa $placa é R$ $veiculo->valor_estadia.<br>";
			//retira veiculo da lista de veiculos estacionados
			unset($this->veiculos[$id]);
			//libera a vaga do estacionamento
			$this->vagas_atuais = $this->vagas_atuais +1;
			return $veiculo;
		}
		else{
			echo "O veiculo de placa $placa não esta estacionado no estabelecimento. Por favor, verifique a placa digitada.<br>";
		}
	}
	
	public function calculaValor($horas){
		$valor = $valor_diarias = $total = 0;
		//compara as datas
		$date1 = DateTime::createFromFormat('d/m/y - H:i:s', $horas);
		$date2 = DateTime::createFromFormat('d/m/y - H:i:s', date('d/m/y - H:i:s'));
		//calcula a difetença entre as datas em horas
		$diff = $date2->diff($date1);
		$horas = $diff->h;
		$horas = $horas + ($diff->days*24);
		//Vê quantas diarias + numero de horas
		$diarias = gmp_div_qr($horas, "24");
		//numero de diarias * preço da diaria
		$valor_diarias = $diarias[0] * 25;
		
		switch($diarias[1]){
			case 0://menos de uma hora
				$valor = 0; break;
			case 1://até uma hora
				$valor = 3; break;
			case 2://até duas horas
				$valor = 5; break;
			case 3:
			case 4://até quatro horas
				$valor = 8; break;
			case $teste > 5 && $teste < 8://até oito horas
				$valor = 14; break;
			default://até 23 horas
				$valor = 25; break;
		}
		$total = money_format('%i', floatval($valor_diarias + $valor));

		return $total;
	}
}

function entrada($placa, $estacionamento){
	$v = new Veiculo();
	$v->placa = $placa;
	$v->daEntrada();
	
	$estacionamento->entradaVeiculo($v);
	return $estacionamento;
}

function saida($placa, $estacionamento){
	$veiculo = $estacionamento->saidaVeiculo($placa);
	return $estacionamento;
}

$e = new Estacionamento();
$e = entrada("AMD1080", $e);
$e = entrada("AMD1200", $e);
$e = entrada("AMD1200", $e);

$e = saida("AMD1200", $e);
$e = saida("AMD1240", $e);