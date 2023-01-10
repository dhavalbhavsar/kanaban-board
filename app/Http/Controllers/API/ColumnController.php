<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Models\Column;
use App\Http\Requests\Column\RequestFormRequest;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = Column::with('cards')->whereHas('cards', function($query) use($request) {
            $date = $request->input('date');
            $status = $request->input('status');

            if(!empty($date))
                $query->where(\DB::raw('DATE(created_at)'), $date);

            if(!empty($status))
                $query->where('status', $status);

        })->get();

        return $this->respond('Column details successfully', $columns, Response::HTTP_OK);
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
            $column = Column::create($payload);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respond('Column fail to create', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->respond('Column created successfully', $column, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $column = Column::findOrFail($id);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respond('Column fail to get edit', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->respond('Column get successfully', $column, Response::HTTP_OK);
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
            $column = Column::find($id);
            $column->fill($payload);
            $column->save();
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respond('Column fail to update', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->respond('Column updated successfully', $column, Response::HTTP_OK);
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
            $column = Column::find($id);
            $column->delete();
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return $this->respond('Column fail to delete', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->respond('Column deleted successfully', [], Response::HTTP_OK);
    }
}
