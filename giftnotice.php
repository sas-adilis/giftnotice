<?php

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class GiftNotice extends Module implements WidgetInterface
{
    function __construct()
    {
        $this->name = 'giftnotice';
        $this->author = 'Adilis';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->displayName = $this->l('Gift notices');
        $this->description = $this->l('Allows you to display notices to obtain a gift');
        $this->confirmUninstall = $this->l('Are you sure ?');

        parent::__construct();
    }

    public function install() {

        if (file_exists($this->getLocalPath().'sql/install.php')) {
            require_once($this->getLocalPath().'sql/install.php');
        }
        return parent::install() && $this->registerHook('displayHeader');
    }

    public function uninstall() {
        if (file_exists($this->getLocalPath().'sql/uninstall.php')) {
            require_once($this->getLocalPath().'sql/uninstall.php');
        }
        return parent::uninstall();
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {

    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {

    }

}