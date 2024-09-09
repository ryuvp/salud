<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PatientFollowResource;
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
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(
            User::with('ipress')
                ->whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'paciente')
                          ->where('name', '!=', 'superadmin');
                })->get()
        );
        //return UserResource::collection(User::with('ipress')->get());
    }

    public function getPatients()
    {
        // Ejecuta la consulta y pasa los resultados a la colección
        $patients = User::with('ipress')->whereHas('roles', function ($query) {
            $query->where('name', 'paciente');
        })->get();

        return PatientResource::collection($patients);
    }

    public function getPatientsForFollow()
    {
        $patients = User::with(['diagnostics.prescriptions.medicament', 'diagnostics.cie10'])->whereHas('roles', function ($query) {
            $query->where('name', 'paciente');
        })
        ->get();
        return PatientFollowResource::collection($patients);
    }

    public function getpatientsForReport(Request $request)
    {
        // Obtén los parámetros de búsqueda del request
        $ipressId = $request->input('search.ipress.id', 0);
        $cie10Id = $request->input('search.cie10.id', 0);
    
        // Construye la consulta base
        $query = User::with(['diagnostics.prescriptions.medicament', 'diagnostics.cie10'])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'paciente');
            });
    
        // Aplica filtros condicionales
        if ($ipressId != 0) {
            $query->whereHas('diagnostics', function ($query) use ($ipressId) {
                $query->whereHas('prescriptions', function ($query) use ($ipressId) {
                    $query->where('ipress_id', $ipressId);
                });
            });
        }
    
        if ($cie10Id != 0) {
            $query->whereHas('diagnostics', function ($query) use ($cie10Id) {
                $query->whereHas('cie10', function ($query) use ($cie10Id) {
                    $query->where('id', $cie10Id);
                });
            });
        }
    
        // Obtén los resultados
        $patients = $query->get();
    
        return PatientFollowResource::collection($patients);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'document' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'document')->whereNull('deleted_at')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->whereNull('deleted_at')
            ],
            'password' => ['required', 'min:6'],
            'confirm_password' => 'same:password',
            'phone' => 'nullable|string|max:15',
            'birthdate' => 'nullable|date',
            'ipress.id' => 'nullable|exists:ipress,id',
            'role' => 'required|exists:roles,id',
            'sex' => 'required|in:0,1',
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

            // Verificar si el usuario existe pero está eliminado
            $existingUser = User::withTrashed()->where('document', $params['document'])->first();

            if ($existingUser && $existingUser->trashed()) {
                // Restaurar usuario eliminado lógicamente
                $existingUser->restore();
                // Actualizar datos del usuario restaurado
                $existingUser->update([
                    'name' => $params['name'],
                    'lastname' => $params['lastname'],
                    'email' => $params['email'],
                    'password' => Hash::make($params['password']),
                    'sex' => $params['sex'],
                    'birthdate' => $birthdate,
                    'ipress_id' => $params['ipress']['id'] ?? null,
                ]);
                $user = $existingUser;
            } else {
                // Crear un nuevo usuario
                $user = User::create([
                    'name' => $params['name'],
                    'lastname' => $params['lastname'],
                    'email' => $params['email'],
                    'document' => $params['document'],
                    'password' => Hash::make($params['password']),
                    'sex' => $params['sex'],
                    'birthdate' => $birthdate,
                    'ipress_id' => $params['ipress']['id'] ?? null,
                ]);
            }

            $role = Role::findOrFail($params['role']);
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

    public function storePatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'document' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'document')->whereNull('deleted_at')
            ],
            'phone' => 'nullable|string|max:15',
            'birthdate' => 'nullable|date',
            'ipress.id' => 'nullable|exists:ipress,id',
            'sex' => 'required|in:0,1',
            'ubigeo' => 'nullable',
            'address' => 'nullable',
            'clinic_history' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $loginUser = Auth::user();

            $params = $request->all();
            $birthdate = $params['birthdate'] ? Carbon::parse($params['birthdate'])->format('Y-m-d') : null;

            // Verificar si el usuario existe pero está eliminado
            $existingUser = User::withTrashed()->where('document', $params['document'])->first();

            if ($existingUser && $existingUser->trashed()) {
                // Restaurar usuario eliminado lógicamente
                $existingUser->restore();
                // Actualizar datos del usuario restaurado
                $existingUser->update([
                    'name' => $params['name'],
                    'lastname' => $params['lastname'],
                    'email' => $params['email'] ?? null,
                    'sex' => $params['sex'],
                    'birthdate' => $birthdate,
                    'ipress_id' => $params['ipress']['id'] ?? $loginUser->ipress_id,
                    'phone' => $params['phone'] ?? null,
                    'address' => $params['address'] ?? null,
                    'clinic_history' => $params['clinic_history'] ?? null,
                    'ubigeo' => $params['ubigeo'] ?? null,
                ]);
                $user = $existingUser;
            } else {
                // Crear un nuevo usuario
                $user = User::create([
                    'name' => $params['name'],
                    'lastname' => $params['lastname'],
                    'email' => $params['email'] ?? null,
                    'document' => $params['document'],
                    'sex' => $params['sex'],
                    'birthdate' => $birthdate,
                    'ipress_id' => $params['ipress']['id'] ?? $loginUser->ipress_id,
                    'phone' => $params['phone'] ?? null,
                    'address' => $params['address'] ?? null,
                    'clinic_history' => $params['clinic_history'] ?? null,
                    'ubigeo' => $params['ubigeo'] ?? null,
                ]);
            }

            // Asignar el rol de 'paciente'
            $patientRole = Role::where('name', 'paciente')->firstOrFail();
            $user->syncRoles($patientRole->name);

            Log::create([
                'user_id' => $user->id,
                'operator_id' => $loginUser->id,
                'title' => 'Create',
                'content' => "Patient created by {$loginUser->name} ({$loginUser->email})"
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Patient created successfully',
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
    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'document' => "nullable|string|max:20|unique:users,document,$id",
            'email' => "nullable|email|unique:users,email,$id",
            'password' => 'nullable|min:6',
            'confirm_password' => 'same:password',
            'phone' => 'nullable|string|max:15',
            'birthdate' => 'nullable|date',
            'ipress.id' => 'nullable|exists:ipress,id',
            'role' => 'nullable|exists:roles,id',
            'sex' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $params = $request->all();

            if (!empty($params['name']) && $params['name'] !== $user->name) {
                $user->name = $params['name'];
            }

            if (!empty($params['lastname']) && $params['lastname'] !== $user->lastname) {
                $user->lastname = $params['lastname'];
            }

            if (!empty($params['document']) && $params['document'] !== $user->document) {
                $user->document = $params['document'];
            }

            if (!empty($params['email']) && $params['email'] !== $user->email) {
                $user->email = $params['email'];
            }

            if (!empty($params['password'])) {
                $user->password = Hash::make($params['password']);
            }

            if (isset($params['sex']) && $params['sex'] !== $user->sex) {
                $user->sex = $params['sex'];
            }

            if (!empty($params['birthdate'])) {
                $birthdate = Carbon::parse($params['birthdate'])->format('Y-m-d');
                if ($birthdate !== $user->birthdate) {
                    $user->birthdate = $birthdate;
                }
            }

            if (isset($params['ipress']['id']) && $params['ipress']['id'] !== $user->ipress_id) {
                $user->ipress_id = $params['ipress']['id'];
            }

            if (!empty($params['role'])) {
                $role = Role::findOrFail($params['role']);
                if (!$user->hasRole($role->name)) {
                    $user->syncRoles($role->name);
                }
            }

            if ($user->isDirty()) {
                $user->save();
            }

            $loginUser = Auth::user();
            Log::create([
                'user_id' => $user->id,
                'operator_id' => $loginUser->id,
                'title' => 'Update',
                'content' => "Updated by {$loginUser->name} ({$loginUser->email})"
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     */
    /*public function permissions(User $user)
    {
        try {
            return responseSuccess([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            return responseFailed($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }*/
}
