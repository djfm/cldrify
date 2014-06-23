<div class='panel'>
	<h3>{l s='Country Name Update Results' mod='cldrify'}</h3>
	{if $updated > 0}
		<div class="alert alert-success">
			{l s='Successfully updated %1$s country names (in %2$s languages)!' mod='cldrify' sprintf=[$updated, $nlanguagues]}
		</div>
	{else}
		<div class="alert alert-danger">
			{l s='Did not updated anything. This is not good.' mod='cldrify'}
		</div>
	{/if}
</div>
