<?php

namespace App\Http\Controllers;

use App\Models\EmployeeBonusSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeBonusController extends Controller
{
    /**
     * Menampilkan daftar pengaturan bonus.
     */
    public function index(Request $request)
    {
        $settings = EmployeeBonusSetting::with('employee')
            ->latest()
            ->get();

        $employees = User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'role',
            ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,

                'data' => $settings->map(function ($setting) {
                    return [
                        'id' => $setting->id,

                        'employee_id' => $setting->employee_id,

                        'employee_name' => $setting->employee?->name ?? '-',

                        'employee_email' => $setting->employee?->email ?? '-',

                        'bonus_type' => $setting->bonus_type,

                        'bonus_value' => (float) $setting->bonus_value,

                        'active' => (bool) $setting->active,
                    ];
                })->values(),

                'employees' => $employees->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'email' => $employee->email,
                        'role' => $employee->role,
                    ];
                })->values(),
            ]);
        }

        return view(
            'bonuses.index',
            compact('settings', 'employees')
        );
    }


    /**
     * Menyimpan pengaturan bonus baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'integer',
                'exists:users,id',
                'unique:employee_bonus_settings,employee_id',
            ],

            'bonus_type' => [
                'required',
                Rule::in([
                    'percentage',
                    'per_transaction',
                ]),
            ],

            'bonus_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi tambahan untuk persentase
        |--------------------------------------------------------------------------
        */

        if (
            $validated['bonus_type'] === 'percentage'
            && (float) $validated['bonus_value'] > 100
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Persentase bonus tidak boleh lebih dari 100%.',
            ], 422);
        }

        $setting = EmployeeBonusSetting::create([
            'employee_id' => $validated['employee_id'],

            'bonus_type' => $validated['bonus_type'],

            'bonus_value' => $validated['bonus_value'],

            'active' => $request->boolean('active'),
        ]);

        $setting->load('employee');

        return response()->json([
            'success' => true,

            'message' => 'Pengaturan bonus berhasil ditambahkan.',

            'data' => [
                'id' => $setting->id,

                'employee_id' => $setting->employee_id,

                'employee_name' => $setting->employee?->name ?? '-',

                'bonus_type' => $setting->bonus_type,

                'bonus_value' => (float) $setting->bonus_value,

                'active' => (bool) $setting->active,
            ],
        ], 201);
    }


    /**
     * Mengubah pengaturan bonus.
     */
    public function update(
        Request $request,
        EmployeeBonusSetting $employeeBonusSetting
    ) {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique(
                    'employee_bonus_settings',
                    'employee_id'
                )->ignore($employeeBonusSetting->id),
            ],

            'bonus_type' => [
                'required',
                Rule::in([
                    'percentage',
                    'per_transaction',
                ]),
            ],

            'bonus_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'active' => [
                'nullable',
                'boolean',
            ],
        ]);

        if (
            $validated['bonus_type'] === 'percentage'
            && (float) $validated['bonus_value'] > 100
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Persentase bonus tidak boleh lebih dari 100%.',
            ], 422);
        }

        $employeeBonusSetting->update([
            'employee_id' => $validated['employee_id'],

            'bonus_type' => $validated['bonus_type'],

            'bonus_value' => $validated['bonus_value'],

            'active' => $request->boolean('active'),
        ]);

        $employeeBonusSetting->load('employee');

        return response()->json([
            'success' => true,

            'message' => 'Pengaturan bonus berhasil diperbarui.',

            'data' => [
                'id' => $employeeBonusSetting->id,

                'employee_id' => $employeeBonusSetting->employee_id,

                'employee_name' => $employeeBonusSetting->employee?->name ?? '-',

                'bonus_type' => $employeeBonusSetting->bonus_type,

                'bonus_value' => (float) $employeeBonusSetting->bonus_value,

                'active' => (bool) $employeeBonusSetting->active,
            ],
        ]);
    }


    /**
     * Menghapus pengaturan bonus.
     */
    public function destroy(
        EmployeeBonusSetting $employeeBonusSetting
    ) {
        $employeeBonusSetting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan bonus berhasil dihapus.',
        ]);
    }
}