<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class FilesController
 * @package App\Http\Controllers
 */
class FilesController extends Controller
{
    /**
     * FilesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get all files for the authenticated user
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $request->user()->files;
    }

    /**
     * Store a new file for the authenticated user
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'string|required',
                'file' => 'required|file|mimes:jpg,jpeg,mp4'
            ]
        );

        $uploadedFile = $request->file('file');
        $url = $uploadedFile->store('/', 's3');

        $mimeType = $uploadedFile->getMimeType();
        $typeArray = explode('/', $mimeType);

        return $request->user()->files()->create(
            [
                'name' => $request->name,
                'path' => $url,
                'type' => $typeArray[0]
            ]
        );
    }

    /**
     * Show the given file
     *
     * @param File $file
     * @return mixed
     */
    public function show(File $file)
    {
        return Storage::disk('s3')->response($file->path);
    }

    /**
     * Delete the given file
     *
     * @param File $file
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(File $file)
    {
        Storage::disk('s3')->delete($file->path);
        $file->delete();
        return response()->json(['message' => 'File Deleted'], 202);
    }
}
