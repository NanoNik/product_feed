<?php
namespace app\commands;

use app\models\Product;
use app\models\Store;
use DateTime;
use yii\console\Controller;
use yii\console\ExitCode;

class RandomController extends Controller {
    public function actionIndex(int $productCount) {
        set_time_limit(3600 * 2);

        // To generate fake data
        $faker = \Faker\Factory::create();

        // Create new store:
        $store = new Store;
        $store->title = $faker->sentence(2);
        $store->description = $faker->paragraph;
        $store->link = $faker->url;
        $store->feedUrl = $faker->url;

        if (!$store->save()) {
            print_r($store->errors);
        }

        // Create some random products for store

        $googleCategory = file_get_contents('taxonomy-with-ids.en-US.txt');
        $categories = explode("\n", $googleCategory);
        array_shift($categories); // remove comment from first line

        $avTypes = array('in stock', 'out of stock', 'preorder', 'available for order');
        $condTypes = array('new', 'refurbished', 'used');
        $genders = array('male', 'female', 'unisex');
        $ageGroups = array('arenewborn', 'infant', 'toddler', 'kids', 'adult');

        for ($i = 0;$i < $productCount;$i++) {
            $model = new Product;
            $model->store_id = $store->id;
            $model->uuid = $faker->uuid;
            $model->availability = $avTypes[array_rand($avTypes, 1)];
            $model->condition = $condTypes[array_rand($condTypes, 1)];
            $model->description = $faker->paragraph();
            $model->image_link = $faker->imageUrl();
            $model->link = $faker->url;
            $model->title = $faker->sentence(3);

            $currency = $faker->currencyCode;
            $price = $faker->randomFloat(2, 1.0, 999.9);
            $model->price = $price . ' ' . $currency;
            $model->brand = $faker->word();

            if ($faker->boolean() && sizeof($categories) > 0) {
                $category = $categories[$faker->numberBetween(0, sizeof($categories) - 1)];
                $model->google_product_category = substr($category, strpos($category, '-') + 2);
            }

            if ($faker->boolean()) {
                $model->gender = $genders[array_rand($genders, 1)];
            }

            if ($faker->boolean()) {
                $model->age_group = $ageGroups[array_rand($ageGroups, 1)];
            }

            if ($faker->boolean()) {
                $model->color = $faker->colorName;
            }

            // Should add addition images
            if ($faker->boolean()) {
                $images = "";
                for ($j = 0;$j < $faker->randomDigit;$j++) {
                    $images .= $faker->imageUrl() . ',';
                }

                if (strlen($images) > 0) {
                    $images = substr($images, 0, strlen($images) - 1);
                    $model->additional_image_link = $images;
                }
            }

            if ($faker->boolean()) {
                $discount = $faker->randomFloat(2, 1.0, $price - 1);

                $model->sale_price = ($price - $discount) . ' ' . $currency;
                $model->sale_price_effective_date = $faker->unixTime(new DateTime('+3 weeks'));
            }

            if (!$model->save()) {
                print_r($model->errors);
            }
        }


        return ExitCode::OK;
    }
}
