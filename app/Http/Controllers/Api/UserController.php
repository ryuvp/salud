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
use Carbon\Carbon;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // return UserResource::collection(User::all());
        return UserResource::collection(User::with('ipress')->get());
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
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'document' => 'required|string|max:20|unique:users,document',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'min:6'],
            'confirm_password' => 'same:password',
            'phone' => 'nullable|string|max:15',
            'birthdate' => 'nullable|date',
            'ipress.id' => 'nullable|exists:ipress,id',
            'role.id' => 'required|exists:roles,id',
            'sex.value' => 'required|in:0,1', // 0 for Male, 1 for Female
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $params = $request->all();

            $birthdate = $params['birthdate'] ? Carbon::parse($params['birthdate'])->format('Y-m-d') : null;

            $user = User::create([
                'name' => $params['name'],
                'lastname' => $params['lastname'],
                'email' => $params['email'],
                'document' => $params['document'],
                'password' => Hash::make($params['password']),
                'sex' => $params['sex']['value'],
                'birthdate' => $birthdate,
                'ipress_id' => $params['ipress']['id'] ?? null,
            ]);

            $role = Role::findOrFail($params['role']['id']);
            $user->syncRoles($role->name);

            $loginUser = Auth::user();
            Log::create([
                'user_id' => $user->id,
                'operator_id' => $loginUser->id,
                'title' => 'Create',
                'content' => "Created by {$loginUser->name} ({$loginUser->email})"
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
