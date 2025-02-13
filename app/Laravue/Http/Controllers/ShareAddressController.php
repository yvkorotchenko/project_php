<?php

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\ShareAddressServices;
use App\Laravue\Services\StaticStorageService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ShareAddressController extends Controller
{
    public function __construct(
        private ShareAddressServices $shareAddress,
        private ResponseFactoryService $responseFactory,
        private StaticStorageService $staticStorageService,
    ) {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {
            $result = $this->shareAddress->get($id);
            $answer = (!empty($result->data)) ?
                ['error' => '', 'message' => 'I got Share Address', 'data' => $result->data] :
                ['error' => 'Error, not received Share Address'];
            return json_encode($answer);
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = [
            "id"             => $request->id,
            "landPageUrl"    => $request->landPageUrl,
            "qrCodeUrl"      => $request->qrCodeUrl,
            "fbContent"      => $request->fbContent,
            "shareUrl"       => $request->shareUrl,
            "shareQrCodeUrl" => $request->shareQrCodeUrl,
        ];
        try {
            $result = $this->shareAddress->update($data);
            $answer = (!empty($result->data)) ?
                ['error' => '', 'message' => 'Recharge sent for confirmation'] :
                ['error' => 'Error, Recharge not sent'];
            return json_encode($answer);
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function generateQrCode(Request $request)
    {
        $url = $request->get('url');
        $imageExtension = 'png';
        if (null === $url) {
            return $this->responseFactory->error('Url field can not be empty');
        }

        try {
            $fileContent = QrCode::encoding('UTF-8')
                ->size(300)
                ->format($imageExtension)
                ->generate($url);
            $url = $this->staticStorageService->uploadFileContent($fileContent, $imageExtension);
            return $this->responseFactory->json(['url' => $url]);
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
