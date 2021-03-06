<?php

namespace GodsDev\MyCMS;

/**
 * Extension of a MyCMS object with translations.
 * It holds all variables needed for the used project.
 * Among others, it translates multilingual texts.
 * 
 * For a new project it is expected to make a extended class and place 
 * additional attributes needed for running, then use that class.
 *
 * required constants: DEFAULT_LANGUAGE
 */
class MyCMS extends MyCMSMonoLingual
{

    use \Nette\SmartObject;

    /**
     * Selected locale strings
     *
     * @var array
     */
    public $TRANSLATION;

    /**
     * Available languages
     *
     * @var array
     */
    public $TRANSLATIONS;

    /**
     * Constructor
     *
     * @param array $myCmsConf
     */
    public function __construct(array $myCmsConf = [])
    {
        parent::__construct($myCmsConf);
    }

    /**
     *
     * @param array $getArray $_GET or its equivalent
     * @param array $sessionArray $_SESSION or its equivalent
     * @param bool $makeInclude for testing may be set to false as mycms itself does not contain the language-XX.inc.php files
     * @return string to be used as $_SESSION['language']
     *
     * constant DEFAULT_LANGUAGE expected
     */
    public function getSessionLanguage(array $getArray, array $sessionArray, $makeInclude = true)
    {
        $resultLanguage = (isset($getArray['language']) && isset($this->TRANSLATIONS[$getArray['language']])) ?
                $getArray['language'] :
                ((isset($sessionArray['language']) && isset($this->TRANSLATIONS[$sessionArray['language']])) ? $sessionArray['language'] : DEFAULT_LANGUAGE);
        if ($makeInclude) {
            $languageFile = DIR_TEMPLATE . '/../language-' . $resultLanguage . '.inc.php';
            if (file_exists($languageFile)) {
                include_once $languageFile; //MUST contain $translation = [...];
                if (isset($translation) && is_array($translation)) {
                    $this->TRANSLATION = $translation;
                } else {
                    $this->logger->error("Missing expected translation {$languageFile}");
                }
            } else {
                $this->logger->error("Missing expected language file {$languageFile}");
            }
        }
        return $resultLanguage;
    }

    /**
     * Translate defined string to the language stored in $_SESSION['language'].
     * Returns original text if translation not found.
     *
     * @param string $id text to translate
     * @param mixed $options case transposition - either null or one of MB_CASE_UPPER, MB_CASE_LOWER, MB_CASE_TITLE or L_UCFIRST
     * @return string
     */
    public function translate($id, $options = null)
    {
        if (!isset($this->TRANSLATION[$id]) && isset($_SESSION['test-translations']) && $_SESSION['language'] != DEFAULT_LANGUAGE) {
            $this->logger->warning('Translation does not exist - ' . $id);
        }
        $result = isset($this->TRANSLATION[$id]) ? $this->TRANSLATION[$id] : $id;
        if ($options === L_UCFIRST) {
            $result = mb_strtoupper(mb_substr($result, 0, 1)) . mb_substr($result, 1);
        } elseif (is_int($options) && ($options == MB_CASE_UPPER || $options == MB_CASE_LOWER || $options == MB_CASE_TITLE)) {
            $result = mb_convert_case($result, $options);
        }
        return $result;
    }

}
