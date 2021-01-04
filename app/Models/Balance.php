<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Balance extends Model
{
    //
    public $timestamps = false;

    //19 método da lógica para inserir o depósito, vou no BalanceController e insiro o Balance $balance no params
    //19 posso especificar q tipo de retorno para o usuário no caso Array e que tipo variável
    public function deposit(float $value) : Array
    {
    	//22 beginTransaction, uso o DB e chamo o método que faz a sincronização com os banco de dados que caso insira o deposit eo historic aí irá commitar senão rollback
    	DB::beginTransaction();

    	//21 valo anterior ao deposito, a atualização, caso o valor do historic estiver vazio o null dará um erro, para que seja evitado isso acrescentamos uma condição e isso resolve o problema $totalBefore = $this->amount
    	$totalBefore = $this->amount ? $this->amount : 0;
    	//19 dou um dd no valor observamos que entrou o valor com sucesso dd($valur);
    	//20 se eu fizer dd($this->amount); já tenho o próprio saldo do usu no caso zero o q será feito: pego o $vl e somo com o amount, formato o $value
    	$this->amount += number_format($value, 2, '.', '');
    	//20 faço o retorno para o usu armazenar em uma var
    	$deposit = $this->save();
    	//21 histórico do usuário autenticado e historic relação que acabamos de criar e o método create que passamos um array com os valores criados, quando passo um método como esse ou update ou delete preciso ir na model e criar a variável fillable que é informado qual coluna da tabela quero inserir, isso evita uma inserção inválida e vamos no User para fazer essa relação de um pra muitos one to many
    	$historic = auth()->user()->historics()->create([
    		'type'			=>		'I',
    		'amount'		=>		$value,
    		'total_before'	=>		$totalBefore,
    		//21 total_after é o valor atualizado com a entrada do usuário
    		'total_after'	=>		$this->amount,
    		'date'			=>		date('Ymd'),
    	]);
    	//20
    	//if ($deposit) {
    	//21 acrescento ao parâmetro o historic
    	//22 se o deposit e historic comitarem fção commit senão rollback
    	if($deposit && $historic)
    	{
    	//21 chamo no user a classe DB:: e confirmo o commit()
    		DB::commit();
    	//21 retorno em forma de array
    		return [
    	//21 se o sucesso é true retorna a mensagem
    			'success' => true,
    			'message' => 'Sucesso ao depositar'
    		];
    	} else {
            DB::rollback();
    	return [
    		'success'	=> false,
    		'message'	=> 'Falha ao depositar'
    	];
    	//23 agora vamos para a validação, vou no cdm para buscar o Request: php artisan make:request MoneyValidationFormRequest e vou lá para configurar 
    	}
    }

    //26 função para saque
    public function withdraw(float $value) : Array
    {
    	//26 verifico se a pessoa vai tirar mais do que ela tem, se o amount for menor que o valor informo, então retorno impedimento
    	if($this->amount < $value)
    		return [
    			'success' => false,
    			'message' => 'Saldo insuficiente',
    		];
    		//26 beginTransaction, uso o DB e chamo o método beginTransaction, caso insira o deposito e o historic aí irá comitar senão rollback
    	DB::beginTransaction();
    	//26 valor anterio ao deposito, a atualização, caso o valor do historic estiver vazio o null dará um erro, para que seja evitado isso acrescentamos uma condição e isso resolve o problema $totalBefore = $this->amount
    	$totalBefore = $this->amount ? $this->amount : 0;
    	//26 agora vou decrementar (tirar o valor) em vez de incrementar
    	$this->amount -= number_format($value, 2, '.', '');
    	//26 faço um retorno para o usuário armazernar em uma variável
    	$withdraw = $this->save();
    	//26 histórico recebe o usuário, chamo o usuário autenticado e o historics que é a relação com o usuário que acabamos de criar e o método create que passamos um array com os valores criados
    	$historic = auth()->user()->historics()->create([
    		'type'				=> 'O',
    		'amount'			=> $value,
    		'total_before'		=> $totalBefore,
    		'total_after'		=> $this->amount,
    		'date'				=> date('Ymd'),
    	]);
    	//26 acrescrnto o withdraw na condição
    	if($withdraw && $historic) {
    		DB::commit();
    		return [ 
    			'success'	=>	true,
    			'message'	=> 'Sucesso ao sacar'
    		];
        } else {

            DB::rollback();

        	return [
        		'success'	=> false,
        		'message'	=> 'Falha ao sacar'
        	];
        }
//26 agora testo depois de fazer a parte da transferência, vou na view index.blade.php para adicionar o icon
    }
//29 function para transferência, tenho $value tipo float e dou use na model User e passo como segundo parâmetro e uso Array como retorno
	public function transfer(float $value, User $sender) : Array
	{
		//29 verifico se a pessoa vai transferir mais do que ela tem, se o amount for menor que o valor informado então retorno impedimento
		if($this->amount < $value)
			return [
				'success'	=> false,
				'message'	=>'Saldo insuficiente',
			];
//29 https://medium.com/@mateusgalasso/laravel-db-transaction-d0eb0ae224b : beginTransaction, se não conseguir fazer algum comando no DB faz o rollback automático, ele pode retornar algum dado se você quiser por exemplo o id do registro que acabou de ser gravado no banco de dados, posso fazer assim: $idRtorn = DB::transaction( function() use ($request){ $cliente = \Auth::user()->cliente;}) uso o DB e chamo o método beginTransaction, caaso insira o deposit e o historic então irá comitar senão rollback
		DB::beginTransaction();
		//29 valor anterior ao deposito, a atualização, caso o valor do historic estiver vazio ou null então dará um erro, para que seja evitado isso acrescentamos uma condição que resolve o problema $totalBefore = $this->amount
		//$totalBefore = se $this->amount for true então depois da interrogação será executado caso contrário logo depois dos dois(2) ponstos será executado
		$totalBefore = $this->amount ? $this->amount : 0;
		//29 agora vou acrescentar em vez de incrementar
		$this->amount -= number_format($value, 2, '.', '');
		//29 faço um retorno para o usuário armazenado em uma variável
		$transfer = $this->save();
		//29 histórico do usuário, chamo o usuário autenticado e o realcionamento que acabamos de criar e o método create que passamos um array com os valores criados
		$historic = auth()->user()->historics()->create([
			'type'			=> 'T',
			'amount'		=> $value,
			'total_before'	=> $totalBefore,
			'total_after'	=> $this->amount,
			'date'			=> date('Ymd'),
			//29 id do usuário que vai receber essa transfer e passamos pra cá o id do usu que vai receber essa transfer que é o sender->id
			'user_id_transaction'	=> $sender->id,
		]);
		//29 agora atualizamos o saldo do recebedor, variável recebe o recebedor que o objeto da model User $sender, o firstOrCreate caso o recebedor tenha nada na conta
		$senderBalance = $sender->balance()->firstOrCreate([]);
		//29 totalBeforeSender recebe o valor do mesmo
		$totalBeforeSender = $senderBalance->amount ? $senderBalance->amount : 0;
		//29 vou incrementar
		$senderBalance->amount += number_format($value, 2, '.', '');
		$transferSender = $senderBalance->save();
		$historicSender = $sender->historics()->create([
			'type'				=> 'I',
			'amount'			=> $value,
			'total_before'		=> $totalBeforeSender,
			'total_after'		=> $senderBalance->amount,
			'date'				=> date('Ymd'),
			'user_id_transaction' => auth()->user()->id,
		]);

		//29 verifico transfer, historic e usuário que revebe
		if ($transfer && $historic && $transferSender && $historicSender) 
		{
			DB::commit();

			return [
				'success'	=>true,
				'message'	=> 'Sucesso ao Transferir',
			];	
		}
		DB::rollback();

		return [
			'success'	=> false,
			'message'	=> 'Falha ao transferir'
		];
		//29 confiro no Controller
	}
}












