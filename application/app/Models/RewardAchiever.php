<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardAchiever extends Model
{
	protected $table = 'reward_achiever';

	protected $primaryKey = 'id';

	protected $fillable = [
		'member_id',
		'reward_id',
		'directs',
		'team_members',
		'self_business',
		'team_business',
		'leg1_business',
		'leg2_business',
		'leg3_business',
		'weekly_salary',
		'achieve_date',
	];

	public function member()
	{
		return $this->belongsTo(User::class, 'member_id');
	}
}
