<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\helpers\StringHelper;
use frontend\assets\AppAsset;

$cidade = $cidade ?? '';
$capacidadeMin = (int)($capacidadeMin ?? 0);
$locais = $locais ?? [];

$this->title = 'Espaços';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/espacos.css', [ // Adiciona o arquivo CSS específico para esta página
    'depends' => [AppAsset::class],
]);
$this->params['meta_description'] = 'Explore os espaços disponíveis na EventLink e encontre o local ideal para o seu evento.';
?>

<!--parte de cima, titulo e descrição da página-->

<div class="site-espacos py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3" style="font-family: 'Playfair Display', serif;">
            Encontre o espaço ideal
        </h1>
        <p class="lead text-body-secondary mx-auto" style="max-width: 650px;">
            Explore a nossa seleção de salões, quintas e espaços exclusivos preparados para acolher o seu próximo evento.
        </p>
    </div>

    <!---->

    <!--Filtros de busca-->
    <div class="card border-0 shadow-sm bg-body-tertiary p-4 mb-5">
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="filtro-cidade" class="form-label fw-semibold small">Cidade</label>
                <input type="text" id="filtro-cidade" name="cidade" class="form-control" placeholder="Ex: Lisboa, Porto, Leiria..."
                    value="<?= Html::encode($cidade) ?>"> <!--É o método de segurança do Yii2 que converte caracteres especiais (<, >, ", ', &) em entidades HTML seguras-->
            </div>
            <div class="col-md-4">
                <label for="filtro-capacidade" class="form-label fw-semibold small">Capacidade mínima (pessoas)</label>
                <input type="number" id="filtro-capacidade" name="capacidade" class="form-control" min="0" placeholder="Ex: 100"
                    value="<?= $capacidadeMin > 0 ? Html::encode((string)$capacidadeMin) : '' ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Pesquisar</button>
                <?php if ($cidade !== '' || $capacidadeMin > 0): ?>
                    <?= Html::a('Limpar', ['site/espacos'], ['class' => 'btn btn-outline-secondary']) ?>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!---->

    <?php if (empty($locais)): ?> <!--empty valida se não houverem locais ou se não corresponderem à pesquisa-->
        <div class="text-center py-5 border rounded bg-body-tertiary">
            <h3 class="h5 fw-semibold mb-2">Nenhum espaço encontrado</h3>
            <p class="text-body-secondary mb-3">Não existem espaços ativos que correspondam aos critérios selecionados.</p>
            <?php if ($cidade !== '' || $capacidadeMin > 0): ?>
                <?= Html::a('Ver todos os espaços', ['site/espacos'], ['class' => 'btn btn-outline-primary']) ?><!--hiperligação para ver todos os locais-->
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($locais as $local): ?> <!--foreach para percorrer os locais encontrados-->
                <?php
                $imagemPrincipal = $local->foto_capa_url ?: ($local->localFotos[0]->foto_url ?? null);
                $totalFotos = count($local->localFotos);
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <div class="position-relative">
                            <?php if ($imagemPrincipal): ?>
                                <img
                                    src="<?= Html::encode($imagemPrincipal) ?>"
                                    class="card-img-top"
                                    alt="<?= Html::encode($local->nome) ?>"
                                    style="height: 220px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary-subtle d-flex align-items-center justify-content-center text-body-secondary" style="height: 220px;">
                                    <span class="small">Sem fotografia disponível</span>
                                </div>
                            <?php endif; ?>

                            <?php if ($totalFotos > 0): ?>
                                <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 end-0 m-2">
                                    +<?= $totalFotos ?> <?= $totalFotos === 1 ? 'foto' : 'fotos' ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <span class="badge bg-light text-dark border"><?= Html::encode($local->cidade) ?></span>
                                <span class="text-body-secondary small">Até <?= Html::encode((string)$local->capacidade_total) ?> pessoas</span>
                            </div>

                            <h3 class="h5 card-title fw-bold mb-2" style="font-family: 'Playfair Display', serif;">
                                <?= Html::encode($local->nome) ?>
                            </h3>

                            <p class="text-body-secondary small mb-3">
                                <?= Html::encode($local->morada) ?>
                            </p>

                            <p class="card-text text-body-secondary small flex-grow-1 mb-4">
                                <?= Html::encode(StringHelper::truncate($local->descricao ?? 'Sem descrição detalhada.', 110)) ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <span class="text-body-secondary d-block" style="font-size: 0.75rem;">A partir de</span>
                                    <span class="fw-bold fs-5"><?= Yii::$app->formatter->asCurrency($local->preco_aluguer_base, 'EUR') ?></span>
                                </div>
                                <?= Html::a('Ver detalhes', ['site/espaco-detalhe', 'id' => $local->id], ['class' => 'btn btn-primary btn-sm px-3']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>