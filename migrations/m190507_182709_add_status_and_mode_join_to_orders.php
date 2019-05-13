<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m190507_182709_add_status_and_mode_join_to_orders
 */
class m190507_182709_add_status_and_mode_join_to_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('orders', 'status', 'status_id');
        $this->renameColumn('orders', 'mode', 'mode_id');

        $this->alterColumn('orders', 'status_id', 'integer');
        $this->alterColumn('orders', 'mode_id', 'integer');

        $orders = (new Query())
            ->select('*')
            ->from('orders')
            ->all();

        foreach ($orders as $order) {
            $modeId = (int)$order['mode_id'] + 1;
            $statusId = (int)$order['status_id'] + 1;

            $this->update(
                'orders',
                ['mode_id' => $modeId, 'status_id' => $statusId],
                ['id' => $order['id']]
            );
        }

        $statusList = ['Pending', 'In progress', 'Completed' , 'Canceled', 'Error'];
        foreach ($statusList as $status) {
            $this->insert('status', ['name' => $status]);
        }

        $modeList = ['Manual', 'Auto'];
        foreach ($modeList as $mode) {
            $this->insert('mode', ['name' => $mode]);
        }

        $this->createIndex(
            'idx-orders-status_id',
            'orders',
            'status_id'
        );

        $this->addForeignKey(
            'fk-orders-status_id',
            'orders',
            'status_id',
            'status',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-orders-mode_id',
            'orders',
            'mode_id'
        );

        $this->addForeignKey(
            'fk-orders-mode_id',
            'orders',
            'mode_id',
            'mode',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190507_182709_add_status_and_mode_join_to_orders cannot be reverted.\n";

        return false;
    }
}
