<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

abstract class ApiController extends BaseController
{
    protected string $modelClass;

    protected string $resourceClass;

    protected string $messageKey;

    /** @var array<int, string> */
    protected array $relationships = [];

    /** @var array<string, array<int, string>> */
    protected array $storeRules = [];

    /** @var array<string, array<int, string>> */
    protected array $updateRules = [];

    public function index(): JsonResponse
    {
        $records = $this->modelClass::query()
            ->with($this->relationships)
            ->latest('id')
            ->paginate(10);

        return $this->handleResponse(
            $this->resourceClass::collection($records),
            Lang::get("api.{$this->messageKey}.index"),
            $records->lastPage(),
            $records->total()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);
        $record = $this->modelClass::query()->create($data);
        $record->load($this->relationships);

        return $this->handleResponse(
            new $this->resourceClass($record),
            Lang::get("api.{$this->messageKey}.store")
        );
    }

    public function show(int $id): JsonResponse
    {
        $record = $this->findRecord($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        return $this->handleResponse(
            new $this->resourceClass($record),
            Lang::get("api.{$this->messageKey}.show")
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $record = $this->findRecord($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        $record->update($request->validate($this->updateRules));
        $record->load($this->relationships);

        return $this->handleResponse(
            new $this->resourceClass($record),
            Lang::get("api.{$this->messageKey}.update")
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $record = $this->findRecord($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        $record->delete();

        return $this->handleResponse(
            null,
            Lang::get("api.{$this->messageKey}.destroy")
        );
    }

    public function restore(int $id): JsonResponse
    {
        $record = $this->modelClass::withTrashed()->find($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        $record->restore();
        $record->load($this->relationships);

        return $this->handleResponse(
            new $this->resourceClass($record),
            Lang::has("api.{$this->messageKey}.restore")
                ? Lang::get("api.{$this->messageKey}.restore")
                : Lang::get('api.restored')
        );
    }

    public function addFiles(Request $request, int $id): JsonResponse
    {
        $record = $this->findRecord($id);

        if (! $record) {
            return $this->handleError(null, Lang::get("api.{$this->messageKey}.not_found"), Response::HTTP_NOT_FOUND);
        }

        $data = $request->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*.file_name' => ['sometimes', 'nullable', 'string'],
            'files.*.file_url' => ['required', 'string'],
            'files.*.file_description' => ['sometimes', 'nullable', 'string'],
            'files.*.file_type' => ['sometimes', 'nullable', 'in:video,photo,audio,document,id_card,ad,qr_code'],
        ]);

        $foreignKey = Str::singular($record->getTable()).'_id';
        $files = collect($data['files'])
            ->map(fn (array $file): File => File::query()->create([
                ...$file,
                'file_type' => $file['file_type'] ?? 'photo',
                $foreignKey => $record->getKey(),
            ]));

        return $this->handleResponse(
            FileResource::collection($files),
            Lang::get('api.files.store')
        );
    }

    protected function findRecord(int $id): ?Model
    {
        return $this->modelClass::query()
            ->with($this->relationships)
            ->find($id);
    }
}
