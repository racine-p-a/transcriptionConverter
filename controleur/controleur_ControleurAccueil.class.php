<?php
/**
 * @author  Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 *
 * Contexte : Contrôleur qui gère les vues que verra le visiteur durant son utilisation web du service.
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/**
 * Class ControleurAccueil Cette classe gère l'affichage des différentes vues que pourra utiliser l'utilsateur web
 * durant son utilisation de l'application.
 */
class ControleurAccueil
{
    private $erreurs=array();

    private $alertes =array();

    /**
     * ControleurAccueil constructor. En fonction du contexte qui appellera le constructeur, on dirigera vers une vue
     * spécifique.
     * @param string $contexte Le contexte de l'application web. Peut prendre les valeurs :
     *                         - '' (deviendra l'accueil par défaut)
     *                         - 'envoiFichier' signifie que l'on a reçu un fichier de l'accueil.
     */
    public function __construct($contexte='')
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleVerification.class.php';
        $donnees = new Verification();
        // TODO Récupérer erreurs->interruption du service en affichant un tableau listant problèmes->solutions


        switch ($contexte)
        {
            case 'envoiFichier':
                $transcriptions = array();
                // IMPORT
                if( isset($_POST['formatSortie']) )
                {
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleUpload.class.php';
                    $donnees = new ModeleUpload($_POST['formatSortie']);
                    $transcriptions = $donnees->getListeTranscriptions();
                }

                // EXPORT
                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/tour/modele_mode_tour_ExportTranscriptionTRS.class.php';
                foreach ($transcriptions as $transcription)
                {

                }


                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/vue/vue_VueVerificationFichier.class.php';
                $vue = new VueVerificationFichier();
                break;
            case 'accueil':
            case '':
            default:
                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/vue/vue_VueAccueil.class.php';
                $vue = new VueAccueil();
                break;
        }
        echo $vue->getVue();
    }
}