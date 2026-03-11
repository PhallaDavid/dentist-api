<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_birth' => 'nullable|date',
            'patient_gender' => 'required|in:male,female,other',
            'patient_address' => 'nullable|string|max:500',
            'patient_phone' => 'nullable|string|regex:/^\+?[0-9]{7,15}$/',
            'treatment' => 'required|string|max:255',
            'doctor_name' => 'required|string|max:255',
            'appointment_at' => 'required|date',
            'price' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
            'status' => 'nullable|in:pending,completed,cancelled',
            'chief_complain' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'allergies' => 'boolean',
            'anesthetic' => 'boolean',
            'penicillin' => 'boolean',
            'hemophilia' => 'boolean',
            'diabetes' => 'boolean',
            'hypertension' => 'boolean',
            'hepatitis' => 'boolean',
            'hiv' => 'boolean',
            'heart_attack' => 'boolean',
            'angina' => 'boolean',
            'bone_disease' => 'boolean',
            'pregnant' => 'boolean',
        ]);

        // Optionally calculate due_amount automatically
        $validated['due_amount'] = $validated['price'] - $validated['paid_amount'];

        $treatment = Treatment::create($validated);

        return response()->json([
            'message' => 'Treatment record created successfully',
            'treatment' => $treatment
        ], 201);
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 12); // Default to 12 records per page
        $treatments = Treatment::paginate($perPage);

        return response()->json([
            'message' => 'Treatment records retrieved successfully',
            'treatments' => $treatments
        ], 200);
    }

    public function show($id)
    {
        $treatment = Treatment::find($id);

        if (!$treatment) {
            return response()->json([
                'message' => 'Treatment record not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Treatment record retrieved successfully',
            'treatment' => $treatment
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $treatment = Treatment::find($id);

        if (!$treatment) {
            return response()->json([
                'message' => 'Treatment record not found'
            ], 404);
        }

        $validated = $request->validate([
            'patient_name' => 'sometimes|required|string|max:255',
            'patient_birth' => 'sometimes|nullable|date',
            'patient_gender' => 'sometimes|required|in:male,female,other',
            'patient_address' => 'sometimes|nullable|string|max:500',
            'patient_phone' => 'sometimes|nullable|string|regex:/^\+?[0-9]{7,15}$/',
            'treatment' => 'sometimes|required|string|max:255',
            'doctor_name' => 'sometimes|required|string|max:255',
            'appointment_at' => 'sometimes|required|date',
            'price' => 'sometimes|required|numeric|min:0',
            'paid_amount' => 'sometimes|required|numeric|min:0',
            'due_amount' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|nullable|in:pending,completed,cancelled',
            'chief_complain' => 'sometimes|nullable|string',
            'medical_history' => 'sometimes|nullable|string',
            'allergies' => 'sometimes|boolean',
            'anesthetic' => 'sometimes|boolean',
            'penicillin' => 'sometimes|boolean',
            'hemophilia' => 'sometimes|boolean',
            'diabetes' => 'sometimes|boolean',
            'hypertension' => 'sometimes|boolean',
            'hepatitis' => 'sometimes|boolean',
            'hiv' => 'sometimes|boolean',
            'heart_attack' => 'sometimes|boolean',
            'angina' => 'sometimes|boolean',
            'bone_disease' => 'sometimes|boolean',
            'pregnant' => 'sometimes|boolean',
        ]);

        // Optionally calculate due_amount automatically if price or paid_amount is updated
        if (isset($validated['price']) || isset($validated['paid_amount'])) {
            $price = $validated['price'] ?? $treatment->price;
            $paid_amount = $validated['paid_amount'] ?? $treatment->paid_amount;
            $validated['due_amount'] = $price - $paid_amount;
        }

        $treatment->update($validated);

        return response()->json([
            'message' => 'Treatment record updated successfully',
            'treatment' => $treatment
        ], 200);
    }

    public function destroy($id)
    {
        $treatment = Treatment::find($id);

        if (!$treatment) {
            return response()->json([
                'message' => 'Treatment record not found'
            ], 404);
        }

        $treatment->delete();

        return response()->json([
            'message' => 'Treatment record deleted successfully'
        ], 200);
    }
}