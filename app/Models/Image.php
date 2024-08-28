<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    
    protected $table = 'images';
    
    //Relacion One To Many / de uno a muchos
    
    public function comments() {
        
        return $this->hasMany(Comment::class)->orderBy('id','desc');
        
    }
    
    //Relacion One To Many / de uno a muchos
    
    public function likes() {
        
        return $this->hasMany(Like::class);
        
    }   
    
    //Relacion de Muchos a uno
    
    public function user() {
        
        return $this->belongsTo(User::class,'user_id');
        
    }
    
    
}
