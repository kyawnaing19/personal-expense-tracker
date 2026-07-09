<?php
namespace App\Services;

use App\Http\Resources\GroupResource;
use App\Repositories\GroupRepository;
use App\Repositories\UserRepositories;
use Illuminate\Support\Facades\DB;

class GroupService
{
    public function __construct(
        private GroupRepository $groupRepository,
        private UserRepositories $userRepository
    )
    {}

    public function getAll(string $userId)
    {
        return $this->groupRepository->getAllByUser($userId);
    }

    public function findById(string $id, string $userId)
    {
        if(!$this->groupRepository->isMember($id, $userId))
        {
            throw new \InvalidArgumentException('You are not a member of this group', 403);
        }

        $group = $this->groupRepository->findById($id);
            return new GroupResource($group);
    }

    public function create(array $data, string $userId)
    {
        return DB::transaction(function () use ($data, $userId){
            $data['created_by'] = $userId;
            $group = $this->groupRepository->create($data);
            $this->groupRepository->addMember($group->id, $userId, 'admin');
            return $group;
        });
    }

    public function update(string $id, array $data, string $userId)
    {
        $group = $this->groupRepository->findById($id);
        if(!$group)
        {
            throw new \InvalidArgumentException('Group not found', 404);
        }

        if(!$this->groupRepository->isAdmin($id, $userId))
        {
            throw new \InvalidArgumentException('You are not an admin of this group', 403);
        }

        return $this->groupRepository->update($group, $data);
    }

    public function delete(string $id, string $userId)
    {
        $group = $this->groupRepository->findById($id);
        if(!$group)
        {
            throw new \InvalidArgumentException('Group not found', 404);
        }

        if(!$this->groupRepository->isAdmin($id, $userId))
        {
            throw new \InvalidArgumentException('You are not an admin of this group', 403);
        }

        return $this->groupRepository->delete($group);
    }
    //add member to group with email directly by admin
    public function addMember(string $groupId, string $email, string $requesterdId)
    {
        if(!$this->groupRepository->isAdmin($groupId, $requesterdId))
        {
            throw new \InvalidArgumentException('You are not an admin of this group', 403);
        }
        $user= $this->userRepository->findByEmail($email);
        if(!$user)
        {
            throw new \InvalidArgumentException('User with this email not found', 404);
        }

        if($this->groupRepository->isMember($groupId, $user->id))
        {
            throw new \InvalidArgumentException('User is already a member of this group', 400);
        }

        return $this->groupRepository->addMember($groupId, $user->id,'member');
    }

    public function removeMember(string $groupId, string $targetUserId, string $requesterdId)
    {
        if(!$this->groupRepository->isAdmin($groupId, $requesterdId))
        {
            throw new \InvalidArgumentException('Only admin can remove members', 403);
        }

        $group = $this->groupRepository->findById($groupId);
        if($targetUserId==$group->created_by)
        {
            throw new \InvalidArgumentException('Cannot remove the group creator', 400);
        }
        return $this->groupRepository->removeMember($groupId, $targetUserId);
    }

    public function generateJoinCode(string $groupId, string $requesterdId):array
    {
        if(!$this->groupRepository->isAdmin($groupId, $requesterdId))
        {
            throw new \InvalidArgumentException('Only admin can generate join code', 403);
        }
        $group= $this->groupRepository->findById($groupId);
        if(!$group)
        {
            throw new \Exception('Group not found', 404);
        }
        $code= (string) random_int(100000, 999999);
        $expireAt= now()->addMinutes(2);
        $this->groupRepository->update(
            $group, ['join_code'=>$code, 'join_code_expires_at'=>$expireAt]);
        return ['join_code'=>$code, 'expires_at'=>$expireAt];
    }

    public function joinByCode(string $code, string $userId)
    {
        $group = $this->groupRepository->findByJoinCode($code);
        if(!$group)
        {
            throw new \InvalidArgumentException('Invalid join code', 400);
        }

        if(!$group->join_code_expires_at || now()->greaterThan($group->join_code_expires_at))
        {
            throw new \InvalidArgumentException('Join code has expired', 400);
        }

        if($this->groupRepository->isMember($group->id, $userId))
        {
            throw new \InvalidArgumentException('You are already a member of this group', 400);
        }

        return $this->groupRepository->addMember($group->id, $userId,'member');
    }
}
