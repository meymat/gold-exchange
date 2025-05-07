<?php

namespace Modules\core\app\Traits\ModelsTrait;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait GeneralCrudTrait
{
    protected int $defaultLimit = 15;

    public function saveModel(array $data): Model
    {
        return $this->create($data);
    }

    public function insertModel(array $data): bool
    {
        return $this->insert($data);
    }

    public function getList(int $limit = null, string $sortBy = 'id', string $sortDirection = 'desc'): LengthAwarePaginator
    {
        $limit = $limit ?? $this->defaultLimit;
        return $this->orderBy($sortBy, $sortDirection)->paginate($limit);
    }

    public function getAll()
    {
        return $this->all();
    }

    public function getById($id): Model
    {
        return $this->findOrFail($id);
    }

    public function getByIdAndLock($id): Model
    {
        return $this->where('id', $id)->lockForUpdate()->firstOrFail();
    }

    public function editModel($id, array $data): Model
    {
        $model = $this->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function deleteById($id): bool
    {
        return $this->findOrFail($id)->delete();
    }

    public function restoreById($id): bool
    {
        return $this->onlyTrashed()->findOrFail($id)->restore();
    }

    public function forceDeleteById($id): bool
    {
        return $this->withTrashed()->findOrFail($id)->forceDelete();
    }

    public function findByField(string $field, $value): ?Model
    {
        return $this->where($field, $value)->first();
    }
    public function _filterAll(array $fields, array $orderBy = ['id' => 'desc'], $query = null)
    {
        return $this->applyFilters($fields, $orderBy, $query)->get();
    }
    public function _filter(array $fields, array $orderBy = ['id' => 'desc'], int $limit = null, $query = null): LengthAwarePaginator
    {
        $limit = $limit ?? $this->defaultLimit;
        return $this->applyFilters($fields, $orderBy,$query)->paginate($limit);
    }

    public function _filterCount(array $fields): int
    {
        return $this->applyFilters($fields, [])->count();
    }

    public function _filterSum(array $fields,$sumField): int
    {
        return $this->applyFilters($fields, [])->sum($sumField);
    }

    public function firstOrSave(array $find, array $data = []): Model
    {
        return $this->firstOrCreate($find, array_merge($data, $find));
    }

    public function scopeFilter(Builder $query, array $fields, array $orderBy): Builder
    {
        return $this->applyFilters($fields, $orderBy, $query);
    }

    public function applyFilters(array $fields, array $orderBy, Builder $query = null): Builder
    {
        $query = $query ?? $this->newQuery();

        $this->applyDateFilters($query, $fields);
        $this->applyFieldFilters($query, $fields);
        $this->applyOrderBy($query, $orderBy);

        return $query;
    }

    private function applyDateFilters(Builder &$query, array &$fields): void
    {
        if (isset($fields['from'])) {
            $query->where('created_at', '>=', $fields['from']);
            unset($fields['from']);
        }
        if (isset($fields['to'])) {
            $query->where('created_at', '<=', $fields['to']);
            unset($fields['to']);
        }
    }

    private function applyFieldFilters(Builder &$query, array $fields): void
    {

        foreach ($fields as $field => $value) {
            if ($field === 'orWhere' && is_array($value)) {
                $this->applyOrWhereConditions($query, $value);
            } else {

                if ($this->isRelationField($field)) {
                    [$relation, $relationField] = explode('__', $field);
                    if ($this->isFilterableRelation($relation, $relationField)) {
                        $query->whereHas($relation, function ($relationQuery) use ($relationField, $value) {
                            $this->applyFieldCondition($relationQuery, $relationField, $value);
                        });
                    }
                } else {
                    $this->applyFieldCondition($query, $field, $value);
                }
            }
        }
    }

    private function applyOrWhereConditions(Builder $query, array $orConditions): void
    {
        $query->where(function ($query) use ($orConditions) {
            foreach ($orConditions as $conditionGroup) {
                $query->orWhere(function ($subQuery) use ($conditionGroup) {
                    foreach ($conditionGroup as $key => $val) {
                        $this->applyFieldCondition($subQuery, $key, $val, true);
                    }
                });
            }
        });
    }

    private function applyFieldCondition(Builder $query, string $field, $value, bool $orWhere = false): void
    {
        $condition = in_array($field, $this->searchable ?? []) ? 'like' : '=';
        if (is_array($value)) {
            $query->where(function ($query) use ($condition,$field, $value, $orWhere) {
                foreach ($value as $v) {
                    $query->orWhere($field, $condition, $condition === 'like' ? "%$v%" : $v);
                }
            });
        } else {
            $orWhere
                ? $query->orWhere($field, $condition, $condition === 'like' ? "%$value%" : $value)
                : $query->where($field, $condition, $condition === 'like' ? "%$value%" : $value);
        }
    }

    private function applyOrderBy(Builder &$query, array $orderBy): void
    {
        foreach ($orderBy as $field => $direction) {
            $query->orderBy($field, $direction);
        }
    }

    private function isRelationField(string $field): bool
    {
        return strpos($field, '__') !== false;
    }

    private function isFilterableRelation(string $relation, string $field): bool
    {
        return isset($this->relationships[$relation]) && in_array($field, $this->relationships[$relation]);
    }


}

