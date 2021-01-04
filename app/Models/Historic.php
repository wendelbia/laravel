<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;


class Historic extends Model
{
    public $fillable = [
    	'type', 
    	'amount', 
    	'total_before', 
    	'total_after',
    	'user_id_transaction',
    	'date'
    ];

     //31 substitui os tipos pelos nomes
    public function type($type = null)
    {
        $types = [
            'I'     =>  'Entrada',
            'O'     =>  'Saque',
            'T'     =>  'Transferência',
        ];
        //31 se type não for null então:
        if(!$type)
            return $types;
        //31 mudamos o trnaferência por Recebido pelo tipo, então se recebedor for diferente de nulo e de input retorna Recebido, vamos na blade fazer um @if($historic->user_id...)
        if($this->user_id_transaction != null && $type == 'I')
                return 'Recebido';
            return $types[$type];
        //31 vamos na blade desenvolver
    }

    //36 scope global, passo o parâmetro query
    public function scopeUserAuth($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    //31 realcionamento inverso de vários para um, para buscar o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //31 relacionamento inverso de vários para um, para buscar o usuário
    public function userSender()
    {
        //31 passo como segundo parâmetro a coluna que quero relacionar, e vou no controller
        return $this->belongsTo(User::class, 'user_id_transaction');
    }

    //31 mutation, pega o valor que é a data
    public function getDateAttribute($value)
    {
    	//vamos dar um use lá em cima chamo o método parse
    	return Carbon::parse($value)->format('d/m/Y');
    }

    //34 retorna pesquisa, parâmetro do tipo array junto com itens por página
    public function search(Array $data, $totalPage)
    {
    	//34 retorna uma query avançada já que são muitos itens a serem pesquisados, uso um callback que tem um parâmetro com as opções que eu quero trabalhar não passo a variável $data dentro do callback por que dentro não aceita então chamo usando o use($data) retorno um filtro mais avançado, mas nem sempre todos os valores serão informados, será escolhido 1 ou 2 valores ou até todos par serem filtrados
    	//34 para debugar tiro o return e troco pela variável $historic e no lugar de ->paginate($total) ->toSql()->dd($historic); e vou BalanceController e acrescento $historic->search(); 
    	//34 return $this->where(function($query) use ($data))
    	$historics = $this->where(function ($query) use ($data) {
    		//34 se $data existe dentro de id então passo um filtro para ele
    		if(isset($data['id']))
    			$query->where('id', $data['id']);
    		if(isset($data['date']))
    			$query->where('date', $data['date']);
    		if(isset($data['type']))
    			$query->where('type', $data['type']);
    	})
    	//36 faço o where no id do usuário logado para filtrar apenas dados do usuário logado
    	//36 ->where('user_id', auth()->user()->id)
    	//36 outra maneira de fazer isso que é usando o scope, o laravel tem um scope global que utiliza o scope local para criar essa query e reaproveitar onde quiser, então acima de function user() faço essa função scope, amarro essa função, não preciso passar os primeiros caracters "scope" apenas userAuth e sem o $qury em menúsculo e posso usar um dd() para verificar tirando o ->paginate()
    	->userAuth()
        //36 e para economizar consiltas chamo também o userSender
        //37 agora vamos preparar a exibição do perfil em view/site/home/index.php
        ->with(['userSender'])
        //34 para teste no lugar de ->paginate($total)
        //34->toSql(); 
        //dd($historics);
        ->paginate($totalPage);
        //34 retorno o resultado da pesquisa->paginate($totalPage);
        return $historics;
    }

}
