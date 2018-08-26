<?php
/**
 * @author  Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 15/08/18 17:24
 *
 * Contexte : TODO
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleAbstrait.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_IModele.class.php';

class FichierTranscription extends ModeleAbstrait implements IModele
{
    private $nomFichier = '';

    private $extensionFichier = '';

    private $dossierFichier = '';

    private $formatDestination = '';

    private $listeTours = array();

    private $listeTiers = array();


    /**
     * FichierTranscription constructor.
     */
    public function __construct($emplacementFichier='', $formatDestination='')
    {
        $this->nomFichier        = pathinfo($emplacementFichier)['basename'];
        $this->extensionFichier  = pathinfo($emplacementFichier)['extension'];
        $this->dossierFichier    = pathinfo($emplacementFichier)['dirname'];

        if( in_array($formatDestination, $this::EXTENSIONS_TRANSCRIPTION_AUTORISEES) )
        {
            $this->formatDestination = $formatDestination;
        }
        else
        {
            //TODO RENVOYER ERREUR.
        }

        switch ($this->extensionFichier)
        {
            case 'trs':
                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/tour/modele_mode_tour_ImportTranscriptionTRS.class.php';
                $import = new ImportTranscriptionTRS($this->dossierFichier . '/' . $this->nomFichier);
                break;
            case '':
            default:
                break;

        }
    }
}