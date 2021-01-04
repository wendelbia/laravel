@extends('adminlte::page')
<!--30 após fazer a blade vamos na model Históric para cirar uma mutation que formata a data-->
@section('title', 'Histórico de Movimentações')

@section('content_header')
<h1>Histórico de Movimentações</h1>
<ol class="breadcrumb">
	<li><a href="">Painel de Controle</a></li>
	<li><a href="">Saldo</a></li>
	<li><a href="">Transferência</a></li>
</ol>
@stop

@section('content')
	<div class="box">
		<div class="box-header">
			<!--crio o método de pesquisa no controller-->
			<form action="{{ route('historic.search') }}" method="POST" class="form form-inline">
                {!! csrf_field() !!}
                <input type="text" name="id" class="form-control" placeholder="ID">
                <input type="date" name="date" class="form-control">
                <select name="type" class="form-control">
                    <option value="">-- Selecione o Tipo --</option>
                    @foreach ($types as $key => $type)
                        <option value="{{ $key }}">{{ $type }}</option>
                    @endforeach
                </select>                

                <button type="submit" class="btn btn-primary">Pesquisar</button>
            </form>
		</div>
	<div class="box-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>?Sender?</th>
                    <tr>
                </thead>
                <tbody>
                    @forelse($historics as $historic)
                    <tr>
                        <td>{{ $historic->id }}</td>
                        <td>{{ number_format($historic->amount, 2, ',', '.') }}</td>
					<!--31 vamos buscar o texto que substitui os tipos <td>{{$historic->type}}</td>-->
					<td>{{ $historic->type($historic->type) }}</td>
                        <td>{{ $historic->date }}</td>
					<!--31 vou na model para fazer um relacionamento inverso para buscar usuário
					<td>{{$historic->user_id_transaction}}</td>-->
					<td>
						<!--31 busco o nome do recebedor, esse modelo funciona mas é desgastante pois faz uma busca no banco dentro do próprio looping, isso irá desgastar por isso vou no BalanceController e em historics dou um dd para ver a diferença-->
						@if ($historic->user_id_transaction)
						<!--31 com a mudança da query na model historic mudo de: {{$historic->user()->get()->first()->name}}-->
						{{ $historic->userSender->name }}
						<!--31 que deixará a consulta muito mais leve-->
						<!--32 paginar esses dados no BalanceController-->
						 @else
                                -
                         @endif
					</td>
				</td>
                    <tr>
                @empty
                @endforelse
            </tbody>
        </table>
		<!--32 chama a paginação
		{!!$historics->links()!!}-->
		<!--35 o filtro é perdido pois a pesquisa é em post e o recebimento é em get, para que não se perca o filtro quando passo de página uso appends, vou na rota e mudo de post para any que aceita qualquer requisição, mas vejo que na url não fica amigável, então vou no BalanceController e no $dataForm modifico extraindo o _token da url-->
		<!--35 faço a condição se existe a variável dataForm que verifica e chamo o appends-->
		<!--36 isso traz no filtro todos os dados dos usuários, mas agora vamos amarrá-los para trazer apenas os usuários logados, para isso pode ser usado um join ou amarrar o auth do próprio usuário logado, vamos na model Historic em search-->
		@if (isset($dataForm))
                {!! $historics->appends($dataForm)->links() !!}
        @else
            {!! $historics->links() !!}
        @endif
        </div>
    </div>
@stop