<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Panoscape\History\HasHistories;

class Item extends Model
{
    use HasHistories;

    protected $fillable = ['name','key'];

    public function getModelLabel()
    {
        return 'item';
    }
}
