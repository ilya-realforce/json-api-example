<?php

namespace App\Controller;

use App\Entity\Book;
use App\JsonApi\Document\Book\BookDocument;
use App\JsonApi\Document\Book\BooksDocument;
use App\JsonApi\Hydrator\Book\CreateBookHydrator;
use App\JsonApi\Hydrator\Book\UpdateBookHydrator;
use App\JsonApi\Transformer\BookResourceTransformer;
use App\Repository\BookRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/books")
 */
class BookController extends Controller
{
    /**
     * @Route("/", name="books_index", methods="GET")
     */
    public function index(BookRepository $bookRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($bookRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new BooksDocument(new BookResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="books_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $book = $this->jsonApi()->hydrate(new CreateBookHydrator($entityManager, $exceptionFactory), new Book());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($book);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new BookDocument(new BookResourceTransformer()),
            $book
        );
    }

    /**
     * @Route("/{id}", name="books_show", methods="GET")
     */
    public function show(Book $book): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new BookDocument(new BookResourceTransformer()),
            $book
        );
    }

    /**
     * @Route("/{id}", name="books_edit", methods="PATCH")
     */
    public function edit(Book $book, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $book = $this->jsonApi()->hydrate(new UpdateBookHydrator($entityManager, $exceptionFactory), $book);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($book);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new BookDocument(new BookResourceTransformer()),
            $book
        );
    }

    /**
     * @Route("/{id}", name="books_delete", methods="DELETE")
     */
    public function delete(Request $request, Book $book): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
