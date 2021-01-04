@extends('site.layouts.app')

@section('title', 'Meu perfil')

@section('content')

<h1>Meu Perfil</h1>
<ol class="breadcrumb">
	<li><a href="{{route('admin.home')}}">Painel de Crontrole  |</a></li> 
	<li><a href="{{route('home')}}">| Página Inicial</a></li>
</ol>
<!--42 chamo o alert que está centralizado em admin/includes/alerts.blade.php-->
@include('admin.includes.alerts')
<!--38 defino a rota e método e em web crio essa rota-->
<form action="{{route('profile.update')}}" method="POST" enctype="multipart/form-data">
	{!! csrf_field() !!}
	<div class="form-group">
	<!--38 uso o value para imprimir os nomes nos inputs -->
	<label for="name">
		Nome
	</label>
	<input type="text" name="name"placeholder="Nome" class="form-control" value="{{ auth()->user()->name }}">
	</div>

	<div class="form-group">
		<label for="email">Email</label>
		<input type="email" name="email" value="{{auth()->user()->email}}" class="form-control" placeholder="Email">
	</div>
	
	<div class="form-group">
		<label for="Senha">Senha</label>
		<input type="password" name="password" class="form-control" placeholder="Senha">
	</div>

	<div class="form-group">
		<!--41 exibir a imagem, se houver imagem, senão for nula então exiir-->
		<div class="form-group">
			@if (auth()->user()->image != null)
			<!--41 mostro o caminho onde será armazenado a imagem e pego a imagem do usuário logado, agora vamos fazer a edição do perfil do usuário, indo na linha de comando para fa\er o arquivo de validação: UpdateFromFileFormRequest-->
			<img src="{{url('storage/users/'.auth()->user()->image)}}" alt="{{auth()->user()->name}}" style="max-width: 50px;">
			@endif
		</div>
		<label for="image">Imagem:</label>
		<input type="file" name="image" class="form-control">
		<div class="form-group">
			<button type="submit" class="btn btn-info">Atualizar Perfil</button>
		</div>
	</div>
</form>
@stop
