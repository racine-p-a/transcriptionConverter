<?php
/**
 * @author  Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 26/08/18 22:30
 *
 * Contexte : TODO
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleAbstrait.php';

class Sync extends ModeleAbstrait
{
    /**
     * @var float
     */
    private $chronoSync = 0.0;

    /**
     * Sync constructor.
     * @param float $chronoSync
     */
    public function __construct(float $chronoSync=0.0)
    {
        $this->chronoSync = $chronoSync;
    }


    /**
     * @return float
     */
    public function getChronoSync(): float
    {
        return $this->chronoSync;
    }

    /**
     * @param float $chronoSync
     */
    public function setChronoSync(float $chronoSync): void
    {
        $this->chronoSync = $chronoSync;
    }


}