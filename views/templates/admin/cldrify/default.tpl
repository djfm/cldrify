{*
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
	<h3>{l s='Update Database' mod='cldrify'}</h3>
	<div class="alert alert-info">{l s='Here you can update different entities of your db using data from the [1]CLDR[/1].' mod='cldrify' tags=['<a href="http://cldr.unicode.org/">']}</div>
    <form class="form-horizontal" method="post">
    	<div class="form-group">
			<label class='control-label col-lg-3'>Update Country Names</label>
			<div class="col-lg-9">
				<button name="action" value="updateCountryNames" class="btn btn-default" type="submit">{l s='Update from CLDR' mod='cldrify'}</button>
				<p class="help-block">{l s='This will update the translations of country names in all installed languages' mod='cldrify'}</p>
			</div>
		</div>
    </form>
</div>
