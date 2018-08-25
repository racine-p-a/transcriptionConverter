<?php
/**
 * @author  Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 15/08/18 17:09
 *
 * Contexte : TODO
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/interfaces/interfaces_IGenerale.class.php';

interface IModele extends IGenerale
{

}