<?php
/**
 * @author  Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
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

    protected function transcoderEnUTF8($texte='', $encodageActuel='iso-8859-1')
    {
        switch (strtolower($encodageActuel))
        {
            case 'iso-8859-1':
                return utf8_encode($texte);
            case 'utf-8':
                return $texte;
            default:
                iconv(mb_detect_encoding($texte, mb_detect_order(), true), "UTF-8", $texte);
                array_push($this->alertes, array('encodageInconnu'=>'Encodage reçu inconnu : ' . $encodageActuel));
                break;

        }
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