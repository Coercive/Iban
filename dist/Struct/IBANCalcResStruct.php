<?php
namespace Coercive\Shop\Iban\Struct;

/**
 * Class IBANCalcResStruct
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
class IBANCalcResStruct
{
	/** @var string */
	public $iban = '';

	/** @var string */
	public $result = '';

	/** @var string */
	public $return_code = '';

	/** @var string */
	public $ibanrueck_return_code = '';

	/** @var string[] */
	public $checks = [];

	/** @var BICStruct[] */
	public $bic_candidates = [];

	/** @var string */
	public $bank_code = '';

	/** @var string */
	public $alternative_bank_code = '';

	/** @var string */
	public $bank = '';

	/** @var string */
	public $bank_address = '';

	/** @var string */
	public $bank_url = '';

	/** @var string */
	public $branch = '';

	/** @var string */
	public $branch_code = '';

	/** @var string */
	public $in_scl_directory = '';

	/** @var string */
	public $sct = '';

	/** @var string */
	public $sdd = '';

	/** @var string */
	public $cor1 = '';

	/** @var string */
	public $b2b = '';

	/** @var string */
	public $scc = '';

	/** @var string */
	public $account_number = '';

	/** @var string */
	public $alternative_account_number = '';

	/** @var string */
	public $account_validation_method = '';

	/** @var string */
	public $account_validation = '';

	/** @var string */
	public $length_check = '';

	/** @var string */
	public $account_check = '';

	/** @var string */
	public $bank_code_check = '';

	/** @var string */
	public $bic_plausibility_check = '';

	/** @var string */
	public $data_age = '';

	/** @var string */
	public $IBANformat = '';

	/** @var string */
	public $formatcomment = '';

	/** @var int */
	public $balance = 0;
}