<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/05/18
 * Time: 18:06
 */

namespace App\Domain\Category;


use Illuminate\Database\Eloquent\Model;

class CategoryEntity extends Model
{
    protected $table = 'category';

    public $incrementing = false;
    public $timestamps = false;

    public function products(){

    }
}