<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        $this->applySearch($query, $request, [
            'name',
            'email',
            'phone',
        ]);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return $this->successPagination(
            $users,
            UserResource::class,
            'Users retrieved successfully.'
        );
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return $this->success(
            new UserResource($user),
            'User created successfully.',
            201
        );
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return $this->success(
            new UserResource($user),
            'User retrieved successfully.'
        );
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $this->success(
            new UserResource($user),
            'User updated successfully.'
        );
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return $this->error(
                'You cannot delete your own account.',
                422
            );
        }

        $user->delete();

        return $this->success(
            null,
            'User deleted successfully.'
        );
    }
}