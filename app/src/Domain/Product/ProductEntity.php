<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/05/18
 * Time: 18:07
 */

namespace App\Domain\Product;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductEntity
 * @package App\Domain\Product
 *
 * @property string $id
 * @property string name
 * @property string description
 * @property string category_id
 * @property string price
 * @property string weight
 * @property string stock_quantity
 * @property string image_url
 * @property string image_thumb_url
 * @property int active
 */
class ProductEntity extends Model
{
    CONST STATUS_ACTIVE = 1;

    protected $table = 'product';

    public $incrementing = false;
    public $timestamps = false;

    //protected $guarded = ['password'];

    public function category(){
        //return $this->hasOne('App\Domain\Order\OrderStudentCartEntity','order_id','id');
    }
}