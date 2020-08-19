<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * @package App
 */
class File extends Model
{
    /** @var string[] The fields that can be mass assigned */
    protected $fillable = ['name', 'path', 'type'];
}
