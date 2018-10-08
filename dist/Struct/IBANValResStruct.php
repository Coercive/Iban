<?php
namespace Coercive\Shop\Iban\Struct;

/**
 * Class IBANValResStruct
 *
 * @API IBAN_Calculator
 * @link Website https://ssl.ibanrechner.de/?L=0
 * @link Webservice https://ssl.ibanrechner.de/soap0.html?L=0
 * @link Documentation https://ssl.ibanrechner.de/soap/#
 * @link WSDL https://ssl.ibanrechner.de/soap/?wsdl
 *
 * @package Coercive\Shop\Iban
 * @link https://github.com/Coercive/Iban
 *
 * @author  	Anthony Moral <contact@coercive.fr>
 * @copyright   (c) 2018 Anthony Moral
 * @license 	MIT
 */
class IBANValResStruct
{
	/** @var string the IBAN that was validated */
	public $iban = '';

	/** @var string 'passed' or 'failed' - for a valid or invalid IBAN */
	public $result = '';

	/**
	 * @var int a condensed representation of the results of various checks.
	 * Not all checks have necessarily been done (see the other fields to find out).
	 * The condensed representation is a number which is 0 if all supported checks were done and passed.
	 * Otherwise, it is the sum of one or more of the following numbers: 1=automatically appended a subaccount number;
	 * 2=account number does not contain a checksum; 4=did not check the checksum; 8=did not check the bank code;
	 * 32=warning: a subaccount number might need to be appended, but you need to check if this is really the case;
	 * 128=checksum error in account number; 256=bank code not found in directory; 512=wrong length for IBAN;
	 * 1024=wrong length for bank code; 2048=IBAN checksum error (digits 3 and 4);
	 * 4096=missing input data (such as country code); 8192=this country is not (yet?) supported.
	 *
	 * This condensed return code can be used for a simple check such as: if the sum is less than 32,
	 * the result can be assumed to be correct; if it is in the range from 32 to 127, it might be correct but should be
	 * checked; if it is 128 or higher, there seems to be a more serious error.
	 *
	 * There is one exception to this rule: if it is 65536, this means that the return code is not set at all.
	 * This should not happen - please notify us if it does.
	 */
	public $return_code = 0;

	/**
	 * @var string[] an array of the checks performed (can contain elements such as 'length', 'bank_code',
	 * 'account_number', 'iban_checksum').
	 *
	 * bic_candidates: an array of BICs that are associated with the given national bank code.
	 * May be empty or may contain one or more elements. The BICstruct element has the attributes bic,
	 * wwwcount, sampleurl, zip code, and city. If a wwwcount greater than zero is given, the BIC was harvested
	 * from the Web (and found on as many pages as indicated by wwwcount, for example at the URL given by sampleurl).
	 *
	 * If wwwcount is zero, the BIC comes from a national bank or the ECB. If city is given, this also indicates
	 * that the BIC comes from a national bank or the ECB. The given city does not necessarily reflect the location
	 * of the given branch - it can also be the location of the headquarters.
	 */
	public $checks = [];

	/** @var BICStruct[] */
	public $bic_candidates = [];

	/** @var BICStruct[] */
	public $all_bic_candidates = [];

	/** @var string the ISO country code (first two letters of the IBAN) */
	public $country = '';

	/** @var string the national bank code. Part of the BIC for NL (where no national bank code exists) */
	public $bank_code = '';

	/** @var string bank name, if known */
	public $bank = '';

	/** @var string some address data, if known */
	public $bank_address = '';

	/** @var string URL of website, if known */
	public $bank_url = '';

	/** @var string branch name, if known */
	public $branch = '';

	/** @var string branch code, if known */
	public $branch_code = '';

	/**
	 * @var string if at least one BIC is returned, this field is set to 'yes' or 'no',
	 * depending on whether the first returned BIC is listed in the German Bundesbank's SCL directory.
	 * If no BIC is returned, this field is left blank.
	 */
	public $in_scl_directory = '';

	/**
	 * @var string if in_scl_directory is set to 'yes', this field is set to 'yes' if a SEPA Credit Transfer
	 * is supported for the first returned BIC. If no SCT is supported, the field is set to 'no'.
	 * If no BIC is returned, the field is left blank.
	 */
	public $sct = '';

	/**
	 * @var string if in_scl_directory is set to 'yes', this field is set to 'yes' if SEPA Direct Debit is supported
	 * for the first returned BIC. If no SDD is supported, the field is set to 'no'.
	 * If no BIC is returned, the field is left blank.
	 */
	public $sdd = '';

	/**
	 * @var string  if in_scl_directory is set to 'yes', this field is set to 'yes' if D-1 Core SEPA Direct Debit
	 * is supported for the first returned BIC. b2b: if in_scl_directory is set to 'yes', this field is set to 'yes'
	 * if SEPA Business to Business is supported for the first returned BIC.
	 * If no B2B is supported, the field is set to 'no'. If no BIC is returned, the field is left blank.
	 */
	public $cor1 = '';

	/** @var string */
	public $b2b = '';

	/**
	 * @var string if in_scl_directory is set to 'yes', this field is set to 'yes'
	 * if SEPA Cards Clearing is supported for the first returned BIC.
	 */
	public $scc = '';

	/** @var string the domestic bank account number */
	public $account_number = '';

	/** @var string name of the validation algorithm for the domestic account number */
	public $account_validation_method = '';

	/** @var string for German or Swiss account numbers, an explanation (in German) */
	public $account_validation = '';

	/** @var string 'passed' or 'failed' - for the right number of characters for the given country */
	public $length_check = '';

	/**
	 * @var string  (not provided for every country): 'passed' or 'failed' (checksum validation;
	 * if the algorithm is unknown, or if there is no checksum, the result is 'passed' or empty).
	 */
	public $account_check = '';

	/**
	 * @var string (not provided for every country): lookup of national bank code was successful ('passed')
	 * or not ('failed')
	 */
	public $bank_code_check = '';

	/** @var string 'passed' or 'failed' (correctness of the two digits right after the country code in the IBAN) */
	public $iban_checksum_check = '';

	/**
	 * @var string (not given for all countries): age of the BIC and other bank-related data
	 * (not defined for data harvested from the Web). Format: yyyymmdd.
	 */
	public $data_age = '';

	/**
	 * @var int the number of web pages where we have found the given IBAN. If this number is greater than zero,
	 * you can use the additional information from the fields www_seen_from until www_prominence for fraud prevention
	 * purposes (such as: judging if an IBAN really belongs to your client or if it was harvested
	 * from the Web and is used by people other than the real account holders).
	 */
	public $iban_www_occurrences = 0;

	/**
	 * @var string  the earliest date when we have found the IBAN.
	 * This is probably not the date when it appeared online because there is a delay until we notice a new IBAN on the Web.
	 */
	public $www_seen_from = '';

	/**
	 * @var string the latest date when we have found the IBAN on a web page. It might have disappeared before
	 * then because there is, for technical reasons, a certain delay between crawling and evaluating pages.
	 */
	public $www_seen_until = '';

	/**
	 * @var string the most prominent online occurrence of the IBAN
	 * (with prominence measured primarily by the rank given in the next field).
	 */
	public $iban_url = '';

	/**
	 * @var string the number of websites which receive more traffic than this one.
	 * So, the lower this number, the more prominent the website.
	 */
	public $url_rank = '';

	/**
	 * @var string the thematic category (in natural language) of the most prominent URL, if known.
	 * url_min_depth: the lowest depth where we found the URL, approximated by the number of forward slashes in the URL.
	 */
	public $url_category = '';

	/** @var string */
	public $url_min_depth = '';

	/**
	 * @var string this field is reserved for future use for a number which will represent the prominence of the URL,
	 * based on a combination of things such as rank, duration of online presence, and depth on the website.
	 */
	public $www_prominence = '';

	/**
	 * @var string if you have submitted the name of the account holder, and if at least one other user has reported
	 * the given IBAN to belong to the given account holder, this field will contain a number greater than zero
	 * (namely, the number of times this IBAN was reported to belong to the given account holder).
	 *
	 * If you have not submitted the account holder or if nobody has reported this IBAN to belong
	 * to the account holder you submitted, this field is set to zero.
	 */
	public $iban_reported_to_exist = '';

	/**
	 * @var string if the given IBAN was reported to belong to the given account holder, this field will contain
	 * the date in the format yyyymmdd when the IBAN was last reported to exist. If you did not submit the account
	 * holder or if nobody has confirmed the IBAN exists, this field is left empty.
	 */
	public $iban_last_reported = '';

	/**
	 * @var string a string describing the IBAN format for the given country, for example: 'DEkk BBBB BBBB CCCC CCCC CC'.
	 * formatcomment: an explanation of the IBANformat string, for example: 'B = sort code (BLZ), C = account No.'
	 */
	public $IBANformat = '';

	/** @var string */
	public $formatcomment = '';

	/**
	 * @var int the number of remaining calculations you can do before having to recharge your account.
	 * This does not apply to customers with a subscription which includes an unlimited number of calculations.
	 */
	public $balance = 0;

	/**
	 * Verify if iban (or iban+bic) is valid
	 *
	 * @return bool
	 */
	public function isValid(): bool
	{
		return 'passed' === $this->result;
	}
}