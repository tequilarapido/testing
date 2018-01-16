<?php

namespace Tequilarapido\Testing\Concerns;

trait OneOrMany
{
    protected function oneOrMany($model, $attributes, $times, $translation = [], $persist = true)
    {
        if (1 === $times) {
            return $this->one($model, $attributes, $translation, $persist);
        }

        $modelItems = [];
        foreach (range(1, $times) as $i) {
            $modelItems[] = $this->one($model, $attributes, $translation, $persist);
        }

        return $modelItems;
    }

    protected function manyByAttributes($model, $list_attributes)
    {
        $modelItems = [];
        foreach ($list_attributes as $attributes) {
            $modelItems[] = factory($model)->create($attributes);
        }

        return $modelItems;
    }

    protected function one($model, $attributes, $translation = [], $persist = true)
    {
        $item = $persist ? factory($model)->create($attributes) : factory($model)->make($attributes);

        if (!empty($translation)) {
            factory($translation['class'])->create([$translation['foreign_key'] => $item->id, 'locale' => 'en']);
            factory($translation['class'])->create([$translation['foreign_key'] => $item->id, 'locale' => 'fr']);
            factory($translation['class'])->create([$translation['foreign_key'] => $item->id, 'locale' => 'es']);
        }

        return $item;
    }
}
