<?php

/**
 * @file plugins/generic/identificationCodes/IdentificationCodesDAO.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.  
 *
 * @class IdentificationCodesDAO
 *
 */

class IdentificationCodesDAO extends DAO {
	/**
	 * Constructor
	 */
	function IdentificationCodesDAO() {
		parent::DAO();
	}

	function getData($locale) {

		$result = $this->retrieve("SELECT pf.publication_format_id, pfs.setting_value AS pfname, ic.code, ic.value, pf.submission_id,ss.setting_value AS btitle FROM identification_codes ic LEFT JOIN publication_formats pf ON pf.publication_format_id=ic.publication_format_id
LEFT JOIN publication_format_settings pfs ON pf.publication_format_id=pfs.publication_format_id 
LEFT JOIN submission_settings ss ON ss.submission_id=pf.submission_id WHERE pfs.setting_name='name' and ss.setting_name='title'  AND ss.locale='".$locale."' and pfs.locale='".$locale."' ORDER BY pf.submission_id"
		);

		if ($result->RecordCount() == 0) {
			$result->Close();
			return null;
		} else {

			$identificationCodes = array();
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);

				$publicationFormatId = $this->convertFromDB($row['publication_format_id']);
				$codeId = $this->convertFromDB($row['code']); 

				$identificationCodes[$publicationFormatId]['subId'] = $this->convertFromDB($row['submission_id']);
				$identificationCodes[$publicationFormatId]['title'] = $this->convertFromDB($row['btitle']); 
				$identificationCodes[$publicationFormatId]['publicationFormat'] = $this->convertFromDB($row['pfname']);
				$identificationCodes[$publicationFormatId][$codeId] = $this->convertFromDB($row['value']);
				$result->MoveNext();
			}
			$result->Close();
			return $identificationCodes;
		}
	}

}

?>
