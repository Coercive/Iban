<?php
declare(strict_types=1);

namespace Coercive\Shop\Iban\Struct;

/**
 * Class ABAValResStruct
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
class ABAValResStruct
{
	/** @var string */
	public $routing_number = '';

	/** @var string */
	public $result = '';

	/** @var string */
	public $return_code = '';

	/** @var string */
	public $OKchecksum = '';

	/** @var string */
	public $OKformat = '';

	/** @var string */
	public $name = '';

	/** @var string */
	public $state = '';

	/** @var string */
	public $city = '';

	/** @var string */
	public $funds_transfer = '';

	/** @var string */
	public $book_entry_securities_transfer = '';

	/** @var string */
	public $settlement_only = '';

	/** @var string */
	public $range = '';

	/** @var int */
	public $balance = 0;
}
