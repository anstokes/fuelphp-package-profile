<?php

namespace Anstech\Profile\Entity;

class Profile extends \Orm\Model
{
    protected static $_table_name = 'users_profiles';

    protected static $_properties = [
        'id'                => [
            'data_type'  => 'int',
            'label'      => 'Id',
            'null'       => false,
            'validation' => [
                0             => 'required',
                'numeric_min' => [
                    0 => -2147483648,
                ],
                'numeric_max' => [0 => 2147483647],
            ],
            'form'       => ['type' => false],
        ],
        'login_id'          => [
            'data_type'  => 'int',
            'label'      => 'LoginId',
            'null'       => true,
            'validation' => [
                'numeric_min' => [
                    0 => -2147483648,
                ],
                'numeric_max' => [0 => 2147483647],
            ],
            'form'       => [
                'type' => 'number',
                'min'  => -2147483648,
                'max'  => 2147483647,
            ],
        ],
        'avatar'            => [
            'data_type'  => 'varchar',
            'label'      => 'Avatar',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 64],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 64,
            ],
        ],
        'realname'          => [
            'data_type'  => 'varchar',
            'label'      => 'Realname',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 64],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 64,
            ],
        ],
        'timezone_id'       => [
            'data_type'  => 'int',
            'label'      => 'TimezoneId',
            'null'       => true,
            'validation' => [
                'numeric_min' => [
                    0 => -2147483648,
                ],
                'numeric_max' => [0 => 999],
            ],
            'form'       => [
                'type' => 'number',
                'min'  => -2147483648,
                'max'  => 999,
            ],
        ],
        'time_format'       => [
            'data_type'  => 'varchar',
            'label'      => 'TimeFormat',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 16],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 16,
            ],
        ],
        'short_date_format' => [
            'data_type'  => 'varchar',
            'label'      => 'ShortDateFormat',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 16],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 16,
            ],
        ],
        'long_date_format'  => [
            'data_type'  => 'varchar',
            'label'      => 'LongDateFormat',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 16],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 16,
            ],
        ],
        'theme'             => [
            'data_type'  => 'varchar',
            'label'      => 'Theme',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 16],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 16,
            ],
        ],
        'home_screen'       => [
            'data_type'  => 'varchar',
            'label'      => 'Home Screen',
            'null'       => true,
            'default'    => 'notifications',
            'validation' => [
                'max_length' => [0 => 45],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 45,
            ],
        ],
    ];

    protected static $_observers = [
        'Orm\Observer_Validation' => [
            'events' => ['before_save'],
        ],
        'Orm\Observer_Typing'     => [
            'events' => [
                'before_save',
                'after_save',
                'after_load',
            ],
        ],
    ];

    protected static $_belongs_to = [
        'timezone'  => [
            'key_from' => 'timezone_id',
            'model_to' => '\Anstech\Profile\Model\Timezone',
            'key_to'   => 'id',
        ],
        'loginUser' => [
            'key_from' => 'login_id',
            'model_to' => '\Model\Auth\User',
            'key_to'   => 'id',
        ],
    ];
}
