<?php
namespace Helmich\TypoScriptLint\Linter\Report;

use Helmich\TypoScriptParser\Parser\ParseError;
use Helmich\TypoScriptParser\Tokenizer\TokenizerException;


/**
 * A single checkstyle warning.
 *
 * @author     Martin Helmich <typo3@martin-helmich.de>
 * @license    MIT
 * @package    Helmich\TypoScriptLint
 * @subpackage Linter\Report
 */
class Warning
{



    const SEVERITY_INFO = "info";
    const SEVERITY_WARNING = "warning";
    const SEVERITY_ERROR = "error";


    /** @var int */
    private $line;


    /** @var int */
    private $column;


    /** @var string */
    private $message;


    /** @var string */
    private $severity;


    /** @var string */
    private $source;



    /**
     * Creates a new warning from a parse error.
     *
     * @param \Helmich\TypoScriptParser\Parser\ParseError $parseError The parse error to convert into a warning.
     * @return \Helmich\TypoScriptLint\Linter\Report\Warning The converted warning.
     */
    public static function createFromParseError(ParseError $parseError)
    {
        return new self(
            $parseError->getSourceLine(),
            NULL,
            "Parse error: " . $parseError->getMessage(),
            self::SEVERITY_ERROR,
            get_class($parseError)
        );
    }



    /**
     * Creates a new warning from a tokenizer error.
     *
     * @param \Helmich\TypoScriptParser\Tokenizer\TokenizerException $tokenizerException The tokenizer error to convert into a warning.
     * @return \Helmich\TypoScriptLint\Linter\Report\Warning The converted warning.
     */
    public static function createFromTokenizerError(TokenizerException $tokenizerException)
    {
        return new self(
            $tokenizerException->getSourceLine(),
            NULL,
            "Tokenization error: " . $tokenizerException->getMessage(),
            self::SEVERITY_ERROR,
            get_class($tokenizerException)
        );
    }



    /**
     * Constructs a new warning.
     *
     * @param int    $line     The original source line the warning belongs to.
     * @param int    $column   The source column.
     * @param string $message  The warning message.
     * @param string $severity The warning severity (see Warning::SEVERITY_* constants).
     * @param string $source   An arbitrary identifier for the generator of this warning.
     */
    public function __construct($line, $column, $message, $severity, $source)
    {
        $this->line     = $line;
        $this->column   = $column;
        $this->message  = $message;
        $this->severity = $severity;
        $this->source   = $source;
    }



    /**
     * Gets the original source line.
     *
     * @return int The original source line.
     */
    public function getLine()
    {
        return $this->line;
    }



    /**
     * Gets the original source column, if applicable (else NULL).
     *
     * @return int The original source column, or NULL.
     */
    public function getColumn()
    {
        return $this->column;
    }



    /**
     * Gets the warning message.
     *
     * @return string The warning message.
     */
    public function getMessage()
    {
        return $this->message;
    }



    /**
     * Gets the warning severity.
     *
     * @return string The warning severity (should be one of the Warning::SEVERITY_* constants).
     */
    public function getSeverity()
    {
        return $this->severity;
    }



    /**
     * Gets the warning source identifier.
     *
     * @return string The warning source identifier.
     */
    public function getSource()
    {
        return $this->source;
    }



}