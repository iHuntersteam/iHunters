<?php

namespace App\Http\Controllers\Site;

use App\Models\Site;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('operation_type') or !$request->has('site_name')) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Нет параметра operation_type ли site_name",
                ]],
                422);
        }

        $site = Site::create([
            'name' => $request->get('site_name'),
        ]);

        return response()->json([
            'response_info' => [
                "error"        => "false",
                "errorMessage" => "Новый сайт добавлен: {$site->id}",
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

        $value = $request->get('site_id');

        try {
            $site = Site::select(['name', 'id'])->findOrFail($value);

            return response()->json([
                'data'          => $site,
                'response_info' => [
                    "error"        => "false",
                    "errorMessage" => "no errors",
                ]], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Такой сайт отсутствует в базе данных",
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
        if (!$request->has('operation_type') or !$request->has('site_id')) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Нет параметра operation_type ли site_id",
                ]],
                422);
        }

        try {
            /** @var Site $site */
            $site = Site::findOrFail($request->get('site_id'));
            $site->update($request->except([
                'operation_type',
                'middleware_token',
                'site_id',
                '_method',
            ]));

            return response()->json([
                'response_info' => [
                    "error"        => "false",
                    "errorMessage" => "Новое имя сайта: {$site->name}",
                ]],
                200);
        } catch (\Exception $e) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Такой сайт отсутствует в базе данных",
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
        if (!$request->has('operation_type') or !$request->has('site_id')) {
            return response()->json([
                'response_info' => [
                    "error"        => "true",
                    "errorMessage" => "Нет параметра operation_type или site_id",
                ]],
                200);
        }

        try {
            if (!Site::destroy($request->get('site_id'))) {
                throw new \Exception("Не было удаления, проверьте параметры");
            }

            return response()->json([
                'response_info' => [
                    "error"        => "false",
                    "errorMessage" => "Сайт удален",
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
