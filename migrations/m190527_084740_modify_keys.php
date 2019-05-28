<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m190527_084740_modify_keys
 */
class m190527_084740_modify_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $orders = (new Query())
            ->select('*')
            ->from('orders')
            ->all();

        foreach ($orders as $order) {
            $modeId = (int)$order['mode'] + 1;
            $statusId = (int)$order['status'] + 1;

            $this->update(
                'orders',
                ['mode' => $modeId, 'status' => $statusId],
                ['id' => $order['id']]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190527_084740_modify_keys cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190527_084740_modify_keys cannot be reverted.\n";

        return false;
    }
    */
}
