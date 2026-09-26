<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nik',
        'nama',
        'username',
        'email',
        'password',
        'no_hp',
        'jenis_kelamin',
        'pekerjaan',
        'alamat',
        'tanggal_lahir',
        'penyandang_disabilitas',
        'foto',
        'role',
        'instansi_id',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'password' => 'hashed',
        ];
    }

    public function poinKontribusi()
    {
        return $this->hasMany(PoinKontribusiLog::class, 'user_id');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }
}