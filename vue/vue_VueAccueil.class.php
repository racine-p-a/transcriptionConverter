<?php
/**
 * @author  Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 26/07/18 15:28
 *
 * Contexte : TODO
 *
 * @link TODO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/vue/vue_VueAbstraite.class.php';

class VueAccueil extends VueAbstraite
{

    /**
     * VueAccueil constructor. À l'accueil, la vue présente une simple interface d'envoi de fichiers.
     * TODO : Afficher les fortmats acceptés en entrée (dynamiques en lisant les constantes de l'interface)
     * TODO : Afficher liste des alertes et erreurs avec les solutions possibles à chaque fois (sudo apt-get install php7.0-zip apache2 restart pour absence de zip par ex)
     */
    public function __construct($erreurs='', $alertes='')
    {
        $this->corpsPage .= '
        <script>
            
            function verificationFormatTranscription(e)
            {
                var formatsTranscriptionAutorises = new Array(' . $this->getConcatenationExtensionsAutorisees() . ');
                var file_list = e.target.files;

                for(var i = 0, file; file = file_list[i]; i++)
                {
                    var sFileName = file.name;
                    var sFileExtension = sFileName.split(".")[sFileName.split(".").length - 1].toLowerCase();
                    var iFileSize = file.size;
                    var iConvert = (file.size / 10485760).toFixed(2);

                    if(formatsTranscriptionAutorises.indexOf(sFileExtension) == -1)
                    {
                        alert("Format de transcription non autorisé.");
                        document.getElementById("fichiersTranscription").value = null;
                    }
                }
            }
            
            function verificationTailleFichier()
            {
                
            }
            
        </script>

        <h1>Choisissez le(s) fichier(s) de transcription à convertir :</h1>
        
        <form action="index.php" method="post" enctype="multipart/form-data">
        
            <p>
                Veuillez noter que vous pouvez envoyer une archive (zip ou autre) contenant plusieurs fichiers.
            </p>
            
            <p>
                <label>
                    Fichier :
                    <input type="file" name="fichiersTranscription" id="fichiersTranscription" required />
                </label>
            </p>
            
            <p>
                <label>
                    Dans quel format souhaitez-vous exporter votre fichier ?
                    <select name="formatSortie" required>
                        <option  disabled>-- Veuillez choisir le format --</option>
                        <option selected value="trs">.trs (Transcriber)</option>
                    </select>
                </label>
            </p>
            
            <p>
                <input type="hidden" name="action" value="envoiFichier" required />
                <input type="submit" value="Envoi" />
            </p>
        </form>
        
        <script>
            fichiersTranscription.addEventListener(\'change\', verificationFormatTranscription, false);
            fichiersTranscription.addEventListener(\'change\', verificationTailleFichier, false);
        </script>
        ';
    }


    private function getConcatenationExtensionsAutorisees()
    {
        $concatenation = '';
        foreach ($this::EXTENSIONS_TRANSCRIPTION_AUTORISEES as $formatT)
        {
            $concatenation .= '"' . $formatT . '",';
        }
        foreach ($this::EXTENSIONS_ARCHIVES_AUTORISEES as $formatA)
        {
            $concatenation .= '"' . $formatA . '",';
        }
        return $concatenation;
    }
}