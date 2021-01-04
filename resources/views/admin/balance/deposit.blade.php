@extends('adminlte::page')
<!--title se eu tiro fica o título padrão-->
@section('title', 'Deposito')

@section('content_header')
<h1>Deposito</h1>
<ol class="breadcrumb">
	<li><a href="">Painel de Controle</a></li>
	<li><a href="">Saldo</a></li>
	<li><a href="">Depósito</a></li>
</ol>
@stop

@section('content')
	<div class="box">
		<div class="box-header">
			<h3>Fazer Depósito</h3>
		</div>
	
	<div class="box-body">
		<!--23 mensagens de erro, erro caso tenha (any) e agora vou no balanController para desenvolver a view de exibição de mensagem
		@if($errors->any())
			<div class="alert alert-warning">
				@foreach($errors->all() as $error)
					<p>{{$error}}</p>
				@endforeach
			</div>
		@endif
		-->
		@include('admin.includes.alerts')
		<!--18 faço o formulário usando o post-->
		<form method="POST" action="{{route('deposit.store')}}">
			<!--18 crio um helper q é usado para proteção e temos o csrf_token() q é o próprio token-->
		{!! csrf_field() !!}
		<div class="form-group">
			<!--19 acrescentamos o name="" e vou no controller-->
			<input type="text" name="value" placeholder="Valor Recarga" class="form-control">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success">Depositar</button>
		</div>
		</form>
	</div>
	</div>
@stop