<?php
namespace app\controllers;

use app\models\Product;
use app\models\Store;
use yii\base\Controller;

class FeedController extends Controller {
    public function actionTest() : string {
        // Just to be sure that huge amount will be processed
        set_time_limit(60 * 5);

        $storeId = \Yii::$app->request->get('id');

        $store = Store::findOne($storeId);
        if ($store == null) {
            return sprintf("Store %d not found", $storeId);
        }

        // Now we are sending XML
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        \Yii::$app->response->headers->add('Content-Type', 'text/xml');

        $storeTitle = htmlspecialchars($store->title, ENT_XML1 | ENT_COMPAT);
        $storeDesc = htmlspecialchars($store->description, ENT_XML1 | ENT_COMPAT);;


        /*
         * Just yield strings with xml because
         * we don't want to exhaust all memory
         * on huge amount of records.
         */
        $streamResponse = function() use ($store, $storeTitle, $storeDesc) {
            // Stream basic XML markup:
            yield "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
            yield "<rss version=\"2.0\" xmlns:g=\"http://base.google.com/ns/1.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">";
            yield "<channel>";

            yield "<title>$storeTitle</title>";
            yield "<description>$storeDesc</description>";
            yield "<link>$store->link</link>";
            yield "<atom:link href=\"$store->feedUrl\" rel=\"self\" type=\"application/rss+xml\" />";

            $records = $store->getProducts()->each(1000);
            foreach ($records as $product) {
                $shouldUpdate = $product->node_update_date !== $product->product_update_date;
                yield $product->createXML();
                if ($shouldUpdate) {
                    $product->save();
                }
            }

            yield "</channel>";
            yield "</rss>";
        };

        \Yii::$app->response->stream = $streamResponse;

        return '';
    }
}