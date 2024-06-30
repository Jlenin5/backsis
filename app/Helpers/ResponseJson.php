<?php

namespace App\Helpers;

class ResponseJson {

  static function store($data = [], $message = "Datos registrados correctamente") {
    return response()->json(['message' => $message, 'content' => $data], 201);
  }

  static function get($data = []) {
    return response()->json(['content' => $data]);
  }

  static function remove($message = 'remove success') {
    return response()->json(['message' => $message]);
  }

  static function update($data = [], $message = 'Datos actualizados correctamente') {
    return response()->json(['message' => $message, 'content' => $data]);
  }

  static function updatedFail($data = [], $message = 'not valid') {
    return response()->json(['message' => $message, 'content' => $data], 400);
  }

  static function destroy($message = 'Datos eliminados correctamente') {
    return response()->json(['message' => $message]);
  }

  static function show($data = []) {
    return response()->json(['content' => $data]);
  }

  static function success($data = [], $message = " success ") {
    return response()->json(['message' => $message, 'content' => $data]);
  }

  static function getAll($data, $message = "success") {
    if (request()->paginate) {
      return response()->json($data, 200);
    }
    return response()->json(['message' => $message, 'content' => $data], 200);
  }
  
  static function getCatalogue($lines, $families, $types, $fuels, $marks, $mark_models, $options, $vehicles) {
    if (request()->paginate) {
      return response()->json($vehicles, 200);
    }
    return response()->json([
      'options'       => $options,
      'vehicles'      => $vehicles,
      'lines'         => $lines,
      'families'      => $families,
      'fuels'         => $fuels,
      'types'         => $types,
      'marks'         => $marks,
      'mark_models'   => $mark_models
    ], 200);
  }

  static function error($errors) {
    return response()->json(['status' => 422, 'message' => 'The given data was invalid.', 'errors' => $errors], 422);
  }
}
