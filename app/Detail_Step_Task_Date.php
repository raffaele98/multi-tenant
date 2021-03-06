<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail_Step_Task_Date extends Model
{
    protected $table = 'detail_step_task_date';

    public function detail_step_task()
    {
        return $this->belongsTo(Detail_Step_Task::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
