<?php

namespace app\models;

use yii\db\ActiveRecord;

class Store extends ActiveRecord {
    public function rules() : array {
        return [
            [['title', 'description', 'link', 'feedUrl'], 'required'],
            ['title', 'string', 'length' => [1, 100]],
            ['description', 'string', 'length' => [1, 1000]],
            ['link', 'url', 'defaultScheme' => 'https'],
            ['feedUrl', 'url', 'defaultScheme' => 'https']
        ];
    }

    public function getProducts() {
        return $this->hasMany(Product::class, ['store_id' => 'id']);
    }
}