<?php

namespace App\Http\Filters;

use Illuminate\Support\Str;

class FilterRules
{
    /**
     * @param $filter
     * @param $value
     * @return bool
     */
    protected function validate($filter, $value): bool
    {
        if (empty($this->filters[$filter])) {
            return true;
        }

        $isValid = true;
        collect(explode('|', $this->filters[$filter]))->each(function ($rule) use (&$isValid, $value) {
            $method = Str::camel($rule . '_rule');
            if (method_exists($this, $method)) {
                $isValid = $this->{$method}($value);
            }
            if (!$isValid) {
                return false;
            }
        });
        return $isValid;
    }

    /**
     * @param $value
     * @return bool
     */
    public function requiredRule($value): bool
    {
        return $value === 0 || !empty($value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function arrayRule($value): bool
    {
        return is_array($value);
    }
}
