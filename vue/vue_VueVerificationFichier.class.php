<?php
/**
 * @author  Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 02/08/18 20:35
 *
 * Contexte : TODO
 *
 * @link TODO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/vue/vue_VueAbstraite.class.php';

class VueVerificationFichier extends VueAbstraite
{


    /**
     * VueVerificationFichier constructor.
     */
    public function __construct()
    {
        $this->corpsPage .= '';
    }
}