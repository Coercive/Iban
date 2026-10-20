<?php
namespace Coercive\Shop\Iban\Tests\Fake;

use Coercive\Shop\Iban\IbanAPI;
use SoapClient;

final class FakeIbanAPI extends IbanAPI
{
	/** @var FakeSoapClient|null */
	public $fake = null;

	/** @var string */
	public $wsdlFile = __DIR__ . '/../fixtures/iban.wsdl';

	protected function client(): SoapClient
	{
		return $this->fake ??= new FakeSoapClient($this->wsdlFile, ['classmap' => self::CLASSMAP]);
	}

	public function requests(): array
	{
		return $this->fake ? $this->fake->requests : [];
	}
}
