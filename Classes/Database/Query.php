<?php
namespace SebastiaanDeJonge\Pm\Database;

/**
 * Simply query model
 *
 * @package SebastiaanDeJonge\Pm\Database
 */
class Query
{
    /**
     * Current only SELECT queries are supported
     */
    const TYPE_SELECT = 'select';

    const ORDER_ASCENDING = 'ASC';
    const ORDER_DESCENDING = 'DESC';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $selectFields = [];

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var array
     */
    protected $joins = [];

    /**
     * @var array
     */
    protected $clauses = [];

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @var array
     */
    protected $orderings = [];

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getSelectFields(): array
    {
        return $this->selectFields;
    }

    /**
     * @param array $selectFields
     * @return $this
     */
    public function setSelectFields(array $selectFields): self
    {
        $this->selectFields = $selectFields;
        return $this;
    }

    /**
     * @param string $selectField
     * @return $this
     */
    public function addSelectField(string $selectField): self
    {
        $this->selectFields[] = $selectField;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     * @return $this
     */
    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * @return array
     */
    public function getJoins(): array
    {
        return $this->joins;
    }

    /**
     * @param array $joins
     * @return $this
     */
    public function setJoins(array $joins): self
    {
        $this->joins = $joins;
        return $this;
    }

    /**
     * @return array
     */
    public function getClauses(): array
    {
        return $this->clauses;
    }

    /**
     * @param array $clauses
     * @return $this
     */
    public function setClauses(array $clauses): self
    {
        $this->clauses = $clauses;
        return $this;
    }

    /**
     * @param string $join
     * @return $this
     */
    public function addJoin(string $join): self
    {
        $this->joins[] = $join;
        return $this;
    }

    /**
     * @param string $join
     * @return $this
     */
    public function removeJoin(string $join): self
    {
        if (in_array($join, $this->joins)) {
            $index = array_search($join, $this->joins);
            if ($index !== false && (int)$index === $index) {
                unset($this->joins[$index]);
            }
        }
        return $this;
    }

    /**
     * @param string $clause
     * @return $this
     */
    public function addClause(string $clause): self
    {
        $this->clauses[] = $clause;
        return $this;
    }

    /**
     * @param string $clause
     * @return $this
     */
    public function removeClause(string $clause): self
    {
        if (in_array($clause, $this->clauses)) {
            $index = array_search($clause, $this->clauses);
            if ($index !== false && (int)$index === $index) {
                unset($this->clauses[$index]);
            }
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this;
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderings(): array
    {
        return $this->orderings;
    }

    /**
     * @param array $orderings
     * @return $this
     */
    public function setOrderings(array $orderings): self
    {
        $this->orderings = $orderings;
        return $this;
    }

    /**
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function addOrdering(string $field, string $direction = self::ORDER_ASCENDING): self
    {
        $this->orderings[$field] = $direction;
        return $this;
    }

    /**
     * Builds the actual SQL query
     *
     * @return string
     */
    public function build(): string
    {
        switch ($this->type)
        {
            case self::TYPE_SELECT:
            default:
                // Query base
                $parts = [
                    'SELECT ' . implode(',', $this->getSelectFields()),
                    'FROM ' . $this->tableName,
                    implode("\n", $this->joins),
                    'WHERE ' . (empty($this->clauses) ? '1' : implode(' AND ', $this->clauses))
                ];

                // Ordering
                if (!empty($this->orderings)) {
                    $orderings = [];
                    foreach ($this->orderings as $field => $direction) {
                        $orderings[] = $field . ' ' . $direction;
                    }
                    $parts[] = 'ORDER BY ' . implode(', ', $orderings);
                }

                // Limit & offset
                if ($this->limit > 0) {
                    if ($this->offset > 0) {
                        $parts[] = 'LIMIT ' . (int)$this->offset . ',' . (int)$this->limit;
                    } else {
                        $parts[] = 'LIMIT ' . (int)$this->limit;
                    }
                }
                break;
        }
        return implode("\n", $parts);
    }
}