<?php
namespace App\Eve;
use Illuminate\Support\Facades\Log;

class Groups {

    public function runRules($user)
    {
        // Get rules that apply to user
        // Get users groups
        $presentGroups = $user->groups()->get();
        $rules = \App\GroupRule::all();
        $allowed = [];
        $groupsWithRules = [];
        foreach ($rules as $rule) {
            if (!in_array($rule->group_id,$groupsWithRules)) {
                $groupsWithRules[] = $rule->group_id;
            }
            switch (strtolower($rule->entity_type)) {
                case 'corporation':
                    if ($user->corporation->id == $rule->entity_id && !in_array($rule->group_id,$allowed)) {
                        $allowed[] = $rule->group_id;
                    }
                    break;
                case 'alliance':
                    if ($user->corporation->alliance && $user->corporation->alliance->id == $rule->entity_id && !in_array($rule->group_id,$allowed)) {
                        $allowed[] = $rule->group_id;
                    }
                    break;
                case 'faction':
                    if ($user->faction_id == $rule->entity_id && !in_array($rule->group_id,$allowed)) {
                        $allowed[] = $rule->group_id;
                    }
                    break;
                default:
                    break;
            }
        }
        $userGroupIds = [];
        foreach ($user->groups()->get() as $group) {
            if (!in_array($group->id,$allowed) && in_array($group->id,$groupsWithRules)) {
                $user->groups()->detach($group->id);
                Log::info("User ID:{$user->id} removed from group ID:{$group->id}");
            }
            $userGroupIds[] = $group->id;
        }
        foreach ($allowed as $groupId) {
            if (!in_array($groupId,$userGroupIds)) {
                $user->groups()->attach($groupId);
                Log::info("User ID:{$user->id} added to group ID:{$groupId}");
            }
        }

    }

}
