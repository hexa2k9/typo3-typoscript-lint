<?php
namespace Helmich\TypoScriptLint\Linter\Sniff\Visitor;


use Helmich\TypoScriptLint\Linter\Report\Warning;
use Helmich\TypoScriptParser\Parser\AST\ConditionalStatement;
use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Helmich\TypoScriptParser\Parser\Traverser\Visitor;


class DuplicateAssignmentVisitor implements Visitor
{


    /** @var \Helmich\TypoScriptParser\Parser\AST\Operator\Assignment[] */
    private $assignments = [];


    /** @var \Helmich\TypoScriptLint\Linter\Report\Warning[] */
    private $warnings = [];


    private $inCondition = FALSE;



    /**
     * @return \Helmich\TypoScriptLint\Linter\Report\Warning[]
     */
    public function getWarnings()
    {
        return $this->warnings;
    }



    public function enterTree(array $statements)
    {
    }



    public function enterNode(Statement $statement)
    {
        if ($statement instanceof ConditionalStatement)
        {
            $this->inCondition = TRUE;
        }

        if ($statement instanceof Assignment && FALSE === $this->inCondition)
        {
            if (isset($this->assignments[$statement->object->absoluteName]))
            {
                /** @var \Helmich\TypoScriptParser\Parser\AST\Statement $lastAssignment */
                $lastAssignment   = $this->assignments[$statement->object->absoluteName];
                $this->warnings[] = new Warning(
                    $lastAssignment->sourceLine,
                    NULL,
                    sprintf('Value of object "%s" is overwritten in line %d.', $statement->object->absoluteName, $statement->sourceLine),
                    Warning::SEVERITY_WARNING,
                    'Helmich\TypoScriptLint\Linter\Sniff\DuplicateAssignmentSniff'
                );
            }

            $this->assignments[$statement->object->absoluteName] = $statement;
        }
    }



    public function exitNode(Statement $statement)
    {
        // Luckily, conditions cannot be nested. Phew.
        if ($statement instanceof ConditionalStatement)
        {
            $this->inCondition = FALSE;
        }
    }



    public function exitTree(array $statements)
    {
    }
}