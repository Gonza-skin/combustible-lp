<?php

/* SIN ESTA PARTE LOS MENSAJES DE ERROR SALDRAN DEEN INGLES YA QUE PONER MENSAJES DE ERROR ESTA PARTE 
*/

return [
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'email' => 'El campo :attribute debe ser un correo válido.',
    'max' => [
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
    ],
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
        'numeric' => 'El campo :attribute debe ser al menos :min.',
    ],
    'unique' => 'Ya existe un registro con ese valor en :attribute.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'exists' => 'El valor seleccionado en :attribute no es válido.',
    'regex' => 'El formato de :attribute no es válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'in' => 'El valor seleccionado en :attribute no es válido.',

    'attributes' => [
        // Usuarios
        'NOM_USU' => 'nombre',
        'EML_USU' => 'correo electrónico',
        'PAS_USU' => 'contraseña',
        'COD_ROL' => 'rol',
        'COD_EST' => 'estación',
        'ACT_USU' => 'estado',
        // Catálogos
        'DES_ROL' => 'nombre del rol',
        'DES_COM' => 'nombre del combustible',
    ],
];