<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;


class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_matkul',
        'sks',
        'jam',
        'semester',
    ];

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class,'mahasiswa_matakuliah','matakuliah_id','mahasiswa_id');
    }

    public function mata_kuliah(){
        return $this->belongsToMany(Mahasiswa_MataKuliah::class);
    }
}
