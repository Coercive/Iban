<?php
namespace Coercive\Shop\Iban\Tests\Fake;

use SoapClient;

/**
 * Answers with the fixture of the called operation, without network
 */
final class FakeSoapClient extends SoapClient
{
	/** @var string[] Requests sent */
	public $requests = [];

	public function __doRequest($request, $location, $action, $version, $oneWay = false, ...$args): ? string
	{
		$this->requests[] = $request;
		preg_match('`<ns1:(\w+)>`', $request, $matches);
		return file_get_contents(__DIR__ . '/../fixtures/' . $matches[1] . '.xml');
	}
}
