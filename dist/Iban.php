<?php
declare(strict_types=1);

namespace Coercive\Shop\Iban;

/**
 * Local IBAN validation (ISO 13616 : length by country + MOD 97-10 checksum)
 *
 * No network call and no API credit : it does not tell if the account exists,
 * only if the IBAN is well formed. Use it before calling the IBAN API.
 *
 * The return codes are the same as the API ones (IBANValResStruct::$return_code).
 *
 * @package Coercive\Shop\Iban
 * @link https://github.com/Coercive/Iban
 *
 * @author  	Anthony Moral <contact@coercive.fr>
 * @copyright   2018 - 2026 Anthony Moral
 * @license 	MIT
 */
class Iban
{
	/** The IBAN is well formed */
	const VALID = 0;

	/** Wrong length for the country */
	const ERROR_LENGTH = 512;

	/** Checksum error (digits 3 and 4) */
	const ERROR_CHECKSUM = 2048;

	/** Empty, bad characters, or does not start with a country code and 2 digits */
	const ERROR_FORMAT = 4096;

	/** Bounds used for a country missing from LENGTHS */
	const MIN_LENGTH = 15;
	const MAX_LENGTH = 34;

	/** @var int[] IBAN length by country (SWIFT IBAN registry) */
	const LENGTHS = [
		'AD' => 24, 'AE' => 23, 'AL' => 28, 'AT' => 20, 'AZ' => 28,
		'BA' => 20, 'BE' => 16, 'BG' => 22, 'BH' => 22, 'BI' => 27, 'BR' => 29, 'BY' => 28,
		'CH' => 21, 'CR' => 22, 'CY' => 28, 'CZ' => 24,
		'DE' => 22, 'DJ' => 27, 'DK' => 18, 'DO' => 28,
		'EE' => 20, 'EG' => 29, 'ES' => 24,
		'FI' => 18, 'FK' => 18, 'FO' => 18, 'FR' => 27,
		'GB' => 22, 'GE' => 22, 'GI' => 23, 'GL' => 18, 'GR' => 27, 'GT' => 28,
		'HN' => 28, 'HR' => 21, 'HU' => 28,
		'IE' => 22, 'IL' => 23, 'IQ' => 23, 'IS' => 26, 'IT' => 27,
		'JO' => 30,
		'KW' => 30, 'KZ' => 20,
		'LB' => 28, 'LC' => 32, 'LI' => 21, 'LT' => 20, 'LU' => 20, 'LV' => 21, 'LY' => 25,
		'MC' => 27, 'MD' => 24, 'ME' => 22, 'MK' => 19, 'MN' => 20, 'MR' => 27, 'MT' => 31, 'MU' => 30,
		'NI' => 28, 'NL' => 18, 'NO' => 15,
		'OM' => 23,
		'PK' => 24, 'PL' => 28, 'PS' => 29, 'PT' => 25,
		'QA' => 29,
		'RO' => 24, 'RS' => 22, 'RU' => 33,
		'SA' => 24, 'SC' => 31, 'SD' => 18, 'SE' => 24, 'SI' => 19, 'SK' => 24, 'SM' => 27, 'SO' => 23, 'ST' => 25, 'SV' => 28,
		'TL' => 23, 'TN' => 24, 'TR' => 26,
		'UA' => 29,
		'VA' => 22, 'VG' => 24,
		'XK' => 20,
		'YE' => 30,
	];

	/**
	 * Electronic format : uppercase, without spaces
	 *
	 * @param string $iban
	 * @return string
	 */
	public static function normalize(string $iban): string
	{
		return strtoupper((string) preg_replace('`\s+`u', '', $iban));
	}

	/**
	 * Check the IBAN, spaces and lowercase allowed
	 *
	 * A country missing from LENGTHS is only checked against MIN_LENGTH / MAX_LENGTH
	 * (the API will tell if it is supported).
	 *
	 * @param string $iban
	 * @return int VALID or ERROR_*
	 */
	public static function check(string $iban): int
	{
		$iban = self::normalize($iban);
		if (!preg_match('`^[A-Z]{2}[0-9]{2}[A-Z0-9]+$`', $iban)) {
			return self::ERROR_FORMAT;
		}

		$length = strlen($iban);
		$expected = self::LENGTHS[substr($iban, 0, 2)] ?? null;
		if (null === $expected ? $length < self::MIN_LENGTH || $length > self::MAX_LENGTH : $length !== $expected) {
			return self::ERROR_LENGTH;
		}

		# 00, 01 and 99 are never issued (01 and 98 give the same remainder)
		$digits = (int) substr($iban, 2, 2);
		if ($digits < 2 || $digits > 98 || self::mod97($iban) !== 1) {
			return self::ERROR_CHECKSUM;
		}
		return self::VALID;
	}

	/**
	 * @param string $iban
	 * @return bool
	 */
	public static function isValid(string $iban): bool
	{
		return self::VALID === self::check($iban);
	}

	/**
	 * Print format : groups of 4 characters
	 *
	 * @param string $iban
	 * @param string $separator [optional]
	 * @return string
	 */
	public static function format(string $iban, string $separator = ' '): string
	{
		$iban = self::normalize($iban);
		return '' === $iban ? '' : implode($separator, str_split($iban, 4));
	}

	/**
	 * MOD 97-10 (ISO 7064) of a normalized IBAN, without bcmath
	 *
	 * @param string $iban
	 * @return int
	 */
	private static function mod97(string $iban): int
	{
		# Country code and check digits at the end, letters to numbers (A = 10 … Z = 35)
		$rearranged = substr($iban, 4) . substr($iban, 0, 4);
		$numeric = strtr($rearranged, array_combine(range('A', 'Z'), array_map('strval', range(10, 35))));

		# By chunks of 7 digits : the remainder (2 digits) + 7 digits fit in 32 bits
		$mod = 0;
		foreach (str_split($numeric, 7) as $chunk) {
			$mod = (int) ($mod . $chunk) % 97;
		}
		return $mod;
	}
}
