<?php $pageTitle = 'Yeni Talep'; $currentPage = 'create-request'; ?>

<form method="POST" action="/dashboard/requests/store" id="createRequestForm" enctype="multipart/form-data">
    <?= \Core\View::csrf() ?>
    <input type="hidden" name="stage_id" id="stageIdInput" value="">

    <div class="request-form-wrapper">
        <div class="request-form-header">
            <h5 class="mb-0 fw-700">Yeni Talep Oluştur</h5>
            <div class="total-credit-badge" id="totalCreditBadge">Toplam <span id="totalCreditValue">0.0</span> Kredi</div>
        </div>

        <div class="request-form-body">
            <div class="form-grid-row">
                <div class="form-grid-col">
                    <label class="form-label-sm">* Marka</label>
                    <select class="form-select form-select-dark" id="brand_id" name="brand_id" required>
                        <option value="">Seçin...</option>
                        <?php foreach ($brands as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= \Core\View::escape($b['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-grid-col">
                    <label class="form-label-sm">* Model</label>
                    <select class="form-select form-select-dark" id="model_id" name="model_id" required disabled>
                        <option value="">Seçin...</option>
                    </select>
                </div>
                <div class="form-grid-col">
                    <label class="form-label-sm">* Araç Generation</label>
                    <select class="form-select form-select-dark" id="generation_id" name="generation_id" disabled>
                        <option value="">Seçin...</option>
                    </select>
                </div>
                <div class="form-grid-col">
                    <label class="form-label-sm">* Araç Motor</label>
                    <select class="form-select form-select-dark" id="engine_id" name="engine_id" disabled>
                        <option value="">Seçin...</option>
                    </select>
                </div>
                <div class="form-grid-col">
                    <label class="form-label-sm">* Araç Ecu</label>
                    <select class="form-select form-select-dark" id="ecu_id" name="ecu_id" disabled>
                        <option value="">Önce motor seçin</option>
                    </select>
                </div>
            </div>

            <div class="form-grid-row mt-3">
                <div class="form-grid-col">
                    <label class="form-label-sm">* Vites Kutusu</label>
                    <select class="form-select form-select-dark" id="transmission_type_id" name="transmission_type_id">
                        <option value="">Seçin...</option>
                        <?php foreach ($transmissionTypes as $tt): ?>
                            <option value="<?= $tt['id'] ?>"><?= \Core\View::escape($tt['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-grid-col">
                    <label class="form-label-sm">* Yıl</label>
                    <div class="year-input-wrapper">
                        <select class="form-select form-select-dark" id="year" name="year">
                            <option value="">Yıl</option>
                            <?php for ($y = date('Y'); $y >= 1990; $y--): ?>
                                <option value="<?= $y ?>"><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="form-grid-col">
                    <label class="form-label-sm">Ecu Sw OR No</label>
                    <input type="text" class="form-control form-control-dark" id="ecu_sw" name="ecu_sw" placeholder="e.g. 03851989">
                </div>
                <div class="form-grid-col">
                    <label class="form-label-sm">Okuma Yöntemi</label>
                    <select class="form-select form-select-dark" id="reading_method_id" name="reading_method_id">
                        <option value="">Seçin...</option>
                        <?php foreach ($readingMethods as $rm): ?>
                            <option value="<?= $rm['id'] ?>"><?= \Core\View::escape($rm['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-grid-col">
                    <label class="form-label-sm">Araç Plaka</label>
                    <input type="text" class="form-control form-control-dark" id="plate_number" name="plate_number" placeholder="e.g. 34CEF34">
                </div>
            </div>

            <div class="stage-buttons-row mt-4" id="stageButtonsRow">
                <?php foreach ($stages as $stage): ?>
                    <?php if ($stage['slug'] === 'more-options') continue; ?>
                    <button type="button" class="stage-btn" data-stage-id="<?= $stage['id'] ?>" data-slug="<?= $stage['slug'] ?>" data-base-credit="<?= $stage['base_credit'] ?>" data-show-services="<?= $stage['show_services'] ?>">
                        <?= \Core\View::escape($stage['name']) ?> (<?= $stage['base_credit'] ?> Kredi)
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="more-options-row mt-2">
                <?php foreach ($stages as $stage): ?>
                    <?php if ($stage['slug'] !== 'more-options') continue; ?>
                    <button type="button" class="more-options-btn" id="moreOptionsBtn" data-stage-id="<?= $stage['id'] ?>" data-slug="<?= $stage['slug'] ?>" data-base-credit="<?= $stage['base_credit'] ?>" data-show-services="<?= $stage['show_services'] ?>">
                        More Options
                    </button>
                <?php endforeach; ?>
            </div>

            <div id="servicesSection" style="display:none;">
                <h6 class="services-section-title">Yapılacak İşlemler:</h6>
                <div class="services-grid" id="servicesGrid"></div>
            </div>

            <div id="originalFileNotice" style="display:none;">
                <p class="original-file-notice">Lütfen orijinal dosyanız veya teknik/ECU orijinal dosyasını bilgisini içeren metin belgesi oluşturup yükleyin.</p>
            </div>

            <h6 class="services-section-title mt-4">Dosyanız</h6>
            <div id="fileDropzone" class="dropzone dropzone-dark">
                <div class="dz-message">
                    <i class="fas fa-file-alt fa-2x mb-2 text-muted"></i>
                    <p class="dropzone-text">Drag and drop a file here or click</p>
                </div>
            </div>

            <h6 class="services-section-title mt-4">Varsa Notunuz (DTC SEÇTİYSENİZ YAZIN)</h6>
            <textarea class="form-control form-control-dark" id="customer_note" name="customer_note" rows="4" placeholder="Örneğin : Lütfen P0420 kodunu silin."></textarea>

            <div class="mt-3" id="insufficientAlert" style="display:none;">
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Yetersiz kredi bakiyesi
                </div>
            </div>

            <div class="form-submit-row mt-4">
                <div class="credit-info-inline">
                    <span>Bakiyeniz: <strong id="currentBalance"><?= $creditBalance ?> Kr</strong></span>
                    <span>Kalan: <strong id="remainingBalance"><?= $creditBalance ?> Kr</strong></span>
                </div>
                <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn" disabled>
                    <i class="fas fa-paper-plane me-2"></i>Talebi Gönder
                </button>
            </div>
        </div>
    </div>
</form>

<?php $extraJs = "<script>
    var creditBalance = " . $creditBalance . ";
    initCreateRequest();
</script>"; ?>
