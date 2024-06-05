<?php

namespace ASB\Status\Facades;

use ASB\Status\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection getModelsHave(string $status) Get all the Models that have this Status.
 *
 * @method static Collection getStatuses(Model $model) Get all the Statuses of Model.
 *
 * @method static boolean hasStatuses(Model $model,string $status) Check The model has this Status.
 *
 * @method static mixed assignStatus(Model $model,string $status) it assigns a Status to the Model.
 *
 * @method static mixed addStatus(Model $model,string $status) it adds a Status to the Model.
 * @method static mixed updateStatus(Model $model,string $status,string $newStatus) it updates a Status from the Model and replace by new or a status that exists.
 * @method static mixed removeStatus(Model $model,string $status) it removes a status from the model.
 * @method static mixed removeAllStatus(Model $model) it removes all statuses from the model.
 *
 * @method static Status|Model createStatusModel(string $status) it Creates a Status.
 * @method static Collection getAllStatusModel(bool $onlyTrashed=false) it gets all Status.
 * @method static Status|Model|null getStatusModel(string $status)  it gets a Status by title.
 * @method static int updateStatusModel(string $status, string $update_status): it updates a Status by title and replace by new_title.
 * @method static boolean removeStatusModel(string $status) it removes a Status by title and removing the Status and from all Models.
 */
class AsbStatus extends Facade
{
    protected static function getFacadeAccessor():string
    {
            return 'AsbStatus';
    }
}
