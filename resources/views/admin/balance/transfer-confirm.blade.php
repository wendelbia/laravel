@extends('adminlte::page')
<!--title se eu tiro fica o título padrão-->
@section('title', 'Transferência')

@section('content_header')
<h1>Saldo</h1>
<ol class="breadcrumb">
	<li><a href="">Painel de Controle</a></li>
	<li><a href="">Saldo</a></li>
	<li><a href="">Deposito</a></li>
	<li><a href="">Saque</a></li>
	<li><a href="">Transferência</a></li>
</ol>
@stop

@section('content')
	<div class="box">
		<div class="box-header">
			<h3>Confirmar Transferência</h3>
		</div>
	
	<div class="box-body">
		@include('admin.includes.alerts')
		<!--28 nome do recebedor-->
		<p><strong>Recebedor: </strong>{{$sender->name}}</p>
		<p><strong>Seu Saldo Atual: </strong>{{ number_format($balance->amount, 2, ',', '.') }}</p>
		<!--28 faço o formulário e uso post e faço a rota-->
		<form method="POST" action="{{route('transfer.store')}}">
			{!! csrf_field() !!}
			<input type="hidden" name="sender_id" value="{{$sender->id}}">
			<div class="form-group">
				<input type="text" name="value" placeholder="Valor" class="form-control">
				
			</div>
			<div class="form-group">
				<button class="btn btn-success">Transferir</button>
			</div>
		</form>
	</div>
	</div>
@stop