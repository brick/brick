<?php

namespace Brick\Translation\Loader;

use Brick\Translation\Loader;
use Brick\Locale\Locale;

/**
 * Translation loader for PDO database connections.
 */
class PdoLoader implements Loader
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
    protected $parameters = array();

    /**
     * @var array
     */
    protected static $defaultOptions = array(
        'keyColumn'    => 't_key',
        'valueColumn'  => 't_value',
        'localeColumn' => 't_locale',
        'tableName'    => 'translation',
        'conditions'   => array()
    );

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
    public function load(Locale $locale)
    {
        $locale = (string) $locale;
        $parameters = array_merge(array($locale), $this->parameters);

        $this->statement->execute($parameters);
        $rows = $this->statement->fetchAll(\PDO::FETCH_NUM);

        $result = array();
        foreach ($rows as $row) {
            $result[$row[0]] = $row[1];
        }

        return $result;
    }
}
