<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

protected $fillable = [

    'cover_letter',
    'resume_file',
    'user_id',
    'vacancy_id'

];

protected $attributes = [
  'status' => 'pending'
];
public function user()
{
    return $this->belongsTo(Vacancy::class);
}
public function vacancy()
{
    return $this->belongsTo(vacancy::class);
}
public function isPending()
{
    return $this->status === 'pending';
}

public function isAccepted()
{
    return $this->status === 'accepted';
}
public function isRejected()
{
    return $this->status === 'rejected';
}



}
