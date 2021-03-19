<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m210317_111136_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer(),
            'uuid' => $this->string(100)->notNull(),
            'availability' => $this->string()->notNull(),
            'condition' => $this->string()->notNull(),
            'description' => $this->string(5000)->notNull(),
            'image_link' => $this->string()->notNull(),
            'link' => $this->string()->notNull(),
            'title' => $this->string(100)->notNull(),
            'price' => $this->string()->notNull(),
            'brand' => $this->string(70)->notNull(),
            'additional_image_link' => $this->string(2000),
            'age_group' => $this->string(),
            'color' => $this->string(100),
            'gender' => $this->string(),
            'google_product_category' => $this->string(250),
            'sale_price' => $this->string(),
            'sale_price_effective_date' => $this->integer(),
            'xmlNode' => $this->text(),
            'node_update_date' => $this->integer(),
            'product_update_date' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%products}}');
    }
}
