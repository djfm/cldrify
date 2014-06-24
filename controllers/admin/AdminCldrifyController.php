<?php
/**
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2014 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

class AdminCldrifyController extends ModuleAdminController
{
	public function init()
	{
		$this->bootstrap = true;

		$this->action = Tools::getValue('action');
		if (!is_string($this->action) || !preg_match('/^\w+$/', $this->action))
			$this->action = 'default';

		$this->template = "{$this->action}.tpl";
		parent::init();
	}

	public function postProcess()
	{
		$methods = array(
			'beforeAll',
			Tools::strtolower($_SERVER['REQUEST_METHOD']).Tools::ucfirst($this->action).'Action'
		);

		foreach ($methods as $method)
		{
			if (is_callable(array($this, $method)))
			{
				$template_params = $this->$method();
				if (is_array($template_params))
					$this->context->smarty->assign($template_params);
			}
		}
	}

	public function beforeAll()
	{
		$got_cldr = $this->module->updateCLDR(false);
		if ($got_cldr !== true)
			$this->errors[] = sprintf($this->l('Could not update the CLDR data: %s. Most things will not work.'), $got_cldr);
	}

	public function getDefaultAction()
	{
	}

	public function postUpdateCountryNamesAction()
	{
		$updated = 0;
		// List all languages, even unactive ones
		$languages = Language::getLanguages(false);

		$countries = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'country');

		$country_name_validator = Country::$definition['fields']['name']['validate'];

		foreach ($languages as $language)
		{
			$code = $language['iso_code'];
			$locale = $this->module->getLocale($code);
			if ($locale)
			{
				foreach ($countries as $country)
				{
					$iso_code = Tools::strtoupper($country['iso_code']);
					$name = $this->module->getCLDRCountryName($iso_code, $locale);
					if (!is_string($name))
						$this->warnings[] = sprintf($this->l('Did not find translation for country "%s" in "%s".'), $iso_code, $locale);
					else
					{
						if (Validate::$country_name_validator($name))
						{
							$query = 'UPDATE %1$scountry_lang SET name=\'%2$s\' WHERE id_country=%3$d AND id_lang=%4$d';
							$query = sprintf($query, _DB_PREFIX_, psql($name), (int)$country['id_country'], (int)$language['id_lang']);
							if (Db::getInstance()->execute($query))
								$updated += 1;
							else
								$this->warnings[] = sprintf($this->l('Could not update translation for country "%s" in "%s".'), $iso_code, $locale);
						}
						else
							$this->warnings[] = sprintf($this->l('PrestaShop considers this name (for country "%s") as invalid: "%s".'), $iso_code, $name);
					}
				}
			}
			else
				$this->warnings[] = sprintf($this->l('Did no update language "%s": could not guess correct locale.'));
		}
		return array('updated' => $updated, 'nlanguagues' => count($languages));
	}
}
