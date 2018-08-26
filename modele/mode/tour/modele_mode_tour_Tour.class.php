<?php
/**
 * @author  Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 26/08/18 21:47
 *
 * Contexte : TODO
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleAbstrait.php';

class Tour extends ModeleAbstrait
{
    private $chronoDebut=0.0;

    private $chronoFin=0.0;

    private $deroulementTour = array();

    private $speaker='';



    /**
     * @param String $nouveauTexte
     */
    public function ajoutTexte(String $nouveauTexte='')
    {
        array_push($this->deroulementTour, $nouveauTexte);
    }

    /**
     * @param Who $nouveauWho
     */
    public function ajoutWho(Who $nouveauWho)
    {
        array_push($this->deroulementTour, $nouveauWho);
    }

    /**
     * @param Background $nouveauBackground
     */
    public function ajoutBackground(Background $nouveauBackground)
    {
        array_push($this->deroulementTour, $nouveauBackground);
    }

    /**
     * @param Comment $nouveauComment
     */
    public function ajoutComment(Comment $nouveauComment)
    {
        array_push($this->deroulementTour, $nouveauComment);
    }

    /**
     * @param Event $nouvelEvent
     */
    public function ajoutEvent(Event $nouvelEvent)
    {
        array_push($this->deroulementTour, $nouvelEvent);
    }

    /**
     * @param $chronoSync
     */
    public function ajoutSync($chronoSync)
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/mode/tour/modele_mode_tour_Sync.class.php';
        array_push($this->deroulementTour, new Sync($chronoSync));
    }

    /**
     * @return array
     */
    public function getDeroulementTour(): array
    {
        return $this->deroulementTour;
    }

    /**
     * @return float
     */
    public function getChronoDebut(): float
    {
        return $this->chronoDebut;
    }

    /**
     * @param float $chronoDebut
     */
    public function setChronoDebut(float $chronoDebut): void
    {
        $this->chronoDebut = $chronoDebut;
    }

    /**
     * @return float
     */
    public function getChronoFin(): float
    {
        return $this->chronoFin;
    }

    /**
     * @param float $chronoFin
     */
    public function setChronoFin(float $chronoFin): void
    {
        $this->chronoFin = $chronoFin;
    }


    /**
     * @return string
     */
    public function getSpeaker(): string
    {
        return $this->speaker;
    }

    /**
     * @param string $speaker
     */
    public function setSpeaker(string $speaker): void
    {
        $this->speaker = $speaker;
    }
}