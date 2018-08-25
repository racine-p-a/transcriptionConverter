<?php
/**
 * @author  Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 *
 * Contrôleur principal de l'application
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

// TODO Foutre des index.php partout.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if( isset( $_POST['action'] ) )
{
    switch ($_POST['action'])
    {
        case 'envoiFichier':
        default:
            require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/controleur/controleur_ControleurAccueil.class.php';
            $controleur = new ControleurAccueil($_POST['action']);
            break;
    }
}

else
{
    require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/controleur/controleur_ControleurAccueil.class.php';
    $controleur = new ControleurAccueil();
}


