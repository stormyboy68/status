<?php

namespace ASB\Status\Traits;

use ASB\Status\App\AsbClassMap;
use ASB\Status\Models\Status;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasStatus
{
    public function __construct()
    {
        $this->with[]='statuses';
        $this->hidden[] = 'pivot';
        AsbClassMap::handler(self::class);
    }
    public function statuses(): MorphToMany
    {
        return $this->morphToMany(Status::class, 'statusable','statusables');
    }

}
