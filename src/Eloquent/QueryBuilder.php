<?php

namespace UmpactoSoluciones\LaravelEloquentSupport\Eloquent;

use \Illuminate\Database\Eloquent\Builder;

class QueryBuilder extends Builder
{
  /**
   * Find a model by its primary key.
   *
   * @param  array  $id
   * @param  array  $columns
   * @return \Illuminate\Database\Eloquent\Model|Collection|static
   */
  public function findMany($id, $columns = array('*'))
  {

    if (!is_array($this->model->getQualifiedKeyName()))
    {
      if (empty($id)) return $this->model->newCollection();
      $this->query->whereIn($this->model->getQualifiedKeyName(), $id);
      return $this->get($columns);
    }else {

      foreach ($id as $i => $key) {
        $this->query->where($this->model->getQualifiedKeyName()[$i],'=',$key);
      }

      return $this->first($columns);
    }

  }
}
