Key data
============

- name of the plugin: Identification Codes
- author: Carola Fanselow
- current version: 1.0
- tested on OMP version: 1.2.0
- github link: https://github.com/langsci/identificationCodes.git
- community plugin: yes
- date: 2016/05/11

Description
============

This plugin exports (csv) the idendentification code types and values that are assigned to submissions in OMP. The following data are exporter: submission id, author name, book title, publication format, identification code type, identification code value. 

Implementation
================

Hooks
-----
- used hooks: 0

New pages
------
- new pages: 1

		[press]/management/importexport/plugin/IdentificationCodesImportExportPlugin

Templates
---------
- templates that substitute other templates: 0
- templates that are modified with template hooks: 0
- new/additional templates: 2

		index.tpl
		settingsForm.tpl

Database access, server access
-----------------------------
- reading access to OMP tables: 5

		submissions
		submission_settings
		identification_codes
		publication_formats
		publication_format_settings

- writing access to OMP tables: 0

- new tables: 0

- writing access to new tables: 0

- nonrecurring server access: no

- recurring server access: no
 
Classes, plugins, external software
-----------------------
- OMP classes used (php): 2
	
		ImportExportPlugin
		MonographDAO
		
- OMP classes used (js, jqeury, ajax): 1

		TabHandler

- necessary plugins: 0

- optional plugins: 0

- use of external software: no

- file upload: no
 
Metrics
--------
- number of files 11
- lines of code: 731

Settings
--------
- settings: no

Plugin category
----------
- plugin category: importexport

Other
=============
- does using the plugin require special (background)-knowledge?: no
- access restrictions: access restricted to admins and managers
- adds css: no


