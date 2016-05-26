<?php

namespace App\Http\Controllers\Person;

use App\Models\Person;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PersonController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('operation_type') or !$request->has('person_name')) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Нет параметра operation_type ли person_name",
                ]],
                422);
        }

        $person = Person::create([
            'name' => $request->get('person_name'),
        ]);

        return response()->json([
            'response_info' => [
                "error"        => "false",
                "errorMessage" => "Личность добавлена: {$person->id}",
            ]], 201);
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
                    "error"        => "true",
                    "errorMessage" => "Укажите operation_type=info&person_id={id}",
                ]],
                422);
        }

        $value = $request->get('person_id');

        try {
            $person = Person::select(['name', 'id'])->findOrFail($value);

            return response()->json([
                'data'          => $person,
                'response_info' => [
                    "error"        => "false",
                    "errorMessage" => "no errors",
                ]], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Такая личность отсутствует в базе данных",
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
        if (!$request->has('operation_type') or !$request->has('person_id')) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Нет параметра operation_type ли person_id",
                ]],
                422);
        }

        try {
            /** @var Person $person */
            $person = Person::findOrFail($request->get('person_id'));
            $person->update($request->except([
                'operation_type',
                'middleware_token',
                'person_id',
                '_method',
            ]));

            return response()->json([
                'response_info' => [
                    "error"        => "false",
                    "errorMessage" => "Новое имя личности: {$person->name}",
                ]],
                200);
        } catch (\Exception $e) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Такая личность отсутствует в базе данных",
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
        if (!$request->has('operation_type') or !$request->has('person_id')) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Нет параметра operation_type ли person_id",
                ]],
                422);
        }

        try {
            if (!Person::destroy($request->get('person_id'))) {
                throw new \Exception("Не было удаления, проверьте параметры");
            }

            return response()->json([
                'response_info' => [
                    "error"        => "false",
                    "errorMessage" => "Личность удалена",
                ]], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => $e->getMessage(),
                ]], 404);
        }
    }
}
