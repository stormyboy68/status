<?php

namespace ASB\Status\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Status extends Model
{
    use HasFactory,SoftDeletes;

    protected $hidden = ['pivot'];

    protected $fillable = [
        'title',
    ];

    public static function CreateTable()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
