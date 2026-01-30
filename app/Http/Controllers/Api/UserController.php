<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateUserRequest;
use App\Http\Resources\Api\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\PaginationTrait;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ResponseTrait, PaginationTrait;

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                $user = $this->userRepository->create($request->validated());

                return $this->successData(new UserResource($user));
            });
        } catch (\Exception $e) {
            return $this->failMsg(__('apis.failed_to_create_user'));
        }
    }
    public function index(): JsonResponse
    {
        try {
            $perPage = request()->get('per_page', $this->paginateNum());
            $users = $this->userRepository->paginate($perPage);
            return $this->response('success', trans('apis.success'), UserResource::collection($users), $this->paginationModel($users));
        } catch (\Exception $e) {
            return $this->failMsg(__('apis.failed_to_get_users'));
        }
    }
}
