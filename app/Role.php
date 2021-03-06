<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Permission');
    }

    public function based_permissions()
    {
        return $this->belongsToMany('App\Permission')->wherePivot('agency_id', null);
    }

    public function isApproved($id)
    {
        return $this->belongsToMany('App\User')->wherePivot('approved', true);
    }
}
