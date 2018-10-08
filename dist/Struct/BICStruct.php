<?php
namespace Coercive\Shop\Iban\Struct;

/**
 * Class BICStruct
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
class BICStruct
{
	/** @var string */
	public $bic = '';

	/** @var string */
	public $zip = '';

	/** @var string */
	public $city = '';

	/** @var string */
	public $wwwcount = '';

	/** @var string */
	public $sampleurl = '';
}