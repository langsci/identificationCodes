{**
 * @file plugins/importexport/identificationCodes/templates/index.tpl
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING. 
 *}

{strip}
{assign var="pageTitle" value="plugins.importexport.identificationCodes.displayName"}
{include file="common/header.tpl"}
{/strip}

<script type="text/javascript">
	// Attach the JS file tab handler.
	$(function() {ldelim}
		$('#importExportTabs').pkpHandler('$.pkp.controllers.TabHandler');
		$('#importExportTabs').tabs('option', 'cache', true);
	{rdelim});
</script>


{if $noCodes}

	<p>{translate key="plugins.importexport.identificationCodes.noCodesSelected"}</p>

{else}

<p>{translate key="plugins.importexport.identificationCodes.instruction"}</p>

<div id="importExportTabs" class="pkp_controllers_tab">
	<ul>
		<li><a href="#export-tab">{translate key="plugins.importexport.identificationCodes.export"}</a></li>
	</ul>
	<div id="export-tab">
		<script type="text/javascript">
			$(function() {ldelim}
				// Attach the form handler.
				$('#exportForm').pkpHandler('$.pkp.controllers.form.FormHandler');
			{rdelim});
		</script>
		<form id="exportForm" class="pkp_form" action="{plugin_url path="export"}" method="post">
			{fbvFormArea id="exportForm"}
				<p>{translate key="plugins.importexport.identificationCodes.export.instructions"}</p>
				{fbvFormButtons hideCancel="true"}
			{/fbvFormArea}
		</form>
	</div>
</div>

{/if}

{include file="common/footer.tpl"}
