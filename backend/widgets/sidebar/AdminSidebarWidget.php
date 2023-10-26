<?php

namespace backend\widgets\sidebar;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * Description of AdminSidebarWidget
 *
 * @author Amit Handa
 */
class AdminSidebarWidget extends Widget
{

    private $menu;

    public function init()
    {
        parent::init();

        if (Yii::$app->user->hasAdminRole()) {
            $this->menu = [
                [
                    'name' => 'Dashboard',
                    'url' => Url::toRoute(['home/index']),
                    'icon' => 'fa fa-home',
                    'visibility' => TRUE,
                ],
                [
                    'name' => 'Applicant',
                    'url' => '',
                    'icon' => 'fa fa-users',
                    'visibility' => TRUE,
                    'childs' => [
                        [
                            'name' => 'Profile',
                            'url' => Url::toRoute(['applicant/profile']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Summary',
                            'url' => Url::toRoute(['applicant/index']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Post',
                            'url' => Url::toRoute(['applicant/post']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Transaction',
                            'url' => Url::toRoute(['transaction/index']),
                            'visibility' => TRUE,
                        ]
                    ]
                ],
                [
                    'name' => 'Log',
                    'url' => '',
                    'icon' => 'fa fa-users',
                    'visibility' => false,
                    'childs' => [
                        [
                            'name' => 'OTP',
                            'url' => Url::toRoute(['otp/index']),
                            'visibility' => TRUE,
                        ]
                    ]
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
                [
                    'name' => 'Applicant Log',
                    'url' => Url::toRoute(['log-applicant/index']),
                    'icon' => 'fa fa-history',
                    'visibility' => false,
                ],
                [
                    'name' => 'OTR Update Log',
                    'url' => Url::toRoute(['log-profile/index']),
                    'icon' => 'fa fa-list',
                    'visibility' => TRUE,
                ],
                [
                    'name' => 'Reports',
                    'url' => '',
                    'icon' => 'fa fa-file',
                    'visibility' => TRUE,
                    'childs' => [
                        [
                            'name' => 'MIS report',
                            'url' => Url::toRoute(['/report/export/mis']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Application Received Report',
                            'url' => Url::toRoute(['/report/export/application-received']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Attendance Sheet',
                            'url' => Url::toRoute(['/report/export/attendance']),
                            'visibility' => TRUE,
                        ]
                    ]
                ]
            ];
        }else if (Yii::$app->user->hasClientAdminRole()) {
            $this->menu = [
                [
                    'name' => 'Dashboard',
                    'url' => '',
                    'icon' => 'fa fa-home',
                    'visibility' => TRUE,
                ],
                [
                    'name' => 'Applicant',
                    'url' => '',
                    'icon' => 'fa fa-users',
                    'visibility' => TRUE,
                    'childs' => [
                        [
                            'name' => 'Profile',
                            'url' => Url::toRoute(['applicant/profile']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Summary',
                            'url' => Url::toRoute(['applicant/index']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Post',
                            'url' => Url::toRoute(['applicant/post']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Transaction',
                            'url' => Url::toRoute(['transaction/index']),
                            'visibility' => TRUE,
                        ]
                    ]
                ],
                [
                    'name' => 'Reports',
                    'url' => '',
                    'icon' => 'fa fa-file',
                    'visibility' => TRUE,
                    'childs' => [
                        [
                            'name' => 'MIS report',
                            'url' => Url::toRoute(['/report/export/mis']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Application Received Report',
                            'url' => Url::toRoute(['/report/export/application-received']),
                            'visibility' => TRUE,
                        ]
                    ]
                ]
            ];
        }else if (Yii::$app->user->hasHelpdeskRole()) {
            $this->menu = [
                [
                    'name' => 'Dashboard',
                    'url' => '',
                    'icon' => 'fa fa-home',
                    'visibility' => TRUE,
                ],
                [
                    'name' => 'Applicant',
                    'url' => '',
                    'icon' => 'fa fa-users',
                    'visibility' => TRUE,
                    'childs' => [
                        [
                            'name' => 'Profile',
                            'url' => Url::toRoute(['applicant/profile']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Summary',
                            'url' => Url::toRoute(['applicant/index']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Post',
                            'url' => Url::toRoute(['applicant/post']),
                            'visibility' => TRUE,
                        ],
                        [
                            'name' => 'Transaction',
                            'url' => Url::toRoute(['transaction/index']),
                            'visibility' => TRUE,
                        ]
                    ]
                ]
            ];
        }
    }

    public function run()
    {
        return $this->render('sidebar', [
                    'menu' => $this->menu
        ]);
    }

}
