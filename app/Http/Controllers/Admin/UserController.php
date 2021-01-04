<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileFormRequest;

class UserController extends Controller
{
    public function profile()
    {
    	//38 para criar o template do site vou no bootstrap e pego o link do css
    	return view('site.profile.profile');
    }

    public function profileUpdate(UpdateProfileFormRequest $request)
    {
    	//40 para o upload de imagem pego os dados do usuário logado
    	//40 posso pegar esses dados pelo id ocultando no hfomr mas é perigoso pois por ser pego esse id e facilitar a invaçao de perfil de outros usuários usando o auth() e mais seguro
    	$user = auth()->user();
    	//38 verifico
    	//dd($request->all());
    	//39 pego os dados do form e não deixo obrigatório editar a senha e imagem
    	$data = $request->all();
    	//39 se houver troca de senha vou criptografá-la
    	if($data['password'] != null)
    		$data['password'] = bcrypt($data['password']);
    	//39 para que não dê erro por que o password não pode entrar null eu o destruo
    	//40 agora faço o upload da imagem, vou em config/filesystems.php
    	else
    		unset($data['password']);
    	//40 verifico se a pessoa tem uma imagem informada, pois quando entrar lá embaixo no update não irá atualizar a imagem
    	$data['image'] = $user->image;
    	//40 se tem imagem e é imagem válida então
    	if($request->hasFile('image') && $request->file('image')->isValid())
    	{
    		//40 se existe
    		if($user->image)
    			//40 recebe o nome do usuário, evitando a imagem duplicada
    			$name = $user->image;
    		else
    			//40 posso tirar os caracteres especiais usando o kebab_case
    			$name = $user->id.kebab_case($user->name);
    			$extenstion = $request->image->extension();
    			$nameFile = "{$name}.{$extenstion}";
    		//40 dd($nameFile);
    		//40 quando entrar aqui em upload ele não vai atualizar o upload da imagem por isso precisamos 
    		$data['image'] = $nameFile;
    		//40 próximo etapa é fazer o upload, envio a imagem para dentro da pasta user em storage, se nao houver o laravel cria essa pasta
    		$upload = $request->image->storeAs('users', $nameFile);
    		//40 se não der certo faz o redirect back
    		if(!$upload)
    			return redirect()
    						->back()
    						->with('error', 'Falha ao fazer o upload da imagem!');
    	}
    	//39 Não é bom usar no formulário o id hidden escondido pois pode pegar o id de outras pessoas e alterar, pra isso pegamos o auth
    	//39 $update = auth()->user()->update($data);
    	//40 mudo para vamos no profile.blade para fazer uma melhoria que é exibir a imagem
    	$update = $user->update($data);

    	if($update)
    		return redirect()
    					->route('profile')
    					->with('success', 'Sucesso ao atualizar');
    		return redirect()
    					->back()
    					->with('error', 'Falha ao atualizar o perfil...');
    }
}
