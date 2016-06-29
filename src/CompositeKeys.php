<?php
namespace UmpactoSoluciones\LaravelEloquentSupport;

use UmpactoSoluciones\LaravelEloquentSupport\Eloquent\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait CompositeKeys
{
  /**
  * Get increment false, because compisite not increment keys.
  *
  * @return bool
  */
  public function getIncrementing()
  {
    return false;
  }
  /**
  * Create a new Eloquent query builder for the model.
  *
  * @param  \Illuminate\Database\Query\Builder $query
  * @return \Illuminate\Database\Eloquent\Builder|static
  */
  public function newEloquentBuilder($query)
  {
    return new QueryBuilder($query);
  }

  public function getQualifiedKeyName()
  {

    //return $this->getTable().'.'.$this->getKeyName();
    $keys = $this->getKeyName();
    if(!is_array($keys))
    {
      return $this->getTable().'.'.$keys;
    }else
    {
      $qualifiedKeyNames = new Collection;
      foreach($keys as $keyName)
      {
        $qualifiedKeyNames->push($this->getTable().'.'.$keyName);
      }

      return $qualifiedKeyNames->toArray();
    }
  }

  /**
  * Set the keys for a save update query.
  *
  * @param  \Illuminate\Database\Eloquent\Builder  $query
  * @return \Illuminate\Database\Eloquent\Builder
  */
  protected function setKeysForSaveQuery(Builder $query)
  {
    $keys = $this->getKeyName();
    if(!is_array($keys)){
      return parent::setKeysForSaveQuery($query);
    }

    foreach($keys as $keyName){
      $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
    }

    return $query;
  }

  /**
  * Get the primary key value for a save query.
  *
  * @param mixed $keyName
  * @return mixed
  */
  protected function getKeyForSaveQuery($keyName = null)
  {
    if(is_null($keyName)){
      $keyName = $this->getKeyName();
    }

    if (isset($this->original[$keyName])) {
      return $this->original[$keyName];
    }

    return $this->getAttribute($keyName);
  }
}
