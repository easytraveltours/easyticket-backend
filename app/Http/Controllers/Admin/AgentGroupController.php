<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgentGroupResource;
use App\Models\AgentGroup;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgentGroupController extends Controller
{
    /**
     * List all agent pricing tiers / groups.
     */
    public function index(): JsonResponse
    {
        $groups = AgentGroup::withCount('users')->orderBy('discount_percent', 'desc')->get();

        return response()->json([
            'data' => AgentGroupResource::collection($groups),
        ]);
    }

    /**
     * Store new agent tier.
     */
    public function store(Request $request): JsonResponse
    {
        $admin = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:agent_groups,name'],
            'discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'credit_limit' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $group = AgentGroup::create($validated);

        ActivityLogService::log(
            $admin,
            'AGENT_GROUP_CREATED',
            'GROUP',
            (string) $group->id,
            "Created pricing tier {$group->name} ({$group->discount_percent}% discount)"
        );

        return response()->json([
            'message' => 'Agent pricing tier created.',
            'data' => new AgentGroupResource($group),
        ], 201);
    }

    /**
     * Update agent group tier.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $admin = $request->user();
        $group = AgentGroup::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:100', 'unique:agent_groups,name,' . $id],
            'discount_percent' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'credit_limit' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $group->update($validated);

        ActivityLogService::log(
            $admin,
            'AGENT_GROUP_UPDATED',
            'GROUP',
            (string) $group->id,
            "Updated pricing tier {$group->name}"
        );

        return response()->json([
            'message' => 'Agent pricing tier updated.',
            'data' => new AgentGroupResource($group),
        ]);
    }
}
