<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'references';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'iso',
        'name',
    ];

    /**
     * Create or Update reference CBR
     *
     * @param array $data
     * @return Reference
     */
    public function addOrUpdate(array $data): self
    {
        return self::updateOrCreate(
            ['code' => $data['code']],
            [
                'iso' => $data['iso'],
                'name' => $data['name'],
            ]
        );
    }
}
