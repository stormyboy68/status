<?php

namespace ASB\Status\App;

use ASB\Status\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class StatusCommand
{
    /**
     * Get all the models that have this status
     * @param string $status
     */
    public function getModelsHave(string $status): array|Collection
    {
        $status = Status::query()->where('title', $status)->first();
        if (empty($status)) return collect([]);
        foreach (AsbClassMap::getClassMap() as $rel => $class) {
            $temp[$rel] = $status->$rel->all();
        }
        return $temp;
    }

    /**
     * Get all the Statuses of Model
     * @param Model $model
     * @return array|Collection
     */
    public function getStatuses(Model $model, bool|string $pluck = false): array|Collection
    {
        return !$pluck ?
            $model->statuses :
            $model->statuses()->pluck($pluck)->toArray();
    }

    /**
     * Check The model has this Status.
     * @param Model $model
     * @param string $status
     * @return bool
     */
    public function hasStatus(Model $model, string $status): bool
    {
        return in_array($status, $this->getStatuses($model, 'title'));
    }

    /**
     *it assigns a Status to the Model.
     * @param Model $model
     * @param string $status
     * @return mixed
     */
    public function assignStatus(Model $model, string $status): mixed
    {
        $status = Status::query()->firstOrCreate(['title' => $status]);
        return $model->statuses()->sync($status);
    }

    /**
     * it adds a Status to the Model.
     * @param Model $model
     * @param string $status
     * @return mixed
     */
    public function addStatus(Model $model, string $status): mixed
    {
        $status = Status::query()->firstOrCreate(['title' => $status]);
        return $this->hasStatus($model, $status->title) ? false : ($model->statuses()->attach($status) ?? true);
    }

    /**
     * it updates a Status from the Model and replace by new or a status that exists
     * @param Model $model
     * @param string $status
     * @param string $updateStatus
     * @return mixed
     */
    public function updateStatus(Model $model, string $status, string $updateStatus): mixed
    {
        $status = Status::query()->where('title', $status)->first();

        $update_status = Status::query()->firstOrCreate(['title' => $updateStatus]);
        $existing_statuses = $this->getStatuses($model, 'id');
        if (!in_array($update_status->id, $existing_statuses) && $status && in_array($status->id, $existing_statuses)) {
            $temp_statuses = array_replace($existing_statuses, [array_search($status->id, $existing_statuses) => $update_status->id]);
            return $model->statuses()->sync($temp_statuses);
        }
        return false;
    }

    /**
     * it removes a status from the model
     * @param Model $model
     * @param string $status
     * @return mixed
     */
    public function removeStatus(Model $model, string $status): mixed
    {
        $status = Status::query()->where('title', $status)->first();
        $existing_statuses = $this->getStatuses($model, 'id');
        if ($status && in_array($status->id, $existing_statuses)) {
            return $model->statuses()->detach($status);
        }
        return false;
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function removeAllStatus(Model $model): mixed
    {
        return $model->statuses()->detach();

    }

    /* =====================> crud status model <============================ */
    /**
     * it Creates a Status
     * @param string $status
     * @return Status|Model
     */
    public function createStatusModel(string $status): Status|model
    {
        return Status::query()->createOrFirst(['title'=>$status]);

    }

    /**
     * it gets all status
     * @param bool $onlyTrashed It only receives statuses in the Trash if it is True
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllStatusModel(bool $onlyTrashed=false): \Illuminate\Database\Eloquent\Collection
    {
        return !$onlyTrashed?Status::all():Status::onlyTrashed()->get();
    }

    /**
     * it gets a status by title
     * @param string $status
     * @return Model|Builder|null
     */
    public function getStatusModel(string $status): Status|Model|null
    {
        return Status::query()->where('title', $status)->first();
    }

    /**
     * it updates a status by title and replace by new_title
     * @param string $status
     * @param string $update_status
     * @return int
     */
    public function updateStatusModel(string $status, string $update_status): int
    {
        return Status::query()->where('title', $status)->update(['title'=> $update_status]);
    }

    /**
     * it removes a status by title
     * @param string $status
     * @return bool
     */
    public function removeStatusModel(string $status): bool
    {
        $status = Status::query()->where('title', $status)->first();
        return $status?$status->delete():false;
    }
}
