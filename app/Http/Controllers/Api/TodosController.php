<?php

namespace App\Http\Controllers\Api;

use App\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class TodosController extends ApiController
{
    /**
     * Create a new TodosController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $jogos = auth()->user()->jogos()->get();

        return $this->respond($jogos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $jogo = Todo::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'done' => $request->input('done'),
            'user_id' => auth()->user()->id
        ]);

        return $this->respond($jogo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  App\Todo $jogo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Todo $jogo)
    {
        if ($jogo->user_id != auth()->user()->id) {
            return $this->respondUnauthorized();
        }

        $jogo = $jogo->fill($request->all())->save();

        return $this->respond($jogo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Todo $jogo)
    {
        if ($jogo->user_id != auth()->user()->id) {
            return $this->respondUnauthorized();
        }

        $jogo = $jogo->delete();

        return $this->respond($jogo);
    }
}
