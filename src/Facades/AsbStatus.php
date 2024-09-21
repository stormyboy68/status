<?php

namespace ASB\Status\Facades;

use ASB\Status\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection getModelsHave(string $Status) it gets all the models that have Status.
 *
 * @method static Collection getStatuses(Model $model,bool|string $pluck = false) it gets all the Statuses of Model.
 *
 * @method static boolean hasStatus(Model $model,string|int $Status) it checks the Model has this Status by Title or ID.
 *
 * @method static mixed assignStatus(Model $model,string|int $Status) it assigns a Status to the Model by Title or ID.
 *
 * @method static mixed addStatus(Model $model,string|int $Status) it adds a Status to the Model by Title or ID.
 * @method static mixed updateStatus(Model $model,string|int $Status,string|int $newStatus) it updates a Status from the Model and replace by new Status Or a Status that exists.
 * @method static mixed removeStatus(Model $model,string|int $Status) it removes a Status from the model by Title or ID.
 * @method static mixed removeAllStatus(Model $model) it removes all Statuses from the model.
 *
 * @method static bool createStatusModel(string $Status) it Creates a Status by a new Title.
 * @method static Collection getAllStatusModel(bool $onlyTrashed=false) it gets all of Status And if it is called with "true" parameter, it will get all the deleted Status.
 * @method static Status|Model|null getStatusModel(string|int $Status)  it gets a Status by Title or ID.
 * @method static bool updateStatusModel(string|int $Status, string $update_Status) updates a Status by Title or ID and replace by a new Title.
 * @method static boolean removeStatusModel(int|string $Status) it removes a Status by Title or ID and removing the Status and from all Models.
 * @method static boolean restoreStatusModel(int|string $Status) it restored a Status by Title or ID.
 */
class AsbStatus extends Facade
{
    protected static function getFacadeAccessor():string
    {
            return 'AsbStatus';
    }
}
