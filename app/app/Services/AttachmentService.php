<?php

namespace App\Services;

use App\Attachment;
use App\Customer;
use App\Services\Files\FileNameService;
use App\Services\Storages\FileManagerService;
use SplFileInfo;

class AttachmentService
{
    /** @var FileManagerService $fileManager */
    private $fileManager;

    /** @var FileNameService $fileNameService */
    private $fileNameService;

    public function __construct(
        FileManagerService $fileManager,
        FileNameService $fileNameService
    )
    {
        $this->fileManager = $fileManager;
        $this->fileNameService = $fileNameService;
    }


    /**
     * @param Customer $customer
     * @param SplFileInfo $file
     * @param string $fileType
     * @return Attachment
     * @throws \Throwable
     */
    public function addCustomerAttachment(
        Customer $customer, SplFileInfo $file,
        string $fileType
    ): Attachment
    {
        $fileExtension = $file->getExtension();
        $offset = $this->getAttachmentsNumber($customer, $fileType);

        $fileName = $this->fileNameService->getName($fileExtension, $offset);
        $fileDirectory = $this->fileNameService->getDirectory($customer->id, $fileType);

        $storagePath = $fileDirectory . $fileName;

        //send file to storage
        $s = $this->fileManager->putObject($file, $storagePath);
dd($s);
        return $this->storeCustomerAttachment($customer, $fileType, $fileType, $storagePath);
    }


    /**
     * @param Customer $customer
     * @param string $fileType
     * @param string $fileName
     * @param string $path
     * @return Attachment
     * @throws \Throwable
     */
    public function storeCustomerAttachment(
        Customer $customer, string $fileType,
        string $fileName, string $path
    ): Attachment
    {
        $attachment = new Attachment;
        $attachment->type = Attachment::TYPE_CUSTOMER;
        $attachment->entity_id = $customer->id;
        $attachment->path = $path;
        $attachment->name = $fileName;
        $attachment->description = $fileType;
        $attachment->saveOrFail();

        return $attachment;
    }

    /**
     * @param Customer $customer
     * @param string $fileType
     * @return int
     */
    public function getAttachmentsNumber(Customer $customer, string $fileType): int
    {
        return Attachment::where('entity_id', $customer->id)
                ->where('type', $fileType)
                ->count();
    }

}
