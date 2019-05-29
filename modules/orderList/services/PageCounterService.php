<?php

namespace app\modules\orderList\services;

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * Class PageCounterService
 */
class PageCounterService
{
    /**
     * @param ActiveDataProvider $dataProvider
     * @return string
     */
    public function createCounter(ActiveDataProvider $dataProvider): string
    {
        $pageCount = $dataProvider->pagination->getPageCount();
        $pageNum = $dataProvider->pagination->page;
        $pageLimit = $dataProvider->pagination->limit;
        $pageOffset = $dataProvider->pagination->offset;
        $pageSum = $dataProvider->totalCount;

        if($pageCount == 1){
            return html::tag('span',
                Yii::t('app', 'Total records:') . ' ' . $pageSum);
        }
        else{
            $pageFrom = $pageNum * $pageLimit + 1;
                if (($pageOffset + $pageLimit) < $pageSum) {
                    $pageTo = ($pageOffset + $pageLimit);
                }
                else {
                    $pageTo = $pageSum;
                }

            return html::tag('span',
                Yii::t('app', 'from') . ' ' . $pageFrom . ' ' .
                Yii::t('app', 'to') . ' ' . $pageTo . ' ' .
                Yii::t('app', 'of') . ' ' . $pageSum);
        }
    }
}
