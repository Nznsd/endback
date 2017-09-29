<?php

namespace NTI\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Applicant extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'dob',
        'updated_at',
        'deleted_at'
    ];

    protected function getSurnameAttribute($value)
    {
        return ucfirst($value);
    }

    protected function getFirstnameAttribute($value)
    {
        return ucfirst($value);
    }

    protected function getOthernameAttribute($value)
    {
        return ucfirst($value);
    }

    protected function getGenderAttribute($value)
    {
        return ucfirst($value);
    }

    protected function getMaritalStatusAttribute($value)
    {
        return ucfirst($value);
    }

    protected function getEntryTypeAttribute($value)
    {
        return ucfirst($value);
    }

    protected function getDobAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}