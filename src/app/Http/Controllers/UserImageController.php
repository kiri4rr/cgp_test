<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\ImageService;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserImageController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly ImageService $imageService
    ) {
        $this->base_main_view = 'welcome';
    }

    public function create(CreateUserRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = $this->userService->firstOrCreateUser(
                $request->only(['name', 'city'])
            );

            if ($request->hasFile('image')) {
                $this->imageService->processAndAttachImage(
                    $user,
                    $request->file('image')
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'User and images created successfully',
                'data' => new UserResource($user->load('user_images')),
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to create user with image', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except('image'),
            ]);

            return response()->json([
                'message' => 'Failed to create user and images',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUsersData(): JsonResponse
    {
        ini_set('memory_limit', '256M');
        $users = $this->userService->getUsersOrderedByImagesCount();

        return response()->json([
            'status' => true,
            'data' => UserResource::collection($users),
        ]);
    }
}