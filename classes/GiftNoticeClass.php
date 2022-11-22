<?php

class GiftNoticeClass extends ObjectModel
{
    public $internal_name;
    public $amount_from;
    public $amount_to;
    public $id_shop;
    public $message;
    public $date_from;
    public $date_to;
    public $active = 1;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'gift_notice',
        'primary' => 'id_gift_notice',
        'multilang' => true,
        'fields' => [
            'internal_name' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255],
            'id_shop' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'amount_from' => ['type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true],
            'amount_to' => ['type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true],
            'message' => ['type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 512],
            'active' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true],
            'date_from' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_to' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' =>['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    );

    public static function getNextGiftNotice(float $cart_amount)
    {
        $context = Context::getContext();
        return Db::getInstance()->getRow('
            SELECT gn.amount_from, gn.amount_to, gnl.message
            FROM ' . _DB_PREFIX_ . 'gift_notice gn
            INNER JOIN '._DB_PREFIX_.'gift_notice_lang gnl
                ON gn.id_gift_notice = gnl.id_gift_notice AND gnl.id_lang = '.(int)$context->cookie->id_lang.'
            WHERE active = 1
            AND id_shop = ' . (int)$context->shop->id . '
            AND amount_from <= ' . (float)$cart_amount . '
            AND amount_to > ' . (float)$cart_amount . '
            AND (date_from <= NOW())
            AND (date_to >= NOW())
        ');
    }
}