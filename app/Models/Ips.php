<?php
/**
 * User: james
 * Date: 2/16/19
 * Time: 11:24 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ips extends Model
{

    protected $fillable = [
        'ip',
        'name'
    ];
}