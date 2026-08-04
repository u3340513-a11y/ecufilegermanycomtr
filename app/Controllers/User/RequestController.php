<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Core\Controller;
use Core\Request;
use Core\Database;
use App\Services\RequestService;
use App\Repositories\MessageRepository;
use App\Repositories\FileRepository;
use App\Helpers\FileUploader;

final class RequestController extends Controller
{
    private RequestService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new RequestService();
    }

    public function index(Request $request): void
    {
        $page = (int) ($request->get('page') ?: 1);
        $result = $this->service->getUserRequests($this->userId(), $page);

        $this->view('user/requests/index', [
            'pageTitle'   => 'Taleplerim',
            'currentPage' => 'requests',
            'requests'    => $result['data'],
            'total'       => $result['total'],
            'page'        => $result['page'],
            'totalPages'  => $result['totalPages'],
        ]);
    }

    public function create(Request $request): void
    {
        $db = Database::getInstance();
        $brands = $db->fetchAll('SELECT * FROM brands WHERE is_active = 1 ORDER BY sort_order ASC, name ASC');
        $readingMethods = $db->fetchAll('SELECT * FROM reading_methods WHERE is_active = 1 ORDER BY name ASC');
        $transmissionTypes = $db->fetchAll('SELECT * FROM transmission_types WHERE is_active = 1 ORDER BY sort_order ASC');
        $stages = $db->fetchAll('SELECT * FROM stages WHERE is_active = 1 ORDER BY sort_order ASC');
        $creditBalance = $db->fetch('SELECT credit_balance FROM users WHERE id = ?', [$this->userId()])['credit_balance'] ?? 0;

        $this->view('user/requests/create', [
            'pageTitle'          => 'Yeni Talep',
            'currentPage'        => 'create-request',
            'brands'             => $brands,
            'readingMethods'     => $readingMethods,
            'transmissionTypes'  => $transmissionTypes,
            'stages'             => $stages,
            'creditBalance'      => $creditBalance,
        ]);
    }

    public function store(Request $request): void
    {
        // Çoklu stage desteği: stage_ids[] array olarak gelir
        $stageIds = $request->post('stage_ids');
        if (!is_array($stageIds) || empty($stageIds)) {
            $this->withError('Lütfen en az bir işlem tipi (stage) seçin.', '/dashboard/requests/create');
        }

        // Tamsayıya çevir ve geçersiz değerleri temizle
        $stageIds = array_values(array_filter(array_map('intval', $stageIds)));
        if (empty($stageIds)) {
            $this->withError('Lütfen en az bir işlem tipi (stage) seçin.', '/dashboard/requests/create');
        }

        // Birincil stage: ilk seçilen (en küçük ID sıralamasıyla tutarlı)
        $primaryStageId = $stageIds[0];
        $extraStageIds  = array_slice($stageIds, 1);

        $serviceIds = $request->post('services');
        if (!is_array($serviceIds)) {
            $serviceIds = [];
        }

        try {
            $requestId = $this->service->createMultiStage(
                $this->userId(),
                $request->only([
                    'brand_id', 'model_id', 'generation_id', 'engine_id',
                    'ecu_id', 'transmission_type_id', 'year', 'ecu_sw', 'ecu_hw',
                    'reading_method_id', 'plate_number', 'customer_note'
                ]),
                $serviceIds,
                $primaryStageId,
                $extraStageIds
            );

            $uploadedFiles = \Core\Session::get('uploaded_files', []);
            if (!empty($uploadedFiles)) {
                \Core\Session::remove('uploaded_files');
                $fileRepo = new \App\Repositories\FileRepository();
                foreach ($uploadedFiles as $file) {
                    $fileRepo->create([
                        'request_id'    => $requestId,
                        'user_id'       => $this->userId(),
                        'filename'      => $file['filename'],
                        'original_name' => $file['original_name'],
                        'path'          => $file['path'],
                        'size'          => $file['size'],
                        'mime_type'     => $file['mime_type'],
                        'type'          => 'original',
                        'version'       => 1,
                    ]);
                }
            }

            $this->withSuccess('Talep başarıyla oluşturuldu.', '/dashboard/requests/' . $requestId);
        } catch (\RuntimeException $e) {
            $this->withError($e->getMessage(), '/dashboard/requests/create');
        }
    }

    public function show(Request $request, string $id): void
    {
        $detail = $this->service->getRequestDetail((int) $id);
        if (!$detail || (int) $detail['user_id'] !== $this->userId()) {
            $this->redirect('/dashboard/requests');
        }

        $msgRepo = new MessageRepository();
        $messages = $msgRepo->getByRequest((int) $id);
        $msgRepo->markAsRead((int) $id, $this->userId());

        $this->view('user/requests/show', [
            'pageTitle'   => 'Talep #' . $detail['ticket_no'],
            'currentPage' => 'requests',
            'req'         => $detail,
            'messages'    => $messages,
        ]);
    }

    public function uploadRevision(Request $request, string $id): void
    {
        $detail = $this->service->getRequestDetail((int) $id);
        if (!$detail || (int) $detail['user_id'] !== $this->userId()) {
            $this->withError('Talep bulunamadı.', '/dashboard/requests');
        }

        if (in_array($detail['status'], ['completed', 'cancelled'], true)) {
            $this->withError('Bu talep için dosya yüklenemez.', '/dashboard/requests/' . $id);
        }

        if (!$request->hasFile('revision_file')) {
            $this->withError('Lütfen bir dosya seçin.', '/dashboard/requests/' . $id);
        }

        $uploader = new FileUploader('requests');

        try {
            $file = $uploader->upload($request->file('revision_file'));
            $fileRepo = new FileRepository();
            $version = $fileRepo->getNextVersion((int) $id, 'revision');

            $fileRepo->create([
                'request_id'    => (int) $id,
                'user_id'       => $this->userId(),
                'filename'      => $file['filename'],
                'original_name' => $file['original_name'],
                'path'          => $file['path'],
                'size'          => $file['size'],
                'mime_type'     => $file['mime_type'],
                'type'          => 'revision',
                'version'       => $version,
            ]);

            $this->withSuccess('Dosya başarıyla yüklendi.', '/dashboard/requests/' . $id);
        } catch (\RuntimeException $e) {
            $this->withError($e->getMessage(), '/dashboard/requests/' . $id);
        }
    }
}
