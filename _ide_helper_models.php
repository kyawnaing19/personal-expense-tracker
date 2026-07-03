<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string|null $category_id
 * @property int $amount
 * @property int $month
 * @property int $year
 * @property int $alert_percentage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\BudgetFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereAlertPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Budget whereYear($value)
 */
	class Budget extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string|null $user_id
 * @property string $name
 * @property string $type
 * @property string|null $icon
 * @property string|null $color
 * @property int $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUserId($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $group_expense_id
 * @property string $user_id
 * @property int $amount_owed
 * @property int $amount_paid
 * @property bool $is_settled
 * @property string|null $settled_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GroupExpense $groupExpense
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettlementRequest> $settlementRequests
 * @property-read int|null $settlement_requests_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit whereAmountOwed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit whereGroupExpenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit whereIsSettled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit whereSettledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSplit whereUserId($value)
 */
	class ExpenseSplit extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string $created_by
 * @property string|null $join_code
 * @property string|null $join_code_expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupUser> $groupUsers
 * @property-read int|null $group_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereJoinCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereJoinCodeExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereUpdatedAt($value)
 */
	class Group extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $group_id
 * @property string $paid_by
 * @property string|null $category_id
 * @property int $amount
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $expense_date
 * @property string $split_type
 * @property bool $include_payer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Group $group
 * @property-read \App\Models\User $payer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseSplit> $splits
 * @property-read int|null $splits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereExpenseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereIncludePayer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense wherePaidBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereSplitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupExpense whereUpdatedAt($value)
 */
	class GroupExpense extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $group_id
 * @property string $user_id
 * @property string $role
 * @property string $joined_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group $group
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser whereJoinedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupUser whereUserId($value)
 */
	class GroupUser extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $category_id
 * @property string $type
 * @property int $amount
 * @property string|null $note
 * @property string $start_date
 * @property string|null $end_date
 * @property string $frequency
 * @property string $next_run_date
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereNextRunDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecurringTransaction whereUserId($value)
 */
	class RecurringTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $expense_split_id
 * @property string $claimed_by
 * @property int $amount
 * @property string $status
 * @property string|null $confirmed_by
 * @property string|null $confirmed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $claimant
 * @property-read \App\Models\User|null $comfirmer
 * @property-read \App\Models\ExpenseSplit $expenseSplit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest whereClaimedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest whereConfirmedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest whereExpenseSplitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettlementRequest whereUpdatedAt($value)
 */
	class SettlementRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $category_id
 * @property string $type
 * @property int $amount
 * @property string|null $note
 * @property string $transaction_date
 * @property string|null $receipt_path
 * @property string|null $recurring_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \App\Models\RecurringTransaction|null $recurringTransaction
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\TransactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereReceiptPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereRecurringId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUserId($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $password
 * @property string|null $google_id
 * @property string|null $avatar
 * @property string|null $fcm_token
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

