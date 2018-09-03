<?php
/**
 * @author  Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 03/08/18 22:38
 *
 * Contexte : La classe gère les fichiers uploadés par l'application web.
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_IModele.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleAbstrait.php';

class ModeleUpload extends ModeleAbstrait implements IModele
{
    private $listeTranscriptions = array();

    private $souhaitFormatSortie='';

    /**
     * ModeleUpload constructor.
     * Étapes :
     * 1° On range les fichiers reçus.
     * 2° On vérifie les fichiers reçus en upload.
     * 3° Création d'objet « Fichier » correspondant à chaque fichier uploadé.
     * 4° On renvoie un tableau d'objets contenant les objets Fichier de tous ceux reçus (dans le cas d'une archive)
     */
    public function __construct($formatSortie='')
    {
        $this->souhaitFormatSortie = $formatSortie;
        if( isset($_FILES['fichiersTranscription']) )
        {
            $this->rangerFichier($_FILES['fichiersTranscription']);
        }
    }

    private function rangerFichier($infosFichier=array())
    {
        if($infosFichier['error'] == 0 && $infosFichier['size'] < $this::TAILLE_MAXIMALE_UPLOAD)
        {
            // UNE TRANSCRIPTION
            if( in_array(pathinfo($infosFichier['name'])['extension'], $this::EXTENSIONS_TRANSCRIPTION_AUTORISEES) )
            {
                move_uploaded_file(
                    $infosFichier['tmp_name'],
                    getcwd() . '/uploads/transcriptions/' . $this->nettoyerNomFichier($infosFichier['name'])
                );
                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleFichierTranscription.class.php';
                array_push(
                    $this->listeTranscriptions,
                    new FichierTranscription(
                        getcwd() . '/uploads/transcriptions/' . $this->nettoyerNomFichier($infosFichier['name']),
                        $this->souhaitFormatSortie
                        )
                    );
            }
            // UNE ARCHIVE
            else if( in_array(pathinfo($infosFichier['name'])['extension'], $this::EXTENSIONS_ARCHIVES_AUTORISEES) )
            {
                /*
                 * En cas d'archive reçue, il faut :
                 * - déplacer cette archive dans un répertoire temporaire
                 * - extraire l'archive
                 */
                $fichiersRecus = $this->extraireFichier(
                    $infosFichier['tmp_name'],
                    pathinfo($infosFichier['name'])['extension'],
                    getcwd() . '/uploads/temp/'
                );
                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleFichierTranscription.class.php';
                foreach ($fichiersRecus as $emplacementFichier)
                {
                    array_push($this->listeTranscriptions,
                        new FichierTranscription( $emplacementFichier, $this->souhaitFormatSortie)
                    );
                }
            }
        }
    }




    private function extraireFichier($emplacementFichier='', $typeArchive='', $destination='/dev/null')
    {
        $listeFichiersAExtraire = array();
        $listeFichiersExtraits = array();

        switch ($typeArchive)
        {
            case 'zip':
                $zip = new ZipArchive();
                if ($zip->open($emplacementFichier) === TRUE)
                {
                    // On pense à récupérer la liste des fichiers à extraire.
                    for ($i = 0; $i < $zip->numFiles; $i++)
                    {
                        array_push($listeFichiersAExtraire, $zip->getNameIndex($i) );

                    }
                    $zip->extractTo($destination);
                    foreach ($listeFichiersAExtraire as $nomFichier)
                    {
                        if( in_array(pathinfo($nomFichier)['extension'], $this::EXTENSIONS_TRANSCRIPTION_AUTORISEES) )
                        {
                            $ancienNom = $destination . $nomFichier;
                            $nouveauNom = pathinfo($destination)['dirname'] . '/transcriptions/' . $this->nettoyerNomFichier($nomFichier);
                            rename($ancienNom, $nouveauNom);
                            array_push($listeFichiersExtraits, $nouveauNom);
                        }
                        else
                        {
                            array_push($this->alertes, 'Le fichier nommé : ' . $nomFichier . ' n\'a pas une extension autorisée.');
                            unlink($destination . $nomFichier);
                        }
                    }
                }
                else
                {
                    array_push($this->erreurs, 'extractionZipImpossible');
                }
                $zip->close();
                break;
            case '7z':
            case '7zip':

            /*
            $cheminCompletVersArchive = getcwd() . '/uploads/' . $this->nomFichierAvecExtension;

            $nomDossierDestination = $typeArchiveRecue . '_' . $this->nomFichierSansExtension;
            $cheminCompletVersDestination = getcwd() . '/uploads/extraits/' . $nomDossierDestination . '/';

            // Lançons l'extraction :
            $commande = '7z e ' . $cheminCompletVersArchive . ' -o' . $cheminCompletVersDestination . ' 2>' . getcwd() . '/uploads/7zErreurs'; // 2> grep-errors.txt
            //echo $commande;
            exec($commande);

            // Il ne reste plus qu'à supprimer l'archive.
            exec('rm ' . $cheminCompletVersArchive);

            // Récupérons les fichiers ainsi créés.
            return scandir($cheminCompletVersDestination);
            */
                break;
        }
        return $listeFichiersExtraits;
    }

    /**
     * @return array
     */
    public function getListeTranscriptions(): array
    {
        return $this->listeTranscriptions;
    }




}