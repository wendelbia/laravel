<?php

//crio um grupo de rotas para todos os controllers q vão passar pelo admin
//middleware fará a filtragem através do auth
//namespace fará a busca da pasta Admin
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin', 'prefix' => 'admin'], function(){
//#16 aqui por causa do prefixo ñ preciso passar ais admin e sim "/"
	$this->get('/', 'AdminController@index')->name('admin.home');	
	$this->get('balance', 'BalanceController@index')->name('admin.balance');
	$this->get('deposit', 'BalanceController@deposit')->name('balance.deposit');
	$this->post('deposit', 'BalanceController@depositStore')->name('deposit.store');
	$this->get('withdraw', 'BalanceController@withdraw')->name('balance.withdraw');
	$this->post('withdraw', 'BalanceController@withdrawStore')->name('withdraw.store');
	$this->get('transfer', 'BalanceController@transfer')->name('balance.transfer');
	$this->post('confirm-transfer', 'BalanceController@confirmTransfer')->name('confirm.transfer');
	$this->post('transfer', 'BalanceController@transferStore')->name('transfer.store');
	$this->any('historic-search', 'BalanceController@searchHistoric')->name('historic.search');
    $this->get('historic', 'BalanceController@historic')->name('admin.historic');
});
//34 crio a rota perfil fora do admin que tem com middleware de nível auth que dará acesso ao perfil só quem estiver logado e desenvolvo o método profile.update
$this->get('meu-perfil', 'Admin\UserController@profile')->name('profile')->middleware('auth');
$this->post('atualizar-perfil', 'Admin\UserController@profileUpdate')->name('profile.update')->middleware('auth');

$this->get('/', 'Site\SiteController@index')->name('home');
 
Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
