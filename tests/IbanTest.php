<?php
namespace Coercive\Shop\Iban\Tests;

use Coercive\Shop\Iban\Iban;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class IbanTest extends TestCase
{
	/**
	 * @return array
	 */
	public static function validIbans(): array
	{
		return [
			['FR1420041010050500013M02606'],
			['FR76 3000 6000 0112 3456 7890 189'],
			['fr76 3000 6000 0112 3456 7890 189'],
			['DE89370400440532013000'],
			['GB82WEST12345698765432'],
			['BE68539007547034'],
			['NL91ABNA0417164300'],
			['ES9121000418450200051332'],
			['IT60X0542811101000000123456'],
			['CH9300762011623852957'],
			['LU280019400644750000'],
			['MC5811222000010123456789030'],
			['NO9386011117947'],
			['PT50000201231234567890154'],
			["DE89 3704\t0044 0532\n0130 00"],
		];
	}

	#[DataProvider('validIbans')]
	public function testValid(string $iban): void
	{
		$this->assertSame(Iban::VALID, Iban::check($iban));
		$this->assertTrue(Iban::isValid($iban));
	}

	/**
	 * @return array
	 */
	public static function invalidIbans(): array
	{
		return [
			'empty' => ['', Iban::ERROR_FORMAT],
			'spaces' => ['   ', Iban::ERROR_FORMAT],
			'no country' => ['1234567890123456', Iban::ERROR_FORMAT],
			'no check digits' => ['FRAB20041010050500013M02606', Iban::ERROR_FORMAT],
			'bad character' => ['FR14-20041010050500013M02606', Iban::ERROR_FORMAT],
			'accent' => ['FR1420041010050500013M0260é', Iban::ERROR_FORMAT],
			'too short for FR' => ['FR142004101005050013M02606', Iban::ERROR_LENGTH],
			'too long for DE' => ['DE893704004405320130000', Iban::ERROR_LENGTH],
			'unknown country too short' => ['ZZ12345678901', Iban::ERROR_LENGTH],
			'unknown country too long' => ['ZZ12' . str_repeat('1', 31), Iban::ERROR_LENGTH],
			'one digit changed' => ['FR1420041010050500013M02607', Iban::ERROR_CHECKSUM],
			'two digits swapped' => ['DE89370400440532031000', Iban::ERROR_CHECKSUM],
			'check digits 01 (same remainder as 98)' => ['GB01WEST12345698765432', Iban::ERROR_CHECKSUM],
			'check digits 00' => ['GB00WEST12345698765432', Iban::ERROR_CHECKSUM],
		];
	}

	#[DataProvider('invalidIbans')]
	public function testInvalid(string $iban, int $code): void
	{
		$this->assertSame($code, Iban::check($iban));
		$this->assertFalse(Iban::isValid($iban));
	}

	public function testUnknownCountryOnlyChecksumAndBounds(): void
	{
		# Same BBAN as GB82WEST…, with a country missing from the list : checksum recomputed by hand
		$bban = 'WEST12345698765432';
		$numeric = strtr($bban . 'ZZ00', array_combine(range('A', 'Z'), array_map('strval', range(10, 35))));
		$digits = 98 - (int) array_reduce(str_split($numeric, 7), function ($mod, $chunk) { return ($mod . $chunk) % 97; }, 0);
		$this->assertTrue(Iban::isValid(sprintf('ZZ%02d%s', $digits, $bban)));
	}

	public function testLengthsAreConsistent(): void
	{
		foreach (Iban::LENGTHS as $country => $length) {
			$this->assertMatchesRegularExpression('`^[A-Z]{2}$`', $country);
			$this->assertGreaterThanOrEqual(Iban::MIN_LENGTH, $length);
			$this->assertLessThanOrEqual(Iban::MAX_LENGTH, $length);
		}
	}

	public function testNormalize(): void
	{
		$this->assertSame('FR7630006000011234567890189', Iban::normalize(" fr76 3000 6000\u{00A0}0112 3456 7890 189\n"));
		$this->assertSame('', Iban::normalize(''));
	}

	public function testFormat(): void
	{
		$this->assertSame('FR76 3000 6000 0112 3456 7890 189', Iban::format('fr7630006000011234567890189'));
		$this->assertSame('BE68&nbsp;5390&nbsp;0754&nbsp;7034', Iban::format('BE68 5390 0754 7034', '&nbsp;'));
		$this->assertSame('', Iban::format(' '));
	}
}
