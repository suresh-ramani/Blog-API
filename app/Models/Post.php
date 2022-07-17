<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=['title','excerpt','body','user_id','slug'];
    use HasFactory;
    public function getRouteKeyName()
    {
        return 'slug';
    }
   
    public function categories()
    {
        return $this->belongsToMany(Category::class,'post_category','post_id','category_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }
    
}
