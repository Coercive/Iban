<?php
declare(strict_types=1);

namespace Coercive\Shop\Iban\Struct;

/**
 * Class BankResStruct
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
 * @copyright   2018 - 2026 Anthony Moral
 * @license 	MIT
 */
#[\AllowDynamicProperties]
class BankResStruct
{
	/** @var string */
	public $result = '';

	/** @var BankStruct[] */
	public $banks = [];

	/** @var int */
	public $balance = 0;
}
