<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function categories()
    {
        return $this->hasMany(Category::class, 'post_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'post_id', 'id');
    }
    // protected $appends = array('total_category','total_tag');

  /*   public function getTotalcategory()
    {
       return $this->hasMany(Category::class)->where('post_id',$this->post_id)->count();
    }

    public function getTag()
    {
       return $this->hasMany(Tag::class)->where('post_id',$this->post_id)->count();
    } */
}
