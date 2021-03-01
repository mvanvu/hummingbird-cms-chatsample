<?php

namespace App\Plugin\Socket;

use App\Factory\WebApplication;
use App\Helper\Uri;
use App\Plugin\SocketPlugin;
use Swoole\WebSocket\Server;

class ChatSample extends SocketPlugin
{
	public function onBootApplication($app)
	{
		if ($app instanceof WebApplication)
		{
			$router = $app->getDI()->getShared('router');
			$router->add(Uri::route('chat-sample/index'), ['controller' => 'ChatSample', 'action' => 'index']);
			$router->addPost(Uri::route('chat-sample/save'), ['controller' => 'ChatSample', 'action' => 'save']);
			$router->addDelete(Uri::route('chat-sample/delete/:int'), ['controller' => 'ChatSample', 'action' => 'delete', 'index' => 1]);
		}
	}

	public function onMessage(Server $server, int $fd)
	{
		$server->push($fd, $this->data->toString());
	}
}