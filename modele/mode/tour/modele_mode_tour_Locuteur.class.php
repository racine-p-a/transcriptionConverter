<?php
/**
 * @author  Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <patcha.dev at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 26/08/18 19:32
 *
 * Contexte : TODO
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/transcriptionConverter/modele/modele_ModeleAbstrait.php';

class Locuteur extends ModeleAbstrait
{
    /**
     * @var int
     * Correspond à trs.
     */
    private $id=0;

    /**
     * @var string
     * Correspond à trs.
     */
    private $nomLocuteur='';

    /**
     * @var string
     * Correspond à trs.
     */
    private $check = 'no';

    /**
     * @var string
     * Correspond à trs.
     */
    private $dialect = 'native';

    /**
     * @var string
     * Correspond à trs.
     */
    private $accent = '';

    /**
     * @var string
     * Correspond à trs.
     */
    private $scope = 'local';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNomLocuteur(): string
    {
        return $this->nomLocuteur;
    }

    /**
     * @param string $nomLocuteur
     */
    public function setNomLocuteur(string $nomLocuteur): void
    {
        $this->nomLocuteur = $nomLocuteur;
    }

    /**
     * @return string
     */
    public function getCheck(): string
    {
        return $this->check;
    }

    /**
     * @param string $check
     */
    public function setCheck(string $check): void
    {
        $this->check = $check;
    }

    /**
     * @return string
     */
    public function getDialect(): string
    {
        return $this->dialect;
    }

    /**
     * @param string $dialect
     */
    public function setDialect(string $dialect): void
    {
        $this->dialect = $dialect;
    }

    /**
     * @return string
     */
    public function getAccent(): string
    {
        return $this->accent;
    }

    /**
     * @param string $accent
     */
    public function setAccent(string $accent): void
    {
        $this->accent = $accent;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope(string $scope): void
    {
        $this->scope = $scope;
    }



}