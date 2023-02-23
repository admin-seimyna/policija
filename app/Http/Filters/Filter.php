<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Filter extends FilterRules
{
    /**
     * @var array
     */
    protected array $filters = [];

    /**
     * @var array
     */
    protected array $sortable = [];

    /**
     * @var array
     */
    protected array $methods = [];

    /**
     * @var array|string[]
     */
    private array $methodsMap = [
        'in' => 'whereIn',
    ];

    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * @var Request|FormRequest
     */
    protected $request;

    /**
     * @param Builder $builder
     * @param null $request
     */
    public function __construct(Builder $builder, $request = null)
    {
        $this->builder = $builder;
        $this->request = $request ?? \request();
    }

    /**
     * @param Builder $builder
     * @param Request|FormRequest $request
     * @return Builder
     */
    public static function apply(Builder $builder, $request = null): Builder
    {
        return (new static($builder, $request))->filter();
    }

    /**
     * @return Builder
     */
    public function filter(): Builder
    {
        $values = $this->request->query('filters');
        collect($values ? json_decode($values, true) : [])
            ->filter(function ($value, $name) {
                return $this->validate($name, $value);
            })->each(function ($value, $name) {
                $method = Str::camel('filter_' . $name);
                if (method_exists($this, $method)) {
                    $this->builder = $this->{$method}($this->builder, $value);
                } else {
                    if (empty($this->methods[$name])) {
                        return;
                    }

                    if (empty($this->methodsMap[$this->methods[$name]])) {
                        return;
                    }

                    $method = $this->methodsMap[$this->methods[$name]];
                    $this->builder->{$method}($name, $value);
                }
            });

        $this->sort();

        return $this->builder;
    }

    /**
     * Sort
     */
    private function sort(): void
    {
        $sortable = $this->request->query('sort');
        collect($sortable ? json_decode($sortable, true) : [])
            ->filter(function ($value, $name) {
                return in_array($name, $this->sortable);
            })->each(function ($value, $name) {
                $method = Str::camel('sort_' . $name);
                if (method_exists($this, $method)) {
                    $this->{$method}($this->builder, $value);
                } else {
                    $this->builder->orderBy($name, $value);
                }
            });
    }

    /**
     * @param Builder $builder
     * @param string $column
     * @param array $dates
     * @return Builder
     */
    public function applyDateFilter(Builder $builder, string $column, array $dates): Builder
    {
        if (!empty($dates['from']) && !empty($dates['to'])) {
            return $builder->whereBetween($column, [$dates['from'], $dates['to']]);
        }
        return $builder->where($column, $dates['from'] ?? $dates['to']);
    }
}
