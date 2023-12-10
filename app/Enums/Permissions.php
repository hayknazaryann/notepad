<?php

namespace App\Enums;

class Permissions
{
    public const VIEW = 'view';

    public const VIEW_AND_EDIT = 'view-and-edit';

    public static function all()
    {
        return [
            self::VIEW => __('View'),
            self::VIEW_AND_EDIT => __('View And Edit')
        ];
    }
}
