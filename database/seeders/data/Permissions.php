<?php

declare(strict_types=1);

namespace Database\Seeders\Data;

trait Permissions
{
	private $permissions = [
		'Applications' => [
			'Browse Applications',
			'Read Applications',
			'Edit Applications',
			'Add Applications',
			'Delete Applications',
			'Restore Applications',
			'Force Delete Applications',
		],
		'Chats' => [
			'Browse Chats',
			'Read Chats',
			'Edit Chats',
			'Add Chats',
			'Delete Chats',
			'Restore Chats',
			'Force Delete Chats',
		],
		'Coupons' => [
			'Browse Coupons',
			'Read Coupons',
			'Edit Coupons',
			'Add Coupons',
			'Delete Coupons',
			'Restore Coupons',
			'Force Delete Coupons',
		],
		'Fines' => [
			'Browse Fines',
			'Read Fines',
			'Edit Fines',
			'Add Fines',
			'Delete Fines',
			'Restore Fines',
			'Force Delete Fines',
		],
		'Games' => [
			'Browse Games',
			'Read Games',
			'Edit Games',
			'Add Games',
			'Delete Games',
			'Restore Games',
			'Force Delete Games',
		],
		'Gift' => [
			'Browse Gift',
			'Read Gift',
			'Edit Gift',
			'Add Gift',
			'Delete Gift',
			'Restore Gift',
			'Force Delete Gift',
		],
		'Message' => [
			'Browse Message',
			'Read Message',
			'Edit Message',
			'Add Message',
			'Delete Message',
			'Restore Message',
			'Force Delete Message',
		],
		'Order' => [
			'Browse Order',
			'Read Order',
			'Edit Order',
			'Add Order',
			'Delete Order',
			'Restore Order',
			'Force Delete Order',
		],
		'User' => [
			'Browse User',
			'Read User',
			'Edit User',
			'Add User',
			'Delete User',
			'Restore User',
			'Force Delete User',
		],
	];
}
