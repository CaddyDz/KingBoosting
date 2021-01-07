<?php

namespace Sitando\NovaChat;

use Laravel\Nova\ResourceTool;

class NovaChat extends ResourceTool
{
	/**
	 * Get the displayable name of the resource tool.
	 *
	 * @return string
	 */
	public function name()
	{
		return 'Nova Chat';
	}

	/**
	 * Get the component name for the resource tool.
	 *
	 * @return string
	 */
	public function component()
	{
		return 'nova-chat';
	}

	public function order($order)
	{
		return $this->withMeta(['order' => $order]);
	}

	public function auth_id(int $auth_id)
	{
		return $this->withMeta(['auth_id' => $auth_id]);
	}
}
