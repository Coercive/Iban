<?php
namespace Coercive\Shop\Iban\Struct;

/**
 * Class Field
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
class Field
{
	/** @var string */
	public $id = '';

	/** @var string */
	public $type = '';

	/** @var string */
	public $value = '';

	/** @var string */
	public $label = '';

	/** @var string[] */
	public $optionids = '';

	/** @var string[] */
	public $options = '';

	/** @var int */
	public $length = 0;

	/** @var string */
	public $newline = '';
}