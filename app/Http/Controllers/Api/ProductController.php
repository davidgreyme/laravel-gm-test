<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductInterface;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * Class ProductController
 * @package App\Http\Controllers\Api
 */
class ProductController extends Controller
{

    /**
     * @var ProductInterface
     */
    private $productsRepo;

    /**
     * ProductController constructor.
     * @param ProductInterface $productsRepo
     */
    public function __construct(ProductInterface $productsRepo)
    {
        $this->productsRepo = $productsRepo;
    }

    /**
     * @param PaginateRequest $request
     * @return JsonResponse
     */
    public function index(PaginateRequest $request)
    {
        $data = $request->inputs();
        $products = $this->productsRepo->getAll($data);

        return response()->json([
            'products' => ProductResource::collection($products),
            'type'     => 'success',
            'success'  => 1
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $product = $this->productsRepo->getById($id);

        return response()->json([
            'product' => new ProductResource($product),
            'type'    => 'success',
            'success' => 1
        ]);
    }

    /**
     * @param ProductCreateRequest $request
     * @return JsonResponse
     */
    public function store(ProductCreateRequest $request)
    {
        try {
            $data = $request->all();
            $data['user_id'] = auth()->id();
            $product = $this->productsRepo->store($data);

            return response()->json([
                'success' => 1,
                'type'    => 'success',
                'product' => new ProductResource($product)
            ])->setStatusCode(200);
        } catch (Exception $exception) {
            return response()->json([
                'success' => 0,
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product)
    {
        try {
            $access = Gate::inspect('delete', $product);
            if (!$access->allowed()) {

                return response()->json([
                    'success' => 0,
                    'type'    => 'error',
                    'message' => $access->message(),
                ])->setStatusCode(403);
            }
            $this->productsRepo->destroy($product->id);

            return response()->json([
                'success' => 1,
                'type'    => 'success',
            ])->setStatusCode(204);
        } catch (Exception $exception) {

            return response()->json([
                'success' => 0,
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ])->setStatusCode(500);
        }
    }


    /**
     * @param Product $product
     * @param ProductUpdateRequest $request
     * @return JsonResponse
     */
    public function update(Product $product, ProductUpdateRequest $request)
    {
        try {

            $access = Gate::inspect('update', $product);
            if (!$access->allowed()) {
                return response()->json([
                    'success' => 0,
                    'type'    => 'error',
                    'message' => $access->message(),
                ])->setStatusCode(403);
            }
            $this->productsRepo->update($product->id, $request->all());

            return response()->json([
                'success' => 1,
                'type'    => 'success',
            ])->setStatusCode(200);
        } catch (Exception $exception) {

            return response()->json([
                'success' => 0,
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
