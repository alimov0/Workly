<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['title','sulg','parent_id'];

    public function vacancies()
{
    return $this->hasMany(Vacancy::class);


}

public function parent()
{
    return $this->belongsTo(vacancy::class, 'parent_id');
}

public function children()
{
    return $this->hasMany(Category::class, 'parent_id');

}
protected static function boot()
{
    parent::boot();
    static::creating(function($category){
        $category->slug = Str::sulg($category->title);
    });
}



public function getRouteKeyName()
{
    return 'slug';
}















}
