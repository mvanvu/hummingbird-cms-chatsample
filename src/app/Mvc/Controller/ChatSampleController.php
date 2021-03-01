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
		Assets::add('js/emoji.js');
		Plugin::addPublicAssets('js/chat.js', 'Socket', 'ChatSample');
		$this->view
			->setVar('messages', json_decode(SocketData::getInstance(['context' => 'ChatSample'])->message ?? '{}', true) ?: [])
			->pick('chat-sample');
	}

	public function saveAction()
	{
		$data = $this->request->getPost('message');

		if (!empty($data['name']) && !empty($data['message']))
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

			$messages[] = ['name' => $data['name'], 'message' => $data['message']];
			$socketModel->assign(
				[
					'context' => 'ChatSample',
					'message' => json_encode($messages),
				]
			)->save();
		}

		return $this->response->setJsonContent('Message saved.');
	}

	public function deleteAction($index)
	{
		if ($socketModel = SocketData::getInstance(['context' => 'ChatSample']))
		{
			$messages = json_decode($socketModel->message ?? '{}', true) ?: [];
			unset($messages[$index]);
			$socketModel->assign(['message' => json_encode(array_values($messages))])->save();
		}

		return $this->response->setJsonContent('Message removed.');
	}
}