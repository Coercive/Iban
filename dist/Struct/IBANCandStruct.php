<?php
namespace Coercive\Shop\Iban\Struct;

/**
 * Class IBANCandStruct
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
class IBANCandStruct
{
	/**
	 * @var string[] zero, one, or more IBANs which fit the given pattern and which are formally correct.
	 * If only one IBAN is returned, this is the only formally correct IBAN which fits the given pattern.
	 */
	public $iban_candidates = [];

	/** @var string ar error or success message in natural language. */
	public $result = '';

	/** @var string a string describing the IBAN format for the given country, for example: 'DEkk BBBB BBBB CCCC CCCC CC'. */
	public $IBANformat = '';

	/** @var string an explanation of the IBANformat string, for example: 'B = sort code (BLZ), C = account No.' */
	public $formatcomment = '';

	/**
	 * @var int the number of remaining calculations you can do before having to recharge your account.
	 * This does not apply to customers with a subscription which includes an unlimited number of calculations.
	 */
	public $balance = '';
}