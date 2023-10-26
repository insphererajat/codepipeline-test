<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * Description of AdminSidebarWidget
 *
 * @author Amit Handa<insphere.amit@gmail.com>
 */
class AdminSidebarWidget extends Widget
{

    private $menu;

    public function init()
    {
        parent::init();
        $this->menu = [
            [
                'name' => 'Dashboard',
                'url' => '',
                'icon' => 'fa fa-home',
                'visibility' => TRUE,
            ],
            [
                'name' => 'Subject',
                'url' => Url::toRoute(['subject/index']),
                'icon' => 'fa fa-book',
                'visibility' => TRUE,
            ],
            [
                'name' => 'List Type',
                'url' => Url::toRoute(['list-type/index']),
                'icon' => 'fa  fa-list-alt',
                'visibility' => TRUE,
            ],
            [
                'name' => 'Qualification',
                'url' => Url::toRoute(['qualification/index']),
                'icon' => 'fa fa-graduation-cap',
                'visibility' => TRUE,
            ],
            [
                'name' => 'University',
                'url' => Url::toRoute(['university/index']),
                'icon' => 'fa fa-university',
                'visibility' => TRUE,
            ],
            [
                'name' => 'Location',
                'url' => '',
                'icon' => 'fa fa-location-arrow',
                'visibility' => TRUE,
                'childs' => [
                    [
                        'name' => 'State',
                        'url' => Url::toRoute(['location/state']),
                        'visibility' => TRUE,
                    ],
                    [
                        'name' => 'District',
                        'url' => Url::toRoute(['location/district']),
                        'visibility' => TRUE,
                    ],
                    [
                        'name' => 'Tehsil',
                        'url' => Url::toRoute(['location/tehsil']),
                        'visibility' => TRUE,
                    ]
                ]
            ],
            
        ];
    }

    public function run()
    {
        return $this->render('sidebar', [
                    'menu' => $this->menu
        ]);
    }

}
