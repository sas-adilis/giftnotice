<?php

class AdminGiftNoticeController extends ModuleAdminController
{
    /** @var $object GiftNoticeClass|null */
    public $object;

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
        parent::__construct();

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->fields_list = array(
            'id_gift_notice' => array(
                'title' => $this->l('ID'),
                'width' => 25,
            ),
            'internal_name' => array(
                'title' => $this->l('Nom interne'),
                'align' => 'left',
            ),
            'amount_from' => array(
                'title' => $this->l('Display from amount'),
                'type' => 'price',
                'align' => 'left',
            ),
            'amount_to' => array(
                'title' => $this->l('Display to amount'),
                'type' => 'price',
                'align' => 'left',
            ),
            'date_from' => array(
                'title' => $this->l('Display from date'),
                'width' => 130,
                'align' => 'right',
                'type' => 'datetime',
                'filter_key' => 'a!date_add'
            ),
            'date_to' => array(
                'title' => $this->l('Display to date'),
                'width' => 130,
                'align' => 'right',
                'type' => 'datetime',
                'filter_key' => 'a!date_upd'
            ),
            'active' => [
                'title' => $this->trans('Enabled', [], 'Admin.Global'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm',
            ],
        );

    }

    public function renderForm()
    {
        if (Shop::isFeatureActive() && Shop::getContext() != \Shop::CONTEXT_SHOP) {
            $this->warnings[]  = $this->l('Please, select a shop before create or edit a notice');
        }

        if (Validate::isLoadedObject($this->object) && $this->object->id_shop != $this->context->shop->id) {
            $this->warnings[]  = $this->l('This notice has been created in another shop');
        }

        if (!Validate::isLoadedObject($this->object)) {
            $this->object->date_from = date('Y-m-d H:i:s');
            $this->object->date_to = date('Y-m-d H:i:s', strtotime('+1 year'));
        }

        $this->fields_form = [
            'legend' => [
                'title' => '<i class="icon-cogs"></i> ' . $this->l('Setup')
            ],
            'input' => [
                [
                    'type' => 'text',
                    'name' => 'internal_name',
                    'id' => 'internal_name',
                    'label' => $this->l('Internal name'),
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'name' => 'amount_from',
                    'id' => 'amount_from',
                    'label' => $this->l('Display from amount'),
                    'required' => true,
                    'suffix' => \Context::getContext()->currency->getSign('right'),
                    'maxlength' => 5
                ],
                [
                    'type' => 'text',
                    'name' => 'amount_to',
                    'id' => 'amount_to',
                    'label' => $this->l('Display to amount'),
                    'required' => true,
                    'suffix' => \Context::getContext()->currency->getSign('right'),
                    'maxlength' => 5
                ],
                [
                    'type' => 'datetime',
                    'name' => 'date_from',
                    'id' => 'date_from',
                    'label' => $this->l('Display from date'),
                    'required' => true
                ],
                [
                    'type' => 'datetime',
                    'name' => 'date_to',
                    'id' => 'date_to',
                    'label' => $this->l('Display to date'),
                    'required' => true
                ],
                [
                    'type' => 'textarea',
                    'name' => 'message',
                    'desc' => $this->l('You can use the following variables: {amount_from}, {amount_to}, {amount_left}, {amount_cart}. eg: "You have {amount_left} left to get a gift"'),
                    'id' => 'message',
                    'label' => $this->l('Message'),
                    'required' => true,
                    'lang' => true,
                    'class' => 'autoload_rte'
                ],
                [
                    'type' => 'switch',
                    'name' => 'active',
                    'required' => true,
                    'is_bool' => true,
                    'label' => $this->l('Active'),
                    'values' => [
                        ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                        ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')],
                    ]
                ],
                
                
            ],
            'buttons' => [
                [
                    'title' => $this->l('Save and stay'),
                    'class' => 'pull-right',
                    'icon' => 'process-icon-save',
                    'type' => 'submit',
                    'name' => 'submitAddhiddenobjects_cmAndStay'
                ]
            ]
        ];

        return parent::renderForm();

    }

    /**
     * @param $object GiftNoticeClass
     */
    protected function copyFromPost(&$object, $table)
    {
        parent::copyFromPost($object, $table);
        $object->id_shop = $this->context->shop->id;
    }
}