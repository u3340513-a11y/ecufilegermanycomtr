<?php $pageTitle = 'Yeni Talep'; $currentPage = 'create-request'; ?>

<form method="POST" action="/dashboard/requests/store" id="createRequestForm" enctype="multipart/form-data">
    <?= \Core\View::csrf() ?>
    <!-- Çoklu stage seçimi: her seçili stage için ayrı hidden input oluşturulur JS ile -->
    <div id="stageIdsContainer"></div>

    <div class="request-form-wrapper">

        <!-- ═══ HEADER ═══ -->
        <div class="request-form-header">
            <div class="rfh-left">
                <div class="rfh-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-700">Yeni Talep Oluştur</h5>
                    <span class="rfh-subtitle">Araç bilgilerini eksiksiz doldurun</span>
                </div>
            </div>
            <div class="total-credit-badge" id="totalCreditBadge">
                <i class="fas fa-coins"></i>
                Toplam <span id="totalCreditValue">0.0</span> Kredi
            </div>
        </div>

        <div class="request-form-body">

            <!-- ═══ SECTION 1: Araç Bilgileri ═══ -->
            <div class="form-section-card">
                <div class="form-section-card-header">
                    <i class="fas fa-car"></i>
                    <span>Araç Bilgileri</span>
                </div>
                <div class="form-section-card-body">
                    <div class="form-grid-row">
                        <div class="form-field-group">
                            <label class="form-label-sm">* Marka</label>
                            <select class="form-select form-select-dark" id="brand_id" name="brand_id" required>
                                <option value="">Seçin...</option>
                                <?php foreach ($brands as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= \Core\View::escape($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field-group">
                            <label class="form-label-sm">* Model</label>
                            <select class="form-select form-select-dark" id="model_id" name="model_id" required disabled>
                                <option value="">Seçin...</option>
                            </select>
                        </div>
                        <div class="form-field-group">
                            <label class="form-label-sm">* Araç Generation</label>
                            <select class="form-select form-select-dark" id="generation_id" name="generation_id" disabled>
                                <option value="">Seçin...</option>
                            </select>
                        </div>
                        <div class="form-field-group">
                            <label class="form-label-sm">* Araç Motor</label>
                            <select class="form-select form-select-dark" id="engine_id" name="engine_id" disabled>
                                <option value="">Seçin...</option>
                            </select>
                        </div>
                        <div class="form-field-group">
                            <label class="form-label-sm">* Araç Ecu</label>
                            <select class="form-select form-select-dark" id="ecu_id" name="ecu_id" disabled>
                                <option value="">Önce motor seçin</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid-row form-grid-row--mt">
                        <div class="form-field-group">
                            <label class="form-label-sm">* Vites Kutusu</label>
                            <select class="form-select form-select-dark" id="transmission_type_id" name="transmission_type_id">
                                <option value="">Seçin...</option>
                                <?php foreach ($transmissionTypes as $tt): ?>
                                    <option value="<?= $tt['id'] ?>"><?= \Core\View::escape($tt['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field-group">
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
                        <div class="form-field-group">
                            <label class="form-label-sm">Ecu Sw OR No</label>
                            <input type="text" class="form-control form-control-dark" id="ecu_sw" name="ecu_sw" placeholder="e.g. 03851989">
                        </div>
                        <div class="form-field-group">
                            <label class="form-label-sm">Okuma Yöntemi</label>
                            <select class="form-select form-select-dark" id="reading_method_id" name="reading_method_id">
                                <option value="">Seçin...</option>
                                <?php foreach ($readingMethods as $rm): ?>
                                    <option value="<?= $rm['id'] ?>"><?= \Core\View::escape($rm['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field-group">
                            <label class="form-label-sm">Araç Plaka</label>
                            <input type="text" class="form-control form-control-dark" id="plate_number" name="plate_number" placeholder="e.g. 34CEF34">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ SECTION 2: Servis Seçimi ═══ -->
            <div class="stage-selection-area">
                <div class="stage-selection-label">
                    <i class="fas fa-sliders-h"></i>
                    <span>Servis Türünü Seçin</span>
                </div>
                <div class="stage-buttons-row" id="stageButtonsRow">
                    <?php foreach ($stages as $stage): ?>
                        <?php if ($stage['slug'] === 'more-options') continue; ?>
                        <button
                            type="button"
                            class="stage-btn"
                            data-stage-id="<?= $stage['id'] ?>"
                            data-slug="<?= $stage['slug'] ?>"
                            data-base-credit="<?= $stage['base_credit'] ?>"
                            data-show-services="<?= $stage['show_services'] ?>"
                        >
                            <span class="stage-btn-name"><?= \Core\View::escape($stage['name']) ?></span>
                            <span class="stage-btn-credit"><?= $stage['base_credit'] ?></span>
                            <span class="stage-btn-unit">Kredi</span>
                        </button>
                    <?php endforeach; ?>
                </div>
                <div class="more-options-row">
                    <?php foreach ($stages as $stage): ?>
                        <?php if ($stage['slug'] !== 'more-options') continue; ?>
                        <button
                            type="button"
                            class="more-options-btn"
                            id="moreOptionsBtn"
                            data-stage-id="<?= $stage['id'] ?>"
                            data-slug="<?= $stage['slug'] ?>"
                            data-base-credit="<?= $stage['base_credit'] ?>"
                            data-show-services="<?= $stage['show_services'] ?>"
                        >
                            <i class="fas fa-cog"></i>
                            More Options
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ═══ Services Grid (hidden by default) ═══ -->
            <div id="servicesSection" style="display:none;">
                <!-- Seçili stage'lerin özet gösterimi -->
                <div id="selectedStageSummary" style="display:none;"></div>
                <h6 class="services-section-title">Ek İşlemler (İsteğe Bağlı):</h6>
                <div class="services-grid" id="servicesGrid"></div>
            </div>

            <!-- ═══ Original File Notice (hidden by default) ═══ -->
            <div id="originalFileNotice" style="display:none;">
                <p class="original-file-notice">Lütfen orijinal dosyanız veya teknik/ECU orijinal dosyasını bilgisini içeren metin belgesi oluşturup yükleyin.</p>
            </div>

            <!-- ═══ SECTION 3: Dosya Yükleme ═══ -->
            <div class="upload-section">
                <div class="upload-section-header">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Dosyanız</span>
                </div>
                <div id="fileDropzone" class="dropzone dropzone-dark">
                    <div class="dz-message">
                        <i class="fas fa-file-alt fa-2x mb-2 text-muted"></i>
                        <p class="dropzone-text">Drag and drop a file here or click</p>
                    </div>
                </div>
            </div>

            <!-- ═══ SECTION 4: Notlar ═══ -->
            <div class="notes-section">
                <div class="notes-section-header">
                    <i class="fas fa-sticky-note"></i>
                    <span>Varsa Notunuz (DTC SEÇTİYSENİZ YAZIN)</span>
                </div>
                <textarea class="form-control form-control-dark" id="customer_note" name="customer_note" rows="4" placeholder="Örneğin : Lütfen P0420 kodunu silin."></textarea>
            </div>

            <!-- ═══ Yetersiz Kredi Uyarısı ═══ -->
            <div class="mt-3" id="insufficientAlert" style="display:none;">
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Yetersiz kredi bakiyesi
                </div>
            </div>

        </div><!-- /.request-form-body -->

        <!-- ═══ FOOTER: Submit ═══ -->
        <div class="form-submit-row">
            <div class="credit-info-inline">
                <div class="credit-info-item">
                    <span class="credit-label">Bakiyeniz</span>
                    <strong id="currentBalance"><?= $creditBalance ?> Kr</strong>
                </div>
                <div class="credit-divider"></div>
                <div class="credit-info-item">
                    <span class="credit-label">İşlem Sonrası</span>
                    <strong id="remainingBalance"><?= $creditBalance ?> Kr</strong>
                </div>
            </div>
            <button type="submit" class="btn-submit-request" id="submitBtn" disabled>
                <i class="fas fa-paper-plane"></i>
                <span>Talebi Gönder</span>
            </button>
        </div>

    </div><!-- /.request-form-wrapper -->
</form>

<?php $extraJs = "<script>
    var creditBalance = " . $creditBalance . ";
    initCreateRequest();
</script>"; ?>
