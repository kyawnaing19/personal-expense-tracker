<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddMemberRequest;
use App\Http\Requests\JoinByCodeRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct(
    private GroupService $groupService
    ) {}

    public function index(Request $request):JsonResponse
    {
        $groups = $this->groupService->getAll($request->user()->id);
        return response()->json([
            'success' => true,
            'data' => $groups,
        ]);
    }

    public function show(Request $request, string $id):JsonResponse
    {
        try {
            $group = $this->groupService->findById($id, $request->user()->id);
            return response()->json([
                'success' => true,
                'data' => $group,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function store(StoreGroupRequest $request):JsonResponse
    {
        try {
            $group = $this->groupService->create($request->validated(), $request->user()->id);
            return response()->json([
                'success' => true,
                'data' => $group,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function update(UpdateGroupRequest $request, string $id):JsonResponse
    {
        try {
            $group = $this->groupService->update($id, $request->validated(), $request->user()->id);
            return response()->json([
                'success' => true,
                'data' => $group,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function destroy(Request $request, string $id):JsonResponse
    {
        try {
            $this->groupService->delete($id, $request->user()->id);
            return response()->json([
                'success' => true,
                'message' => 'Group deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function addMember(AddMemberRequest $request, string $id):JsonResponse
    {
        try {
            $group = $this->groupService->addMember(
                $id,
                $request->validated('email'),
                $request->user()->id
                );
            return response()->json([
                'success' => true,
                'data' => $group,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function removeMember(Request $request, string $id, string $userId):JsonResponse
    {
        try {
            $result = $this->groupService->removeMember($id, $userId, $request->user()->id);
            if (!$result) {
            return response()->json([
            'success' => false,
            'message' => 'Member not found in this group or already removed.'
            ], 422);
}
            return response()->json([
                'success' => true,
                'message' => 'Member removed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function generateJoinCode(Request $request, string $id):JsonResponse
    {
        try {
            $joinCode = $this->groupService->generateJoinCode($id, $request->user()->id);
            return response()->json([
                'success' => true,
                'data' => ['join_code' => $joinCode],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function joinByCode(JoinByCodeRequest $request):JsonResponse
    {

        try {
            $group = $this->groupService->joinByCode(
                $request->validated('join_code'),
                $request->user()->id);
            return response()->json([
                'success' => true,
                'data' => $group,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }
}
