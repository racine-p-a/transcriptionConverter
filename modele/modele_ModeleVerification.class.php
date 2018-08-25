<?php
/**
 * @author  Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 17/08/18 14:37
 *
 * Contexte : TODO
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_IModele.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleAbstrait.php';


class Verification extends ModeleAbstrait implements IModele
{
    /**
     * Verification constructor.
     * La classe regarde quels sont les différents points nécessaires à l'utilisation de l'application et prévient si
     * ils ne sont pas installés/accessibles.
     * - zip (si présent dans la constante des formats d'archives autorisés)
     * - 7zip (si présent dans la constante des formats d'archives autorisés) TODO
     * - rar (si présent dans la constante des formats d'archives autorisés) TODO
     * - droits dans les dossiers d'upload TODO
     * - vérifier la taille autorisée dans les uploads du php.ini TODO
     * - vérifier installation et activation de XMLREADER TODO
     */
    public function __construct()
    {
        foreach ($this::EXTENSIONS_ARCHIVES_AUTORISEES as $formatArchiveAutorise)
        {
            switch ($formatArchiveAutorise)
            {
                case 'zip':
                    if( !$this->testZip() )
                    {
                        array_push($this->erreurs, 'absenceZip');
                    }
                    break;
                case '7z':
                    if( !$this->test7Zip() )
                    {
                        array_push($this->erreurs, 'absence7Zip');
                    }
                    break;
            }
        }
    }

    private function testZip()
    {
        if(extension_loaded('zip'))
        {
            return true;
        }
        return false;
    }

    private function getLinuxDistribution()
    {
        return trim(shell_exec('lsb_release -is'));
    }


    private function test7Zip()
    {
        /*
         * Résultats possibles de PHP_OS :
         * -CYGWIN_NT-5.1
         * -Darwin
         * -FreeBSD
         * -HP-UX
         * -IRIX64
         * -Linux
         * -NetBSD
         * -OpenBSD
         * -SunOS
         * -Unix
         * -WIN32
         * -WINNT
         * -Windows
         */
        switch (PHP_OS)
        {
            case 'Linux':
                switch ($this->getLinuxDistribution())
                {
                    case 'Ubuntu':
                    case 'ubuntu':
                        $resultat = shell_exec('dpkg -s p7zip');
                        if($resultat == null)
                        {
                            return false;
                        }
                        return true;
                        break;
                    default:
                        array_push($this->alertes, 'linuxInconnu');
                }
                break;
            case 'Windows':
                break;
            case 'Unix':
                break;
            default:
                array_push($this->erreurs, 'OSInconnu');
                break;
        }
    }
}