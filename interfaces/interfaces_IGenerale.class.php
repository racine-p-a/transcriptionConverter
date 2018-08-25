<?php
/**
 * @author  Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @copyright Pierre-Alexandre RACINE <pierrealexandreracine at{@} gmail dot[.] com>
 * @license http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html LICENCE DE LOGICIEL LIBRE CeCILL-C
 * @date 15/08/18 16:47
 *
 * Contexte : TODO
 *
 * @link https://github.com/racine-p-a/transcriptionConverter
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


interface IGenerale
{
    const EXTENSIONS_TRANSCRIPTION_AUTORISEES = array(
        'trs',
        'txt',
    );

    const EXTENSIONS_ARCHIVES_AUTORISEES = array(
        'zip',
        );

    const TAILLE_MAXIMALE_UPLOAD = 50*1024*1024; // 50Mb

    /*
     *
     * private $extensionsTranscriptionsAutorisees = array(
        'eaf',
        'trico',
        'trs',
        'txt',
        'textgrid',
        'ca',
        'cha',
        'rtf',
        'doc',
        'odt'
    );

    private $extensionsArchivesAutorisees = array('7z', 'rar', 'tar', 'zip', 'gz');

     *
     *
     *
     *
     */
}