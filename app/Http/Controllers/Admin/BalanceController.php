<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoneyValidationFormRequest;
use App\User;
use App\Models\Balance;
use App\Models\Historic;
use Illuminate\Http\Request;

class BalanceController extends Controller
{

	private $totalPage = 5;

    public function index()
    {
#16 crio views/admin/balance return view('admin.balance.index');

#17 aqui eu injeto a model do usu para criar o objeto do usu ou posso simplesmente pego os dados do usu logado, e como faço? dd(auth()->user()); dd(auth()->user()->name); e pra retornar o sldo do usu? dd(auth()->user()->balance); posso chamá-lo em formato de método também que retorna uma colection no caso vazia dd(auth()->user()->balance()->get()); 
//dd($user->$balance); e vou passar esse var através do compact, verifico se o amount é null, observamos q lá no bd o amount é um acoluna, se tem algo no amount então retorno ele, senão retorno zero e em index.blade.php exibo o valor dela, $balance recebe o usuário logado e a função balance que faz a relação do User com o Balance essas duas models
    	$balance = auth()->user()->balance;
    	$amount = $balance ? $balance->amount : 0;
    	return view('admin.balance.index', compact('amount'));
    }
//18 método para depósito, crio a view e formato
    public function deposit()
    {
    	return view('admin.balance.deposit');
    }
//19 aqui usando o Balance eu crio o objeto e jogo na var $balance, lá em cima chamo o user Balance pela variável $balance ser um objeto de Balance ñ 
//23 troco Request pela classe de validação e vou em deposit.blade.php para fazer as mensagens de erro
    public function depositStore(MoneyValidationFormRequest $request)
    {
//19 método para depositar injeto o Request, para pegar o valor uso esse método ->all() q pega todo o array de dados ou armazeno o all() em uma var ou dd($request->all()); e levamos essa lógica lá para o Balance.php dd($request->value); e assim $balance->deposit($request->value);
 //dd($balance);$balance->deposit($request->value); e agora chamo o método q foi criado no Balance e passo o valor pego no input lá na model q tem a responsabilidade de fzer a lógica, o q precisa ser feito aqui: recuperar o saldo do usuário por tanto pego o usuário logado(auth) e o usuário(user) e os dados da conta (balance) dou um dd

//Vamos nos atentar para o seguinte detalhe: se eu pegar o saldo de um usuário, preciso somar o depósito com o saldo atual, mas se ñ há nada depositado então dará erro, para isso ñ acontecer uso um método chamado firstOrCreate([]) que recebe um array com os valores que quero passar, ele verifica se existe esse registro, se existe então retorna pra mim tembém dd(auth()->user()->balance()->get()); portanto uso assim, o q faria aqui, no balance ele buscaria o depósito e no firatOrCreate tendo amount como zero ele verificaria se assim é e criaria um novo vl isso ñ quero por isso coloco um array vazio dd(auth()->user()->balance()->firstOrCreate(['amount'] = 0)); isso dará um erro e para corrigí-lo precisamos mudar a estrutura da nossa tabela definindo o vl do amount como default o vl zero vou no migration balance e no $table->double(amount) acrescento o ->default(0) vou em seeders em UsersTableSeeder.php para transferência apenas, ou no cdm e rodo php artisan migrated:refresh --seed que apaga e cria um nova agora podemos ver que na tabela de balance no amount temos o zero pois o firstOrCreate criou para o balance assim ñ dando mais erro dd(auth()->user()->balance()->firstOrCreate([])); armazeno esse saldo em uma variável e $balance e chamo o método que vai fazer o depósito que é o deposit($request...), o $balance é um objeto de Balance por isso ñ preciso injetar como params(Balance $balance) agora vou no Balance implementar o método deposit() agora vou desenvolver o historic lá na model User.php
//sobre firstOrCreate([]): https://imasters.com.br/back-end/um-pouco-mais-sobre-criacao-de-models-com-eloquent
    	$balance = auth()->user()->balance()->firstOrCreate([]);
//dd($balance->deposit($request->value));
//22 e agora vou no Balance par desenvolver o beginTransaction que é uma sincronia do deposit com o historic na inserção co o banco de dados
//24 armazeno em uma variável ess comando
    	$response = $balance->deposit($request->value);
//24 e verifico se $response é igual a true
    	if($response['success'])
    		return redirect()
    					->route('admin.balance')
    					->with('success', $response['message']);
//24 caso contrário
    	return redirect()
    				->back()
    				->with('error', $response['message']);
//24 crio uma pasta view/admin/includes/alerts.blade.php    		
    }

    public function withdraw()
    {
    	return view('admin.balance.withdraw');
    }
//25 lógica do saque
    public function withdrawStore(MoneyValidationFormRequest $request)
    {
//25 dou um dd  e vamos implementar 
//dd($request->value);
//dd($request->all());
//sobre firstOrCreate()    	
//https://imasters.com.br/back-end/um-pouco-mais-sobre-criacao-de-models-com-eloquent
    	$balance = auth()->user()->balance()->firstOrCreate([]);
//26 armazeno em uma variável esse comando
    	$response = $balance->withdraw($request->value);
    	//26 e verifico se $response é igual a true
    	if($response['success'])
    		return redirect()
    					->route('admin.balance')
    					->with('success', $response['message']);
    	//26 caso contrário
    	return redirect()
    				->back()
    				->with('error', $response['message']);
    }

    public function transfer()
    {
    	return view('admin.balance.transfer');
    }
//27 método para transferência
//28 injeto o User $user como params
    public function confirmTransfer(Request $request, User $user)
    {
//dd($request->sender);
//28 verifico se o usuário foi encontrado usando a function getSender chamada pelo $user    
		if (!$sender = $user->getSender($request->sender))
		//return $sender;  dd($sender);
//28 caso o usuário não encontrado uso o return 
			return redirect()			
						->back()
						->with('error', 'Usuário informado não foi encontrado!');
//28 para evitar que o usuário faça transferência para ele mesmo pegamos o id do sender e comparamos com o do id usuário logado
		if($sender->id === auth()->user()->id)
			return redirect()				
						->back()	
						->with('error', 'Não pode transferir para você mesmo!');
//28 para mostrar o valor do saldo do usuário logado pego a variável que recebe o usuário logado e os dados da tabela balance e passamos para nossa view pelo compact
		$balance = auth()->user()->balance;
//28 chamo a view de confirmação
		return view('admin.balance.transfer-confirm', compact('sender', 'balance'));
//28 criamos a view transfer-confirm				
    }
//29 uso a validação e o User
	public function transferStore(MoneyValidationFormRequest $request, User $user)
	{ 
		//29 vereifico
		//dd($request->all());
		//29 como estou usando o User utilizo o find() para saber o id do usuário que quero fazer a recarga, que é exatamente o campo sender_id do input type="hidden" name="sender_id" value="{{$sender->id}}"> da blade transfer-confirm 
		//dd($user->find($request->sender_id));
		//29 faço uma veridicação caso não encontre o usuário com esse id se sim, recupero o sender para quem quero fazer a transferência
		if(!$sender = $user->find($request->sender_id))
			return redirect()
						->route('balance.transfer')
						->with('success', 'Recebedor não Enconrado');
		//29 firstOrCreate([]) fará a criação de um registro, mas primeiro ele validará se o registro já existe no banco. Caso já exista, a função retornará o resultado, senão, ele criará o registro. Como fica claro, este comando só será útil para realizar a criação de dados que são únicos. Já que não quero que exista mais de uma linha de mesmo nome
		$balance = auth()->user()->balance()->firstOrCreate([]);
		//29 armazeno em uma variável esse comando, se encontro o recebedor passo o método transfer os parâmetros que são o valor ($request->value) que recebi e quem vai receber (sender) e vou no Balance cria a function transfer
		$response = $balance->transfer($request->value, $sender);
		//29 e verifico se $response é igual a true
		if($response['success'])
			return redirect()
		//29 se ok vai para admin.balance
						->route('admin.balance')
                        ->with('success', $response['message']);
		//29 caso contrário
		return redirect()
                    ->route('balance.transfer')
                    ->with('error', $response['message']);
	}    
	//29 agora a execução do histórico do usuário, faço a rota no web.php

	//30 página historic.blade.php
	//33 para filtragem não posso usar esse método para buscar o userSender que tem o type(os tipos de transações) pois ele retorna ->paginate()
	//33 public function historic() entãok acrescento a model Historic
	public function historic(Historic $historic)
	{
		//30 variável recebe usuário autenticado e o método historics
		//dd($historics = auth()->user()->historics()->get());
		//32 troco o get pelo paginate para paginação, crio um atributo para isso: $historic = auth()->user()->historic()->with(['userSender'])->get();
		$historics = auth()
						->user()
						->historics()
						->with(['userSender'])->paginate($this->totalPage);
						//->paginate($this->totalPage);
		//32 vou na view para paginar

		//31 na relação não mostra nada, mas acrescentado como o método userSender
		$types = $historic->type();

		//31 na relação não mostra nada mas acrescentado como parâmetro o método userSender->with(['userSender']) busco a relação, como uso essa query de forma mais amigável? Vou na blade 
		//dd($historics);
		return view('admin.balance.historic', compact('historics', 'types'));
	}
	//33 método de pesquisa
	public function searchHistoric(Request $request, Historic $historic)
	{
		//34 verifico se fez a busca e armazeno dd($request->all());
		//34 crio a variável que recebe os dado sde historic e na model Históric crio uma função que vai centralizar os comandos necessários par filtragem
		//$dataForm = $request->all();
		//dd($dataForm);
		//34 uso o except para tirar da url o token
		$dataForm = $request->except('_token');
		//34 pego o objeto e passo os dados do formulário e o toal das páginas
		$historics = $historic->search($dataForm, $this->totalPage);
		$types = $historic->type();
		//dd($historics);
		//35 depois de filtar quando acesso a página 2 perco o filtro, então vamos resolver isso, pego a $dataForm e passo par a view
		return view('admin.balance.historic', compact('historics', 'types', 'dataForm'));

	}
}
