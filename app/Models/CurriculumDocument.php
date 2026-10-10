<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumDocument extends Model
{
    use HasFactory;

    protected $table = 'curriculum_documents';

    protected $fillable = [
        'curriculum_id',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'extracted_text',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }
}
