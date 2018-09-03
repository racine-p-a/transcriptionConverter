<?php
/**
 * @author  Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
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
require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/modele_mode_ModeleModeTranscription.class.php';

class ImportTranscriptionTRS extends ModeleAbstrait implements IModele, Transcription
{
    private $emplacementFichier = '';

    private $encodageFichier = 'UTF-8';

    private $informationsGenerales = array();

    private $listeLocuteurs = array();

    private $listeTours = array();


    /**
     * ImportTranscription constructor.
     */
    public function __construct($emplacementFichier='')
    {
        $this->emplacementFichier = $emplacementFichier;
        $this->recupererEncodage();
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
            array_push($this->erreurs, array('mauvaisFormat', 'trs attendu mais ' . pathinfo($emplacementFichier)['extension'] . ' reçu.'));
            return;
        }

        $xml = file_get_contents($this->emplacementFichier);
        $reader = new XMLReader();
        $reader->XML($xml, $this->encodageFichier);

        // Inutile de vérifier la présence de la DTD.
        $reader->setParserProperty(XMLReader::VALIDATE, false);

        require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/tour/modele_mode_tour_Tour.class.php';
        $tourCourant = new Tour();

        while ($reader->read())
        {


            switch ($reader->nodeType)
            {
                case XMLReader::TEXT:
                    $tourCourant->ajoutTexte($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                    break;

                case XMLReader::ELEMENT:
                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                    {
                        // Certaines balises ne déclenchent rien.
                        case 'Speakers':
                        case 'Episode':
                            break;

                        case 'Trans':
                            if($reader->hasAttributes)
                            {
                                while($reader->moveToNextAttribute())
                                {
                                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                                    {
                                        case 'scribe':
                                            $this->informationsGenerales['auteur'] = $this->transcoderEnUTF8($reader->value, $this->encodageFichier);
                                            break;
                                        case 'version':
                                            $this->informationsGenerales['numeroVersion'] = $this->transcoderEnUTF8($reader->value, $this->encodageFichier);
                                            break;
                                        case 'audio_filename':
                                            $this->informationsGenerales['fichierAudioAssocie'] = $this->transcoderEnUTF8($reader->value, $this->encodageFichier);
                                            break;
                                        case 'version_date':
                                            $this->informationsGenerales['dateTranscription'] = $this->transcoderEnUTF8($reader->value, $this->encodageFichier);
                                            break;
                                        default:
                                            array_push(
                                                $this->alertes,
                                                array(
                                                    'attributInconnu',
                                                    'La balise Trans a un attribut inconnu : « ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . ' » (Valeur : « ' . $this->transcoderEnUTF8($reader->value, $this->encodageFichier) . ' ».')
                                            );
                                            break;
                                    }
                                }
                            }
                            break;

                        case 'Speaker':
                            if($reader->hasAttributes)
                            {
                                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/tour/modele_mode_tour_Locuteur.class.php';
                                $nouveauLocuteur = new Locuteur();
                                while($reader->moveToNextAttribute())
                                {
                                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                                    {
                                        case 'id':
                                            $nouveauLocuteur->setId(intval($reader->value));
                                            break;
                                        case 'name':
                                            $nouveauLocuteur->setNomLocuteur($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        case 'check':
                                            $nouveauLocuteur->setCheck($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        case 'dialect':
                                            $nouveauLocuteur->setDialect($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        case 'scope':
                                            $nouveauLocuteur->setScope($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        default:
                                            array_push(
                                                $this->alertes,
                                                array(
                                                    'attributInconnu',
                                                    'La balise Speaker a un attribut inconnu : « ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . ' » (Valeur : « ' . $this->transcoderEnUTF8($reader->value, $this->encodageFichier) . ' ».')
                                            );
                                            break;
                                    }
                                }
                                array_push($this->listeLocuteurs, $nouveauLocuteur);
                            }
                            break;

                        case 'Section':
                            if($reader->hasAttributes)
                            {
                                while($reader->moveToNextAttribute())
                                {
                                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                                    {
                                        case 'type':
                                            $this->informationsGenerales['sectionType'] = $this->transcoderEnUTF8($reader->value, $this->encodageFichier);
                                            break;
                                        case 'startTime':
                                            $this->informationsGenerales['chronoDebutTranscription'] = $this->transcoderEnUTF8($reader->value, $this->encodageFichier);
                                            break;
                                        case 'endTime':
                                            $this->informationsGenerales['chronoFinTranscription'] = $this->transcoderEnUTF8($reader->value, $this->encodageFichier);
                                            break;
                                        default:
                                            array_push(
                                                $this->alertes,
                                                array(
                                                    'attributInconnu',
                                                    'La balise Section a un attribut inconnu : « ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . ' » (Valeur : « ' . $this->transcoderEnUTF8($reader->value, $this->encodageFichier) . ' ».')
                                            );
                                            break;
                                    }
                                }
                            }
                            break;

                        case 'Turn':
                            $tourCourant = new Tour();
                            if($reader->hasAttributes)
                            {
                                while($reader->moveToNextAttribute())
                                {
                                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                                    {
                                        case 'startTime':
                                            $tourCourant->setChronoDebut(floatval($reader->value));
                                            break;
                                        case 'endTime':
                                            $tourCourant->setChronoFin(floatval($reader->value));
                                            break;
                                        case 'speaker':
                                            $tourCourant->setSpeaker($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        default:
                                            array_push(
                                                $this->alertes,
                                                array(
                                                    'attributInconnu',
                                                    'La balise Turn a un attribut inconnu : « ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . ' » (Valeur : « ' . $this->transcoderEnUTF8($reader->value, $this->encodageFichier) . ' ».')
                                            );
                                            break;
                                    }
                                }
                            }
                            break;

                        case 'Sync':
                            if($reader->hasAttributes)
                            {
                                while($reader->moveToNextAttribute())
                                {
                                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                                    {
                                        case 'time':
                                            $tourCourant->ajoutSync(floatval($reader->value));
                                            break;
                                        default:
                                            array_push(
                                                $this->alertes,
                                                array(
                                                    'attributInconnu',
                                                    'La balise Sync a un attribut inconnu : « ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . ' » (Valeur : « ' . $this->transcoderEnUTF8($reader->value, $this->encodageFichier) . ' ».')
                                            );
                                            break;
                                    }
                                }
                            }
                            break;

                        case 'Event':
                            if($reader->hasAttributes)
                            {
                                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/tour/modele_mode_tour_Event.class.php';
                                $nouvelEvent = new Event();
                                while($reader->moveToNextAttribute())
                                {
                                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                                    {
                                        case 'type':
                                            $nouvelEvent->setType($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        case 'desc':
                                            $nouvelEvent->setDesc($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        case 'extent':
                                            $nouvelEvent->setExtent($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        default:
                                            array_push(
                                                $this->alertes,
                                                array(
                                                    'attributInconnu',
                                                    'La balise Event a un attribut inconnu : « ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . ' » (Valeur : « ' . $this->transcoderEnUTF8($reader->value, $this->encodageFichier) . ' ».')
                                            );
                                            break;
                                    }
                                }
                                $tourCourant->ajoutEvent($nouvelEvent);
                            }
                            break;
                        case 'Comment':
                            if($reader->hasAttributes)
                            {
                                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/tour/modele_mode_tour_Comment.class.php';
                                $nouveauComment = new Comment();
                                while($reader->moveToNextAttribute())
                                {
                                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                                    {
                                        case 'desc':
                                            $nouveauComment->setDesc($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        default:
                                            array_push(
                                                $this->alertes,
                                                array(
                                                    'attributInconnu',
                                                    'La balise Comment a un attribut inconnu : « ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . ' » (Valeur : « ' . $this->transcoderEnUTF8($reader->value, $this->encodageFichier) . ' ».')
                                            );
                                            break;
                                    }
                                }
                                $tourCourant->ajoutComment($nouveauComment);
                            }
                            break;

                        case 'Who':
                            if($reader->hasAttributes)
                            {
                                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/tour/modele_mode_tour_Who.class.php';
                                $nouveauWho = new Who();
                                while($reader->moveToNextAttribute())
                                {
                                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                                    {
                                        case 'nb':
                                            $nouveauWho->setNb(intval($reader->value));
                                            break;
                                        default:
                                            array_push(
                                                $this->alertes,
                                                array(
                                                    'attributInconnu',
                                                    'La balise Who a un attribut inconnu : « ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . ' » (Valeur : « ' . $this->transcoderEnUTF8($reader->value, $this->encodageFichier) . ' ».')
                                            );
                                            break;
                                    }
                                }
                                $tourCourant->ajoutWho($nouveauWho);
                            }
                            break;
                        case 'Background':
                            if($reader->hasAttributes)
                            {
                                require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/tour/modele_mode_tour_Background.class.php';
                                $nouveauBackground = new Background();
                                while($reader->moveToNextAttribute())
                                {
                                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                                    {
                                        case 'type':
                                            $nouveauBackground->setType($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        case 'time':
                                            $nouveauBackground->setTime(floatval($reader->value));
                                            break;
                                        case 'level':
                                            $nouveauBackground->setLevel($this->transcoderEnUTF8($reader->value, $this->encodageFichier));
                                            break;
                                        default:
                                            array_push(
                                                $this->alertes,
                                                array(
                                                    'attributInconnu',
                                                    'La balise Event a un attribut inconnu : « ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . ' » (Valeur : « ' . $this->transcoderEnUTF8($reader->value, $this->encodageFichier) . ' ».')
                                            );
                                            break;
                                    }
                                }
                                $tourCourant->ajoutBackground($nouveauBackground);
                            }
                            break;



                        default:
                            echo '<p>ouverture de balise inconnue trouvée : ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier) . '</p>';
                            if($reader->hasAttributes)
                            {
                                while($reader->moveToNextAttribute()) {
                                    echo $this->transcoderEnUTF8($reader->name, $this->encodageFichier), ' = ', $this->transcoderEnUTF8($reader->value, $this->encodageFichier), ', ';
                                }
                            }
                            break;
                    }
                    break;

                case XMLReader::END_ELEMENT:
                    switch ($this->transcoderEnUTF8($reader->name, $this->encodageFichier))
                    {
                        case 'Speakers':
                        case 'Section':
                        case 'Episode':
                        case 'Trans':
                            break;
                        case 'Turn':
                            array_push($this->listeTours, $tourCourant);
                            break;
                        default:
                            array_push($this->alertes, array('baliseSortanteInconnue', 'Balise fermante inconnue : ' . $this->transcoderEnUTF8($reader->name, $this->encodageFichier)));
                            echo '<p>fermeture de balise inconnue trouvée : ' . $reader->name . '</p>';
                            break;
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
            array_push($this->erreurs,
                array(
                    'mauvaisFichierReçu',
                    'Un format trs était attendu. ' . pathinfo($emplacementFichier)['extension'] . ' a été reçu'
                )
            );
            return;
        }
        /*
         * Arrivé ici, on peut tenter une ouverture de fichier pour récupérer la première ligne du fichier où se trouve
         * inscrit l'encodage.
         */

        if (file_exists($emplacementFichier))
        {
            $handle = @fopen($emplacementFichier, "r");
            if ($handle)
            {
                while (($buffer = fgets($handle, 4096)) !== false)
                {
                    if( trim($buffer)!='' ) // Une première ligne non vide, elle doit donc contenir.
                    {
                        $morceaux = explode("encoding", trim($buffer));
                        if(count($morceaux)>1)
                        {
                            $ligne = $morceaux[1];
                            // Et se trouve obligatoirement entre guillemets.
                            $morceaux = explode("\"", $ligne);
                            if(count($morceaux)>1)
                            {
                                $this->encodageFichier = $morceaux[1];
                            }
                        }
                        else
                        {
                            array_push($this->erreurs, array('encodageFichierIntrouvable', $emplacementFichier));
                        }
                        return;
                    }
                }
                if (!feof($handle))
                {
                    array_push($this->erreurs, array('ouvertureFichierImpossible', $emplacementFichier));
                    return;
                }
                fclose($handle);
            }
        }
        else
        {
            array_push($this->erreurs, array('fichierIntrouvable', $emplacementFichier) );
            return;
        }
    }

    /**
     * @return array
     */
    public function getListeTours(): array
    {
        return $this->listeTours;
    }

    /**
     * @return array
     */
    public function getListeTiers(): array
    {
        return array();
    }




}