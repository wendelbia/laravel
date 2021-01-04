@extends('adminlte::page')
<!--title se eu tiro fica o título padrão-->
@section('title', 'Home Dashboard')

@section('content_header')
<h1>Painel de Controle</h1>
@stop

@section('content')
	<p><strong>{{$name}}</strong>, você está logado!</p>
@stop