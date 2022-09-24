<?php

namespace App\Models\Kos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Kos extends Model
{
    use HasFactory, SoftDeletes;

    CONST KOS_TYPE = 'putra,putri,campur';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function address() {
        return $this->hasOne(Address::class);
    }

    public function facility() {
        return $this->hasOne(Facility::class);
    }

    public function kosImage() {
        return $this->hasMany(KosImage::class);
    }

    public function room() {
        return $this->hasOne(Room::class);
    }
}
