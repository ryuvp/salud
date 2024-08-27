<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\Log;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'password' => ['required', 'string', 'min:8'],
                    'password_confirmation' => ['required', 'string', 'same:password'],
                ]
            )
        );

        if ($validator->fails()) {
            return responseFailed($validator->errors(), Response::HTTP_BAD_REQUEST);
        } else{
            try{
                $params = $request->all();
                DB::beginTransaction();
                $user = User::create([
                    'name' => $params['name'],
                    'last_name' => $params['last_name'],
                    'email' => $params['email'],
                    'document' => $params['document'],
                    'password' => bcrypt($params['password']),
                    'sex' => $params['sex'],
                    'birthdate' => $params['birthdate'],
                    'ipress' => $params['ipress'] ?? null,
                ]);
                $role = Role::findByName($params['role']);
                $user->syncRoles($role);
                $loginUser  = Auth::user();
                Log::query()->create([
                    'user_id' => $user->id,
                    'operator_id' => $loginUser->id,
                    'title' => 'Create',
                    'content' => "Created by {$loginUser->name}({$loginUser->email})"
                ]);
                DB::commit();
                return new UserResource($user);
            } catch (\Exception $ex) {
                DB::rollBack();
                return responseFailed($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $currentUser = Auth::user();
        if (
            !$currentUser->hasRole('superadmin') //&&
            //$currentUser->id !== $user->id
        ) {
            return response()->json(['error' => 'Permission denied'], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        }

        DB::beginTransaction();
        try {
            $user->sex = intval($request->input('sex'));
            $user->birthday = $request->input('birthday');
            $user->description = $request->input('description');
            $password = $request->input('password');

            if (!empty($password)) {
                $user->password = Hash::make($password);
            }

            $user->save();

            Log::query()->create([
                'user_id' => $user->id,
                'operator_id' => $currentUser->id,
                'title' => 'Updated',
                'content' => "Updated by {$currentUser->name}({$currentUser->email})"
            ]);

            DB::commit();

            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred, transaction rolled back'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     */
    public function permissions(User $user)
    {
        try {
            return responseSuccess([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            return responseFailed($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
