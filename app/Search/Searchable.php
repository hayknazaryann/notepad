<?php

namespace App\Search;

trait Searchable
{
    /**
     * @return void
     */
    public static function bootSearchable()
    {
        if (config('services.search.enabled')) {
            static::observe(ElasticsearchObserver::class);
        }
    }

    /**
     * @return string
     */
    public function getSearchIndex()
    {
        return $this->getTable();
    }

    /**
     * @return mixed|string
     */
    public function getSearchType()
    {
        if (property_exists($this, 'useSearchType')) {
            return $this->useSearchType;
        }
        return $this->getTable();
    }

    /**
     * @return array
     */
    public function toSearchArray()
    {
        return $this->toArray();
    }
}
