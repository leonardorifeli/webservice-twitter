<?php

namespace ApiBundle\Business\Service;

use MessageBundle\Business\DependencyInjection\MessageServiceInterface;

class ApiMessageService
{
	
	private $messageService;

	public function __construct(MessageServiceInterface $messageService)
	{
		$this->messageService = $messageService;
	}

	private function getMessageService()
	{
		return $this->messageService;
	}

	public function create($data, $accessToken)
	{
		$data = $this->decodeData($data);
		if(!$data) throw new \Exception("The data is empty.", 1);
		
		$data->token = $accessToken;
		
		$message = $this->getMessageService()->create($data);
		if(!$message) throw new \Exception("The message not sent.", 1);
		
		return $message;
	}

	public function delete($id)
	{
		$message = $this->getMessageService()->delete($id);
		if(!$message) throw new \Exception("The message not delete.", 1);
		
		return $message;
	}

	public function listMessage($page, $limit)
	{
		$entities = $this->getMessageService()->listMessage($page, $limit);

		$data = [];
		foreach($entities as $entity) {
			$response = [
				'id' => $entity->getId(),
				'message' => $entity->getMessage(),
				'createdAt' => $entity->getCreatedAt()->format('d/m/Y H:i:s'),
				'updatedAt' => $entity->getUpdatedAt()->format('d/m/Y H:i:s'),
			];

			$data[] = $response;
		}

		return $data;
	}

	private function decodeData($data)
	{
		return json_decode($data);
	}

}