<?php
namespace Coercive\Shop\Iban\Struct;

/**
 * Class BankStruct
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
class BankStruct
{
	/** @var string */
	public $name = '';

	/** @var string */
	public $bic = '';

	/** @var $int */
	public $bankcode = 0;

	/** @var $int */
	public $branchcode = 0;

	/** @var string */
	public $country = '';

	/** @var string */
	public $address = '';

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
}