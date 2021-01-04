@extends('adminlte::page')
<!--title se eu tiro fica o título padrão-->
@section('title', 'Saque')

@section('content_header')
<h1>Saque</h1>
<ol class="breadcrumb">
	<li><a href="">Painel de Controle</a></li>
	<li><a href="">Saldo</a></li>
	<li><a href="">Depósito</a></li>
</ol>
@stop

@section('content')
	<div class="box">
		<div class="box-header">
			<h3>Saque</h3>
		</div>
	
	<div class="box-body">
		@include('admin.includes.alerts')
		<!--25 vou em route e faço a rota e aqui posso colocar o name ou a rota-->
		<form method="POST" action="{{route('withdraw.store')}}">
			{!! csrf_field() !!}
			<div class="form-group">
				<input type="text" name="value" placeholder="Valor da Retirada" class="form-control">
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-success">Saque</button>
			</div>
		</form>
	</div>
	</div>
@stop