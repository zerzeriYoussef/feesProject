<?php
namespace App\Models;
use App\Models\sections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*
    protected $fillable = [
        'Product_name',
        'description',
        'section_id',
    ];
    */
    public function section()
    {
        return $this->belongsTo(sections::class);
    }
    
}

