<?php
/**
 * GMS integration api
 *
 * Use this api for sms sending
 *
 * -------------------------------
 * Minimum system requirements
 * - PHP 5.3
 */

/**
 * BMS URL get this url from our engineers. Or from your manager.
 */
$bmsUrl = 'https://api-v2.hyber.im/208';

/**
 * Get your password and username from GMS http://www.gms-worldwide.com/
 * Username and password are using for
 * GMS system authentication.
 *
 * <!> ATTENTION
 * Don't forgot to tell GMS about you'r
 * server ip address. We are using firewalls.
 * UserName:Password
 */
$bms_username = "MEDSERVICE";
$bms_password = "dsmA@2C&2fkc9)ec1";


/**
 * oa or alpha name
 * Send your list of alpha names to GMS manager.
 * or use Test
 */
$oa = 'Test';

/**
 * da or phone number
 * use next format 380....
 * without +
 * Example 380979817365
 */
$da = '380964739264';

/**
 * Text of message
 */
$text = 'GMS – телекоммуникационная компания, лицензированный оператор связи, специализирующийся на SMS и MMS-услугах.';

//Формируем JSON
$request_data = array(
    'phone_number' => $da,
    'tag' => 'MEDSERVICE',
    'is_promotional' => 'true',
    'channels' => 'viber',
    'channel_options' => [
        'viber' => [
            'text' => 'тест',
            'ttl' => 24
        ]
    ]);
$json = json_encode($request_data);

//Настраиваем cURL
$ch = curl_init($bmsUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic TUVEU0VSVklDRTpkc21BQDJDJjJma2M5KWVjMQ=='));
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Получаем данные
$response = curl_exec($ch);

echo "<pre>";
print_r($response);
die;



/**
 * Sending
 */
$result = SMSDoor_lib_SMSBMS::send(
    $bms_username . ':' . $bms_password,
    $text,
    $oa,
    $da,
    $bmsUrl
);

/**
 * Work results
 */
echo '<pre>';
var_dump($result);
echo '</pre>';

/**
 * Log result into file
 */
$file = 'send-log.txt';
if (!fopen($file, 'r')) {
    fopen($file, 'w');
}
$_file = fopen($file, 'a');
$logContent = null;
foreach ($result['response'] as $item) {
    $logContent .= date('Y-m-d H:i:s') . ' - ' . 'code: "' . $item['state']['code'] .
        '", date: "' . $item['state']['date'] . '", text: "' . $item['state']['text'] .
        '", reference: "' . $item['reference'] . '", part: "' . $item['part'] . '"' . PHP_EOL;
}
fputs($_file, $logContent);
fclose($_file);

/**
 * Log XML result into file
 */
$file = 'send-log-xml.txt';
if (!fopen($file, 'r')) {
    fopen($file, 'w');
}
$_file = fopen($file, 'a');
$logContent = null;
foreach ($result['xml'] as $item) {
    $logContent .=  date('Y-m-d H:i:s'). PHP_EOL . $item . PHP_EOL . PHP_EOL;
}
fputs($_file, $logContent);
fclose($_file);

echo '<h2>Work complete, read log.</h2>';

/**
 * Library
 */
class SMSDoor_lib_SMSBMS
{
    public static function send(
        $credentials,
        $text,
        $oa,
        $da,
        $bms_url,
        $rid = NULL,
        $logger = NULL,
        $concat_ref = NULL,
        $strict_resp = false)
    {
        if (!$credentials) {
            return 'ERROR! Set up ($bms_username and $bms_password,) your username and password or get them from GMS http://www.gms-worldwide.com/.';
        } elseif (!$text) {
            return 'ERROR! Set up ($text) sms text.';
        } elseif (!$oa) {
            return 'ERROR! Set up ($oa) alpha name. Send your list of alpha names to GMS manager or use Test alpha name';
        } elseif (!$da) {
            return 'ERROR! Set up ($da) phone number, use next format 380...., without +.';
        } elseif (!$bms_url) {
            return 'ERROR! Set up ($bmsUrl)';
        }

        $send_results = [];
        $parts = $text;
        if (is_string($text)) {
            $carr = SMSDoor_lib_GSM::convert_to_carr($text);
            $parts = SMSDoor_lib_GSM::split($carr);
        }
        $send_resps = SMSDoor_lib_BMS::send(
            $bms_url,
            $credentials,
            $oa,
            $da,
            $parts,
            $rid,
            $concat_ref,
            $logger
        );

        if (!$send_resps) {
            return false;
        }

        foreach ($send_resps->result as $sr) {
            $xml = SMSDoor_lib_BMS::parse_response_xml($sr);

            if ($strict_resp) {
                if(!$xml || $xml['state']['code'] !== 'ACCEPT') {
                    return false;
                }
            }

            $send_results['response'][] = $xml;
            $send_results['xml'][] = $sr;
        }

        foreach ($send_resps->parts as $key => $value) {
            $send_results['response'][$key]['part'] = $value;
        }

        return $send_results;
    }

    public static function parse_income_xml($str_xml, $logger)
    {
        libxml_use_internal_errors(true);

        try {
            $xml = new SimpleXMLElement($str_xml);
        } catch (Exception $e) {
            var_dump('Not valid XML');
            return false;
        }

        switch ((string) $xml->getName()) {
            case 'message':
                $res = self::parse_income_xml_message($xml, $logger);
                break;
            case 'status':
                $res = self::parse_income_xml_status($xml, $logger);
                break;
            default:
                var_dump('[SMSBMS] Unknown XML format');
                $res = NULL;
                break;
        }

        return $res;
    }

    public static function parse_income_xml_message($xml)
    {
        if (!isset($xml->da)) {
            var_dump('[SMSBMS] Not valid XML: no "da" element');
            return false;
        }

        if (!isset($xml->oa)) {
            var_dump('[SMSBMS] Not valid XML: no "oa" element');
            return false;
        }

        if (!isset($xml->text)) {
            var_dump('[SMSBMS] Not valid XML: no "text" element');
            return false;
        }

        $result = [];
        $result['name'] = (string) $xml->getName();
        $result['oa'] = (string) $xml->oa;
        $result['da'] = (string) $xml->da;
        $result['text'] = (string) $xml->text;
        $result['rid'] = isset($xml->rid) ? (string) $xml->rid : NULL;

        return $result;
    }

    public static function parse_income_xml_status($xml){
        if (!isset($xml->state)) {
            var_dump('[SMSBMS] Not valid XML: no "state" element');
            return false;
        }

        if (!isset($xml->state->attributes()['code'])) {
            var_dump('[SMSBMS] Not valid XML: no "code" attribute in "state" element');
            return false;
        }

        if (!isset($xml->message)) {
            var_dump('[SMSBMS] Not valid XML: no "message" element');
            return false;
        }

        if (!isset($xml->message->attributes()['id'])) {
            var_dump('[SMSBMS] Not valid XML: no "id" attribute in "message" element');
            return false;
        }

        $result = [];
        $result['name'] = (string) $xml->getName();
        $result['state'] = (string) $xml->state;
        $result['code'] = (string) $xml->state->attributes()['code'];
        $result['date'] = isset($xml->state->attributes()['date']) ? (string) $xml->state->attributes()['date'] : NULL;
        $result['id'] = (string) $xml->message->attributes()['id'];

        return $result;
    }
}

class SMSDoor_lib_GSM
{
    static $charset = [
        '@'=> 1, '£'=> 1, '$'=> 1, '¥'=> 1, 'è'=> 1, 'é'=> 1, 'ù'=> 1, 'ì'=> 1, 'ò'=> 1, 'Ç'=> 1,
        "\n"=>1, 'Ø'=> 1, 'ø'=> 1, "\r"=>1, 'Å'=> 1, 'å'=> 1, 'Δ'=> 1, '_'=> 1, 'Φ'=> 1, 'Γ'=> 1,
        'Λ'=> 1, 'Ω'=> 1, 'Π'=> 1, 'Ψ'=> 1, 'Σ'=> 1, 'Θ'=> 1, 'Ξ'=> 1, 'Æ'=> 1, 'æ'=> 1, 'ß'=> 1,
        'É'=> 1, ' '=> 1, '!'=> 1, '"'=> 1, '#'=> 1, '¤'=> 1, '%'=> 1, '&'=> 1, "'"=>1, '('=> 1,
        ')'=> 1, '*'=> 1, '+'=> 1, ','=> 1, '-'=> 1, '.'=> 1, '/'=> 1, '0'=> 1, '1'=> 1, '2'=> 1,
        '3'=> 1, '4'=> 1, '5'=> 1, '6'=> 1, '7'=> 1, '8'=> 1, '9'=> 1, '=>'=> 1, ';'=> 1, '<'=> 1,
        '='=> 1, '>'=> 1, '?'=> 1, '¡'=> 1, 'A'=> 1, 'B'=> 1, 'C'=> 1, 'D'=> 1, 'E'=> 1, 'F'=> 1,
        'G'=> 1, 'H'=> 1, 'I'=> 1, 'J'=> 1, 'K'=> 1, 'L'=> 1, 'M'=> 1, 'N'=> 1, 'O'=> 1, 'P'=> 1,
        'Q'=> 1, 'R'=> 1, 'S'=> 1, 'T'=> 1, 'U'=> 1, 'V'=> 1, 'W'=> 1, 'X'=> 1, 'Y'=> 1, 'Z'=> 1,
        'Ä'=> 1, 'Ö'=> 1, 'Ñ'=> 1, 'Ü'=> 1, '§'=> 1, '¿'=> 1, 'a'=> 1, 'b'=> 1, 'c'=> 1, 'd'=> 1,
        'e'=> 1, 'f'=> 1, 'g'=> 1, 'h'=> 1, 'i'=> 1, 'j'=> 1, 'k'=> 1, 'l'=> 1, 'm'=> 1, 'n'=> 1,
        'o'=> 1, 'p'=> 1, 'q'=> 1, 'r'=> 1, 's'=> 1, 't'=> 1, 'u'=> 1, 'v'=> 1, 'w'=> 1, 'x'=> 1,
        'y'=> 1, 'z'=> 1, 'ä'=> 1, 'ö'=> 1, 'ñ'=> 1, 'ü'=> 1, 'à'=> 1, ':'=> 1, "\f"=> 2, '^'=> 2, '{'=> 2,
        '}'=> 2, '\\'=> 2, '['=> 2, '~'=> 2, ']'=> 2, '|'=> 2, '€'=> 2,
    ];

    public static function convert_to_carr($text)
    {
        $text = str_replace('`', '\'', $text);
        $text = str_replace('&', '&amp;', $text);

        $carr = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

        return $carr;
    }

    public static function is_gsm($carr)
    {
        if (is_string($carr)) {
            $carr = self::convert_to_carr($carr);
        }

        foreach ($carr as &$c) {
            if(!isset(self::$charset[$c])) {
                return false;
            }
        }

        return true;
    }

    public static function length($carr, $is_gsm = null)
    {
        if (is_string($carr)) {
            $carr = self::convert_to_carr($carr);
        }

        if ($is_gsm === null) {
            $is_gsm = self::is_gsm($carr);
        }

        if (!$is_gsm) {
            $len = count($carr);
        } else {
            $len = 0;
            foreach ($carr as $c) {
                $len += self::$charset[$c];
            }
        }

        return $len;
    }

    public static function split($carr, $is_gsm = null, $text_len = null)
    {
        if (is_string($carr)) {
            $carr = self::convert_to_carr($carr);
        }

        if ($is_gsm === null) {
            $is_gsm = self::is_gsm($carr);
        }

        if ($text_len === null) {
            $text_len = self::length($carr);
        }

        $messages = array();
        $text = implode('', $carr);

        if ($is_gsm) {
            if ($text_len <= 160) {
                $messages[] = $text;
            } else {
                $i = 0;
                $cnt = 0;
                $tlen = $text_len;
                $message = '';
                while ($i < $tlen) {
                    $cl = self::$charset[$carr[$i]];
                    if ($cnt > 153 - $cl) {
                        $messages[] = $message;
                        $message = '';
                        $cnt	 = 0;
                        continue;
                    }
                    $message .= $carr[$i];
                    $cnt += $cl;
                    $i += 1;
                }
                $messages[] = $message;
            }
        } else {
            if ($text_len <= 70) {
                $messages[] = $text;
            } else {
                $parts = floor(($text_len-1)/67+1);
                for ($i=0; $parts > $i; $i++) {
                    $messages[] = mb_substr($text, $i*67, 67, 'UTF-8');
                }
            }
        }

        return $messages;
    }
}

class SMSDoor_lib_BMS
{
    public static $ACCEPT =
        '<?xml version="1.0" encoding="utf-8"?><message><state code="ACCEPTED">ACCEPTED</state></message>';
    public static $REJECT =
        '<?xml version="1.0" encoding="utf-8"?><message><state code="REJECTED">REJECTED</state></message>';

    public static function send(
        $bms_url,
        $credentials,
        $oa,
        $da,
        $parts,
        $rid = NULL,
        $concat_ref = NULL,
        $logger = NULL)
    {
        $reasult = array();
        $xml_arr = array();
        $parts_count = count($parts);

        if (!$concat_ref) {
            $concat_ref = rand(1, 255);
        }

        $_parts = array();

        $text = implode("", $parts);
        $charset = 'default';
        $encoding = SMSDoor_lib_GSM::is_gsm($text);
        if(!$encoding)
            $charset = 'ucs2';

        for ($cp=0; $parts_count > $cp; $cp++) {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><message/>');
            $xml->addChild('oa', $oa);
            $xml->addChild('da', $da);
            $xml->addChild('charset', $charset);
            $xml->addChild('text', $parts[$cp]);

            if ($rid) {
                $xml->addChild('rid', $rid);
            }

            if ($parts_count > 1) {
                $xml->addChild('concatenation', NULL);
                $xml->concatenation->addAttribute('total', $parts_count);
                $xml->concatenation->addAttribute('part', $cp+1);
                $xml->concatenation->addAttribute('id', $concat_ref);
            }

            $xml_arr[] = $xml->asXML();
            $_parts[] = $parts[$cp];
        }

        $result = array();
        foreach($xml_arr as $xml){
            $headers = "Content-type: text/xml\r\nContent-Length: " . strlen($xml) .
                "\r\nAuthorization: Basic " . base64_encode($credentials);
            $resp = self::__send_http_request($bms_url, 'POST', $headers, $xml, $logger);
            $result[] = $resp;
        }

        $success = count(array_filter($result));
        if (!$success) {
            return false;
        }

        return (object) array(
            'result' => (object) $result,
            'parts' => (object) $parts,
        );
    }

    public static function __send_http_request($url, $method, $headers, $body)
    {
        $params = array('http' => array(
            'method' => $method,
            'header' => $headers,
            'content'=> $body,
        ));

        $context = stream_context_create($params);
        $resp = file_get_contents($url, false, $context);

        return $resp;
    }

    public static function parse_response_xml($xml_str)
    {
        $xml = simplexml_load_string($xml_str);

        if (!$xml) {
            return false;
        }

        if (!isset($xml->state)) {
            return false;
        }

        $state_attr = $xml->state->attributes();

        if (!isset($state_attr['date'])) {
            return false;
        }

        if (!isset($state_attr['code'])) {
            return false;
        }

        $res = [
            'state' => ['code' => (string) $state_attr['code'],
                'date' => (string) $state_attr['date'],
                'text' => (string) $xml->state],
            'reference' => (string) $xml->reference,
        ];

        return $res;
    }

    static function ParseXML($raw_input)
    {
        $p = xml_parser_create();
        xml_parse_into_struct($p, $raw_input, $results, $index);
        xml_parser_free($p);

        if (!isset($index['OA'][0]) || !isset($index['DA'][0]) || !isset($index['TEXT'][0])) {
            return false;
        }

        $oa= $results[$index['OA'][0]]['value'];
        $da = $results[$index['DA'][0]]['value'];

        if(!isset($results[$index['TEXT'][0]]['value'])) {
            $text = '';
        } else {
            $text = $results[$index['TEXT'][0]]['value'];
        }

        $rid = isset($index['RID'][0])? $index['RID'][0] : null;

        if ($rid) {
            $rid = $results[$index['RID'][0]]['value'];
            return array(
                'oa' =>  $oa,
                'da' => $da,
                'rid' => $rid,
                'text' => $text,
            );
        }

        return array(
            'oa' =>  $oa,
            'da' => $da,
            'rid' => false,
            'text' => $text,
        );
    }

    static function CreateXML($oa_val, $da_val, $rid_val, $text_val)
    {
        $dom = new DOMDocument("1.0");

        $root = $dom->createElement("message");
        $dom->appendChild($root);

        $oa = $dom->createElement("oa");
        $root->appendChild($oa);
        $oa_text = $dom->createTextNode($oa_val);
        $oa->appendChild($oa_text);

        $da = $dom->createElement("da");
        $root->appendChild($da);
        $da_text = $dom->createTextNode($da_val);
        $da->appendChild($da_text);

        if ($rid_val) {
            $rid = $dom->createElement("rid");
            $root->appendChild($rid);
            $rid_text = $dom->createTextNode($rid_val);
            $rid->appendChild($rid_text);
        }

        $text = $dom->createElement("text");
        $root->appendChild($text);
        $text_text = $dom->createTextNode($text_val);
        $text->appendChild($text_text);

        return $dom->saveXML();
    }
}
