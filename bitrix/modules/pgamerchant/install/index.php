<?php
/**
 * Обязательный файл с описанием модуля, содержащий инсталлятор/деинсталлятор модуля.
 * @author Alex Rubera (www.rubera.ru)
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/pgamerchant/prolog.php'); // пролог модуля

/**
 * Класс для инсталляции и деинсталляции модуля PGAMerchantPlugin
 */
Class PGAMerchant extends CModule
{
    //var $MODULE_CSS;
    //var $MODULE_GROUP_RIGHTS = "Y";

    // Обязательные свойства.
    /**
     * Имя партнера - автора модуля.
     * @var string
     */
    var $PARTNER_NAME;
    /**
     * URL партнера - автора модуля.
     * @var string
     */
    var $PARTNER_URI;
    /**
     * Версия модуля.
     * @var string
     */
    var $MODULE_VERSION;
    /**
     * Дата и время создания модуля.
     * @var string
     */
    var $MODULE_VERSION_DATE;
    /**
     * Имя модуля.
     * @var string
     */
    var $MODULE_NAME;
    /**
     * Описание модуля.
     * @var string
     */
    var $MODULE_DESCRIPTION;
    /**
     * Массив с путями для инсталляции модуля.
     * @var array
     */
    var $aPaths;
    /**
     * ID модуля.
     * @var string
     */
    var $MODULE_ID = 'pgamerchant';

    function PGAMerchant()
    {
        $this->PARTNER_NAME = 'intervale';
        $this->PARTNER_URI = 'http://www.intervale.ru';

        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = GetMessage("INTERVALE.PGA_INSTALL_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("INTERVALE.PGA_INSTALL_DESCRIPTION");
    }

    function DoInstall()
    {
        global $APPLICATION, $DB;

        $GLOBALS['errors'] = false;
        $this->errors = false;

        // Создаёт таблицу в БД.
        $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/pgamerchant/install/db/mysql/install.sql');

        // Копирует нужные файлы в нужные места.
        if (!CModule::IncludeModule($this->MODULE_ID)) {
            $this->InstallFiles();
            RegisterModule($this->MODULE_ID);
        }

        $GLOBALS['errors'] = $this->errors;

        // Показывает страницу с результатом установки модуля.
        $APPLICATION->IncludeAdminFile(GetMessage('INTERVALE.PGA_INSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/pgamerchant/install/step_ok.php');
    }

    function DoUninstall()
    {
        global $APPLICATION, $uninstall, $DB;

        if (isset($uninstall) && $uninstall == 'Y' && CModule::IncludeModule($this->MODULE_ID)) {
            $this->UnInstallFiles();

            UnRegisterModule($this->MODULE_ID);

            // Удаляет таблицу из БД.
            $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/pgamerchant/install/db/mysql/uninstall.sql');
        } else {
            // Показывает страницу с настройками удаления модуля.
            $APPLICATION->IncludeAdminFile(GetMessage('INTERVALE.PGA_INSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/pgamerchant/install/unstep_ok.php');
        }
    }

    function InstallFiles($site_dir="/", $default_site_id=false)
    {
        $path_from = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/pgamerchant/install/www';
        $path_to = $_SERVER['DOCUMENT_ROOT'];

        // bitrix/modules/sale/payment/pgamerchant/payment.php

        if (!CopyDirFiles($path_from . $this->aPaths['admin'], $path_to . $this->aPaths['admin'], true, true)) {
            $this->errors = array(GetMessage('INTERVALE.PGA_INSTALL_ERROR'));
        }

        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFilesEx('/bitrix/modules/sale/payment/pgamerchant/');
        return true;
    }
}