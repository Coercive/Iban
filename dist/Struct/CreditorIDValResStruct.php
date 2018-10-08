<?php
namespace Coercive\Shop\Iban\Struct;

/**
 * Class CreditorIDValResStruct
 *
 * Validates the given Creditor Identifier.
 * The user ID and password you need to pass with the request are the same you use for logging in on the website iban-bic.com.
 * Instead of the password, you may also pass md5('validate_credit_identifier'.user.creditor_identifier.password),
 * that is, the MD5 hash of the concatenation of the function name, your user name, the creditor identifier,
 * and your password.
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
 * @author Anthony Moral <contact@coercive.fr>
 * @copyright (c) 2018 Anthony Moral
 * @license MIT
 */
class CreditorIDValResStruct
{
	/** @var string the creditor identifier which was validated. */
	public $id = '';

	/** @var string 'passed' or 'failed' - for a valid or invalid Creditor Identifier. */
	public $result = '';

	/**
	 * @var string a condensed representation of the results of various checks.
	 * Not all checks have necessarily been done (see the other fields to find out).
	 * The condensed representation is a number which is 0 if all supported checks were done and passed.
	 * Otherwise, it is the sum of one or more of the following numbers: 512=wrong length of the Creditor Identifier;
	 * 1024=invalid country code (no SEPA country); 2048=ID checksum error (digits3 and 4).
	 *
	 * If the return code is 65536, this means that it is not set at all.
	 * This should not happen - please notify us if it does.
	 */
	public $return_code = '';

	/** @var string[] an array of the checks performed (can contain elements such as 'length' and 'checksum'). */
	public $checks = [];

	/** @var string the ISO country code (first 2 digits of the ID). */
	public $country = '';

	/** @var string the 3 digits which are chosen by the creditor, for instance to keep track of different branches. */
	public $creditor_business_code = '';

	/** @var string the domestic ID number which identifies the creditor. */
	public $national_id = '';

	/** @var string 'passed' or 'failed' - for the right number of characters for the given country */
	public $length_check = '';

	/** @var string */
	public $country_check = '';

	/** @var string 'passed' or 'failed' (correctness of the two digits right after the country code in the Creditor Identifier) */
	public $checksum_check = '';

	/**
	 * @var int the number of remaining calculations you can do before having to recharge your account.
	 * This does not apply to customers with a subscription which includes an unlimited number of calculations.
	 */
	public $balance = 0;
}