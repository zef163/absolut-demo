<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatesRequest;
use App\Repositories\ExchangeRatesRepository;
use Illuminate\Http\JsonResponse;

class ExchangeRatesController extends Controller
{
    /**
     * @var ExchangeRatesRepository
     */
    protected ExchangeRatesRepository $repository;

    /**
     * ExchangeRatesController constructor.
     * @param ExchangeRatesRepository $repository
     */
    public function __construct(ExchangeRatesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Show rates
     *
     * @param RatesRequest $request
     * @return JsonResponse
     */
    public function index(RatesRequest $request): JsonResponse
    {
        $data = $request->validated();

        return response()->json($this->repository->get($data));
    }
}
