<?php
namespace Coercive\Shop\Iban\Struct;

/**
 * Class SubmitIBANResStruct
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
class SubmitIBANResStruct
{
	/** @var string */
	public $result = '';

	/** @var int */
	public $earned_points = 0;

	/** @var int */
	public $balance = 0;
}