<?php
/**
 * @author  Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 22/08/18 15:58
 *
 * Contexte : TODO
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


abstract class ModeleAbstrait
{
    protected $erreurs = array();

    protected $alertes = array();



    /**
     * @param string $nomFichier
     * @return string
     */
    protected function nettoyerNomFichier($nomFichier='')
    {
        $caracteresInterdits = array(
            '(', ')', '[', ']', '{', '}',
            ' ', '\'', '?', '!',
            '&', '~', '#', '"', '|', '`', '\\', '^', '@',
            '+', '=', '*', '/',
            '¨', '^', '$', '*', '%',
            ':', ';', '/', ',',
            '<', '>',
        );
        return strtolower(str_replace($caracteresInterdits, '_', $nomFichier));
    }


    /**
     * @return array
     */
    public function getAlertes(): array
    {
        return $this->alertes;
    }

    /**
     * @return array
     */
    public function getErreurs(): array
    {
        return $this->erreurs;
    }

}