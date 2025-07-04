<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'college_name',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }      

// Relationships
    

      public function participants()
    {
        return $this->hasMany(Participant::class);
        

    }
    
    
}   

