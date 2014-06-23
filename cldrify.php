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

if (defined('_PS_VERSION_') === false)
	exit;

class Cldrify extends Module
{
	public function __construct()
	{
		$this->name = 'cldrify';
		$this->tab = 'administration';
		$this->version = '1.0.0';
		$this->author = 'fmdj';
		$this->need_instance = '0.0.1';

		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('cldrify');
		$this->description = $this->l('Use the CLDR to automatically populate certain localizable items in PrestaShop\'s database.');

		$this->admin_tpl_path = $this->local_path.'views/templates/admin/';
	}

	/**
	* Install Tab
	* @return boolean
	*/
	private function installTab()
	{
		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = 'AdminCldrify';
		$tab->name = array();
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = 'Cldrify';
		unset($lang);
		$tab->id_parent = -1;
		$tab->module = $this->name;
		return $tab->add();
	}

	/**
	* Uninstall Tab
	* @return boolean
	*/
	private function uninstallTab()
	{
		$id_tab = (int)Tab::getIdFromClassName('AdminCldrify');
		if ($id_tab)
		{
			$tab = new Tab($id_tab);
			return $tab->delete();
		}
		else
			return false;
	}

	/**
	* Insert module into datable
	* @return boolean result
	*/
	public function install()
	{
		return parent::install() && $this->installTab();
	}

	/**
	* Delete module from datable
	* @return boolean result
	*/
	public function uninstall()
	{
		return parent::uninstall() && $this->uninstallTab();
	}

	public function getContent()
	{
		Tools::redirectAdmin($this->context->link->getAdminLink('AdminCldrify'));
	}

	public function path()
	{
		$base = realpath(dirname(__FILE__));
		$separator = preg_match('#^/#', $base) ? '/' : '\\';
		foreach (func_get_args() as $arg) {
			$base .= $separator.trim($arg, '/\\');
		}
		return $base;
	}

	// Real work starts here

	/**
	* Download data from CLDR, only if not present (unless $force is true).
	*/
	public function updateCLDR($force=false)
	{
		require_once _PS_TOOL_DIR_.'/pclzip/pclzip.lib.php';

		$url = 'http://unicode.org/Public/cldr/latest/core.zip';

		$dir = $this->path('data');

		$arch = $this->path('data', 'core.zip');

		if (file_exists($arch) && !$force)
			return true;

		$zip = Tools::file_get_contents($url);

		if (!$zip)
			return $this->l('Could download CLDR archive');

		$dl = @file_put_contents($arch, $zip);

		if (!$dl)
			return sprintf($this->l('Could not write to "%s"'), $arch);

		$pcl = new PclZip($arch);

		$unzipTo = $this->path('data', 'cldr');
		if (!is_dir($unzipTo) && !mkdir($unzipTo))
			return sprintf($this->l('Could not create folder "%s"', $unzipTo));

		$ok = $pcl->extract(PCLZIP_OPT_PATH, $unzipTo);

		if (is_int($ok))
			return $this->l('Could not extract CLDR archive');

		return true;
	}

}
