<?php

namespace Appwrite\Utopia\Database\Validator\Queries;

use Utopia\Config\Config;
use Utopia\Database\Database;
use Utopia\Database\Document;
use Utopia\Database\Exception\Query as QueryException;
use Utopia\Database\QueryContext;
use Utopia\Database\Validator\Queries;
use Utopia\Database\Validator\Queries\V2 as DocumentsValidator;
use Utopia\Database\Validator\Query\Cursor;
//use Utopia\Database\Validator\Query\Filter;
use Utopia\Database\Validator\Query\Limit;
use Utopia\Database\Validator\Query\Offset;
//use Utopia\Database\Validator\Query\Order;

class Base extends DocumentsValidator
{
    /**
     * Expression constructor
     *
     * @param string $collection
     * @param string[] $allowedAttributes
     * @throws \Exception
     */
    public function __construct(string $collection, array $allowedAttributes)
    {
        $config = Config::getParam('collections', []);

        $collections = array_merge(
            $config['projects'],
            $config['buckets'],
            $config['databases'],
            $config['console'],
            $config['logs']
        );

        $collection = $collections[$collection];

        $collection = new Document($collection);

        $attributes = [];

        foreach ($collection['attributes'] as $attribute) {
            if (in_array($attribute['$id'], $allowedAttributes)){
                $attributes[] = $attribute;
            }
        }

        $collection->setAttribute('attributes', $attributes);

        $context = new QueryContext;
        $context->add($collection);
        var_dump($collection);

        parent::__construct(
            $context,
            maxQueriesCount: APP_DATABASE_QUERY_MAX_VALUES
        );
    }
}
