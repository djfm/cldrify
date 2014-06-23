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
