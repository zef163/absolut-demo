<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date_start',
        'date_end',
        'base_code',
        'compare_code',
        'note',
    ];

    /**
     * The attributes that should be casted to native types
     *
     * @var array
     */
    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date'
    ];

    /**
     * Add collection in storage
     *
     * @param array $data
     * @return self
     */
    public function add(array $data): self
    {
        return self::create([
            'date_start' => $data['dateStart'] ?? now(),
            'date_end' => $data['dateEnd'] ?? now(),
            'base_code' => $data['baseCode'],
            'compare_code' => $data['compareCode'],
            'note' => $data['note'] ?? null
        ]);
    }
}
