<?php

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/classes/GiftNoticeClass.php';

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

        return parent::install() &&
            $this->installTab() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayCartAjaxInfo');
    }

    private function installTab() {
        if (!\Tab::getIdFromClassName('AdminGiftNotice')) {
            $tab = new Tab();
            $tab->class_name = 'AdminGiftNotice';
            $tab->id_parent = -1;
            $tab->module = $this->name;
            foreach(\Language::getLanguages(false) as $lang) {
                $tab->name[$lang['id_lang']] = $this->displayName;
            }
            return $tab->add();
        }
        return true;
    }

    public function uninstall() {
        if (file_exists($this->getLocalPath().'sql/uninstall.php')) {
            require_once($this->getLocalPath().'sql/uninstall.php');
        }

        return parent::uninstall() && $this->uninstallTab();
    }

    private function uninstallTab() {
        $id_tab = \Tab::getIdFromClassName('AdminGiftNotice');
        if ($id_tab) {
            $tab = new \Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }

        if ($this->context->cart->isVirtualCart()){
            return;
        }

        $assign = $this->getWidgetVariables($hookName, $configuration);
        if ($assign) {
            $this->smarty->assign($assign);
            return $this->fetch('module:' . $this->name . '/views/templates/hook/gift_notice.tpl');
        }
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $amount_cart_numeric = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);
        $gift_notice = GiftNoticeClass::getNextGiftNotice($amount_cart_numeric);
        if (!$gift_notice) {
            return false;
        }

        $priceFormatter = new PriceFormatter();
        $vars = [
            'amount_cart_numeric' => $amount_cart_numeric,
            'amount_cart' => $priceFormatter->format($amount_cart_numeric),
            'amount_from_numeric' => (float)$gift_notice['amount_from'],
            'amount_from' => $priceFormatter->format((float)$gift_notice['amount_from']),
            'amount_to_numeric' => (float)$gift_notice['amount_to'],
            'amount_to' => $priceFormatter->format((float)$gift_notice['amount_to']),
            'amount_left_numeric' => (float)$gift_notice['amount_to'] - $amount_cart_numeric,
            'amount_left' => $priceFormatter->format((float)$gift_notice['amount_to'] - $amount_cart_numeric),
        ];
        $vars['percentage'] = round($vars['amount_cart_numeric'] / $vars['amount_to_numeric'] * 100, 2);
        $vars['message'] = str_replace([
            '{amount_cart}',
            '{amount_from}',
            '{amount_to}',
            '{amount_left}',
        ], [
            $vars['amount_cart'],
            $vars['amount_from'],
            $vars['amount_to'],
            $vars['amount_left'],
        ], $gift_notice['message']);

        return $vars;
    }

    public function getContent() {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminGiftNotice'));
    }

}