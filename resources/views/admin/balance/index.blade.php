@extends('adminlte::page')
<!--title se eu tiro fica o título padrão-->
@section('title', 'Saldo')

@section('content_header')
<h1>Saldo</h1>
<ol class="breadcrumb">
	<li><a href="">Painel de Controle</a></li>
	<li><a href="">Saldo</a></li>
</ol>
@stop

@section('content')
	<div class="box">
		<div class="box-header">
			<a href="{{ route('balance.deposit')}}" class="btn btn-primary"><i class="fa fa-cart-plus" aria-hidden></i> Recarregar</a>

			<!--25 verifico se tem algum saldo, senão tem não aparece o butão-->
			@if($amount > 0)
				<!--25 vou na routes/web e faço então a rota-->
				<a href="{{ route('balance.withdraw')}}" class="btn btn-danger"><i class="fa fa-cart-arrow-down" aria-hidden></i> Sacar</a>
			@endif

			@if($amount > 0)
			<!--27 vou na routes-->
				<a href="{{route('balance.transfer')}}" class="btn btn-success"><i class="fa fa-exchange-alt" aria-hidden></i> Transferência</a>
			@endif
		</div>
	
	<div class="box-body">
		@include('admin.includes.alerts')
		<!--#10 copio o tamplate do https://adminlte.io no dashboar v1 clico em inspecionar e copio-->
		<div class="small-box bg-green">
			<div class="inner">
				<h3>R$ {{number_format($amount, 2, ',', '')}}</h3>
				<p>Saldo</p>
			</div>
			<div class="icon">
				<i class="ion ion-cash"></i>
			</div>
			<a href="" class="small-box-footer">Mais info<i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	</div>
@stop