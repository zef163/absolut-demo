<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionStoreRequest;
use App\Models\Collection;
use App\Repositories\ExchangeRatesRepository;
use Illuminate\Http\JsonResponse;

class CollectionController extends Controller
{
    protected ExchangeRatesRepository $repository;

    /**
     * CollectionController constructor.
     * @param ExchangeRatesRepository $repository
     */
    public function __construct(ExchangeRatesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store collection in database
     *
     * @param CollectionStoreRequest $request
     * @return JsonResponse
     */
    public function store(CollectionStoreRequest $request): JsonResponse
    {
        $collection = app(Collection::class)->add($request->validated());

        return response()->json(['id' => $collection->id]);
    }

    /**
     * Get collection data
     *
     * @param Collection $collection
     * @return JsonResponse
     */
    public function show(Collection $collection): JsonResponse
    {
        // Note of collection
        $note = $collection->note;

        // Collection params
        $params = [
            'dateStart' => $collection->date_start,
            'dateEnd' => $collection->date_end,
            'baseCode' => $collection->base_code,
            'compareCode' => $collection->compare_code,
        ];
        $result = $this->repository->get($params);

        return response()->json(compact('result', 'params', 'note'));
    }
}
