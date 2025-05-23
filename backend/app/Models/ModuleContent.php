<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleContent extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'module_contents';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'content_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_progress_id',
        'content_type',
        'content_title',
        'content_data',
        'sequence_order'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sequence_order' => 'integer',
    ];

    /**
     * Get the module progress that owns the content.
     */
    public function moduleProgress(): BelongsTo
    {
        return $this->belongsTo(ModuleProgress::class, 'module_progress_id', 'progress_id');
    }
}
