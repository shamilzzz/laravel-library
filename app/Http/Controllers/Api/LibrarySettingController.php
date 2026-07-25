<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LibrarySetting\UpdateLibrarySettingRequest;
use App\Http\Resources\LibrarySettingResource;
use App\Models\LibrarySetting;

class LibrarySettingController extends Controller
{
    /**
     * Display the library settings.
     */
    public function show()
    {
        $settings = LibrarySetting::firstOrCreate(
            [],
            [
                'max_borrow_days' => 14,
                'max_borrow_limit' => 2,
                'borrow_charge' => 50,
                'late_fee_per_day' => 10,
            ]
        );

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
        $settings = LibrarySetting::firstOrCreate(
            [],
            [
                'max_borrow_days' => 14,
                'max_borrow_limit' => 2,
                'borrow_charge' => 50,
                'late_fee_per_day' => 10,
            ]
        );

        $settings->update($request->validated());

        return $this->success(
            new LibrarySettingResource($settings->fresh()),
            'Library settings updated successfully.'
        );
    }
}