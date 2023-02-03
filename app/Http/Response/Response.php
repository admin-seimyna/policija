<?php

namespace App\Http\Response;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Response
{
    use ResponseTrait;

    const REPOSITORY_METHOD_KEY = 'r';

    /**
     * @var array|null
     */
    public ?array $data = null;

    /**
     * @var FormRequest|Request
     */
    public $request = null;

    /**
     * @var Builder|null
     */
    protected ?Builder $builder = null;

    /**
     * @var string|null
     */
    protected ?string $requestFilter = null;

    /**
     * @var string|null
     */
    protected ?string $commit = null;

    /**
     * @var string|null
     */
    protected ?string $repository = null;

    /**
     * @var string|null
     */
    protected ?string $repositoryMethod = null;

    /**
     * @var array
     */
    protected array $policies = [];

    /**
     * @var int|null
     */
    protected ?int $pagination = null;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static(\request());
    }

    /**
     * @param FormRequest|Request $request
     * @return static
     */
    public function setRequest($request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param Builder $builder
     * @return $this
     */
    public function setQuery(Builder $builder): self
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * @param string $commit
     * @return $this
     */
    public function setCommit(string $commit): self
    {
        $this->commit = $commit;
        return $this;
    }

    /**
     * @return $this
     */
    public function setPagination(int $limit = 15): self
    {
        $this->pagination = $limit;
        return $this;
    }

    /**
     * @param $filter
     * @return $this
     */
    public function setFilter($filter): self
    {
        $this->requestFilter = $filter;
        return $this;
    }

    /**
     * @param string $repository
     * @param string|null $method
     * @return $this
     */
    public function setRepository(string $repository, ?string $method = null): self
    {
        $this->repository = $repository;
        $this->repositoryMethod = $method;

        return $this;
    }

    /**
     * @param callable|string|null $callback
     * @return $this
     */
    public function handle($callback = null): self
    {
        $data = null;
        if (is_null($callback)) {
            if ($this->builder) {
                if ($this->requestFilter) {
                    $this->builder = $this->requestFilter::apply($this->builder, $this->request);
                }

                if ($this->repository) {
                    $method = $this->repositoryMethod ?? $this->request->query(static::REPOSITORY_METHOD_KEY);
                    if (method_exists($this->repository, $method)) {
                        $this->builder = $this->repository::$method($this->builder);
                    }
                }
                $data = $this->pagination ? $this->builder->paginate($this->pagination) : $this->builder->get();
                $data = $data->toArray();

                if ($this->commit) {
                    $data = [$this->commit => $data];
                }
            }
        } else {
            $data = is_string($callback) ? (new $callback($this->request))->get() : $callback($this);
            if (!is_array($data) && method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }
        }

        if (!is_array($data)) {
            return $this;
        }

        $this->data = array_merge($this->data ?? [], $data);
        return $this;
    }

    /**
     * @param string $method
     * @param $arguments
     * @return $this
     */
    public function authorize(string $method, $arguments): self
    {
        $response = Gate::inspect($method, $arguments);
        if (!$response->allowed()) {
            abort($response->code(), $response->message());
        }

        return $this;
    }

    /**
     * @param int $code
     * @return JsonResponse
     */
    public function json(int $code = 200): JsonResponse
    {
        return new JsonResponse($this->data ?? [], $code);
    }
}
