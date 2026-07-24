<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LibrarySetting\UpdateLibrarySettingRequest;
use App\Models\LibrarySetting;
use App\Http\Resources\LibrarySettingResource;

class LibrarySettingController extends Controller
{
    /**
     * Display the library settings.
     */
    public function show()
    {
        $settings = LibrarySetting::firstOrFail();

        return $this->success(
            new LibrarySettingResource($settings),
            'Library settings retrieved successfully.'
        );
    }

    /**
     * Update the library settings.
     */
    public function update(UpdateLibrarySettingRequest $request)
    {
        $settings = LibrarySetting::firstOrFail();

        $settings->update($request->validated());

        return $this->success(
            new LibrarySettingResource($settings->fresh()),
            'Library settings updated successfully.'
        );
    }
}