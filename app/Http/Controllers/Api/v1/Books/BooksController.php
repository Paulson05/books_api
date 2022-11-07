<?php

namespace App\Http\Controllers\Api\v1\Books;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Library\RestFullResponse\ApiResponse;
use App\Http\Repository\BookRepository;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\v1\Books\BookResource;
use App\Http\Resources\v1\Books\BookResourceCollection;
use App\Http\Resources\v1\Books\ExternalBookResourceCollection;
use App\Http\Resources\v1\Books\SingleBookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BooksController extends Controller
{

    protected $bookRepository;
    protected $apiResponse;
    protected $bookResource;


    /**
     * BooksController constructor.
     * @param BookRepository $bookRepository
     * @param ApiResponse $apiResponse
     * @param BookResource $bookResource
     */
    public function __construct(
        BookRepository $bookRepository,
        ApiResponse $apiResponse,
        BookResource $bookResource
    )
    {
        $this->bookRepository = $bookRepository;
        $this->apiResponse = $apiResponse;
        $this->bookResource = $bookResource;
    }

    /**
     * @group Book management
     *
     *  Book Collection
     *
     * An Endpoint to get all Book in the system
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @apiResourceCollection \App\Http\Resources\v1\Book\BookResourceCollection
     * @apiResourceModel \App\Models\Book
     */
    


    /**
     * @group Book management
     *
     *  Books Collection
     *
     * An Endpoint to store book in the system
     *
     * @param CreateBookRequest $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     * @apiResourceCollection \App\Http\Resources\v1\Book\BookResourceCollection
     * @apiResourceModel \App\Models\Book
     */
    public function store(CreateBookRequest $request, Book $book)
    {
        $book = $book->create($request->toArray());
        $book['hide_id'] = true;
        return $this->apiResponse->respondWithDataStatusAndCodeOnly(
            ['book' => $this->bookResource->transform($book)], JsonResponse::HTTP_CREATED);
    }


    /**
     * @group Book management
     *
     *  Books Collection
     *
     * An Endpoint to get single Book detail in the system
     *
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     * @apiResourceCollection \App\Http\Resources\v1\Book\BookResourceCollection
     * @apiResourceModel \App\Models\Book
     */
    public function show(Book $book)
    {
        return $this->apiResponse->respondWithDataStatusAndCodeOnly(
            $this->bookResource->transform($book));
    }

    /**
     * An Endpoint to update the specified resource from storage.
     *
     * @param UpdateBookRequest $request
     * @return void
     */
    public function update(UpdateBookRequest $request, $id)
    {
        $updatedBook = $this->bookRepository->updateBook($request, $id);
        if (is_string($updatedBook)) return $this->apiResponse->respondWithError($updatedBook);

        return $this->apiResponse->respondWithNoPagination(
            $this->bookResource->transform($updatedBook),
            "The book $updatedBook->name was updated successfully");
    }


    /**
     * An Endpoint to Remove the specified resource from storage.
     *
     * @param Book $book
     * @return void
     * @throws \Exception
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return $this->apiResponse->respondDeleted("The book $book->name was deleted successfully");
    }


    /**
     * An Endpoint to Get a book from external storage.
     *
     * @param Request $request
     * @return void
     */
    public function externalBook(Request $request)
    {
        try {

            $bookName = trim($request->name);
            $param = '?name=' . $bookName;
            $baseUrl =  config('services.iceAndFire.base_url');
            $url = $baseUrl . '/api/books' . $param;
            $response = Http::get($url);
            $data = [
                'name' => '',
                'isbn' => '',
                'authors' => '',
                'country' => '',
                'numberOfPages' => 0,
                'publisher' => '',
                'released' => ''

            ];

            if(empty($response->json())) {
                return ["status_code" => 404,
                    "Status" => "not found",
                    "data" => []
                ];
            }


            if(is_array($response->json())) {
                $data = $response->json()[0];
            }

            return [
                'status_code' => 200,
                'status' => 'success',
                'data' => [
                    'name' => $data['name'],
                    'isbn' => $data['isbn'],
                    'authors' => $data['authors'],
                    'number_of_pages' => $data['numberOfPages'],
                    'publisher'  => $data['publisher'],
                    'country'=>$data['country'],
                    'release_date'=> $data['released']
                ]
            ];
        } catch(\Exception $e) {
            Log::debug($e->getMessage());
            return ["status_code" => 500,
                "Status" => "An error occured",
                "data" => []
            ];
        }
        

        
        
       
    }

    

}
