<?php

namespace App\Http\Requests;

use App\Enums\TipoDocumento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentoLegalRequest extends FormRequest
{
    /** El grupo de rutas ya exige sesion iniciada (middleware `auth`). */
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $documento = $this->route('documento');
        $esNuevo = $documento === null;

        return [
            'tipo' => [
                'required',
                Rule::enum(TipoDocumento::class),
                Rule::unique('documentos_legales', 'tipo')->ignore($documento),
            ],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'activo' => ['boolean'],

            // Al crear, el PDF es opcional: se puede dar de alta el registro y
            // subir el archivo despues, cuando el despacho lo entregue.
            // `mimetypes` revisa el contenido real del archivo, no solo la
            // extension, para que no pase un .pdf que en realidad no lo es.
            'archivo' => [
                $esNuevo ? 'nullable' : 'nullable',
                'file',
                'mimetypes:application/pdf',
                'mimes:pdf',
                'max:'.config('isp.max_kb_documento'),
            ],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'archivo.mimetypes' => 'El archivo debe ser un PDF válido.',
            'archivo.mimes' => 'El archivo debe tener extensión .pdf',
            'archivo.max' => 'El PDF no debe pesar más de :max kilobytes.',
            'tipo.unique' => 'Ya existe un documento registrado con ese tipo.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'tipo' => 'tipo de documento',
            'titulo' => 'título',
            'descripcion' => 'descripción',
            'archivo' => 'archivo PDF',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'activo' => $this->boolean('activo'),
        ]);
    }
}
