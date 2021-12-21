<?php
namespace  App;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'user_meta';
     protected $fillable = [
         'val',
         'user_id',
     ];
}
