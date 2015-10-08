<?php

namespace Brick\Translation\Loader;

use Brick\Translation\TranslationLoader;

/**
 * Translation loader for PDO database connections.
 */
class PdoLoader implements TranslationLoader
{
    /**
     * The prepared statement for loading translations.
     *
     * @var \PDOStatement
     */
    protected $statement;

    /**
     * The extra parameters for the query, as specified in the options.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array
     */
    protected static $defaultOptions = [
        'keyColumn'    => 't_key',
        'valueColumn'  => 't_value',
        'localeColumn' => 't_locale',
        'tableName'    => 'translation',
        'conditions'   => []
    ];

    /**
     * Class constructor.
     *
     * @param \PDO $pdo
     * @param array $options
     */
    public function __construct(\PDO $pdo, array $options)
    {
        $options = $options + self::$defaultOptions;

        $query = sprintf(
            'SELECT %s, %s FROM %s WHERE %s = ?',
            $options['keyColumn'],
            $options['valueColumn'],
            $options['tableName'],
            $options['localeColumn']
        );

        foreach ($options['conditions'] as $column => $value) {
            $query .= sprintf(' AND %s = ?', $column);
            $this->parameters[] = $value;
        }

        $this->statement = $pdo->prepare($query);
    }

    /**
     * {@inheritdoc}
     */
    public function load($locale)
    {
        $parameters = array_merge([$locale], $this->parameters);

        $this->statement->execute($parameters);
        $rows = $this->statement->fetchAll(\PDO::FETCH_NUM);

        $result = [];
        foreach ($rows as $row) {
            $result[$row[0]] = $row[1];
        }

        return $result;
    }
}
