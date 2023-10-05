<?php

namespace Anstech\Profile\Entity;

class Timezone extends \Orm\Model
{
    protected static $_table_name = 'users_timezones';

    protected static $_properties = [
        'id'           => [
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
        'countryCode'  => [
            'data_type'  => 'varchar',
            'label'      => 'CountryCode',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 2],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 2,
            ],
        ],
        'timezone'     => [
            'data_type'  => 'varchar',
            'label'      => 'Timezone',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 64],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 64,
            ],
        ],
        'zoneType'     => [
            'data_type'  => 'varchar',
            'label'      => 'ZoneType',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 16],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 16,
            ],
        ],
        'utcOffset'    => [
            'data_type'  => 'varchar',
            'label'      => 'UtcOffset',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 8],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 8,
            ],
        ],
        'utcDstOffset' => [
            'data_type'  => 'varchar',
            'label'      => 'UtcDstOffset',
            'null'       => true,
            'validation' => [
                'max_length' => [0 => 8],
            ],
            'form'       => [
                'type'      => 'text',
                'maxlength' => 8,
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

    protected static $_has_many = [
        'profiles' => [
            'key_from' => 'id',
            'model_to' => '\Anstech\Profile\Model\Profile',
            'key_to'   => 'timezone_id',
        ],
    ];
}
