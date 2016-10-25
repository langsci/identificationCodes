{**
 * @file plugins/importexport/identificationCodes/templates/settingsForm.tpl
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 *}

<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#identificationCodesSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

<form class="pkp_form" id="identificationCodesSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="importexport" plugin=$pluginName verb="settings" save=true}">
	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="identificationCodesSettingsFormNotification"}

	<h3>{translate key="plugins.generic.identificationCodes.settings"}</h3>

	{fbvFormArea id="identificationCodesSettingsFormArea"}

		{fbvFormSection list=true}
			<span>{translate key="plugins.generic.identificationCodes.settings.intro"}</span>
			{fbvElement type="text" id="langsci_identificationCodes_codes" value=$langsci_identificationCodes_codes label="plugins.generic.identificationCodes.settings.codes" maxlength="200" size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

	{/fbvFormArea}

	{fbvFormButtons}
</form>


