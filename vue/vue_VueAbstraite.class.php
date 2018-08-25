<?php
/**
 * @author  Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 26/07/18 15:57
 *
 * Contexte : TODO
 *
 * @link TODO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/vue/vue_IVue.class.php';
class VueAbstraite implements IVue
{
    protected $enTete = '<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="TranscriptionConverter, application de conversion de transcriptions">
        <meta name="keywords" content="transcriptionConverter, laboratoire ICAR, CNRS, conversion en trs, conversion en trico, conversion en cha, conversion en transcription, conversion en ca, conversion en eaf, conversion en textgrid, conversion en PRAAT, conversion en txt, conversion en rtf, conversion en odt, conversion en doc,">
        <meta name="author" content="RACINE Pierre-Alexandre">
        
        <title>TranscriptionConverter</title>
    </head>
    
    <body>';

    protected $corpsPage = '';

    protected $piedDePage = '
    </body>
</html>';

    public function getVue()
    {
        return $this->enTete . $this->corpsPage . $this->piedDePage;
    }
}