<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\web\Response;

class ProductController extends ActiveController {
    public $modelClass = 'app\models\Product';
}