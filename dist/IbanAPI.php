<?php
namespace Coercive\Shop\Iban;

use SoapFault;
use SoapClient;

use Coercive\Shop\Iban\Struct\ABAValResStruct;
use Coercive\Shop\Iban\Struct\BalanceResStruct;
use Coercive\Shop\Iban\Struct\BankResStruct;
use Coercive\Shop\Iban\Struct\BICStruct;
use Coercive\Shop\Iban\Struct\CountryResStruct;
use Coercive\Shop\Iban\Struct\CreditorIDValResStruct;
use Coercive\Shop\Iban\Struct\IBANCalcResStruct;
use Coercive\Shop\Iban\Struct\IBANCandStruct;
use Coercive\Shop\Iban\Struct\IBANValResStruct;
use Coercive\Shop\Iban\Struct\Form;
use Coercive\Shop\Iban\Struct\GetSaltsResStruct;
use Coercive\Shop\Iban\Struct\SubmitIBANResStruct;

/**
 * Class Iban
 *
 * @api IBAN_Calculator
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
class IbanAPI
{
	const WSDL = 'https://ssl.ibanrechner.de/soap/?wsdl';

	/** @var string Wsdl Api */
	private $wsdl = '';

	/** @var string Api User id */
	private $user = '';

	/** @var string Api User password */
	private $password = '';

	/** @var string Api User account holder */
	private $holder = '';

	/** @var SoapClient Soap Api */
	private $client = null;

	/**
	 * INIT CLIENT
	 *
	 * @return SoapClient
	 * @throws SoapFault
	 */
	private function connect()
	{
		# Already setted
		if(null !== $this->client) {
			return $this->client;
		}

		# Prepare Soap instance with mapping
		return $this->client = new SoapClient($this->wsdl, [
			'classmap' => [
				'IBANCandStruct' => IBANCandStruct::class,
				'IBANValResStruct' => IBANValResStruct::class,
				'IBANCalcResStruct' => IBANCalcResStruct::class,
				'BalanceResStruct' => BalanceResStruct::class,
				'CreditorIDValResStruct' => CreditorIDValResStruct::class,
				'BICStruct' => BICStruct::class
			]
		]);
	}

	/**
	 * Iban constructor.
	 *
	 * @param string $user [optional]
	 * @param string $password [optional]
	 * @param string $holder [optional]
	 * @return void
	 */
	public function __construct(string $user = '', string $password = '', string $holder = '')
	{
		# Init default WSDL
		$this->wsdl = self::WSDL;

		# Init authentication params
		$this->user = $user;
		$this->password = $password;
		$this->holder = $holder;
	}

	/**
	 * SET CUSTOM WSLD (override default)
	 *
	 * @param string $wsdl
	 * @return IbanAPI
	 */
	public function setWsdl(string $wsdl): IbanAPI
	{
		$this->wsdl = $wsdl;
		return $this;
	}

	/**
	 * Account Balance
	 *
	 * @return BalanceResStruct
	 * @throws SoapFault
	 */
	public function get_balance(): BalanceResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->get_balance($this->user, $this->password);
	}

	/**
	 * Validate IBAN
	 *
	 * @param string $iban
	 * @return IbanValResStruct
	 * @throws SoapFault
	 */
	public function validate_iban(string $iban): IbanValResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->validate_iban($iban, $this->user, $this->password, $this->holder);
	}

	/**
	 * Restore IBAN
	 *
	 * @param string $iban
	 * @return IbanCandStruct
	 * @throws SoapFault
	 */
	public function restore_iban(string $iban): IbanCandStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->restore_iban($iban, $this->user, $this->password);
	}

	/**
	 * Validate IBAN + BIC
	 *
	 * @param string $iban
	 * @param string $bic
	 * @return IbanValResStruct
	 * @throws SoapFault
	 */
	public function validate_iban_bic(string $iban, string $bic): IbanValResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->validate_iban_bic($iban, $bic, $this->user, $this->password, $this->holder);
	}

	/**
	 * @TODO
	 *
	 * @param string $iban
	 * @param string $bic
	 * @return TransferResStruct
	 * @throws SoapFault
	 */
	public function transfer_form(string $kind, string $date, string $toname, string $toaccount)//: TransferResStruct
	{
		die;
	}

	/**
	 * Validate Creditor Identifier
	 *
	 * @param string $creditor_identifier
	 * @return CreditorIDValResStruct
	 * @throws SoapFault
	 */
	public function validate_creditor_identifier(string $creditor_identifier): CreditorIDValResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->validate_creditor_identifier($creditor_identifier, $this->user, $this->password);
	}

	/**
	 * Calculate IBAN
	 *
	 * @param string $country
	 * @param string $bankcode
	 * @param string $account
	 * @param string $bic [optional]
	 * @param int $legacy_mode [optional]
	 * @return IbanCalcResStruct
	 * @throws SoapFault
	 */
	public function calculate_iban(string $country, string $bankcode, string $account, string $bic = '', int $legacy_mode = 0): IbanCalcResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->calculate_iban($country, $bankcode, $account, $this->user, $this->password, $bic, $legacy_mode);
	}

	/**
	 * Validate IBAN batch
	 *
	 * @param string $countries
	 * @param string $bankcodes
	 * @param string $accounts
	 * @param string $bics [optional]
	 * @param int $legacy_mode [optional]
	 * @return IbanValResStruct[]
	 * @throws SoapFault
	 */
	public function validate_iban_batch(string $countries, string $bankcodes, string $accounts, string $bics = '', int $legacy_mode = 0): array
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->validate_iban_batch($countries, $bankcodes, $accounts, $this->user, $this->password, $bics, $legacy_mode);
	}

	/**
	 * Calculate IBAN batch
	 *
	 * @param string $countries
	 * @param string $bankcodes
	 * @param string $accounts
	 * @param string $bics [optional]
	 * @param int $legacy_mode [optional]
	 * @return IbanCalcResStruct[]
	 * @throws SoapFault
	 */
	public function calculate_iban_batch(string $countries, string $bankcodes, string $accounts, string $bics = '', int $legacy_mode = 0): array
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->calculate_iban_batch($countries, $bankcodes, $accounts, $this->user, $this->password, $bics, $legacy_mode);
	}

	/**
	 * Validate ABA
	 *
	 * @param string $routing_number
	 * @return ABAValResStruct
	 * @throws SoapFault
	 */
	public function validate_ABA(string $routing_number): ABAValResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->validate_ABA($routing_number, $this->user, $this->password);
	}

	/**
	 * Get Dutch banks
	 *
	 * @return BankResStruct
	 * @throws SoapFault
	 */
	public function get_dutch_banks(): BankResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->get_dutch_banks($this->user, $this->password);
	}

	/**
	 * Get Swiss banks
	 *
	 * @warning Due to the large number of BC numbers, this function needs to be used in two steps:
	 *          1. If called with an empty bank name, get_swiss_banks returns a list of all names of Swiss banks, but still without BC numbers.
	 *          2. If called with one of the bank names that were obtained in step 1, the function returns all BC numbers for the given bank.
	 *
	 * @param string $bank [optional]
	 * @return BankResStruct
	 * @throws SoapFault
	 */
	public function get_swiss_banks(string $bank = ''): BankResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->get_swiss_banks($bank, $this->user, $this->password);
	}

	/**
	 * Find bank
	 *
	 * @param string $searchterms [optional]
	 * @param string $country [optional]
	 * @param string $bankcode [optional]
	 * @param int $maxitems [optional]
	 * @return BankResStruct
	 * @throws SoapFault
	 */
	public function find_bank(string $searchterms = '', string $country = '', string $bankcode = '', int $maxitems = 0): BankResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->find_bank($searchterms, $country, $bankcode, $maxitems, $this->user, $this->password);
	}

	/**
	 * Get salts
	 *
	 * @param string $account_holder
	 * @return GetSaltsResStruct
	 * @throws SoapFault
	 */
	public function get_salts(string $account_holder): GetSaltsResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->get_salts($account_holder, $this->user, $this->password);
	}

	/**
	 * Submit IBAN
	 *
	 * @param string $iban
	 * @param string $account_holder
	 * @return SubmitIBANResStruct
	 * @throws SoapFault
	 */
	public function submit_iban(string $iban, string $account_holder): SubmitIBANResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->submit_iban($iban, $account_holder, $this->user, $this->password);
	}

	/**
	 * Submit hashes
	 *
	 * @param string $hashes
	 * @param string $salts
	 * @param string $IBANprefix
	 * @return SubmitIBANResStruct
	 * @throws SoapFault
	 */
	public function submit_hashes(string $hashes, string $salts, string $IBANprefix): SubmitIBANResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->submit_hashes($hashes, $salts, $IBANprefix, $this->user, $this->password);
	}

	/**
	 * Supported countries
	 *
	 * @param string $supported_condition
	 * @param string $language
	 * @return CountryResStruct
	 * @throws SoapFault
	 */
	public function supported_countries(string $supported_condition, string $language): CountryResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->supported_countries($supported_condition, $language, $this->user, $this->password);
	}

	/**
	 * Country supported
	 *
	 * @warning THIS IS A LEGACY FUNCTION. PLEASE DO NOT USE IT ANYMORE. USE supported_countries INSTEAD.
	 *
	 * @param string $country
	 * @return CountryResStruct
	 * @throws SoapFault
	 */
	public function country_supported(string $country): CountryResStruct
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->country_supported($country, $this->user, $this->password);
	}

	/**
	 * Get form
	 *
	 * @param array $map
	 * @return Form
	 * @throws SoapFault
	 */
	public function get_form(array $map): Form
	{
		# Prepare
		$this->connect();

		# Retreive
		return $this->client->get_form($map, $this->user, $this->password);
	}
}