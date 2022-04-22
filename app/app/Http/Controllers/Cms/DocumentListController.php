<?php

namespace App\Http\Controllers\Cms;

use App\Models\Document;
use App\Models\ContractualCategory;
use App\Common\Models\Activity\Logger;
use App\Common\Models\DocumentList;
use App\Http\Controllers\CmsController;
use Illuminate\Http\Request;

class DocumentListController extends CmsController
{
    protected static $pagination = 100;

    public function index()
    {
        $brokers = DocumentList::$BROKERS;
        return view('document-list.index', compact('brokers'));
    }


    public function show($id)
    {
        return redirect()->route('document-list.edit', ['id' => $id]);
    }


    public function edit(Request $request, $broker)
    {
        try {
            $brokerLabel = DocumentList::$BROKERS[$broker];
            $documents = Document::all();
            $brokerDocumentList = DocumentList::getByBrokerGroupedByContractualCategory($broker);
            $contractualCategories = ContractualCategory::all();
            $brokerDocuments = [];

            foreach ($contractualCategories as $contractualCategory) {
                foreach ($documents as $document) {
                    $newDocument = clone $document;
                    $newDocument->enabled = false;
                    $newDocument->custom_title = null;
                    $newDocument->custom_link = null;
                    $brokerDocument = null;

                    if(array_key_exists($contractualCategory->id, $brokerDocumentList)){
                        $brokerDocument = $brokerDocumentList[$contractualCategory->id]
                            ->where('document_id', $newDocument->id)->first();
                    }

                    if (!empty($brokerDocument)) {
                        $newDocument->enabled = true;
                        $newDocument->custom_title = $brokerDocument->title;
                        $newDocument->custom_link = $brokerDocument->link;
                    }

                    $brokerDocuments[$contractualCategory->id][] = $newDocument;
                }
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return view('document-list.edit', compact('brokerDocumentList', 'contractualCategories', 'broker', 'brokerLabel', 'brokerDocuments'));
    }

    public function update(Request $request, $broker)
    {
        Logger::request('DocumentList@Update', $request);

        try {
            $uploadedFiles = $request->file('links', []);

            foreach ($request->enabled as $contractualCategoryId => $documents) {
                foreach ($documents as $documentId => $enabled) {
                    if (filter_var($enabled, FILTER_VALIDATE_BOOLEAN)) {
                        $updateData = [ 'title' => $request->titles[$contractualCategoryId][$documentId] ];

                        $uploadFile = $uploadedFiles[$contractualCategoryId][$documentId] ?? null;

                        if (!empty($uploadFile)) {
                            $updateData['link'] = Document::uploadFile($uploadFile);
                        }

                        DocumentList::updateOrCreate(
                            [
                                'contractual_category_id' => $contractualCategoryId,
                                'broker' => $broker,
                                'document_id' => $documentId
                            ],
                            $updateData
                        );
                    } else {
                        DocumentList::where('contractual_category_id', $contractualCategoryId)
                                    ->where('broker', $broker)
                                    ->where('document_id', $documentId)
                                    ->delete();
                    }
                }
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Lista documenti aggiornata con successo');
    }
}
