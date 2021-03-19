<?php

namespace app\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    public function rules() : array
    {
        return [
            // required fields
            [['uuid', 'availability', 'condition', 'description', 'image_link', 'link', 'title', 'price', 'brand'], 'required'],
            // field validation
            ['uuid', 'string', 'length' => [0, 100]],
            ['availability', 'in', 'range' => array('in stock', 'out of stock', 'preorder', 'available for order')],
            ['condition', 'in', 'range' => array('new', 'refurbished', 'used')],
            ['age_group', 'in', 'range' => array('arenewborn', 'infant', 'toddler', 'kids', 'adult')],
            ['gender', 'in', 'range' => array('male', 'female', 'unisex')],
            ['description', 'string', 'length' => [0, 5000]],
            ['title', 'string', 'length' => [0, 100]],
            ['brand', 'string', 'length' => [0, 70]],
            ['additional_image_link', 'string', 'length' => [0, 2000]],
            ['color', 'string', 'length' => [0, 100]],
            ['google_product_category', 'string', 'length' => [0, 250]],
            ['node_update_date', 'datetime', 'format' => 'php:U'],
            ['product_update_date', 'datetime', 'format' => 'php:U'],
        ];
    }

    public function createXML(): string
    {
        if ($this->node_update_date != 0 && $this->product_update_date != 0 &&
            $this->node_update_date === $this->product_update_date) {
            return $this->xmlNode;
        }

        $xml = new \DOMDocument();
        $xml->formatOutput = false;

        $item = $xml->createElement("item");

        $item->appendChild($xml->createElement("g:id", htmlspecialchars($this->uuid, ENT_XML1 | ENT_COMPAT)));
        $item->appendChild($xml->createElement("g:title", htmlspecialchars($this->title, ENT_XML1 | ENT_COMPAT)));
        $item->appendChild($xml->createElement("g:description", htmlspecialchars($this->description, ENT_XML1 | ENT_COMPAT)));
        $item->appendChild($xml->createElement("g:link", $this->link));
        $item->appendChild($xml->createElement("g:image_link", $this->image_link));
        $item->appendChild($xml->createElement("g:brand", htmlspecialchars($this->brand, ENT_XML1 | ENT_COMPAT)));
        $item->appendChild($xml->createElement("g:condition", $this->condition));
        $item->appendChild($xml->createElement("g:availability", $this->availability));
        $item->appendChild($xml->createElement("g:price", $this->price));

        if ($this->age_group != null) {
            $item->appendChild($xml->createElement("g:age_group", $this->age_group));
        }

        if ($this->google_product_category != null) {
            $item->appendChild($xml->createElement("g:google_product_category", htmlspecialchars($this->google_product_category, ENT_XML1 | ENT_COMPAT)));
        }

        if ($this->gender != null) {
            $item->appendChild($xml->createElement("g:gender", $this->gender));
        }

        if ($this->additional_image_link != null) {
            $links = explode(',', $this->additional_image_link);
            foreach ($links as $url) {
                $item->appendChild($xml->createElement("additional_image_link", $url));
            }
        }

        if ($this->sale_price != null) {
            $item->appendChild($xml->createElement("g:sale_price", $this->sale_price));
        }


        if ($this->sale_price_effective_date != null) {
            $formattedDate = date('c', $this->sale_price_effective_date);
            $item->appendChild($xml->createElement("g:sale_price_effective_date", $formattedDate));
        }


        if ($this->color != null) {
            $item->appendChild($xml->createElement("color", $this->color));
        }

        $this->xmlNode = $xml->saveXML($item);
        $this->node_update_date = (new \DateTime())->getTimestamp();

        return $this->xmlNode;
    }

    public function beforeSave($insert) {
        $this->createXML(); // Create XML cache to speed up XML generation
        $this->product_update_date = (new \DateTime())->getTimestamp();

        return parent::beforeSave($insert);
    }

    public static function tableName()
    {
        return 'products';
    }

    public function getStore() {
        return $this->hasOne(Store::class, ['store_id' => 'id']);
    }
}