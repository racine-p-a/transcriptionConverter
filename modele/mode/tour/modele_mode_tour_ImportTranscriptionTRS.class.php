<?php
/**
 * @author  Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 22/08/18 18:26
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
require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/modele_mode_Tanscription.class.php';

class ImportTranscriptionTRS extends ModeleAbstrait implements IModele, Transcription
{
    private $emplacementFichier = '';

    private $encodageFichier = 'UTF-8';


    /**
     * ImportTranscription constructor.
     */
    public function __construct($emplacementFichier='')
    {
        $this->emplacementFichier = $emplacementFichier;
        $this->importer();
    }

    public function importer($emplacementFichier='')
    {
        if($emplacementFichier=='')
        {
            $emplacementFichier = $this->emplacementFichier;
        }
        else if( strtolower(pathinfo($emplacementFichier)['extension']) != 'trs' )
        {
            return 'Erreur : Format requis : trs';
        }

        $xml = file_get_contents($this->emplacementFichier);
        $reader = new XMLReader();
        $reader->XML($xml);

        // On s'en fout si la dtd est absente.
        $reader->setParserProperty(XMLReader::VALIDATE, false);

        while ($reader->read())
        {
            switch ($reader->nodeType)
            {
                case XMLReader::TEXT:
                    echo 'Texte trouvé : ' . $reader->value . '<br>';
                    //$nouveauTour->ajouterTexte($reader->value);
                    break;

                case XMLReader::ELEMENT:
                    switch ($reader->name)
                    {
                        case 'Speaker';
                            break;

                        default:
                            echo 'balise trouvée : ' . $reader->name . '<br>';
                            break;
                    }
                    break;

                case XMLReader::END_ELEMENT:
                    if($reader->name == 'Speakers')
                    {
                        echo 'tous les locuteurs ont été trouvés.<br>';
                    }
                    break;

                default:
                    break;
            }
        }
    }

    public function recupererEncodage($emplacementFichier= '')
    {
        if($emplacementFichier=='')
        {
            $emplacementFichier = $this->emplacementFichier;
        }
        else if( strtolower(pathinfo($emplacementFichier)['extension']) != 'trs' )
        {
            return 'Erreur : Format requis : trs';
        }
        /*
         * Arrivé ici, on peut tenter une ouverture de fichier pour récupérer la première ligne du fichier où se trouve
         * inscrit l'encodage.
         */
    }
}