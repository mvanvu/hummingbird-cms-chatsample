<?php

namespace App\Mvc\Controller;

use App\Helper\Assets;
use App\Helper\Date;
use App\Mvc\Model\SocketData;
use App\Plugin\Plugin;

class ChatSampleController extends ControllerBase
{
	public function indexAction()
	{
		Assets::inlineCss(<<<CSS
@keyframes dotsWidth {
	from {width: 1px}
	to {width: 20px}
}
span.dots {
	display: inline-block;
	transition: .8s all ease;
	animation: dotsWidth 1s infinite;
	overflow: hidden;
	vertical-align: bottom;
}
CSS
		);
		Assets::add('js/emoji.js');
		Assets::addFromPlugin('js/chat.js', 'Socket', 'ChatSample');
		$this->view
			->setVar('messages', json_decode(SocketData::getInstance(['context' => 'ChatSample'])->message ?? '{}', true) ?: [])
			->pick('chat-sample');
	}

	public function saveAction()
	{
		$data = $this->request->getPost('message');

		if (!empty($data['name'])
			&& !empty($data['message'])
			&& !empty($data['time'])
		)
		{
			$socketModel = SocketData::getInstance(['context' => 'ChatSample']);
			$messages    = [];

			if ($socketModel->id)
			{
				$messages = json_decode($socketModel->message ?? '{}', true) ?: [];
			}
			else
			{
				$socketModel->assign(
					[
						'createdAt' => Date::now('UTC')->toSql(),
					]
				);
			}

			$messages[$data['time']] = ['name' => $data['name'], 'message' => $data['message']];
			$socketModel->assign(
				[
					'context' => 'ChatSample',
					'message' => json_encode($messages),
				]
			)->save();
		}

		return $this->response->setJsonContent('Message saved.');
	}

	public function deleteAction($time)
	{
		if ($socketModel = SocketData::getInstance(['context' => 'ChatSample']))
		{
			$messages = json_decode($socketModel->message ?? '{}', true) ?: [];
			unset($messages[$time]);
			$socketModel->assign(['message' => json_encode($messages)])->save();
		}

		return $this->response->setJsonContent('Message removed.');
	}
}