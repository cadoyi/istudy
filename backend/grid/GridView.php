<?php

namespace backend\grid;

use Yii;

class GridView extends \yii\grid\GridView
{
    
    public $tableOptions = [
        'class' => [
            'table',
            'table-bordered',
            'table-stripped',
            'table-hover',
            'table-responsive',
            'table-gray',
        ],
    ];


    /**
     * Renders the summary text.
     */
    public function renderSummary()
    {
        $count = $this->dataProvider->getCount();
        if ($count <= 0) {
            return '<div class="nosummary">&nbsp;</div>';
        }
        return parent::renderSummary();
    }

}