<?php

namespace ASB\Status\App;

use ASB\Status\App\Requests\StatusRequest;
use ASB\Status\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ASB\Status\App\AsbClassMap;

class StatusCommand
{
    /**
     * it gets all the models that have Status.
     * @param string|int $Status Title|ID Title|ID
     * @return array|Collection
     */
    public function getModelsHave(string|int $Status): array|Collection
    {
        $temp=[];
        $Status = $this->getStatusModel($Status);
        if (empty($Status)) return collect([]);
        foreach (AsbClassMap::getClassMap() as $rel => $class) {
            $current_temp= $Status->$rel->all();
            if(!$current_temp) continue;
            $temp[$rel] = $current_temp;
        }
        return $temp;
    }

    /**
     * it gets all the Statuses of Model.
     * @param Model $model
     * @return array|Collection
     */
    public function getStatuses(Model $model, bool|string $pluck = false): array|Collection
    {
        return !$pluck && !empty($model->statuses) ?
            $model->statuses :
            $model->statuses()->pluck($pluck)->toArray();
    }

    /**
     * it checks The model has this Status by Title or ID.
     * @param Model $model
     * @param string|int $Status Title|ID
     * @return bool
     */
    public function hasStatus(Model $model, string|int $Status): bool
    {
        return in_array($Status, $this->getStatuses($model, is_int($Status)?'id':'title'));
    }

    /**
     * it assigns a Status to the Model by Title or ID.
     * @param Model $model
     * @param string|int $Status Title|ID
     * @return bool|Collection
     */
    public function assignStatus(Model $model, string|int $Status): bool|Collection
    {
        if($this->isTrashed($Status)) return false;
        $Status = $this->firstOrCreateStatusInternal($Status);
        if(is_bool($Status) && $Status) return false;
        $model->statuses()->sync($Status);
        return $model->statuses;
    }

    /**
     * it adds a Status to the Model by Title or ID.
     * @param Model $model
     * @param string|int $Status Title|ID
     * @return bool|Collection
     */
    public function addStatus(Model $model, string|int $Status): bool|Collection
    {
        if($this->isTrashed($Status)) return false;
        $Status = $this->firstOrCreateStatusInternal($Status);
        if(is_bool($Status) && $Status) return false;
        return  $this->hasStatus($model, $Status->title) ? $model->statuses :
            ($model->statuses()->attach($Status) ?? $model->statuses);
    }

    /**
     * it updates a Status from the Model and replace by new Status Or a Status that exists.
     * @param Model $model
     * @param string|int $Status Title|ID
     * @param string|int $updateStatus Title|ID
     * @return bool
     */
    public function updateStatus(Model $model, string|int $Status, string|int $updateStatus): bool
    {
        if (!$Status = $this->getStatusModel($Status)) return false;
        $update_Status = $this->firstOrCreateStatusInternal($updateStatus);
        $existing_statuses = $this->getStatuses($model, 'id');
        if (in_array($Status->id, $existing_statuses)) {
            if (in_array($update_Status->id, $existing_statuses)){
                unset($existing_statuses[array_search($update_Status->id, $existing_statuses)]);
            }
            $temp_statuses = array_replace($existing_statuses,
                [array_search($Status->id, $existing_statuses) => $update_Status->id]);
            return $existing_statuses && $model->statuses()->sync($temp_statuses);
        }
        return false;
    }

    /**
     * it removes a Status from the model by Title or ID
     * @param Model $model
     * @param string|int $Status Title|ID
     * @return mixed
     */
    public function removeStatus(Model $model, string|int $Status): mixed
    {
        $Status = $this->getStatusModel($Status);
        $existing_statuses = $this->getStatuses($model, 'id');
        if ($Status && in_array($Status->id, $existing_statuses)) {
            return $model->statuses()->detach($Status);
        }
        return false;
    }

    /**
     * it removes all the Status from the Model
     * @param Model $model
     * @return mixed
     */
    public function removeAllStatus(Model $model): mixed
    {
        return $model->statuses()->detach();

    }

    /* =====================> crud Status model <============================ */
    /**
     * it Creates a Status by a new Title
     * @param string $Status
     * @return bool
     */
    public function createStatusModel(string $Status): bool
    {
        return !StatusRequest::rules($Status) && Status::query()->createOrFirst(['title'=>$Status]);
    }

    /**
     * it gets all of Status And if it is called with "true" parameter, it will get all the deleted Status.
     * @param bool $onlyTrashed It only receives Statuses in the Trash if it is True
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllStatusModel(bool $onlyTrashed=false): \Illuminate\Database\Eloquent\Collection
    {
        return !$onlyTrashed?Status::all():Status::onlyTrashed()->get();
    }

    /**
     * it gets a Status by Title or ID.
     * @param string|int $Status Title|ID
     * @return Status|null
     */
    public function getStatusModel(string|int $Status): ?Status
    {
        return Status::query()->where('title', $Status)->first() ??
            Status::query()->where('id', $Status)->first();
    }

    /**
     * it updates a Status by Title or ID and replace by a new Title
     * @param string|int $Status Title|ID
     * @param string $update_Status
     * @return bool
     */
    public function updateStatusModel(string|int $Status, string $update_Status): bool
    {
        $Status = $this->getStatusModel($Status);
        return $Status && !StatusRequest::rules($Status) && $Status->update(['title'=> $update_Status]);
    }

    /**
     * it removes a Status by title or id
     * @param int|string $Status
     * @return bool
     */
    public function removeStatusModel(int|string $Status): bool
    {
        $Status = $this->getStatusModel($Status);
        return $Status && $Status->delete();
    }
    /**
     * it restores a $Status by title or id
     * @param int|string $Status
     * @return bool
     */
    public function restoreStatusModel(int|string $Status): bool
    {
        $Status = Status::onlyTrashed()->where('title', $Status)->first() ?? Status::onlyTrashed()->where('id', $Status)->first();
        return $Status && $Status->restore();
    }
     /**
     * @param int|string $Status
     * @return Status|bool|Model
     */
    public function firstOrCreateStatusInternal(int|string $Status): bool|Model|Status
    {
        return Status::query()->where(['id' => $Status])->first() ??
            is_int($Status) ?: Status::query()->firstOrCreate(['title' => $Status]);
    }

    /*====================================> Validation <===========================================*/
    /**
     * @param string $Status
     * @return bool
     */
    public function isTrashed(string $Status): bool
    {
        return Status::onlyTrashed()->where(['id' => $Status])->first() ||
         Status::onlyTrashed()->where(['title' => $Status])->first();
    }
}
