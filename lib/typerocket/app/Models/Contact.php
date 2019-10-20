<?php
namespace App\Models;

use \TypeRocket\Models\Model;

class Contact extends Model
{
    protected $resource = 'pipelab_contacts';

    protected $fillable = [
		'owner_id',
		'first_name',
		'last_name',
		'gender',
		'type',
		'job_title',
		'mobile_number',
		'address',
		'city',
		'country',
		'zipcode',
		'source'
    ];

    protected $guard = [
    	'id',
	    'created_at',
	    'modified_at'
    ];

    protected $cast = [
    	'id' => 'int',
	    'first_name' => 'string',
	    'last_name' => 'string'
    ];
}
