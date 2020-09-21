<?php
/**
 * Project: bitrix2
 * User: andrey.rybalko
 * Date: 19.02.14
 * Time: 13:47
 */

/**
 * Add-File: #CARD1#=>card1.jpg;#CARD2#=>card2.jpg;
 *
 * @param $to
 * @param $subject
 * @param $message
 * @param $additional_headers
 * @param $additional_parameters
 * @return bool
 */
function custom_mail($to, $subject, $message, $additional_headers, $additional_parameters) {
    try {
        $messagePostfix = '';
        $un = strtoupper(uniqid(time()));
        $eol = CAllEvent::GetMailEOL();

        // Ищем инструкции по подключеннию файлов и выполняем их
        // ADD-FILE: #CARD1#=>card1.jpg;#CARD2#=>card2.jpg;
        $additional_headers = preg_replace_callback('#^\s*ADD-FILE:\s*([^\r\n]+)(?:[\r\n]+|$)#sim', function ($m) use (&$messagePostfix, $un, $eol) {
            $strAddFile = trim(mb_decode_mimeheader($m[0]));
            if (empty($strAddFile)) {
                return '';
            }
            // Разбираемся какие файлы подключать
            // #CARD1#=>card1.jpg;#CARD2#=>card2.jpg;
            $arrFileAa = preg_split('#\s*;\s*#', $strAddFile, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($arrFileAa as $strFileAs) {
                // #CARD1#=>card2.jpg
                $arrFileAs = preg_split('#\s*=>\s*#', $strFileAs, -1, PREG_SPLIT_NO_EMPTY);
                // Открываем файл
                $f = fopen($arrFileAs[0], 'rb');
                if ($f === false) {
                    $fileData = 'Сan not open file ' . $arrFileAs[0];
                    $fileName = $arrFileAs[1] . '.txt';
                } else {
                    $fileData = fread($f, filesize($arrFileAs[0]));
                    $fileName = $arrFileAs[1];
                    fclose($f);
                }
                $messagePostfix .= '--------' . $un . $eol;
                $messagePostfix .= 'Content-Type: application/octet-stream;name="' . $fileName . '"' . $eol;
                $messagePostfix .= 'Content-Disposition:attachment;filename="' . $fileName . '"' . $eol;
                $messagePostfix .= 'Content-Transfer-Encoding: base64' . $eol . $eol;
                $messagePostfix .= chunk_split(base64_encode($fileData)) . $eol . $eol;
            }
            return '';
        }, $additional_headers);

        // Нужно ли добавлять файлы
        if ($messagePostfix) {
            $messagePrefix = '--------' . $un . $eol;
            $additional_headers = preg_replace_callback('#^\s*(Content-Type:[^\r\n]+)(?:[\r\n]+|$)#sim', function ($m) use (&$messagePrefix, $eol) {
                $messagePrefix .= $m[1] . $eol;
                return '';
            }, $additional_headers);
            $additional_headers = preg_replace_callback('#^\s*(Content-Transfer-Encoding:[^\r\n]+)(?:[\r\n]+|$)#sim', function ($m) use (&$messagePrefix, $eol) {
                $messagePrefix .= $m[1] . $eol;
                return '';
            }, $additional_headers);
            $additional_headers = trim($additional_headers) . $eol;
            $additional_headers .= 'Mime-Version: 1.0' . $eol;
            $additional_headers .= 'Content-Type:multipart/mixed;boundary="------' . $un . '"' . $eol . $eol;

            $message = $messagePrefix . $eol . $eol . $message . $eol . $eol . $messagePostfix;
        }

        // file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'mail.txt', $additional_headers . $message);

        if ($additional_parameters != '') {
            return @mail($to, $subject, $message, $additional_headers, $additional_parameters);
        }
        return @mail($to, $subject, $message, $additional_headers);
    } catch (Exception $e) {
        return false;
    }
}