<?php

class GiftNoticeClass extends ObjectModel
{
    public $internal_name;
    public $amount_from;
    public $amount_to;
    public $id_shop;
    public $message;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'gift_notice',
        'primary' => 'id_gift_notice',
        'fields' => [
            'internal_name' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255],
            'id_shop' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'amount_from' => ['type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true],
            'amount_to' => ['type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true],
            'message' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 512],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' =>['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    );
}