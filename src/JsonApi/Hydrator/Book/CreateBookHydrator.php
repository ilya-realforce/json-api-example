<?php

namespace App\JsonApi\Hydrator\Book;

use App\Entity\Book;

/**
 * Create Book Hydrator.
 */
class CreateBookHydrator extends AbstractBookHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($book): array
    {
        return [
            'title' => function (Book $book, $attribute, $data, $attributeName) {
                $book->setTitle($attribute);
            },
            'isbn' => function (Book $book, $attribute, $data, $attributeName) {
                $book->setIsbn($attribute);
            },
            'authors' => function (Book $book, $attribute, $data, $attributeName) {
                $book->setAuthors($attribute);
            },
        ];
    }
}
