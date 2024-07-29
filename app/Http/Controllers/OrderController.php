<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\OrderRequest;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        $orders = $request->user()
            ->orders()
            ->latest();

        if ($request->exists('status'))
            $orders = $orders->whereStatus(request('status'));

        $orders->with('invoice_products');

        $paginator = $orders->paginate(request('perPage'));

        return $this->customPaginate($paginator);
    }

    /**
     * Display the specified resource.
     *
     * @param int $order_id
     * @param Request $request
     * @return Invoice
     */
    public function show(int $order_id, Request $request): Invoice
    {
        return $request->user()->orders()
            ->with([
                'address',
                'identification',
                'invoice_products',
                'invoice_products.option',
            ])
            ->findOrFail($order_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @return JsonResponse
     * @throws ConnectionException
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $data = [
            'invoice_products' => $request->validated('invoice_products'),
            'vehicle_registration_support' => $request->validated('vehicle_registration_support'),
            'shipping_type' => $request->validated('shipping_type'),
        ];

        if ($request->exists('registration_option'))
            $data['registration_option'] = $request->validated('registration_option');

        if ($request->exists('license_plate_registration_option'))
            $data['license_plate_registration_option'] = $request->validated('license_plate_registration_option');

        $response = Http::baseUrl(config('app.admin_url'))
            ->post('api/price-quote', $data);

        $request->user()->orders()
            ->create([
                'tax' => $response->json('tax'),
                'shipping_fee' => $response->json('shipping_fee'),
                'handling_fee' => $response->json('handling_fee'),
                'other_fees' => [
                    'vehicle_registration_support_fee' => $response->json('vehicle_registration_support_fee'),
                    'registration_fee' => $response->json('registration_fee'),
                    'license_plate_registration_fee' => $response->json('license_plate_registration_fee'),
                ],
                'total' => $response->json('total'),
                'status' => OrderStatus::TO_PAY,
                'note' => $request->validated('note'),
                'other_fields' => [
                    'vehicle_registration_support' => $request->validated('vehicle_registration_support'),
                    'registration_option' => $request->validated('registration_option'),
                    'license_plate_registration_option' => $request->validated('license_plate_registration_option'),
                ],
                'shipping_type' => $request->validated('shipping_type'),
                'transaction_type' => $request->validated('transaction_type'),
                'address_id' => $request->validated('address'),
                'identification_id' => $request->validated('identification'),
            ])
            ->invoice_products()
            ->saveMany(array_map(
                fn(array $invoice_product): InvoiceProduct => InvoiceProduct::make([
                    'price' => $invoice_product['price'],
                    'amount' => $invoice_product['amount'],
                    'value_added_tax' => $invoice_product['value_added_tax'],
                    'option_id' => $invoice_product['option'],
                ]),
                array_values($response->json('invoice_products'))
            ));

        return response()->json('', 201);
    }
}
