<?php
namespace App\Repositories;

use App\Models\Group;
use App\Models\GroupUser;

class GroupRepository
{
    public function getAllByUser(string $userId)
    {
        return Group::whereHas('groupUsers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
    }

    public function findById(string $id)
    {
        return Group::with('members')->find($id);
    }

    public function findByJoinCode(string $code): ?Group
    {
        return Group::where('join_code', $code)->first();
    }

    public function create(array $data): Group
    {
        return Group::create($data);
    }

    public function update(Group $group, array $data): Group
    {
        $group->update($data);
        return $group;
    }

    public function delete(Group $group): bool
    {
        return $group->delete();
    }

    public function addMember(string $groupId, string $userId, string $role='member'): GroupUser
    {
        return GroupUser::create([
            'group_id' => $groupId,
            'user_id' => $userId,
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    public function removeMember(string $groupId, string $userId): bool
    {
        return GroupUser::where('group_id', $groupId)
                        ->where('user_id', $userId)
                        ->delete() > 0;
    }

    public function findMembership(string $groupId, string $userId): ?GroupUser
    {
        return GroupUser::where('group_id', $groupId)
                         ->where('user_id', $userId)
                         ->first();
    }

    public function isMember(string $groupId, string $userId): bool
    {
        return $this->findMembership($groupId, $userId) !== null;
    }

    public function isAdmin(string $groupId, string $userId): bool
    {
        $membership = $this->findMembership($groupId, $userId);
        return $membership && $membership->role === 'admin';
    }
}
