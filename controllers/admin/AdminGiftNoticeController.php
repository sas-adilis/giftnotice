<?php

class AdminGiftNoticeController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'gift_notice';
        $this->className = 'GiftNoticeClass';
        $this->identifier = 'id_gift_notice';
        $this->bootstrap = true;
        $this->lang = true;
        $this->specificConfirmDelete = false;
        $this->_defaultOrderWay = 'DESC';
        $this->_defaultOrderBy = 'amount_from';

        $this->fields_list = array(
            'number' => array(
                'title' => $this->l('Numéro'),
                'align' => 'center',
                'width' => 25,
                'havingFilter' => true,
                'callback' => 'displayFormatedNumber'
            ),
            'name' => array(
                'title' => $this->l('Nom interne'),
                'align' => 'left',
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'width' => 130,
                'align' => 'right',
                'type' => 'datetime',
                'filter_key' => 'a!date_add'
            ),
            'date_upd' => array(
                'title' => $this->l('Dernière modification'),
                'width' => 130,
                'align' => 'right',
                'type' => 'datetime',
                'filter_key' => 'a!date_upd'
            ),
        );

        parent::__construct();
    }

}