<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModuleContent;
use App\Models\ModuleProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ModuleContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $moduleId): JsonResponse
    {
        $moduleProgress = ModuleProgress::find($moduleId);
        
        if (!$moduleProgress) {
            return response()->json(['error' => 'Module progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        $moduleContents = ModuleContent::where('module_progress_id', $moduleId)
            ->orderBy('sequence_order')
            ->get();
            
        return response()->json(['data' => $moduleContents], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $moduleId): JsonResponse
    {
        $moduleProgress = ModuleProgress::find($moduleId);
        
        if (!$moduleProgress) {
            return response()->json(['error' => 'Module progress not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'content_type' => 'required|in:text,video,quiz,assignment,discussion',
            'content_title' => 'required|string|max:100',
            'content_data' => 'required|string',
            'sequence_order' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Check if sequence_order already exists for this module
        $existingContent = ModuleContent::where('module_progress_id', $moduleId)
            ->where('sequence_order', $request->sequence_order)
            ->first();
            
        if ($existingContent) {
            // Shift all sequence_order values >= the new one
            ModuleContent::where('module_progress_id', $moduleId)
                ->where('sequence_order', '>=', $request->sequence_order)
                ->increment('sequence_order');
        }

        $moduleContent = ModuleContent::create([
            'module_progress_id' => $moduleId,
            'content_type' => $request->content_type,
            'content_title' => $request->content_title,
            'content_data' => $request->content_data,
            'sequence_order' => $request->sequence_order,
        ]);
        
        return response()->json(['data' => $moduleContent], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $moduleContent = ModuleContent::with('moduleProgress')
            ->find($id);
            
        if (!$moduleContent) {
            return response()->json(['error' => 'Module content not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(['data' => $moduleContent], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $moduleContent = ModuleContent::find($id);
        
        if (!$moduleContent) {
            return response()->json(['error' => 'Module content not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validator = Validator::make($request->all(), [
            'content_type' => 'sometimes|required|in:text,video,quiz,assignment,discussion',
            'content_title' => 'sometimes|required|string|max:100',
            'content_data' => 'sometimes|required|string',
            'sequence_order' => 'sometimes|required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // If sequence_order is changing
        if ($request->has('sequence_order') && $request->sequence_order != $moduleContent->sequence_order) {
            // Check if new sequence_order already exists for this module
            $existingContent = ModuleContent::where('module_progress_id', $moduleContent->module_progress_id)
                ->where('sequence_order', $request->sequence_order)
                ->where('content_id', '!=', $id)
                ->first();
                
            if ($existingContent) {
                // If moving up in sequence
                if ($request->sequence_order < $moduleContent->sequence_order) {
                    ModuleContent::where('module_progress_id', $moduleContent->module_progress_id)
                        ->where('sequence_order', '>=', $request->sequence_order)
                        ->where('sequence_order', '<', $moduleContent->sequence_order)
                        ->increment('sequence_order');
                } 
                // If moving down in sequence
                else {
                    ModuleContent::where('module_progress_id', $moduleContent->module_progress_id)
                        ->where('sequence_order', '>', $moduleContent->sequence_order)
                        ->where('sequence_order', '<=', $request->sequence_order)
                        ->decrement('sequence_order');
                }
            }
        }

        $moduleContent->update($request->all());
        return response()->json(['data' => $moduleContent], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $moduleContent = ModuleContent::find($id);
        
        if (!$moduleContent) {
            return response()->json(['error' => 'Module content not found'], Response::HTTP_NOT_FOUND);
        }
        
        $moduleProgressId = $moduleContent->module_progress_id;
        $sequenceOrder = $moduleContent->sequence_order;
        
        $moduleContent->delete();
        
        // Re-order remaining content items
        ModuleContent::where('module_progress_id', $moduleProgressId)
            ->where('sequence_order', '>', $sequenceOrder)
            ->decrement('sequence_order');
            
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
