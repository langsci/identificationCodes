<?php

/**
 * @file plugins/importexport/identificationCodes/IdentificationCodesExportPlugin.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING. 
 *
 * @class IdentificationCodesExportPlugin
 *
 */

import('lib.pkp.classes.plugins.ImportExportPlugin');
import('plugins.importexport.identificationCodes.IdentificationCodesDAO');
import('classes.monograph.MonographDAO');

class IdentificationCodesExportPlugin extends ImportExportPlugin {
	/**
	 * Constructor
	 */
	function IdentificationCodesExportPlugin() {
		parent::ImportExportPlugin();
	}

	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @param $path string
	 * @return boolean True iff plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path) {
		$success = parent::register($category, $path);
		$this->addLocaleData();
		return $success;
	}

	/**
	 * @see Plugin::getTemplatePath($inCore)
	 */
	function getTemplatePath($inCore = false) {
		return parent::getTemplatePath($inCore) . 'templates/';
	}

	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'IdentificationCodesImportExportPlugin';
	}

	/**
	 * Get the display name.
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.importexport.identificationCodes.displayName');
	}

	/**
	 * Get the display description.
	 * @return string
	 */
	function getDescription() {
		return __('plugins.importexport.identificationCodes.description');
	}

	/**
	 * Display the plugin.
	 * @param $args array
	 * @param $request PKPRequest
	 */
	function display($args, $request) {
		$templateMgr = TemplateManager::getManager($request);
		$press = $request->getPress();
		$context = $request->getContext();		
		$contextId = $context->getId();

		parent::display($args, $request);
		$templateMgr->assign('plugin', $this);

		switch (array_shift($args)) {
			case 'index':
			case '':

				$authorizedUserGroups = array(ROLE_ID_SITE_ADMIN,ROLE_ID_MANAGER);
				$userRoles = array(ROLE_ID_MANAGER);

				// redirect to index page if user does not have the rights
				$user = $request->getUser();
				if (!array_intersect($authorizedUserGroups, $userRoles)) {
					$request->redirect(null, 'index');
				}

				// get codes from the settings
				$identifcationCodesSettings = array_map('trim', explode(',', $this->getSetting($press->getId(), 'langsci_identificationCodes_codes')));

				// get codes from the ONIX code list
				$onixCodelistItemDao = DAORegistry::getDAO('ONIXCodelistItemDAO');
				$onixCodes = $onixCodelistItemDao->getCodes('List5');


				// get codes from the ONIX code list
				$onixCodelistItemDao = DAORegistry::getDAO('ONIXCodelistItemDAO');
				$onixCodes = $onixCodelistItemDao->getCodes('List5');

				// get all codes from the settings that really exist
				$selectedIdentificationCodes = array();
				foreach ($onixCodes as $id => $codename) {
					// remove id from name
					$pos = strrpos($codename," ");
					if ($pos) {
						$codename = substr($codename,0,$pos);
					}

					if (in_array(trim($codename),$identifcationCodesSettings,true)) {
						$selectedIdentificationCodes[] = $id;
					}
				}

				if (empty($selectedIdentificationCodes)) {
					$templateMgr->assign('noCodes', true);
					$templateMgr->display($this->getTemplatePath() . 'index.tpl');
					break;
				}

				// get all code values from the database
				$identificationCodesDAO = new IdentificationCodesDAO();
				$identificationCodes = $identificationCodesDAO->getData($press->getPrimaryLocale());

				// get full title
				$monographDao = new MonographDAO;

				foreach($identificationCodes as $publicationFormatId => $item) {
					$monograph = $monographDao->getById($item['subId']);
					if ($monograph) {
						$identificationCodes[$publicationFormatId]['title'] = $monograph->getLocalizedPrefix()." ".$monograph->getLocalizedTitle();
					}
				}

				$templateMgr->display($this->getTemplatePath() . 'index.tpl');
				break;

			case 'export':

				// get codes from the settings
				$identifcationCodesSettings = array_map('trim', explode(',', $this->getSetting($press->getId(), 'langsci_identificationCodes_codes')));

				// get codes from the ONIX code list
				$onixCodelistItemDao = DAORegistry::getDAO('ONIXCodelistItemDAO');
				$onixCodes = $onixCodelistItemDao->getCodes('List5');

				// get all codes from the settings that really exist
				$selectedIdentificationCodes = array();
				foreach ($onixCodes as $id => $codename) {
					// remove id from name
					$pos = strrpos($codename," ");
					if ($pos) {
						$codename = substr($codename,0,$pos);
					}

					if (in_array(trim($codename),$identifcationCodesSettings,true)) {
						$selectedIdentificationCodes[] = $id;
					}
				}

				// get all code values from the database
				$identificationCodesDAO = new IdentificationCodesDAO();
				$identificationCodes = $identificationCodesDAO->getData($press->getPrimaryLocale());

				// create header
				$data = array();
				$data[0]['submissionId'] = 'SubId';
				$data[0]['author'] = 'Author';
				$data[0]['title'] = 'Title';
				$data[0]['publicationFormat'] = 'PubFormat';
				foreach ($selectedIdentificationCodes as $code) {

					$codename = $onixCodes[$code];
					// remove id from name
					$pos = strrpos($codename," ");
					if ($pos) {
						$codename = substr($codename,0,$pos);
					}
					$data[0][$codename] = $codename;
				}
				// insert identification codes
				$count = 1;
				$monographDAO = new MonographDAO;
				foreach ($identificationCodes as $identificationCode) {

					$submissionId = $identificationCode['subId'];
					$data[$count]['subId'] = $identificationCode['subId'];
					$monograph = $monographDAO->getById($submissionId);
					if ($monograph) {
						$data[$count]['author'] = $monograph->getFirstAuthor();
						$data[$count]['title'] = $monograph->getLocalizedPrefix()." ".$monograph->getLocalizedTitle();
					}

					$data[$count]['publicationFormat'] = $identificationCode['publicationFormat'];
					foreach ($selectedIdentificationCodes as $code) {
						$codename = $onixCodes[$code];
						// remove id from name
						$pos = strrpos($codename," ");
						if ($pos) {
							$codename = substr($codename,0,$pos);
						}
						$data[$count][$codename] =$identificationCode[$code];
					}
					$count++;
				}

				// output data
				header("Content-Type: text/csv; charset=utf-8");
				header("Content-Disposition: attachment; filename=identificationCodes.csv");
				$output = fopen("php://output", "w");
				foreach ($data as $row) {
				  fputcsv($output, $row); // here you can change delimiter/enclosure
				}
				fclose($output);
				break;

			default:
				$dispatcher = $request->getDispatcher();
				$dispatcher->handle404();
	
		}
	}

	/**
	 * @see Plugin::getActions()
	 */
	function getActions($request, $verb) {

		$router = $request->getRouter();

		return array_merge(
			$this->getEnabled()?array(
				new LinkAction(
					'pluginSettings',
					new AjaxModal(
						$router->url($request, null, null, 'manage', null, array('verb' => 'settings',
						'plugin' => $this->getName(), 'category' => 'importexport')),
						$this->getDisplayName()
					)
					,
					__('manager.plugins.settings'),
					null
				),
			):array()
			,
			parent::getActions($request, $verb)
		);
	}

 	/**
	 * @see Plugin::manage()
	 */
	function manage($args, $request) {
		switch ($request->getUserVar('verb')) {
			case 'settings':
				$context = $request->getContext();
				$this->import('SettingsForm');
				$form = new SettingsForm($this, $context->getId());

				if ($request->getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						return new JSONMessage(true);
					}
				} else {
					$form->initData();
				}
				return new JSONMessage(true, $form->fetch($request));
		}
		return parent::manage($args, $request);
	}

	/**
	 * @copydoc ImportExportPlugin::executeCLI
	 */
	function executeCLI($scriptName, &$args) {
		fatalError('Not implemented.');
	}

	/**
	 * @copydoc ImportExportPlugin::usage
	 */
	function usage($scriptName) {
		fatalError('Not implemented.');
	}
}

?>
