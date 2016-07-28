<?php

namespace Iatstuti\Database\Support;

use Illuminate\Database\Eloquent\Model;

trait OwnsModels
{
    /**
     * {@inheritdoc}
     */
    abstract public function getForeignKey();


    /**
     * {@inheritdoc}
     */
    abstract public function getKey();


    /**
     * Determine if this model owns the given model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  mixed  $foreignKey
     * @param  bool  $strict
     * @return bool
     */
    public function owns(Model $model, $foreignKey = null, $strict = false)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        if ($strict) {
            return $this->getKey() === $model->{$foreignKey};
        }

        return $this->getKey() == $model->{$foreignKey};
    }


    /**
     * Determine if this model doesn't own the given model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  mixed  $foreignKey
     * @param  bool  $strict
     * @return bool
     */
    public function doesntOwn(Model $model, $foreignKey = null, $strict = false)
    {
        return ! $this->owns($model, $foreignKey, $strict);
    }
}
