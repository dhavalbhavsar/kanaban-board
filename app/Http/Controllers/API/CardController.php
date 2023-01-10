<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\Card;
use App\Models\Column;
use App\Http\Requests\Card\RequestFormRequest;
use App\Http\Requests\Card\OrderRequest;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::with('column')->get();

        return $this->respond('Card details successfully', $cards, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestFormRequest $request)
    {
        $payload = $request->validated();

        try {

            $column = Column::findOrFail($payload['column_id']);

            $card = $column->cards()->create([
                'name'              => $payload['name'],
                'description'       => $payload['description'] ?? null
            ]);

        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respond('Card fail to create', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->respond('Card created successfully', $card, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $card = Card::with('column')->findOrFail($id);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respond('Card fail to get edit', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->respond('Card get successfully', $card, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestFormRequest $request, $id)
    {
        $payload = $request->validated();

        try {

            $column = Column::findOrFail($payload['column_id']);

            $column->cards()->where('id', $id)->update([
                'name' => $payload['name'],
                'description' => $payload['description']
            ]);

            $card = Card::find($id);

        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respond('Card fail to update', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->respond('Card updated successfully', $card, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $card = Card::find($id);
            $card->fill([
                'status' => 0
            ]);
            $card->save();

        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respond('Card fail to delete', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->respond('Card deleted successfully', $card, Response::HTTP_OK);
    }

    public function order(OrderRequest $request)
    {
        $payload = $request->validated();

        try {

            foreach ($payload['columns'] as $column) {
                foreach ($column['cards'] as $i => $card) {
                    $order = $i + 1;
                    $card = Card::find($card['id']);
                    $card->order = $order;
                    $card->column_id = $column['id'];
                    $card->save();
                }
            }

        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respond('Column/Card fail to order', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->respondApiOk();
    }
}
