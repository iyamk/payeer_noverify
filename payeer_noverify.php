<?php

class PayeerNoVerify
{
	private $url = 'https://payeer.com';
	private $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36';
	private $cookie;

	public function __construct($cookie, $agent = NULL)
	{
		$this->cookie = $cookie;
		if (!is_null($agent))
			$this->agent = $agent;
	}

	public function history()
	{
		$ch  = curl_init();
		$query_arr = [
			'operation_id' => '',
			'date_from' => '',
			'date_to' => '',
			'type' => '',
			'curr' => '',
			'block' => '0',
			'json' => 'Y',
			'data-id' => '#tab-myoperations .table_history table tbody',
			'append' => '0',
			'template' => 'ajax',
			'last-id' => '',
			'template' => 'csv'
		];
		$query = http_build_query($query_arr, '', '&', PHP_QUERY_RFC3986);
		curl_setopt($ch, CURLOPT_URL, $this->url . '/bitrix/components/payeer/account.history/templates/04_19_2/ajax.php?'.$query);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
			'Accept-Language: en-US,en;q=0.9',
			'Connection: keep-alive',
			'Cookie: PHPSESSID='.$this->cookie,
			'Referer: https://payeer.com/ru/account/history/',
			'Sec-Fetch-Dest: iframe',
			'Sec-Fetch-Mode: navigate',
			'Sec-Fetch-Site: same-origin',
			'Sec-Fetch-User: ?1',
			'Upgrade-Insecure-Requests: 1'
		]);
		$content = curl_exec($ch);
		curl_close($ch);
		if ($content === '')
			return [
				'auth_error' => '1',
				'errors' => [ 'Auth error: cookie session expired' ]
			];
		$csv = explode("\n", $content);
		$result = [];
		$fields = [
			'date',
			'type',
			'from',
			'creditedAmount',
			'creditedCurrency',
			'to',
			'debitedAmount',
			'debitedCurrency',
			'paySystem',
			'status',
			'id',
			'shopUrl',
			'shopId',
			'shopOrderId',
			'isApi',
			'comment'
		];
		for ($i = 1; $i < count($csv); $i++)
		{
			$line = explode(';', $csv[$i]);
			if (count($line) < 16) continue;
			$new = [];
			for ($c = 0; $c < count($line); $c++)
				$new[$fields[$c]] = $line[$c];
			$result[] = $new;
		}
		return [
			'auth_error' => '0',
			'errors' => [],
			'params' => [],
			'history' => $result
		];
	}
}
