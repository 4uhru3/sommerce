<?php


namespace app\modules\orderList\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class OrdersExport
{
  public function exportCSV(ActiveDataProvider $dataProvider, ActiveRecord $orders): string
  {
      $dataProvider->setPagination(false);

      $model = $dataProvider->getModels();

      $data = "ID;User;Link;Quantity;Service;Status;Mode;Created \r\n";
      foreach ($model as $value) {
          $data .= $value->id .
              ';' . $value->user .
              ';' . $value->link .
              ';' . $value->quantity .
              ';' . $value->services->name .
              ';' . $orders::STATUS[$value->status] .
              ';' . $orders::MODE[$value->mode] .
              ';' . date('Y-m-d H:i:s', $value->created_at).
              "\r\n";
      }

      header('Content-type: text/csv');
      header('Content-Disposition: attachment; filename="export_' . date('d.m.Y') . '.csv"');

      echo $data;
      die;
  }
}