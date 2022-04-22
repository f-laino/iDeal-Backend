<?php

namespace App\Http\Controllers\Cms;

use App\Models\Document;
use App\Models\ContractualCategory;
use App\Common\Models\Activity\Logger;
use App\Common\Models\DocumentList;
use App\Http\Controllers\CmsController;
use App\Http\Requests\Cms\DocumentStoreRequest;
use Illuminate\Http\Request;

class DocumentsController extends CmsController
{
    protected static $pagination = 100;

    public function index()
    {
        $documents = Document::paginate(self::$pagination);
        return view('documents.index', compact('documents'));
    }


    public function show($id)
    {
        return redirect()->route('documents.edit', ['id' => $id]);
    }


    public function edit(Request $request, $id)
    {
        try {
            $document = Document::getWithDocumentList($id)->toArray();
            $brokers = DocumentList::$BROKERS;
            $contractualCategories = ContractualCategory::all()->toArray();

            $brokersContractualCategories = [];

            foreach ($document['document_list'] as $documentList) {
                $brokersContractualCategories[$documentList['broker']][$documentList['contractual_category_id']] = [
                    'title' => $documentList['title'],
                    'link' => $documentList['link']
                ];
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return view('documents.edit', compact('document', 'brokers', 'contractualCategories', 'brokersContractualCategories'));
    }

    public function update(DocumentStoreRequest $request, Document $document)
    {
        Logger::request('DocumentController@Update', $request);

        $oldDocument = $document->replicate();

        try {
            $fields = ['title' => $request->title];

            $uploadFile = $request->file('link', null);

            if (!empty($uploadFile)) {
                $fields['link'] = Document::uploadFile($uploadFile);
            }

            $document->update($fields);

            Logger::activity('DocumentController@Update', $request, $document, $oldDocument);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Documento aggiornato con successo');
    }

    public function create()
    {
        try {
            $brokers = DocumentList::$BROKERS;
            $contractualCategories = ContractualCategory::where('id', '>=', ContractualCategory::$DEFAULT)
                                        ->get()
                                        ->toArray()
                                        ;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return view('documents.create', compact('brokers', 'contractualCategories'));
    }

    public function store(DocumentStoreRequest $request)
    {
        Logger::request('DocumentController@Store', $request);

        try {
            $document = new Document();
            $document->title = $request->title;
            $document->type = $request->type;

            $uploadFile = $request->file('link', null);

            if (!empty($uploadFile)) {
                $document->link = Document::uploadFile($uploadFile);
            }

            $document->save();

            Logger::activity('DocumentController@Store', $request, $document);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return redirect()->route('documents.edit', ['id' => $document->id]);
    }
}
