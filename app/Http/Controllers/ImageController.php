<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Сохраняет изображение в базу данных.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Проверяем, есть ли файл в запросе
        if ($request->hasFile('image')) {
            // Получаем имя файла
            $originalName = $request->file('image')->getClientOriginalName();

            // Получаем расширение файла
            $extension = $request->file('image')->getClientOriginalExtension();

            // Генерируем уникальное имя для файла
            $fileName = time() . '_' . uniqid() . '.' . $extension;

            // Сохраняем файл на сервере
            $request->file('image')->storeAs('', $fileName, 'custom_public');


            // Создаем новую запись в базе данных для изображения
            $image = new Image();
            $image->name = $fileName;
            $image->original_name = $originalName; // Сохраняем оригинальное имя для отображения пользователю
            $image->save();

            return response()->json([
                'message' => 'Изображение успешно сохранено',
                'image_name' => $fileName
            ], 201);
        } else {
            return response()->json(['message' => 'Изображение не найдено в запросе'], 400);
        }
    }
}
