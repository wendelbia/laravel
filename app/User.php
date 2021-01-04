<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Balance;
use App\Models\Historic;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    #17 relacionamento entre tabela user com a balance, lá em cima chamo o Balance faço o relacionamento de um para um (one to one) com isso já consigo retornar o saldo do usu através desse método em BalanceController par recuperar esses dados
    public function balance()
    {
        return $this->hasOne(Balance::class);
    }

    //21 faço um relacionamento de 1 para muitos de One to many que é: um usuário terá vários históricos, cada histórico representa 1 usuário
    public function historics()
    {
        return $this->hasMany(Historic::class);
    }
    //28 busco os dados do sender (recebedor)
    public function getSender($sender)
    {
        //28 faço this que é instância dessa própria classe, filtro pelo e uso o LIKE para pegar o nome igual e suo % para buscar tanto pelo começo do nome quanto pelo fim dele
        return $this->where('name', 'LIKE', "%$sender%")
        //28 uso o orWhere (outro dado) e filtro pelo email e não preciso usar o 'email' == '$sender' apenas vírgula sender
                    ->orWhere('email', $sender)
                    //28 posso usar esse método para saber qual query está sendo usada
                    //->toSql(); 
                    //"select * from `users` where `name` LIKE ? or `email` = ?"
                    //uso o get para recuperar
                    ->get()
                    ->first();
    }
}
