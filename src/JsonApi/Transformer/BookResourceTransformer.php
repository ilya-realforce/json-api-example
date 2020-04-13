<?php

namespace App\JsonApi\Transformer;

use App\Entity\Book;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Book Resource Transformer.
 */
class BookResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($book): string
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($book): string
    {
        return (string) $book->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($book): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($book): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/books/'.$this->getId($book)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($book): array
    {
        return [
            'title' => function (Book $book) {
                return $book->getTitle();
            },
            'isbn' => function (Book $book) {
                return $book->getIsbn();
            },
            'authors' => function (Book $book) {
                return $book->getAuthors();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($book): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($book): array
    {
        return [
        ];
    }
}
