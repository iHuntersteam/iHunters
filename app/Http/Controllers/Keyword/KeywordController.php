<?php

namespace App\Http\Controllers\Keyword;

use App\Models\Keyword;
use App\Models\Person;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class KeywordController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('operation_type') or !$request->has('keyword_name') or !$request->has('person_id')) {
            return response()->json([
                'response_info' => [
                    "error" => "true",
                    "errorMessage" => "Нет параметра operation_type, keyword_name  или person_id",
                ]],
                422);
        }

        try {
            $keyword = Keyword::create([
                'name' => $request->get('keyword_name'),
                'person_id' => $request->get('person_id')
            ]);
            $person = Person::where('id', $request->get('person_id'))->firstOrFail();
            $person_name = $person->name;
            return response()->json([
                'response_info' => [
                    "error" => "false",
                    "errorMessage" => "Ключевое слово : {$keyword->name}  добавлено к личности: {$person_name}",
                ]], 201);

        }
        catch(\Exception $e) {

                return response()->json([
                    'response_info' => [
                        "error" => "true",
                        "errorMessage" => "Вы указали несуществующий person_id",
                    ]], 404);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (!$request->has('operation_type')) {
            return response()->json([
                'response_info' => [
                    "error" => "true",
                    "errorMessage" => "Укажите operation_type=info&keyword_id={id}",
                ]],
                422);
        }

        $value = $request->get('keyword_id');

        try {
            $keyword = Keyword::select(['name', 'id'])->findOrFail($value);

            return response()->json([
                'data' => $keyword,
                'response_info' => [
                    "error" => "false",
                    "errorMessage" => "no errors",
                ]], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response_info' => [
                    "error" => "true",
                    "errorMessage" => "Такое ключевое слово отсутствует в базе данных",
                ]], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->has('operation_type') or !$request->has('keyword_id')) {
            return response()->json([
                'response_info' => [
                    "error" => "true",
                    "errorMessage" => "Нет параметра operation_type ли keyword_id",
                ]],
                422);
        }

        try {
            /** @var Keyword $keyword */
            $keyword = Keyword::findOrFail($request->get('keyword_id'));
            $keyword->update($request->except([
                'operation_type',
                'middleware_token',
                'keyword_id',
                '_method',
            ]));

            $person = Person::where('id', $keyword->person_id)->firstOrFail();
            $person_name = $person->name;

            return response()->json([
                'response_info' => [
                    "error" => "false",
                    "errorMessage" => "Отредактированное ключевое слово {$keyword->name} добавлено к личности: {$person_name} ",
                ]],
                200);
        } catch (\Exception $e) {
            return response()->json([
                'response_info' => [
                    "error" => "true",
                    "errorMessage" => "Такое ключевое слово отсутствует в базе данных",
                ]], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!$request->has('operation_type') or !$request->has('keyword_id')) {
            return response()->json([
                'response_info' => [
                    "error" => "true",
                    "errorMessage" => "Нет параметра operation_type или keyword_id",
                ]],
                200);
        }

        try {
            if (!Keyword::destroy($request->get('keyword_id'))) {
                throw new \Exception("Не было удаления, проверьте параметры");
            }

            return response()->json([
                'response_info' => [
                    "error" => "false",
                    "errorMessage" => "Ключевое слово удалено",
                ]], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response_info' => [
                    "error" => "true",
                    "errorMessage" => $e->getMessage(),
                ]], 404);
        }
    }
}


