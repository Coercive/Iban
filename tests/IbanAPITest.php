<?php
namespace Coercive\Shop\Iban\Tests;

use Coercive\Shop\Iban\Iban;
use Coercive\Shop\Iban\IbanAPI;
use Coercive\Shop\Iban\Struct\BalanceResStruct;
use Coercive\Shop\Iban\Struct\BICStruct;
use Coercive\Shop\Iban\Struct\Field;
use Coercive\Shop\Iban\Struct\Form;
use Coercive\Shop\Iban\Struct\IBANValResStruct;
use Coercive\Shop\Iban\Struct\TransferResStruct;
use Coercive\Shop\Iban\Tests\Fake\FakeIbanAPI;
use PHPUnit\Framework\TestCase;

final class IbanAPITest extends TestCase
{
	public function testValidateIbanMapsTheResponse(): void
	{
		$api = new FakeIbanAPI('user', 'secret', 'Holder');
		$result = $api->validate_iban('GB82WEST12345698765432');

		$this->assertInstanceOf(IBANValResStruct::class, $result);
		$this->assertTrue($result->isValid());
		$this->assertSame('0', $result->return_code);
		$this->assertSame(['length', 'iban_checksum'], $result->checks);
		$this->assertCount(1, $result->bic_candidates);
		$this->assertInstanceOf(BICStruct::class, $result->bic_candidates[0]);
		$this->assertSame('NWBKGB2LXXX', $result->bic_candidates[0]->bic);
		$this->assertSame(0, $result->bic_candidates[0]->wwwcount);
		$this->assertSame([], $result->all_bic_candidates);
		$this->assertSame(7999, $result->balance);

		$request = $api->requests()[0];
		$this->assertStringContainsString('<ns1:validate_iban>', $request);
		$this->assertStringContainsString('>user<', $request);
		$this->assertStringContainsString('>secret<', $request);
		$this->assertStringContainsString('>Holder<', $request);
	}

	public function testFieldAddedToTheWsdlLater(): void
	{
		# The WSDL is read live : a new field must not raise a deprecation (PHP 8.2+ dynamic properties)
		$wsdl = tempnam(sys_get_temp_dir(), 'wsdl');
		file_put_contents($wsdl, preg_replace(
			'`(<xsd:complexType name="IBANValResStruct">.*?<xsd:all>)`s',
			'$1<xsd:element name="field_added_by_the_api" type="xsd:string"/>',
			file_get_contents(__DIR__ . '/fixtures/iban.wsdl')
		));

		$api = new FakeIbanAPI('user', 'secret');
		$api->wsdlFile = $wsdl;
		$result = $api->validate_iban('GB82WEST12345698765432');
		unlink($wsdl);

		$this->assertSame('later', $result->field_added_by_the_api);
	}

	public function testMalformedIbanDoesNotCallTheApi(): void
	{
		$api = new FakeIbanAPI('user', 'secret');

		$result = $api->validate_iban('GB83WEST12345698765432');
		$this->assertFalse($result->isValid());
		$this->assertSame('failed', $result->result);
		$this->assertSame((string) Iban::ERROR_CHECKSUM, $result->return_code);
		$this->assertSame(['length', 'iban_checksum'], $result->checks);
		$this->assertSame('passed', $result->length_check);
		$this->assertSame('failed', $result->iban_checksum_check);
		$this->assertSame('GB', $result->country);
		$this->assertSame('GB83WEST12345698765432', $result->iban);

		$result = $api->validate_iban_bic('FR14 2004', 'BNPAFRPP');
		$this->assertSame((string) Iban::ERROR_LENGTH, $result->return_code);
		$this->assertSame('failed', $result->length_check);
		$this->assertSame('', $result->iban_checksum_check);

		$result = $api->validate_iban('');
		$this->assertSame((string) Iban::ERROR_FORMAT, $result->return_code);
		$this->assertSame([], $result->checks);

		$this->assertSame([], $api->requests());
	}

	public function testValidateIbanBic(): void
	{
		$api = new FakeIbanAPI('user', 'secret');
		$this->assertTrue($api->validate_iban_bic('gb82 west 1234 5698 7654 32', 'NWBKGB2L')->isValid());
		$this->assertStringContainsString('<ns1:validate_iban_bic>', $api->requests()[0]);
		$this->assertStringContainsString('>NWBKGB2L<', $api->requests()[0]);
	}

	public function testBalance(): void
	{
		$balance = (new FakeIbanAPI('user', 'secret'))->get_balance();
		$this->assertInstanceOf(BalanceResStruct::class, $balance);
		$this->assertSame(8000, $balance->balance);
		$this->assertTrue($balance->hasCredits());
	}

	public function testTransferForm(): void
	{
		$api = new FakeIbanAPI('user', 'secret');
		$result = $api->transfer_form('sepa', '2026-10-20', 'To', 'GB82WEST12345698765432', '', 'From', 'DE89370400440532013000', '', '10.00');
		$this->assertInstanceOf(TransferResStruct::class, $result);
		$this->assertSame('https://example.test/form', $result->url);
		$this->assertInstanceOf(IBANValResStruct::class, $result->validation_to);
		$this->assertTrue($result->validation_from->isValid());
		$this->assertStringContainsString('>10.00<', $api->requests()[0]);
	}

	public function testGetFormAcceptsAnAssociativeArray(): void
	{
		$api = new FakeIbanAPI('user', 'secret');
		$form = $api->get_form(['country' => 'FR']);
		$this->assertInstanceOf(Form::class, $form);
		$this->assertInstanceOf(Field::class, $form->fields[0]);
		$this->assertSame(['DE', 'FR'], $form->fields[0]->optionids);

		$api->get_form([['key' => 'language', 'value' => 'fr']]);
		[$assoc, $items] = $api->requests();
		$this->assertMatchesRegularExpression('`<key[^>]*>country</key><value[^>]*>FR</value>`', $assoc);
		$this->assertMatchesRegularExpression('`<key[^>]*>language</key><value[^>]*>fr</value>`', $items);
	}

	public function testClassmapMatchesTheWsdl(): void
	{
		$client = new \SoapClient(__DIR__ . '/fixtures/iban.wsdl');
		$types = implode("\n", $client->__getTypes());
		foreach (IbanAPI::CLASSMAP as $type => $class) {
			$this->assertTrue(class_exists($class), $class);
			$this->assertStringContainsString("struct $type {", $types);

			# Every WSDL field is declared in the struct
			preg_match('`struct ' . $type . ' \{\n(.*?)\n\}`s', $types, $matches);
			preg_match_all('`^ \S+ (\w+);$`m', $matches[1], $fields);
			foreach ($fields[1] as $field) {
				$this->assertTrue(property_exists($class, $field), "$class::\$$field");
			}
		}
	}

	public function testEveryWsdlOperationIsAvailable(): void
	{
		$client = new \SoapClient(__DIR__ . '/fixtures/iban.wsdl');
		foreach ($client->__getFunctions() as $function) {
			preg_match('`^\w+ (\w+)\(`', $function, $matches);
			$this->assertTrue(method_exists(IbanAPI::class, $matches[1]), $matches[1]);
		}
	}
}
