<?php


namespace App\Widgets;


use App\Services\CustomOrder;
use App\Support\Widget\AbstractWidget;
use App\Models\Banner as BannerModel;

class Banner extends AbstractWidget
{

    protected $config = [
        'type' => 'default',
        'limit' => 10,
    ];

    public function getData(array $params)
    {
        return [
            'banners' => app(CustomOrder::class)
                ->order(BannerModel::byType($this->config['type'])->limit($this->config['limit'])->recent()->get()),
        ];
    }

}
